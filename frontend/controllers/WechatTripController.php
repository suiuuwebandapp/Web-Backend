<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/6/29
 * Time: 上午11:22
 */

namespace frontend\controllers;


use common\components\Code;
use common\components\DateUtils;
use common\components\LogUtils;
use common\components\SysMessageUtils;
use common\entity\TravelTrip;
use common\entity\TravelTripService;
use common\entity\UserOrderInfo;
use frontend\components\Page;
use frontend\services\TripService;
use frontend\services\UserAttentionService;
use frontend\services\UserBaseService;
use frontend\services\UserOrderService;
use yii\base\Exception;

class WechatTripController extends WController {
    public $layout=false;
    public $enableCsrfValidation=false;
    public $userOrderService =null ;
    private $tripService;
    public function __construct($id, $module = null)
    {
        $this->userOrderService = new UserOrderService();
        $this->tripService=new TripService();
        parent::__construct($id, $module);
    }

    public function actionIndex()
    {
        $this->loginValid(false);
        $page=new Page();
        $page->pageSize=4;
        $attentionService=new UserAttentionService();
        $recommendTravel =$attentionService->getRecommendTravel($page);
       return $this->renderPartial("index",["recommendTravel"=>$recommendTravel['data'],'userObj'=>$this->userObj,'active'=>1,'newMsg'=>0]);
    }

    public function actionSelect()
    {
        return $this->renderPartial('select');
    }

    public function actionSelectList()
    {
        $str=\Yii::$app->request->get("str");
        $peopleCount=\Yii::$app->request->post("peopleCount");
        $amount=\Yii::$app->request->post("amount");
        $tag=\Yii::$app->request->post("tag");
        $type=\Yii::$app->request->post("type");//是否是ajax;
        $startPrice="";
        $endPrice="";
        if(!empty($amount)){
            $amount=str_replace("￥","",$amount);
            $amount=str_replace(" ","",$amount);
            $priceArr=explode("-",$amount);
            $startPrice=$priceArr[0];
            $endPrice=$priceArr[1];
        }
        $page=new Page(\Yii::$app->request);
        $page->pageSize=10;
        $page= $this->tripService->getList($page,$str,null,null,$peopleCount,$startPrice,$endPrice,$tag);
        if($type=="post")
        {
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$page->getList()));
        }
        return $this->renderPartial('selectList',['str'=>$str,'list'=> $page->getList(),'c'=>$page->currentPage,'peopleCount'=>$peopleCount,"amount"=>$amount,"tag"=>$tag]);
    }

    public function actionSearch()
    {
        $str=\Yii::$app->request->get("str");
        $peopleCount=\Yii::$app->request->get("peopleCount",1);
        $amount=\Yii::$app->request->get("amount");
        $startPrice=0;
        $endPrice=5000;
        if(!empty($amount)){
            $amount=str_replace("￥","",$amount);
            $amount=str_replace(" ","",$amount);
            $priceArr=explode("-",$amount);
            $startPrice=$priceArr[0];
            $endPrice=$priceArr[1];
        }
        $tag=\Yii::$app->request->get("tag");
        return $this->renderPartial('search',['str'=>$str,'peopleCount'=>$peopleCount,"startPrice"=>$startPrice,"endPrice"=>$endPrice,"tag"=>$tag]);
    }

    public function actionInfo()
    {
        $tripId=\Yii::$app->request->get("tripId");
        if(empty($tripId))
        {
            return $this->redirect('/we-chat/error?str=未知随游');
        }
        $travelInfo=$this->tripService->getTravelTripInfoById($tripId);
        if(empty($travelInfo['info']))
        {
            return $this->redirect('/we-chat/error?str=未知随游');
        }
        return $this->renderPartial("info",['info'=>$travelInfo]);
    }

    public function actionAddOrder()
    {
        $this->loginValidJson();
        $userSign = $this->userObj->userSign;
        $tripId=trim(\Yii::$app->request->post("tripId", ""));
        $peopleCount=trim(\Yii::$app->request->post("peopleCount", ""));
        $beginDate=trim(\Yii::$app->request->post("beginDate", ""));
        $startTime=trim(\Yii::$app->request->post("startTime", ""));
        $serviceIds=trim(\Yii::$app->request->post("serviceIds", ""));
        if(empty($userSign)){
            return json_encode(Code::statusDataReturn(Code::FAIL,'无效用户'));
        }
        if(empty($tripId)){
            return json_encode(Code::statusDataReturn(Code::FAIL,'未知随游'));
        }
        if(empty($peopleCount)||$peopleCount==0){
            return json_encode(Code::statusDataReturn(Code::FAIL,'出行人数不正确'));
        }
        if(empty($beginDate)){
            return json_encode(Code::statusDataReturn(Code::FAIL,'行程日期不正确'));
        }
        if(empty($startTime)){
            return json_encode(Code::statusDataReturn(Code::FAIL,'起始时间不正确'));
        }
        if(strtotime($beginDate)<time()){
            return json_encode(Code::statusDataReturn(Code::FAIL,'无效的出行日期'));
        }
        if(strtotime($beginDate)==strtotime(date('y-M-d'),time())){
            //TODO 判断如果是当天，需要判断当前时间是否正确  并且判断服务时间
        }
        //判断开始时间是否小于当前时间


        $tripService=new TripService();
        try{
            $travelInfo=$tripService->getTravelTripInfoById($tripId);
            $tripInfo=$travelInfo['info'];
            $tripPriceList=$travelInfo['priceList'];
            $tripPublisherList=$travelInfo['publisherList'];
            $tripServiceList=$travelInfo['serviceList'];

            $orderTripInfoJson=json_encode($travelInfo);//订单详情
            $selectServiceList=[];//附加服务list
            $servicePrice=0;//附加服务价格
            $basePrice=0;

            if($peopleCount>$tripInfo['maxUserCount']){
                return Code::statusDataReturn(Code::PARAMS_ERROR,"PeopleCount Over Max User Count");
            }
            $bo=true;
            if($tripInfo['basePriceType']==TravelTrip::TRAVEL_TRIP_BASE_PRICE_TYPE_COUNT){
                $basePrice=$tripInfo['basePrice'];
            }else{
                //计算阶梯价格 和 基础价格
                if(!empty($tripPriceList)&&count($tripPriceList)>0){
                    foreach($tripPriceList as $stepPrice)
                    {
                        if($peopleCount>=$stepPrice['minCount']&&$peopleCount<=$stepPrice['maxCount']){
                            $basePrice=$stepPrice['price']*$peopleCount;
                            $bo=false;
                            break;
                        }
                    }
                }else{
                    $basePrice=$tripInfo['basePrice']*$peopleCount;
                }
                if($bo)
                {
                    $basePrice=$tripInfo['basePrice']*$peopleCount;
                }
            }


            if(!empty($serviceIds)){
                $serviceIdArr=explode(",",$serviceIds);
                foreach($serviceIdArr as $serviceId)
                {
                    foreach($tripServiceList as $tripService)
                    {
                        if($serviceId==$tripService['serviceId'])
                        {
                            $selectServiceList[]=$tripService;
                            if($tripService['type']==TravelTripService::TRAVEL_TRIP_SERVICE_TYPE_COUNT){
                                $servicePrice+=($tripService['money']);
                            }else if($tripService['type']==TravelTripService::TRAVEL_TRIP_SERVICE_TYPE_PEOPLE){
                                $servicePrice+=($tripService['money']*$peopleCount);
                            }
                            break;
                        }
                    }
                }
            }
            $serviceInfo=json_encode($selectServiceList);//附加服务详情

            $userOrderInfo=new UserOrderInfo();
            $userOrderInfo->tripId=$tripInfo['tripId'];
            $userOrderInfo->userId=$userSign;
            $userOrderInfo->beginDate=$beginDate;
            $userOrderInfo->startTime=DateUtils::convertTimePicker($startTime,1);
            $userOrderInfo->personCount=$peopleCount;
            $userOrderInfo->serviceInfo=$serviceInfo;
            $userOrderInfo->basePrice=$tripInfo['basePrice'];
            $userOrderInfo->servicePrice=$servicePrice;
            $userOrderInfo->tripJsonInfo=$orderTripInfoJson;
            $userOrderInfo->totalPrice=$basePrice+$servicePrice;
            $userOrderInfo->status=UserOrderInfo::USER_ORDER_STATUS_PAY_WAIT;//默认订单状态，待支付
            $userOrderInfo->orderNumber=Code::createOrderNumber();
            $this->userOrderService->addUserOrder($userOrderInfo);
            //给随友发送消息
            $sysMessageUtils=new SysMessageUtils();
            $sysMessageUtils->sendNewOrderMessage($tripPublisherList,$userOrderInfo->orderNumber);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$userOrderInfo->orderNumber));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,'系统未知异常'));
        }
    }

    public function actionAddOrderView()
    {
        $this->loginValid();
        $userSign=$this->userObj->userSign;
        $userBaseService = new UserBaseService();
        $userPublisherObj=$userBaseService->findUserPublisherByUserSign($userSign);
        $publisherId=isset($userPublisherObj->userPublisherId)?$userPublisherObj->userPublisherId:0;
        $tripId=\Yii::$app->request->get("tripId");
        if(empty($tripId))
        {
            return $this->redirect('/we-chat/error?str=未知随游');
        }
        $travelInfo=$this->tripService->getTravelTripInfoById($tripId);
        if(empty($travelInfo))
        {
            return $this->redirect('/we-chat/error?str=未知随游');
        }
        return $this->renderPartial("addOrderView",['info'=>$travelInfo,'publisherId'=>$publisherId]);
    }


}