<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/8/21
 * Time: 下午5:19
 */

namespace app\modules\v1\controllers;


use app\modules\v1\entity\TravelTrip;
use app\modules\v1\entity\TravelTripService;
use app\modules\v1\entity\UserOrderContact;
use app\modules\v1\entity\UserOrderInfo;
use app\modules\v1\services\TripService;
use app\modules\v1\services\UserBaseService;
use app\modules\v1\services\UserOrderService;
use common\components\Code;
use common\components\DateUtils;
use common\components\LogUtils;
use common\components\SysMessageUtils;
use common\components\Validate;
use yii\base\Exception;

class AppUserOrderController extends AController
{
    private $travelSer;
    private $userOrderService;
    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        $this->travelSer=new TripService();
        $this->userOrderService = new UserOrderService();
    }
    /**
     * 获取用户未完成的订单
     * @throws Exception
     * @throws \Exception
     */
    public function actionGetUnFinishOrder()
    {
        try{
            $this->loginValid();
            $userSign=$this->userObj->userSign;
            $list=$this->userOrderService->getUnFinishOrderList($userSign);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$list));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 获取用户已完成的订单
     */
    public function actionGetFinishOrder()
    {
        try{
            $this->loginValid();
            $userSign=$this->userObj->userSign;
            $list=$this->userOrderService->getFinishOrderList($userSign);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$list));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 获取未确认的订单（根据随友Id）
     */
    public function actionGetUnConfirmOrder()
    {
        try{
            $this->loginValid();
            $publisherId=$this->userPublisherObj->userPublisherId;
            if(empty($publisherId)){
                return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的随友信息"));
            }
            $list=$this->userOrderService->getUnConfirmOrderByPublisher($publisherId);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$list));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 确认订单
     */
    public function actionPublisherConfirmOrder()
    {
        $this->loginValid();
        $orderId=trim(\Yii::$app->request->post("orderId", ""));
        $publisherId=$this->userPublisherObj->userPublisherId;
        if(empty($orderId)){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
        }
        if(empty($publisherId)){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的随友信息"));
        }
        try{
            $this->userOrderService->publisherConfirmOrder($orderId,$publisherId);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e) {
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 忽略订单
     */
    public function actionPublisherIgnoreOrder()
    {
        $this->loginValid();
        $orderId=trim(\Yii::$app->request->post("orderId", ""));
        $publisherId=$this->userPublisherObj->userPublisherId;

        if(empty($orderId)){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
        }
        if(empty($publisherId)){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的随友信息"));
        }
        try{
            $this->userOrderService->publisherIgnoreOrder($orderId,$publisherId);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e) {
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }

    }

    /**
     * 尚未接单情况，直接取消订单
     * @return string
     */
    public function actionCancelOrder()
    {
        $this->loginValid();
        $orderId=trim(\Yii::$app->request->post("orderId", ""));
        if(empty($orderId)){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
        }

        try{
            $orderInfo=$this->userOrderService->findOrderByOrderId($orderId);
            if(empty($orderInfo)){
                return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
            }
            if($orderInfo->status!=UserOrderInfo::USER_ORDER_STATUS_PAY_WAIT){
                return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"该订单目前无法直接取消"));
            }
            if($orderInfo->userId!=$this->userObj->userSign){
                return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"您没有权限取消此订单"));
            }
            $this->userOrderService->changeOrderStatus($orderInfo->orderNumber,UserOrderInfo::USER_ORDER_STATUS_CANCELED);

            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,"取消订单失败"));
        }

    }

    /**
     * 订单申请退款 未接单状态
     * @return string
     */
    public function actionRefundOrder()
    {
        $this->loginValid();
        $orderId=trim(\Yii::$app->request->post("orderId", ""));

        if(empty($orderId)){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
        }

        try{
            $orderInfo=$this->userOrderService->findOrderByOrderId($orderId);
            if(empty($orderInfo)){
                return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
            }
            if($orderInfo->userId!=$this->userObj->userSign){
                return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"您没有权限申请退款"));
            }
            if($orderInfo->status!=UserOrderInfo::USER_ORDER_STATUS_PAY_SUCCESS){
                return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"订单暂时无法申请退款"));
            }
            $this->userOrderService->changeOrderStatus($orderInfo->orderNumber,UserOrderInfo::USER_ORDER_STATUS_REFUND_WAIT);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,"申请退款失败"));
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
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
        }
        if(empty($message)){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"请填写退款申请"));
        }
        $this->loginValid();
        try{
            $this->userOrderService->userRefundOrder($this->userObj->userSign,$orderId,$message);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,"申请退款失败"));
        }
    }

    /**
     * 用户删除订单
     * @return string
     */
    public function actionDeleteOrder()
    {
        $this->loginValid();
        $orderId=trim(\Yii::$app->request->post("orderId", ""));

        if(empty($orderId)){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
        }
        try{
            $this->userOrderService->deleteOrderInfo($this->userObj->userSign,$orderId);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,"删除订单失败"));
        }
    }

    /**
     * 获取随友订单
     * @return string
     */
    public function actionGetPublisherOrderList()
    {
        $this->loginValid();
        $publisherId=$this->userPublisherObj->userPublisherId;
        if(empty($publisherId)){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的随友信息"));
        }
        try{
            $list=$this->userOrderService->getPublisherOrderList($publisherId);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$list));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,"获取随友订单失败"));
        }
    }

    /**
     * 随友取消订单
     * @return string
     */
    public function actionPublisherCancelOrder()
    {
        $this->loginValid();
        $orderId=trim(\Yii::$app->request->post("orderId", ""));
        $message=trim(\Yii::$app->request->post("message", ""));
        $publisherId=$this->userPublisherObj->userPublisherId;
        if(empty($orderId)){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
        }
        if(empty($message)) {
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR, "请输入取消原因"));
        }
        if(empty($orderId)){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的随友信息"));
        }

        try{
            $this->userOrderService->publisherCancelOrder($publisherId,$orderId,$message);

            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,"取消订单失败"));
        }
    }

    /**
     * 用户确认游玩
     * @return string
     */
    public function actionUserConfirmPlay()
    {
        $this->loginValid();
        $orderId=trim(\Yii::$app->request->post("orderId", ""));
        if(empty($orderId)){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
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
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,"确认游玩失败"));
        }
    }


    /**
     * 添加用户订单
     * @return void|\yii\web\Response
     * @throws Exception
     * @throws \Exception
     */
    public function actionAddOrder()
    {
        $this->loginValid();
        $tripId=trim(\Yii::$app->request->post("tripId", ""));
        $peopleCount=trim(\Yii::$app->request->post("peopleCount", ""));
        $beginDate=trim(\Yii::$app->request->post("beginDate", ""));
        $startTime=trim(\Yii::$app->request->post("startTime", ""));
        $serviceIds=trim(\Yii::$app->request->post("serviceIds", ""));
        $userSign = $this->userObj->userSign;
        if(empty($userSign)){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,'无效用户'));
        }
        if(empty($tripId)){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"随游编号不正确"));
        }
        if(empty($peopleCount)||$peopleCount==0){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"出行人数不正确"));
        }
        if(empty($beginDate)){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"行程日期不正确"));
        }
        if(empty($startTime)){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"起始时间不正确"));
        }

        if(strtotime($beginDate)<time()){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的出行日期"));
        }

        if(strtotime($beginDate)==strtotime(date('y-M-d'),time())){
            //TODO 判断如果是当天，需要判断当前时间是否正确  并且判断服务时间
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"时间不正确"));
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
                return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"PeopleCount Over Max User Count"));
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
                            break;
                        }
                    }
                }else{
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
            $userOrderInfo->startTime=$startTime;
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
            $sysMessageUtils->sendNewOrderMessage($userSign,$tripPublisherList,$userOrderInfo->orderNumber);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$userOrderInfo->orderNumber));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"系统未知异常"));
        }
    }
//随友订单详情
    public function actionTripOrderInfo()
    {
        try{

            $this->loginValid();
            $userSign=$this->userObj->userSign;
            $userBaseService = new UserBaseService();
            $userPublisherObj=$userBaseService->findUserPublisherByUserSign($userSign);
            if(empty($userPublisherObj)){
                return $this->apiReturn(Code::statusDataReturn(Code::FAIL,'无效的随友信息'));
            }
            $publisherId=$userPublisherObj->userPublisherId;
            $orderNumber=\Yii::$app->request->get('orderNumber');
            if(empty($orderNumber)){
                return $this->apiReturn(Code::statusDataReturn(Code::FAIL,'未知的订单'));
            }
            $info = $this->userOrderService->findOrderByOrderNumber($orderNumber);
            if(empty($info))
            {
                return $this->apiReturn(Code::statusDataReturn(Code::FAIL,'未知的订单'));
            }
            $tripId = $info->tripId;
            $lstPublisher =$this->travelSer->getTravelTripPublisherList($tripId);
            $bo=true;
            foreach($lstPublisher as $publisherInfo)
            {
                if($publisherInfo['publisherId']==$publisherId)
                {
                    $bo=false;
                }
            }
            $orderList = $this->userOrderService->getPublisherOrderList($publisherId);
            foreach($orderList as $orderInfo)
            {
                if($orderInfo['orderNumber']==$orderNumber)
                {
                    $bo=false;
                }
            }
            if($bo)
            {
                return $this->apiReturn(Code::statusDataReturn(Code::FAIL,'无关订单详情'));
            }
            $userSer =new UserBaseService();
            $userInfo = $userSer->findUserByUserSign($info->userId);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,array('info'=>$info,'userInfo'=>$userInfo)));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,'系统异常'));
        }
    }

    //用户订单详情
    public function actionUserOrderInfo()
    {
        try{
            $this->loginValid();
            $userSign=$this->userObj->userSign;
            $orderNumber=\Yii::$app->request->get('orderNumber');
            if(empty($orderNumber)){
                return $this->apiReturn(Code::statusDataReturn(Code::FAIL,'未知的订单'));
            }
            $info = $this->userOrderService->findOrderByOrderNumber($orderNumber);
            if(empty($info)){
                return $this->apiReturn(Code::statusDataReturn(Code::FAIL,'未知的订单'));
            }
            $orderId=$info->orderId;
            $publisherInfo =$this->userOrderService->findPublisherByOrderId($orderId);
            if($userSign!=$info->userId)
            {
                return $this->apiReturn(Code::statusDataReturn(Code::FAIL,'订单用户不匹配'));
            }
            $publisherBase=null;
            if(!empty($publisherInfo))
            {
                $sign=$publisherInfo->userId;
                $userBaseService = new UserBaseService();
                $publisherBase=$userBaseService->findUserByUserSign($sign);
            }
            $contact=$this->userOrderService->getOrderContactByOrderId($orderId);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,array('info'=>$info,'publisherBase'=>$publisherBase,"contact"=>$contact)));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,'系统异常'));
        }
    }

    /**
     * @return string 保存订单联系人
     */
    public function actionOrderContact()
    {
        $this->loginValid();
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
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
        }
        if(empty($username)){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的用户姓名"));
        }
        if(empty($phone)||Validate::validatePhone($phone)!=''){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的手机号"));
        }
        if(empty($urgentUsername)){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的紧急联系人姓名"));
        }
        if(empty($urgentPhone)){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的紧急联系人手机号"));
        }


        try{
            $orderInfo=$this->userOrderService->findOrderByOrderNumber($orderNumber);
            $contact=$this->userOrderService->getOrderContactByOrderId($orderInfo->orderId);
            if($orderInfo->orderNumber!=$orderNumber){
                return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
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
                    return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的接送机号码"));
                }
                if(empty($destination)){
                    return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的目的地"));
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
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }

    }




    public function actionAddTrafficOrder()
    {
        $this->loginValid();
        $tripId=trim(\Yii::$app->request->post("tripId", ""));
        $serviceStr=trim(\Yii::$app->request->post("serviceList", ""));

        if(empty($tripId)){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"随游编号不正确"));
        }
        if(empty($serviceStr)){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"服务列表不能为空"));
        }

        try{
            $serviceList=json_decode($serviceStr,true);
            $tripService=new TripService();
            $travelTripInfo=$tripService->getTravelTripInfoById($tripId);
            $tripInfo=$travelTripInfo['info'];
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
                    return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的出行日期"));
                }
                if(!empty($tripInfo['startTime'])&&!empty($tripInfo['endTime'])){
                    $startTimeNumber=strtotime(date("Y-m-d",time())." ".$tripInfo['startTime']);
                    $endTimeNumber=strtotime(date("Y-m-d",time())." ".$tripInfo['endTime']);
                    $tempServiceInfo=[];
                    if($timeNumber<$startTimeNumber||$timeNumber>$endTimeNumber){
                        return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的出行时间"));
                    }
                }
                if($tempPerson>$tripInfo['maxUserCount']){
                    return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的出行人数"));
                }
                if($tempType=="car"){
                    $tempPrice=$trafficInfo['carPrice'];
                }else{
                    if(self::isNightServiceTime($tempTime,$trafficInfo['nightTimeStart'],$trafficInfo['nightTimeEnd'])){
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
                return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"请选择有效的服务"));
            }

            if($totalPrice==0){
                return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"服务金额不能为0"));
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

            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$userOrderInfo->orderNumber));

        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 判断是否是夜间服务时间
     * @param $choseTime
     * @param $startTime
     * @param $endTime
     * @return bool
     */
    private function isNightServiceTime($choseTime,$startTime,$endTime) {

        $isNight=false;
        //如果结束时间大于开始时间 那么是正常情况
        if($choseTime==$startTime||$choseTime==$endTime){
            return true;
        }
        if(DateUtils::compareTime($endTime,$startTime)){
            if(DateUtils::compareTime($choseTime,$startTime)&&!DateUtils::compareTime($choseTime,$endTime)){
                $isNight=true;
            }
        }else{
            if((DateUtils::compareTime($choseTime,$startTime)&&!DateUtils::compareTime($choseTime,$endTime,1))
                ||(!DateUtils::compareTime($choseTime,$startTime,1)&&DateUtils::compareTime($endTime,$choseTime))){
                $isNight=true;
            }
        }
        return $isNight;
    }
}