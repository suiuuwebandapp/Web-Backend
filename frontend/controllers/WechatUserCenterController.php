<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/6/23
 * Time: 下午4:31
 */

namespace frontend\controllers;


use common\components\Code;
use common\components\LogUtils;
use common\components\SysMessageUtils;
use common\entity\TravelTripService;
use common\entity\UserOrderInfo;
use common\entity\WeChatUserInfo;
use frontend\components\Page;
use frontend\services\PublisherService;
use frontend\services\TripService;
use frontend\services\UserBaseService;
use frontend\services\UserMessageRemindService;
use frontend\services\UserOrderService;
use frontend\services\WeChatService;
use yii\base\Exception;

class WechatUserCenterController extends WController {
    public $layout=false;
    public $enableCsrfValidation=false;
    public $userOrderService =null ;
    private $tripSer;
    public function __construct($id, $module = null)
    {
        $this->userOrderService = new UserOrderService();
        $this->tripSer=new TripService();
        parent::__construct($id, $module);
    }

    public function actionIndex()
    {
        $login = $this->loginValid();
        if(!$login){
            return $this->redirect(['/we-chat/login']);
        }
        $userSign=$this->userObj->userSign;
        $publisherService=new PublisherService();
        $createPublisherInfo = $publisherService->findUserPublisherByUserSign($userSign);
        if(isset($createPublisherInfo->userPublisherId)){
             $this->redirect(['/wechat-user-center/my-trip']);
        }else{
             $this->redirect(['/wechat-user-center/my-order']);
        }
    }

    public function actionMyTrip()
    {
        $login = $this->loginValid();
        if(!$login){
            return $this->redirect(['/we-chat/login']);
        }
        $userSign=$this->userObj->userSign;
        $publisherService=new PublisherService();
        $createPublisherInfo = $publisherService->findUserPublisherByUserSign($userSign);
        $myList=array();
        if(!empty($createPublisherInfo)){
            $userPublisherId=$createPublisherInfo->userPublisherId;
            $myList=$this->tripSer->getMyTripList($userPublisherId);
        }
        return $this->renderPartial('myTrip',['list'=>$myList,'userObj'=>$this->userObj,'active'=>5,'newMsg'=>0]);
    }


    /**
     * 我的订单  //只有未完成订单列表
     */
    public function actionMyOrder()
    {
        $login = $this->loginValid();
        if(!$login){
        return $this->redirect(['/we-chat/login']);
        }
        try{
            $userSign=$this->userObj->userSign;
            $unList=$this->userOrderService->getUnFinishOrderList($userSign);
            $list=$this->userOrderService->getFinishOrderList($userSign);
            return $this->renderPartial('myOrder',['list'=>$list,'unList'=>$unList,'userObj'=>$this->userObj,'active'=>3,'newMsg'=>0]);
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->redirect('/we-chat/error?str="系统异常"');
        }

    }

    /**
     * 随游订单
     * @return string|\yii\web\Response
     * @throws Exception
     * @throws \Exception
     */
    public function actionTripOrder()
    {
        $login = $this->loginValid();
        if(!$login){
            return $this->redirect(['/we-chat/login']);
        }
        $userSign=$this->userObj->userSign;
        $userBaseService = new UserBaseService();
        $userPublisherObj=$userBaseService->findUserPublisherByUserSign($userSign);
        if(empty($userPublisherObj)){
            return $this->redirect('/we-chat/error?str=无效的随友信息');
        }
        $publisherId=$userPublisherObj->userPublisherId;
        $list=$this->userOrderService->getPublisherOrderList($publisherId);
        $newList=$this->userOrderService->getUnConfirmOrderByPublisher($publisherId);
        return $this->renderPartial('tripOrder',['list'=>$list,'newList'=>$newList,'userObj'=>$this->userObj,'active'=>6,'newMsg'=>0]);
    }

    public function actionTripOrderInfo()
    {
        try{

            $login = $this->loginValid();
            if(!$login){
                return $this->redirect(['/we-chat/login']);
            }
            $userSign=$this->userObj->userSign;
            $userBaseService = new UserBaseService();
            $userPublisherObj=$userBaseService->findUserPublisherByUserSign($userSign);
            if(empty($userPublisherObj)){
                return $this->redirect('/we-chat/error?str=无效的随友信息');
            }
            $publisherId=$userPublisherObj->userPublisherId;
            $orderNumber=\Yii::$app->request->get('id');
            if(empty($orderNumber)){
               return $this->redirect('/we-chat/error?str=未知的订单&url=javascript:history.go(-1);');
              }
            $info = $this->userOrderService->findOrderByOrderNumber($orderNumber);
            if(empty($info))
            {
                return $this->redirect('/we-chat/error?str=未知订单');
            }
            $tripId = $info->tripId;
            $lstPublisher =$this->tripSer->getTravelTripPublisherList($tripId);
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
                return $this->redirect('/we-chat/error?str=无关订单详情');
            }
            $userSer =new UserBaseService();
            $userInfo = $userSer->findUserByUserSign($info->userId);
            return $this->renderPartial('tripOrderInfo',['info'=>$info,'userInfo'=>$userInfo]);
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->redirect('/we-chat/error?str=系统异常');
        }
    }

    public function actionMyOrderInfo()
    {
        try{
            $login = $this->loginValid();
            if(!$login){
                return $this->redirect(['/we-chat/login']);
            }
            $userSign=$this->userObj->userSign;
            $orderNumber=\Yii::$app->request->get('id');

            if(empty($orderNumber)){
                return $this->redirect('/we-chat/error?str=未知的订单&url=javascript:history.go(-1);');
            }
            $info = $this->userOrderService->findOrderByOrderNumber($orderNumber);
            if(empty($info))
            {
                return $this->redirect('/we-chat/error?str=未知订单');
            }
            $orderId=$info->orderId;
            $publisherInfo =$this->userOrderService->findPublisherByOrderId($orderId);
            if($userSign!=$info->userId)
            {
                return $this->redirect('/we-chat/error?str=订单用户不匹配');
            }
            $publisherBase=null;
            if(!empty($publisherInfo))
            {
                $sign=$publisherInfo->userId;
                $userBaseService = new UserBaseService();
                $publisherBase=$userBaseService->findUserByUserSign($sign);
            }
            return $this->renderPartial('myOrderInfo',['info'=>$info,'publisherBase'=>$publisherBase,'userObj'=>$this->userObj,'active'=>6,'newMsg'=>0]);
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->redirect('/we-chat/error?str="系统异常"');
        }
    }

    /**
     * 确认订单
     */
    public function actionPublisherConfirmOrder()
    {
        $login = $this->loginValid();
        if(!$login){
            return $this->redirect(['/we-chat/login']);
        }
        $orderId=trim(\Yii::$app->request->post("orderId", ""));
        try{
            $userSign=$this->userObj->userSign;
            $userBaseService = new UserBaseService();
            $userPublisherObj=$userBaseService->findUserPublisherByUserSign($userSign);
            if(empty($userPublisherObj)){
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的随友信息"));
            }
            $publisherId=$userPublisherObj->userPublisherId;
        if(empty($orderId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
        }
        if(empty($publisherId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的随友信息"));
        }

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
        $login = $this->loginValid();
        if(!$login){
            return $this->redirect(['/we-chat/login']);
        }
        $orderId=trim(\Yii::$app->request->post("orderId", ""));
        $userSign=$this->userObj->userSign;
        $userBaseService = new UserBaseService();
        $userPublisherObj=$userBaseService->findUserPublisherByUserSign($userSign);
        $publisherId=$userPublisherObj->userPublisherId;
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
     * 尚未接单情况，直接取消订单
     * @return string
     */
    public function actionCancelOrder()
    {
        $login = $this->loginValid();
        if(!$login){
            return $this->redirect(['/we-chat/login']);
        }
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
        $login = $this->loginValid();
        if(!$login){
            return $this->redirect(['/we-chat/login']);
        }
        $orderId=trim(\Yii::$app->request->post("orderId", ""));

        if(empty($orderId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
        }

        try{
            $orderInfo=$this->userOrderService->findOrderByOrderId($orderId);
            if(empty($orderInfo)){
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
            }
            if($orderInfo->userId!=$this->userObj->userSign){
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"您没有权限申请退款"));
            }
            if($orderInfo->status!=UserOrderInfo::USER_ORDER_STATUS_PAY_SUCCESS){
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"订单暂时无法申请退款"));
            }
            $this->userOrderService->changeOrderStatus($orderInfo->orderNumber,UserOrderInfo::USER_ORDER_STATUS_REFUND_WAIT);
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
        $login = $this->loginValid();
        if(!$login){
            return $this->redirect(['/we-chat/login']);
        }
        $orderId=trim(\Yii::$app->request->post("orderId", ""));
        $message=trim(\Yii::$app->request->post("message", ""));

        if(empty($orderId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
        }
        if(empty($message)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"请填写退款申请"));
        }
        try{
            $this->userOrderService->userRefundOrder($this->userObj->userSign,$orderId,$message);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"申请退款失败"));
        }
    }

    public function actionApplyRefund()
    {
        $orderId=trim(\Yii::$app->request->get("id", ""));
        return $this->renderPartial("applyRefund",['orderId'=>$orderId]);
    }

    /**
     * 用户删除订单
     * @return string
     */
    public function actionDeleteOrder()
    {
        $login = $this->loginValid();
        if(!$login){
            return $this->redirect(['/we-chat/login']);
        }
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
     * 随友取消订单
     * @return string
     */
    public function actionPublisherCancelOrder()
    {
        $login = $this->loginValid();
        if(!$login){
            return $this->redirect(['/we-chat/login']);
        }
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
        $login = $this->loginValid();
        if(!$login){
            return $this->redirect(['/we-chat/login']);
        }
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
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"确认游玩失败"));
        }
    }

    public function actionGetUserRemind()
    {
        try{
            $login = $this->loginValid();
            if(!$login){
                return $this->redirect(['/we-chat/login']);
            }
            $userSign=$this->userObj->userSign;
            $userMessageSer = new UserMessageRemindService();
            $page  = new Page(\Yii::$app->request);
            $rst = $userMessageSer->getWebSysMessage($userSign,$page,null);
            return $this->renderPartial('messageRemind',['list'=>$rst,'userObj'=>$this->userObj,'active'=>4,'newMsg'=>0]);
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->redirect('/we-chat/error?str=获取消息异常');
        }
    }
}