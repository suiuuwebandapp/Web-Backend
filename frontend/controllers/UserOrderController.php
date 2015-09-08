<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/5
 * Time : 上午11:59
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\controllers;

use common\components\LogUtils;
use common\components\SMSUtils;
use common\components\Code;
use common\components\DateUtils;
use common\components\SysMessageUtils;
use common\components\Validate;
use common\entity\TravelTrip;
use common\entity\TravelTripService;
use common\entity\UserMessage;
use common\entity\UserOrderComment;
use common\entity\UserOrderContact;
use common\entity\UserOrderInfo;
use frontend\services\TripService;
use frontend\services\UserBaseService;
use frontend\services\UserMessageService;
use frontend\services\UserOrderService;
use yii\base\Exception;
class UserOrderController extends  CController{


    private $userOrderService;

    public function __construct($id, $module = null)
    {
        $this->userOrderService = new UserOrderService();
        parent::__construct($id, $module);
    }

    public function actionList()
    {
        $this->render("list");
    }

    /**
     * 跳转到订单详情页面
     * @return string|\yii\web\Response
     * @throws Exception
     * @throws \Exception
     */
    public function actionInfo()
    {
        $orderNumber=trim(\Yii::$app->request->get("orderNumber", ""));
        if(empty($orderNumber)){
            return $this->redirect(['/result', 'result' => '无效的订单号']);
        }
        $orderInfo=$this->userOrderService->findOrderByOrderNumber($orderNumber);
        $userBaseService=new UserBaseService();
        $tripJsonInfo=json_decode($orderInfo->tripJsonInfo,true);
        $publisher=null;
        $publisherInfo=null;

        $publisher=$this->userOrderService->findPublisherByOrderId($orderInfo->orderId);
        if(isset($publisher)){
            $publisherInfo=$userBaseService->findUserByPublisherId($publisher->userPublisheId);
        }else{
            $publisherInfo=$userBaseService->findUserByPublisherId($tripJsonInfo['info']['createPublisherId']);
        }

        if($orderInfo==null){
            return $this->redirect(['/result', 'result' => '无效的订单号']);
        }
        if($orderInfo->userId!=$this->userObj->userSign){
            return $this->redirect(['/result', 'result' => '无效的用户']);
        }

        $contact=$this->userOrderService->getOrderContactByOrderId($orderInfo->orderId);
        if($contact==null){
            $contact=new UserOrderContact();
            $contact->username=$this->userObj->surname.$this->userObj->name;
            $contact->phone=$this->userObj->phone;
            $contact->wechat=$this->userObj->wechat;
        }
        return $this->render("info",[
            'orderInfo'=> $orderInfo,
            'userPublisherInfo'=>$publisherInfo,
            'contact'=>$contact
        ]);
    }


    public function actionViewOrderInfo()
    {
        $orderNumber=trim(\Yii::$app->request->get('orderNumber', ''));
        $returnUrl='userOrderInfo';
        if(empty($orderNumber)){
            return $this->redirect(['/result', 'result' => '无效的订单号']);
        }
        $orderInfo=$this->userOrderService->findOrderByOrderNumber($orderNumber);

        $tripJsonInfo=json_decode($orderInfo->tripJsonInfo,true);
        $tripService=new TripService();
        $userInfo=$this->userObj;

        $isAllowUser=false;
        if($this->userObj->userSign!=$orderInfo->userId){
            //如果是带接单状态 判断当前用户是不是随友  如果不是带接单状态 那么 判断是不是当前用户  或者是已接单的随友
            if($orderInfo->status==UserOrderInfo::USER_ORDER_STATUS_PAY_SUCCESS){
                $publisherList=$tripService->getTravelTripPublisherList($tripJsonInfo['info']['tripId']);
                foreach($publisherList as $publisher ){
                    if($publisher['userSign']==$this->userObj->userSign){
                        $isAllowUser=true;
                    }
                }
            }else{
                $publisher=$this->userOrderService->findPublisherByOrderId($orderInfo->orderId);
                if(isset($publisher)&&$publisher->userId==$this->userObj->userSign){
                    $isAllowUser=true;
                }
            }
            $returnUrl='publisherOrderInfo';
            $userBaseService=new UserBaseService();
            $userInfo=$userBaseService->findUserByUserSign($orderInfo->userId);
        }else{
            $isAllowUser=true;
        }
        if(!$isAllowUser){
            return $this->redirect(['/result', 'result' => '无效的用户']);
        }
        $contact=$this->userOrderService->getOrderContactByOrderId($orderInfo->orderId);


        return $this->render($returnUrl,[
            'orderInfo'=>$orderInfo,
            'tripJsonInfo'=>$tripJsonInfo,
            'userInfo'=>$userInfo,
            'contact'=>$contact
        ]);

    }

    /**
     * 添加用户订单
     * @return void|\yii\web\Response
     * @throws Exception
     * @throws \Exception
     */
    public function actionAddOrder()
    {
        $tripId=trim(\Yii::$app->request->post("tripId", ""));
        $peopleCount=trim(\Yii::$app->request->post("peopleCount", ""));
        $beginDate=trim(\Yii::$app->request->post("beginDate", ""));
        $startTime=trim(\Yii::$app->request->post("startTime", ""));
        $serviceIds=trim(\Yii::$app->request->post("serviceIds", ""));

        if(empty($tripId)){
            return $this->redirect(['/result', 'result' => '随游编号不正确']);
        }
        if(empty($peopleCount)||$peopleCount==0){
            return $this->redirect(['/result', 'result' => '出行人数不正确']);
        }
        if(empty($beginDate)){
            return $this->redirect(['/result', 'result' => '行程日期不正确']);
        }
        if(empty($startTime)){
            return $this->redirect(['/result', 'result' => '起始时间不正确']);
        }
        if(strtotime($beginDate)<time()){
            return $this->redirect(['/result', 'result' => '无效的出行日期']);
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
            $stepFlag=false;

            if($peopleCount>$tripInfo['maxUserCount']){
                return Code::statusDataReturn(Code::PARAMS_ERROR,"PeopleCount Over Max User Count");
            }
            if($tripInfo['basePriceType']==TravelTrip::TRAVEL_TRIP_BASE_PRICE_TYPE_COUNT){
                $basePrice=$tripInfo['basePrice'];
            }else{
                //计算阶梯价格 和 基础价格
                if(!empty($tripPriceList)&&count($tripPriceList)>0){
                    foreach($tripPriceList as $stepPrice)
                    {
                        if($peopleCount>=$stepPrice['minCount']&&$peopleCount<=$stepPrice['maxCount']){
                            $basePrice=$stepPrice['price']*$peopleCount;
                            $stepFlag=true;
                            break;
                        }
                    }
                }else{
                    $basePrice=$tripInfo['basePrice']*$peopleCount;
                }
                if(!$stepFlag){
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
            $userOrderInfo->userId=$this->userObj->userSign;
            $userOrderInfo->beginDate=$beginDate;
            $userOrderInfo->startTime=$startTime.":00";
            $userOrderInfo->personCount=$peopleCount;
            $userOrderInfo->serviceInfo=$serviceInfo;
            $userOrderInfo->basePrice=$tripInfo['basePrice'];
            $userOrderInfo->servicePrice=$servicePrice;
            $userOrderInfo->tripJsonInfo=$orderTripInfoJson;
            $userOrderInfo->totalPrice=$basePrice+$servicePrice;
            $userOrderInfo->status=UserOrderInfo::USER_ORDER_STATUS_PAY_WAIT;//默认订单状态，待支付
            $userOrderInfo->orderNumber=Code::createOrderNumber();
            $this->userOrderService->addUserOrder($userOrderInfo);

            return $this->redirect(["/user-order/info",
                'orderNumber'=>$userOrderInfo->orderNumber
            ]);
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->redirect(['/result', 'result' => '系统未知异常']);
        }
    }

    public function actionAddTrafficOrder()
    {
        $tripId=trim(\Yii::$app->request->post("tripId", ""));
        $serviceStr=trim(\Yii::$app->request->post("serviceList", ""));

        if(empty($tripId)){
            return $this->redirect(['/result', 'result' => '随游编号不正确']);
        }
        if(empty($serviceStr)){
            return $this->redirect(['/result', 'result' => '服务列表不能为空']);
        }

        try{
            $serviceList=json_decode($serviceStr,true);

            $tripService=new TripService();
            $travelTripInfo=$tripService->getTravelTripInfoById($tripId);
            $tripInfo=$travelTripInfo['info'];
            $tripPublisherList=$travelTripInfo['publisherList'];
            $trafficInfo=$travelTripInfo['trafficInfo'];
            $scheduledDay=0;
            $totalPrice=0;

            $nowDay=strtotime(date("Y-m-d",time()));

            $serviceResult=[];
            $beginDate='';
            $startTime='';
            $personCount='';

            if(!empty($tripInfo['scheduledTime'])){
                $scheduledDay=$tripInfo['scheduledTime'];
            }

            foreach($serviceList as $serviceInfo){
                $tempDate=$serviceInfo['date'];
                $tempTime=$serviceInfo['time'];
                $tempType=$serviceInfo['type'];
                $tempPerson=$serviceInfo['person'];

                $tempPrice=null;
                $timeNumber=strtotime(date("Y-m-d",time())." ".$tempTime);

                if(strtotime($tempDate)<($nowDay+$scheduledDay)){
                    return $this->redirect(['/result', 'result' => '无效的出行日期']);
                }
                if(!empty($tripInfo['startTime'])&&!empty($tripInfo['endTime'])){
                    $startTimeNumber=strtotime(date("Y-m-d",time())." ".$tripInfo['startTime']);
                    $endTimeNumber=strtotime(date("Y-m-d",time())." ".$tripInfo['endTime']);
                    $tempServiceInfo=[];
                    if($timeNumber<$startTimeNumber||$timeNumber>$endTimeNumber){
                        return $this->redirect(['/result', 'result' => '无效的出行时间']);
                    }
                }
                if($tempPerson>$tripInfo['maxUserCount']){
                    return $this->redirect(['/result', 'result' => '无效的出行人数']);
                }
                if($tempType=="car"){
                    $tempPrice=$trafficInfo['carPrice'];
                }else{
                    $nightStartNumber=strtotime(date("Y-m-d",time())." ".$trafficInfo['nightTimeStart']);
                    $nightEndNumber=strtotime(date("Y-m-d",time())." ".$trafficInfo['nightTimeEnd']);
                    if($timeNumber>$nightStartNumber&&$timeNumber<$nightEndNumber){
                        $tempPrice=intval($trafficInfo['airplanePrice'])+intval($trafficInfo['nightServicePrice']);
                    }else{
                        $tempPrice=$trafficInfo['airplanePrice'];
                    }
                }

                if(empty($beginDate)){
                    $beginDate=$tempDate;
                    $startTime=$tempTime;
                }else{
                    if(strtotime($tempDate)<strtotime($beginDate)){
                        $beginDate=$tempDate;
                        $startTime=$tempTime;
                    }
                }


                if(empty($personCount)){
                    $personCount=$tempPerson;
                }else{
                    if($tempPerson>$personCount){
                        $personCount=$tempPerson;
                    }
                }

                $totalPrice=intval($totalPrice)+intval($tempPrice);
                $tempServiceInfo['date']=$tempDate;
                $tempServiceInfo['time']=$tempTime;
                $tempServiceInfo['type']=$tempType;
                $tempServiceInfo['price']=$tempPrice;
                $tempServiceInfo['person']=$tempPerson;

                $serviceResult[]=$tempServiceInfo;
            }

            if(empty($serviceResult)){
                throw new Exception("请选择有效的服务");
            }


            $userOrderInfo=new UserOrderInfo();
            $userOrderInfo->tripId=$tripInfo['tripId'];
            $userOrderInfo->userId=$this->userObj->userSign;
            $userOrderInfo->beginDate=$beginDate;
            $userOrderInfo->startTime=$startTime;
            $userOrderInfo->personCount=$personCount;
            $userOrderInfo->serviceInfo=json_encode($serviceResult);
            $userOrderInfo->basePrice=$tripInfo['basePrice'];
            $userOrderInfo->servicePrice=$totalPrice;
            $userOrderInfo->tripJsonInfo=json_encode($travelTripInfo);
            $userOrderInfo->totalPrice=$totalPrice;
            $userOrderInfo->status=UserOrderInfo::USER_ORDER_STATUS_PAY_WAIT;//默认订单状态，待支付
            $userOrderInfo->orderNumber=Code::createOrderNumber();
            $this->userOrderService->addUserOrder($userOrderInfo);

            return $this->redirect(["/user-order/info",
                'orderNumber'=>$userOrderInfo->orderNumber
            ]);


        }catch (Exception $e){
            LogUtils::log($e);
            return $this->redirect(['/result', 'result' => '系统未知异常']);
        }
    }

    public function actionToPay()
    {
        //跳转到选择支付页面 暂时用支付宝
        $orderNumber=trim(\Yii::$app->request->post("orderNumber", ""));
        if(empty($orderNumber)){
            return $this->redirect(['/result', 'result' => '无效的订单编号']);
        }
    }

    /**
     * 获取用户未完成的订单
     * @throws Exception
     * @throws \Exception
     */
    public function actionGetUnFinishOrder()
    {
        try{
            $userSign=$this->userObj->userSign;
            $list=$this->userOrderService->getUnFinishOrderList($userSign);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$list));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 获取用户已完成的订单
     */
    public function actionGetFinishOrder()
    {
        try{
            $userSign=$this->userObj->userSign;
            $list=$this->userOrderService->getFinishOrderList($userSign);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$list));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 获取未确认的订单（根据随友Id）
     */
    public function actionGetUnConfirmOrder()
    {
        try{
            $publisherId=$this->userPublisherObj->userPublisherId;
            $list=$this->userOrderService->getUnConfirmOrderByPublisher($publisherId);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$list));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 确认订单
     */
    public function actionPublisherConfirmOrder()
    {
        $orderId=trim(\Yii::$app->request->post("orderId", ""));
        $publisherId=$this->userPublisherObj->userPublisherId;
        if(empty($orderId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
        }
        if(empty($publisherId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的随友信息"));
        }
        try{
            $this->userOrderService->publisherConfirmOrder($orderId,$publisherId);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 忽略订单
     */
    public function actionPublisherIgnoreOrder()
    {
        $orderId=trim(\Yii::$app->request->post("orderId", ""));
        $publisherId=$this->userPublisherObj->userPublisherId;

        if(empty($orderId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
        }
        if(empty($publisherId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的随友信息"));
        }
        try{
            $this->userOrderService->publisherIgnoreOrder($orderId,$publisherId);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }

    /**
     * 尚未付款，直接取消订单
     * @return string
     */
    public function actionCancelOrder()
    {
        $orderId=trim(\Yii::$app->request->post("orderId", ""));
        if(empty($orderId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
        }

        try{
            $orderInfo=$this->userOrderService->findOrderByOrderId($orderId);
            if(empty($orderInfo)){
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
            }
            if($orderInfo->status!=UserOrderInfo::USER_ORDER_STATUS_PAY_WAIT){
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"该订单目前无法直接取消"));
            }
            if($orderInfo->userId!=$this->userObj->userSign){
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"您没有权限取消此订单"));
            }
            $this->userOrderService->changeOrderStatus($orderInfo->orderNumber,UserOrderInfo::USER_ORDER_STATUS_CANCELED);

            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"取消订单失败"));
        }

    }

    /**
     * 订单申请退款 未接单状态
     * @return string
     */
    public function actionRefundOrder()
    {
        $orderId=trim(\Yii::$app->request->post("orderId", ""));

        if(empty($orderId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
        }
        try{
            $this->userOrderService->userRefundOrder($this->userObj->userSign,$orderId);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"申请退款失败"));
        }
    }

    /**
     * 订单在已接单情况下申请退款
     * @return string
     */
    public function actionRefundOrderByMessage()
    {
        $orderId=trim(\Yii::$app->request->post("orderId", ""));
        $message=trim(\Yii::$app->request->post("message", ""));

        if(empty($orderId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
        }
        if(empty($message)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"请填写退款申请"));
        }

        try{
            $this->userOrderService->userRefundOrderByMessage($this->userObj->userSign,$orderId,$message);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"申请退款失败"));
        }
    }

    /**
     * 用户删除订单
     * @return string
     */
    public function actionDeleteOrder()
    {
        $orderId=trim(\Yii::$app->request->post("orderId", ""));

        if(empty($orderId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
        }
        try{
            $this->userOrderService->deleteOrderInfo($this->userObj->userSign,$orderId);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"删除订单失败"));
        }
    }

    /**
     * 获取随友订单
     * @return string
     */
    public function actionGetPublisherOrderList()
    {
        $publisherId=$this->userPublisherObj->userPublisherId;
        if(empty($publisherId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的随友信息"));
        }
        try{
            $list=$this->userOrderService->getPublisherOrderList($publisherId);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$list));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"获取随友订单失败"));
        }
    }

    /**
     * 随友取消订单
     * @return string
     */
    public function actionPublisherCancelOrder()
    {
        $orderId=trim(\Yii::$app->request->post("orderId", ""));
        $message=trim(\Yii::$app->request->post("message", ""));
        $publisherId=$this->userPublisherObj->userPublisherId;
        if(empty($orderId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
        }
        if(empty($message)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "请输入取消原因"));
        }
        if(empty($orderId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的随友信息"));
        }

        try{
            $this->userOrderService->publisherCancelOrder($publisherId,$orderId,$message);

            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"取消订单失败"));
        }
    }

    /**
     * 用户确认游玩
     * @return string
     */
    public function actionUserConfirmPlay()
    {
        $orderId=trim(\Yii::$app->request->post("orderId", ""));
        if(empty($orderId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
        }
        try{
            $orderInfo=$this->userOrderService->findOrderByOrderId($orderId);
            if($orderInfo==null){
                throw new Exception("Invalid Order Id");
            }
            $this->userOrderService->userConfirmPlay($orderId,$this->userObj->userSign);
            $userPublisher=$this->userOrderService->findPublisherByOrderId($orderId);
            if($userPublisher==null){
                throw new Exception("Invalid Publisher");
            }
            //给随友发送消息
            $sysMessageUtils=new SysMessageUtils();
            $sysMessageUtils->sendUserConfirmPlayMessage($this->userObj->userSign,$userPublisher->userId,$orderInfo->orderNumber);
            //刷新用户信息
            $this->refreshUserInfo();
            //TODO 刷新随友用户信息
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"确认游玩失败"));
        }
    }

    /**
     * 跳转到评论页面
     * @return string|\yii\web\Response
     * @throws Exception
     * @throws \Exception
     */
    public function actionToComment()
    {
        $orderId=trim(\Yii::$app->request->get("orderId", ""));
        if(empty($orderId)){
            return $this->redirect(['/result', 'result' => '无效的订单号']);
        }
        //判断订单是否已经评论，
        $tempComment=$this->userOrderService->findUserOrderCommentByOrderId($orderId);
        $orderInfo=$this->userOrderService->findOrderByOrderId($orderId);
        if(!isset($orderInfo)){
            return $this->redirect(['/result', 'result' => '无效的订单号']);
        }
        if(isset($tempComment)){
            return $this->redirect(['/result', 'result' => '您已经评论过了']);
        }
        if($orderInfo->status!=UserOrderInfo::USER_ORDER_STATUS_PLAY_SUCCESS&&$orderInfo->status!=UserOrderInfo::USER_ORDER_STATUS_PLAY_FINISH){
            return $this->redirect(['/result', 'result' => '此订单暂时不能评价']);
        }
        if($orderInfo->isDel){
            return $this->redirect(['/result', 'result' => '无效的订单号']);
        }
        $orderPublisher=$this->userOrderService->findPublisherByOrderId($orderId);
        if(empty($orderPublisher)){
            return $this->redirect(['/result', 'result' => '无效的随友信息']);
        }
        $publisherInfo=$this->userBaseService->findUserPublisherByPublisherId($orderPublisher->userPublisherId);
        $publisherUserInfo=$this->userBaseService->findUserByUserSign($orderPublisher->userId);
        $tripService=new TripService();
        $tripInfo=$tripService->getTravelTripById($orderInfo->tripId);

        return $this->render("comment",[
            'userPublisher'=>$orderPublisher,
            'publisherInfo'=>$publisherInfo,
            'publisherUserInfo'=>$publisherUserInfo,
            'tripInfo'=>$tripInfo,
            'orderInfo'=>$orderInfo,
            'orderId'=>$orderId
        ]);
    }

    /**
     * 添加评论
     * @return string
     * @throws Exception
     * @throws \Exception
     */
    public function actionAddComment()
    {
        $orderId=trim(\Yii::$app->request->post("orderId", ""));
        $content=\Yii::$app->request->post("content","");
        $otherContent=\Yii::$app->request->post("otherContent","");
        $tripScore=\Yii::$app->request->post("tripScore","");
        $familiarScore=\Yii::$app->request->post("familiarScore","");
        $absorbedScore=\Yii::$app->request->post("absorbedScore","");
        $punctualScore=\Yii::$app->request->post("punctualScore","");

        if(empty($orderId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
        }
        if(empty($content)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的评价"));
        }
        if(!is_numeric($tripScore)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的随游评分"));
        }
        if(!is_numeric($familiarScore)||!is_numeric($absorbedScore)||!is_numeric($punctualScore)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的随友评分"));
        }
        $familiarScore=self::validateScore($familiarScore);
        $absorbedScore=self::validateScore($absorbedScore);
        $punctualScore=self::validateScore($punctualScore);
        $tripScore=self::validateScore($tripScore);

        $publisherScore=round(($familiarScore+$absorbedScore+$punctualScore)/3);
        $publisherScore=self::validateScore($publisherScore);

        //判断订单是否已经评论，
        $tempComment=$this->userOrderService->findUserOrderCommentByOrderId($orderId);
        if(isset($tempComment)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"您已经评论过了"));
        }
        $userOrderInfo=$this->userOrderService->findOrderByOrderId($orderId);
        $orderPublisher=$this->userOrderService->findPublisherByOrderId($orderId);
        if(empty($userOrderInfo)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
        }
        if(empty($orderPublisher)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的随友信息"));
        }

        $userOrderComment=new UserOrderComment();
        $userOrderComment->userId=$this->userObj->userSign;
        $userOrderComment->tripId=$userOrderInfo->tripId;
        $userOrderComment->orderId=$orderId;
        $userOrderComment->publisherId=$orderPublisher->userPublisherId;
        $userOrderComment->tripScore=$tripScore;
        $userOrderComment->publisherScore=$publisherScore;
        $userOrderComment->content=$content;
        $userOrderComment->familiarScore=$familiarScore;
        $userOrderComment->absorbedScore=$absorbedScore;
        $userOrderComment->punctualScore=$punctualScore;
        $userOrderComment->otherContent=$otherContent;
        try{
            $this->userOrderService->addUserOrderComment($userOrderComment);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }


    /**
     * @return string 保存订单联系人
     */
    public function actionSaveOrderContact()
    {
        $orderNumber=\Yii::$app->request->post('orderNumber');
        $username=\Yii::$app->request->post('username');
        $phone=\Yii::$app->request->post('phone');
        $sparePhone=\Yii::$app->request->post('sparePhone');
        $wechat=\Yii::$app->request->post('wechat','');
        $urgentUsername=\Yii::$app->request->post('urgentUsername');
        $urgentPhone=\Yii::$app->request->post('urgentPhone');
        $arriveFlyNumber=\Yii::$app->request->post('arriveFlyNumber');
        $leaveFlyNumber=\Yii::$app->request->post('leaveFlyNumber');
        $destination=\Yii::$app->request->post('destination');

        if(empty($orderNumber)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
        }
        if(empty($username)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的用户姓名"));
        }
        if(empty($phone)||Validate::validatePhone($phone)!=''){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的手机号"));
        }
        if(empty($urgentUsername)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的紧急联系人姓名"));
        }
        if(empty($urgentPhone)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的紧急联系人手机号"));
        }


        try{
            $orderInfo=$this->userOrderService->findOrderByOrderNumber($orderNumber);
            $contact=$this->userOrderService->getOrderContactByOrderId($orderInfo->orderId);

            if($orderInfo->orderNumber!=$orderNumber){
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
            }
            $serviceInfo=json_decode($orderInfo->serviceInfo,true);
            $hasAirplane=false;
            foreach($serviceInfo as $service){
                if($service['type']=='airplane'){
                    $hasAirplane=true;
                }
            }
            if($hasAirplane){
                if(empty($arriveFlyNumber)&&empty($leaveFlyNumber)){
                    return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的接送机号码"));
                }
                if(empty($destination)){
                    return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的目的地"));
                }
            }
            if(empty($contact)){
                $contact=new UserOrderContact();
            }

            $contact->orderId=$orderInfo->orderId;
            $contact->userId=$orderInfo->userId;

            $contact->username=$username;
            $contact->phone=$phone;
            $contact->sparePhone=$sparePhone;
            $contact->wechat=$wechat;
            $contact->urgentUsername=$urgentUsername;
            $contact->urgentPhone=$urgentPhone;
            $contact->arriveFlyNumber=$arriveFlyNumber;
            $contact->leaveFlyNumber=$leaveFlyNumber;
            $contact->destination=$destination;

            $this->userOrderService->saveUserContact($contact);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }


    private function validateScore($score)
    {
        if(!is_numeric($score)||$score<0){
            $score=2;
        }
        if($score>10){
            $score=10;
        }
        return $score;
    }


}