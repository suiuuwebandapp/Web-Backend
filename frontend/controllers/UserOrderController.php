<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/5
 * Time : 上午11:59
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\controllers;


use common\components\Code;
use common\components\DateUtils;
use common\entity\TravelTripService;
use common\entity\UserOrderInfo;
use frontend\services\TripService;
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
        if(empty($orderInfo)){
            return $this->redirect(['/result', 'result' => '无效的订单号']);
        }
        return $this->render("info",[
           'orderInfo'=> $orderInfo
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
            return $this->redirect(['/result', 'result' => '随友编号不正确']);
        }
        if(empty($peopleCount)||$peopleCount==0){
            return $this->redirect(['/result', 'result' => '出行人数不正确']);
        }
        if(empty($beginDate)){
            return $this->redirect(['/result', 'result' => '行程日期不正确']);
        }
        if(empty($startTime)){
            return $this->redirect(['/result', 'result' => '起始时间不正确']);
            return;
        }
        if(strtotime($beginDate)<strtotime(date('y-M-d'),time())){
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
            $servicePrice="";//附加服务价格
            $basePrice=$tripInfo['basePrice'];




            if($peopleCount>$tripInfo['maxUserCount']){
                echo Code::statusDataReturn(Code::PARAMS_ERROR,"PeopleCount Over Max User Count");
                return;
            }
            //计算阶梯价格 和 基础价格
            if(!empty($tripPriceList)&&count($tripPriceList)>0){
                foreach($tripPriceList as $stepPrice)
                {
                    if($peopleCount>=$stepPrice['minCount']&&$peopleCount<=$stepPrice['maxCount']){
                        $basePrice=$stepPrice['price'];
                        break;
                    }
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
            $userOrderInfo->startTime=DateUtils::convertTimePicker($startTime,1);
            $userOrderInfo->personCount=$peopleCount;
            $userOrderInfo->serviceInfo=$serviceInfo;
            $userOrderInfo->basePrice=$basePrice;
            $userOrderInfo->servicePrice=$servicePrice;
            $userOrderInfo->tripJsonInfo=$orderTripInfoJson;
            $userOrderInfo->totalPrice=($basePrice*$peopleCount)+$servicePrice;
            $userOrderInfo->status=UserOrderInfo::USER_ORDER_STATUS_PAY_WAIT;//默认订单状态，待支付
            $userOrderInfo->orderNumber=Code::createOrderNumber();
            $this->userOrderService->addUserOrder($userOrderInfo);

            //$tripPublisherList 循环发送消息
            return $this->redirect(["/user-order/info",
                'orderNumber'=>$userOrderInfo->orderNumber
            ]);
        }catch (Exception $e){
            throw $e;
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
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$list));
        }catch (Exception $e){
            echo json_encode(Code::statusDataReturn(Code::FAIL));
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
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$list));
        }catch (Exception $e){
            echo json_encode(Code::statusDataReturn(Code::FAIL));
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
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$list));
        }catch (Exception $e){
            echo json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }


    /**
     * 确认订单
     */
    public function actionPublisherConfirmOrder()
    {
        $orderId=trim(\Yii::$app->request->post("orderId", ""));

        try{
            $publisherId=$this->userPublisherObj->userPublisherId;
            $this->userOrderService->publisherConfirmOrder($orderId,$publisherId);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e) {
            echo json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 忽略订单
     */
    public function actionPublisherIgnoreOrder()
    {
        $orderId=trim(\Yii::$app->request->post("orderId", ""));

        try{
            $publisherId=$this->userPublisherObj->userPublisherId;
            $this->userOrderService->publisherIgnoreOrder($orderId,$publisherId);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e) {
            echo json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }




}