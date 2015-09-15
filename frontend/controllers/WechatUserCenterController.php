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
use common\components\Validate;
use common\entity\TravelTripService;
use common\entity\UserOrderContact;
use common\entity\UserOrderInfo;
use common\entity\WeChatUserInfo;
use frontend\components\Page;
use frontend\services\PublisherService;
use frontend\services\TripService;
use frontend\services\UserBaseService;
use frontend\services\UserMessageRemindService;
use frontend\services\UserMessageService;
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
            $allList = array_merge($unList,$list);
            return $this->renderPartial('myOrder',['list'=>$list,'unList'=>$unList,'allList'=>$allList,'userObj'=>$this->userObj,'active'=>3,'newMsg'=>0]);
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
            return $this->renderPartial('tripOrderInfo',['info'=>$info,'userInfo'=>$userInfo,'userObj'=>$this->userObj,'active'=>6,'newMsg'=>0]);
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
            $contact=$this->userOrderService->getOrderContactByOrderId($info->orderId);
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
            return $this->renderPartial('myOrderInfo',['info'=>$info,"contact"=>$contact,'publisherBase'=>$publisherBase,'userObj'=>$this->userObj,'active'=>3,'newMsg'=>0]);
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
     * @return string 保存订单联系人
     */
    public function actionOrderContact()
    {

        if(empty($_POST))
        {
            $login = $this->loginValid();
            if(!$login){
                return $this->redirect(['/we-chat/login']);
            }
            $orderNumber=\Yii::$app->request->get('orderNumber');
            $orderInfo=$this->userOrderService->findOrderByOrderNumber($orderNumber);
            if(empty($orderInfo))
            {
                return $this->redirect('/we-chat/error?str=未知订单');
            }
            $serviceInfo=json_decode($orderInfo->serviceInfo,true);
            $hasAirplane=false;
            foreach($serviceInfo as $service){
                if($service['type']=='airplane'){
                    $hasAirplane=true;
                }
            }
            $contact=$this->userOrderService->getOrderContactByOrderId($orderInfo->orderId);
            if(empty($contact))
            {
                $contact=new UserOrderContact();
            }
            return $this->renderPartial("perfectOrder",["orderNumber"=>$orderNumber,"hasAirplane"=>$hasAirplane,'orderInfo'=> $orderInfo,"contact"=>$contact,'userObj'=>$this->userObj,'active'=>4,'newMsg'=>0]);
        }
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
            $page->showAll=true;
            $list = $userMessageSer->getWebSysMessage($userSign,$page,null);
            //用户会话列表
            $userMessageService=new UserMessageService();
            $sessionList=$userMessageService->getUserMessageSessionList($userSign);
            return $this->renderPartial('messageRemind',['list'=>$list,"sessionList"=>$sessionList,'userObj'=>$this->userObj,'active'=>4,'newMsg'=>0]);
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->redirect('/we-chat/error?str=获取消息异常');
        }
    }

    public function actionUserMessageInfo()
    {
        try{
            $login = $this->loginValid();
            if(!$login){
                return $this->redirect(['/we-chat/login']);
            }
            $rUserSign =\Yii::$app->request->get("rUserSign");
            $userSign=$this->userObj->userSign;
            $sessionKey = $this->getMessageSessionKey($userSign,$rUserSign);
            //用户会话列表
            $userMessageService=new UserMessageService();
            $userBaseService = new UserBaseService();
            $rInfo = $userBaseService->findBaseInfoBySign($rUserSign);
            $list=$userMessageService->getUserMessageSessionInfo($userSign,$sessionKey);
            /*if(empty($list)){
                return $this->redirect('/we-chat/error?str=未知的会话列表');
            }*/
            return $this->renderPartial('messageInfo',["list"=>$list,"userSign"=>$userSign,'rInfo'=>$rInfo,'userObj'=>$this->userObj,'active'=>4,'newMsg'=>0]);
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->redirect('/we-chat/error?str=获取消息异常');
        }
    }
    /**
     * 生成SessionKey
     * @param $senderId
     * @param $receiveId
     * @return string
     */
    private function getMessageSessionKey($senderId,$receiveId)
    {
        //暂时修改为两个会话
        if($senderId>$receiveId){
            return md5($senderId.$receiveId);
        }else{
            return md5($receiveId.$senderId);
        }
    }

    /**
     * 更新系统消息已读
     */
    public function actionChangeSystemMessageRead()
    {
        $this->loginValidJson();
        $messageId=trim(\Yii::$app->request->post("messageId"));
        if(empty($messageId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的消息"));
        }
        try{
            $userSign=$this->userObj->userSign;
            $userMessageRemindService=new UserMessageRemindService();
            $userMessageRemindService->deleteUserMessageRemind($messageId,$userSign);
            //$userMessageSetting=$this->userMessageService->changeSystemMessageRead($messageId,$userSign);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }
}