<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/5
 * Time : 上午11:47
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\services;


use common\components\Code;
use common\components\SysMessageUtils;
use common\entity\TravelTrip;
use common\entity\TravelTripComment;
use common\entity\UserAccountRecord;
use common\entity\UserMessage;
use common\entity\UserOrderComment;
use common\entity\UserOrderInfo;
use common\entity\UserOrderPublisher;
use common\entity\UserOrderPublisherCancel;
use common\entity\UserOrderPublisherIgnore;
use common\entity\UserOrderRefundApply;
use common\entity\UserPublisher;
use common\models\BaseDb;
use common\models\TravelTripCommentDb;
use common\models\TravelTripDb;
use common\models\UserOrderDb;
use common\models\UserOrderRefundDb;
use frontend\models\UserBaseDb;
use common\models\UserPublisherDb;
use yii\base\Exception;

class UserOrderService extends BaseDb
{

    private $userOrderDb;


    /**
     * 添加订单
     * @param UserOrderInfo $userOrderInfo
     * @throws Exception
     * @throws \Exception
     */
    public function addUserOrder(UserOrderInfo $userOrderInfo)
    {
        try{
            $conn=$this->getConnection();
            $this->userOrderDb=new UserOrderDb($conn);
            $this->userOrderDb->addUserOrderInfo($userOrderInfo);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }

    /**
     * 根据订单号获取订单详情
     * @param $orderNumber
     * @return mixed|null
     * @throws Exception
     * @throws \Exception
     */
    public function findOrderByOrderNumber($orderNumber)
    {
        if(empty($orderNumber)){
            throw new Exception("Order Number Is Not Allow Empty");
        }
        $orderInfo=null;
        try{
            $conn=$this->getConnection();
            $this->userOrderDb=new UserOrderDb($conn);
            $rst=$this->userOrderDb->findOrderByOrderNumber($orderNumber);
            $orderInfo=$this->arrayCastObject($rst,UserOrderInfo::class);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
        return $orderInfo;
    }

    /**
     * 根据Id获取订单详情
     * @param $orderId
     * @return mixed|null
     * @throws Exception
     * @throws \Exception
     */
    public function findOrderByOrderId($orderId)
    {
        if(empty($orderId)){
            throw new Exception("OrderId Is Not Allow Empty");
        }
        $orderInfo=null;
        try{
            $conn=$this->getConnection();
            $this->userOrderDb=new UserOrderDb($conn);
            $rst=$this->userOrderDb->findOrderById($orderId);
            $orderInfo=$this->arrayCastObject($rst,UserOrderInfo::class);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
        return $orderInfo;
    }


    /**
     * 获取未完成订单列表
     * @param $userSign
     * @return array
     * @throws Exception
     * @throws \Exception
     */
    public function getUnFinishOrderList($userSign)
    {
        if(empty($userSign)){
            throw new Exception("UserSign Is Not Allow Empty");
        }
        try{
            $conn = $this->getConnection();
            $this->userOrderDb=new UserOrderDb($conn);
            return $this->userOrderDb->getUnFinishOrderList($userSign);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }

    /**
     * 获取已完成订单列表
     * @param $userSign
     * @return array
     * @throws Exception
     * @throws \Exception
     */
    public function getFinishOrderList($userSign)
    {
        if(empty($userSign)){
            throw new Exception("UserSign Is Not Allow Empty");
        }
        try{
            $conn = $this->getConnection();
            $this->userOrderDb=new UserOrderDb($conn);
            return $this->userOrderDb->getFinishOrderList($userSign);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }

    /**
     * 获取用户未确认的订单
     * @param $publisherId
     * @return array
     * @throws Exception
     * @throws \Exception
     */
    public function getUnConfirmOrderByPublisher($publisherId)
    {
        if(empty($publisherId)){
            throw new Exception("PublisherId Is Not Allow Empty");
        }
        try{
            $conn = $this->getConnection();
            $this->userOrderDb=new UserOrderDb($conn);
            return $this->userOrderDb->getUnConfirmOrderByPublisher($publisherId);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }


    /**
     * 随友确认用户订单
     * @param $orderId
     * @param $publisherId
     * @throws Exception
     * @throws \Exception
     */
    public function publisherConfirmOrder($orderId,$publisherId)
    {
        if(empty($orderId)){
            throw new Exception("OrderId Is Not Allow Empty");
        }
        if(empty($publisherId)){
            throw new Exception("PublisherId Is Not Allow Empty");
        }

        $conn=$this->getConnection();
        $tran=$conn->beginTransaction();
        try{
            $this->userOrderDb=new UserOrderDb($conn);
            $travelTripDb=new TravelTripDb($conn);
            $orderInfo=$this->userOrderDb->findOrderById($orderId);
            $publisherUserSign="";
            if($orderInfo['isDel']){
                throw new Exception("Invalid Order Info");
            }
            //必须是支付成功状态，才能被抢单
            if($orderInfo['status']!=UserOrderInfo::USER_ORDER_STATUS_PAY_SUCCESS){
                throw new Exception("Invalid Order Info");
            }
            //判断随友是否有权限去接此随游
            $publisherFlag=false;
            $publisherList=$travelTripDb->getTravelTripPublisherList($orderInfo['tripId']);
            foreach($publisherList as $publisher){
                if($publisher['publisherId']==$publisherId){
                    $publisherUserSign=$publisher['userSign'];
                    $publisherFlag=true;
                    break;
                }
            }
            if(!$publisherFlag){
                throw new Exception("Invalid Publisher");
            }
            //判断时间是否在接单范围 如果订单时间大于于当前，则可接单
            $orderTime=$orderInfo['beginDate']." ".$orderInfo['startTime'];
            if(strtotime($orderTime)<time()){
                throw new Exception("Invalid Order Times");
            }
            //随友不能自己接自己的订单
            if($publisherUserSign==$orderInfo['userId']){
                throw new Exception("Publisher Not Allow Equals User");
            }

            $userOrderPublisher=new UserOrderPublisher();
            $userOrderPublisher->orderId=$orderId;
            $userOrderPublisher->publisherId=$publisherId;

            $this->userOrderDb->addUserOrderPublisher($userOrderPublisher);//添加随友接单信息
            $this->userOrderDb->changeOrderStatus($orderId,UserOrderInfo::USER_ORDER_STATUS_CONFIRM);//改变订单状态

            $this->commit($tran);
            //随友确认订单，给用户发送消息提醒
            $sysMessageUtils=new SysMessageUtils();
            $sysMessageUtils->sendPublisherConfirmOrderMessage($orderInfo['userId'],$orderInfo['orderNumber']);
        }catch (Exception $e){
            $this->rollback($tran);
            throw $e;
        }finally{
            $this->closeLink();
        }

    }


    /**
     * 随友忽略订单
     * @param $orderId
     * @param $publisherId
     * @throws Exception
     * @throws \Exception
     */
    public function publisherIgnoreOrder($orderId,$publisherId)
    {
        if(empty($orderId)){
            throw new Exception("OrderId Is Not Allow Empty");
        }
        if(empty($publisherId)){
            throw new Exception("PublisherId Is Not Allow Empty");
        }

        try{
            $conn=$this->getConnection();
            $this->userOrderDb=new UserOrderDb($conn);

            $userOrderPublisherIgnore=new UserOrderPublisherIgnore();
            $userOrderPublisherIgnore->orderId=$orderId;
            $userOrderPublisherIgnore->publisherId=$publisherId;
            $this->userOrderDb->addUserOrderPublisherIgnore($userOrderPublisherIgnore);

        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }


    /**
     * 改变订单状态
     * @param $orderNumber
     * @param $status
     * @throws Exception
     * @throws \Exception
     */
    public function changeOrderStatus($orderNumber,$status)
    {
        if(empty($orderNumber)){
            throw new Exception("OrderId Is Not Allow Empty");
        }

        try{
            $conn=$this->getConnection();
            $this->userOrderDb=new UserOrderDb($conn);

            $orderInfo=$this->userOrderDb->findOrderByOrderNumber($orderNumber);
            switch($status){
                //如果改变订单状态为确认付款，那么当前状态必须为 待付款
                case UserOrderInfo::USER_ORDER_STATUS_PAY_SUCCESS:
                    if($orderInfo['status']!=UserOrderInfo::USER_ORDER_STATUS_PAY_WAIT){
                        throw new Exception("Invalid UserOrderStatus ");
                    }
                    break;
                //如果改变订单状态为确认游玩，那么当前状态必须为 已确认
                case UserOrderInfo::USER_ORDER_STATUS_PLAY_SUCCESS:
                    if($orderInfo['status']!=UserOrderInfo::USER_ORDER_STATUS_CONFIRM){
                        throw new Exception("Invalid UserOrderStatus ");
                    }
                    break;
                //如果改变订单状态为确认订单，那么当前状态必须为 已付款
                case UserOrderInfo::USER_ORDER_STATUS_CONFIRM:
                    if($orderInfo['status']!=UserOrderInfo::USER_ORDER_STATUS_PAY_SUCCESS){
                        throw new Exception("Invalid UserOrderStatus ");
                    }
                    break;
                default:
            }


            $this->userOrderDb->changeOrderStatus($orderInfo['orderId'],$status);

        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }


    /**
     * 用户提交退款申请 退款将进入审核阶段
     * @param $userId
     * @param $orderId
     * @param $message
     * @throws Exception
     * @throws \Exception
     */
    public function userRefundOrder($userId,$orderId,$message)
    {
        if(empty($userId)){
            throw new Exception("UserId Is Not Allow Empty");
        }
        if(empty($orderId)){
            throw new Exception("OrderId Is Not Allow Empty");
        }
        if(empty($message)){
            throw new Exception("Message Is Not Allow Empty");
        }

        try{

            $conn=$this->getConnection();
            $this->userOrderDb=new UserOrderDb($conn);
            $userOrderRefundDb=new UserOrderRefundDb($conn);
            $orderInfo=$this->userOrderDb->findOrderById($orderId);
            if($orderInfo['status']!=UserOrderInfo::USER_ORDER_STATUS_CONFIRM){
                throw new Exception("Invalid Order Status");
            }
            if($orderInfo['userId']!=$userId){
                throw new Exception("Invalid User");
            }
            $userOrderRefundApply=new UserOrderRefundApply();
            $userOrderRefundApply->orderId=$orderInfo['orderId'];
            $userOrderRefundApply->userId=$orderInfo['userId'];
            $userOrderRefundApply->tripId=$orderInfo['tripId'];
            $userOrderRefundApply->applyContent=$message;
            $userOrderRefundApply->status=UserOrderRefundApply::USER_ORDER_REFUND_APPLY_STATUS_WAIT;

            $userOrderRefundDb->addUserOrderRefundApply($userOrderRefundApply);
            $this->userOrderDb->changeOrderStatus($orderId,UserOrderInfo::USER_ORDER_STATUS_REFUND_VERIFY);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }


    /**
     * 用户删除订单
     * @param $userId
     * @param $orderId
     * @throws Exception
     * @throws \Exception
     */
    public function deleteOrderInfo($userId,$orderId)
    {
        if(empty($userId)){
            throw new Exception("UserId Is Not Allow Empty");
        }
        if(empty($orderId)){
            throw new Exception("OrderId Is Not Allow Empty");
        }

        try{
            $conn=$this->getConnection();
            $this->userOrderDb=new UserOrderDb($conn);
            $orderInfo=$this->userOrderDb->findOrderById($orderId);
            if($orderInfo['status']!=UserOrderInfo::USER_ORDER_STATUS_PLAY_SUCCESS&&
                $orderInfo['status']!=UserOrderInfo::USER_ORDER_STATUS_PLAY_FINISH&&
                $orderInfo['status']!=UserOrderInfo::USER_ORDER_STATUS_CANCELED&&
                $orderInfo['status']!=UserOrderInfo::USER_ORDER_STATUS_PUBLISHER_CANCEL&&
                $orderInfo['status']!=UserOrderInfo::USER_ORDER_STATUS_REFUND_SUCCESS
            ){
                throw new Exception("Invalid Order Status");
            }
            if($orderInfo['userId']!=$userId){
                throw new Exception("Invalid User");
            }
            $this->userOrderDb->deleteOrderInfo($orderId);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }

    /**
     * 获取随友已接单列表
     * @param $publisherId
     * @return array|null
     * @throws Exception
     * @throws \Exception
     */
    public function getPublisherOrderList($publisherId)
    {
        if(empty($publisherId)){
            throw new Exception("PublisherId Is Not Allow Empty");
        }
        $list=null;
        try{
            $conn = $this->getConnection();
            $this->userOrderDb=new UserOrderDb($conn);
            $list=$this->userOrderDb->getPublisherOrderList($publisherId);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
        return $list;
    }


    /**
     * 根据订单获取随友信息
     * @param $orderId
     * @return array|bool|null
     * @throws Exception
     * @throws \Exception
     */
    public function findPublisherByOrderId($orderId)
    {
        if(empty($orderId)){
            throw new Exception("OrderId Is Not Allow Empty");
        }
        $userPublisher=null;
        try{
            $conn = $this->getConnection();
            $this->userOrderDb=new UserOrderDb($conn);
            $userPublisher=$this->userOrderDb->findPublisherByOrderId($orderId);
            $userPublisher=$this->arrayCastObject($userPublisher,UserPublisher::class);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
        return $userPublisher;
    }


    /**
     * 随友取消订单
     * @param $publisherId
     * @param $orderId
     * @param $message
     * @throws Exception
     * @throws \Exception
     */
    public function publisherCancelOrder($publisherId,$orderId,$message)
    {
        if(empty($orderId)){
            throw new Exception("OrderId Is Not Allow Empty");
        }
        if(empty($publisherId)){
            throw new Exception("OrderId Is Not Allow Empty");
        }
        if(empty($message)){
            throw new Exception("Message Is Not Allow Empty");
        }

        $orderInfo=$this->findOrderByOrderId($orderId);
        $userPublisher=$this->findPublisherByOrderId($orderId);

        if(empty($orderInfo)){
           throw new Exception("Invalid OrderId");
        }
        if($orderInfo->status!=UserOrderInfo::USER_ORDER_STATUS_CONFIRM){
            throw new Exception("Invalid Order Status");
        }
        if(empty($userPublisher)){
            throw new Exception("Invalid Publisher");
        }
        if($userPublisher->userPublisherId!=$publisherId){
            throw new Exception("Invalid Publisher Power");
        }
        $conn = $this->getConnection();
        $tran=$conn->beginTransaction();
        try{
            $this->userOrderDb=new UserOrderDb($conn);
            $userPublisher=$this->userOrderDb->findPublisherByOrderId($orderId);
            $userOrderPublisherCancel=new UserOrderPublisherCancel();
            $userOrderPublisherCancel->orderId=$orderId;
            $userOrderPublisherCancel->publisherId=$publisherId;
            $userOrderPublisherCancel->content=$message;
            $userOrderPublisherCancel->status=UserOrderPublisherCancel::USER_ORDER_PUBLISHER_CANCEL_STATUS_WAIT;
            //TODO 判断时间限制
            $this->userOrderDb->changeOrderStatus($orderId,UserOrderInfo::USER_ORDER_STATUS_PUBLISHER_CANCEL);
            $this->userOrderDb->addUserOrderPublisherCancel($userOrderPublisherCancel);
            $this->arrayCastObject($userPublisher,UserPublisher::class);
            $this->commit($tran);

            //给随友发送消息
            $sysMessageUtil=new SysMessageUtils();
            $sysMessageUtil->sendPublisherCancelOrderMessage($orderInfo->userId,$orderInfo->orderNumber);

        }catch (Exception $e){
            $this->rollback($tran);
            throw $e;
        }finally{
            $this->closeLink();
        }
    }


    /**
     * 用户确认游玩
     * @param $orderId
     * @param $userSign
     * @throws Exception
     * @throws \Exception
     */
    public function userConfirmPlay($orderId,$userSign)
    {
        if(empty($orderId)){
            throw new Exception("OrderId Is Not Allow Empty");
        }
        $orderInfo=$this->findOrderByOrderId($orderId);
        if($orderInfo==null){
            throw new Exception("Invalid Order Id");
        }
        //1.判断状态是否可以确认游玩（如果状态不是确认订单状态，那么抛出异常）
        if($orderInfo->status!=UserOrderInfo::USER_ORDER_STATUS_CONFIRM){
            throw new Exception("Invalid Order Status");
        }
        if($orderInfo->userId!=$userSign){
            throw new Exception("Invalid User");
        }
        $userPublisher=$this->findPublisherByOrderId($orderId);
        if($userPublisher==null){
            throw new Exception("Invalid Publisher");
        }
        $tripJsonInfo=json_decode($orderInfo->tripJsonInfo,true);
        $tripInfo=$tripJsonInfo['info'];

        $conn = $this->getConnection();
        $tran = $conn->beginTransaction();
        try{
            $this->userOrderDb=new UserOrderDb($conn);
            $userBaseDb=new UserBaseDb($conn);
            $userPublisherDb=new UserPublisherDb($conn);
            $tripDb=new TravelTripDb($conn);
            //2.更新用户游玩次数
            $userBaseDb->addUserTravelCount($userSign);
            //3.更新随友带队次数
            $userPublisherDb->addPublisherLeadCount($userPublisher->userPublisherId);
            //4.更新随游次数
            $tripDb->addTravelTripCount($orderInfo->tripId);
            //5.改变订单状态为确认游玩
            $this->userOrderDb->changeOrderStatus($orderId,UserOrderInfo::USER_ORDER_STATUS_PLAY_SUCCESS);
            //6.更新随友账户余额
            $userMoney=$this->userOrderDb->getUserMoney($userPublisher->userId);
            $balance=$userMoney['balance']+$orderInfo->totalPrice;
            $version=$userMoney['version'];
            $rstCount=$this->userOrderDb->updateUserBaseMoney($userPublisher->userId,$balance,$version);
            if($rstCount==0){
                throw new Exception("Invalid UserBase Version");
            }
            //7.添加随友账户记录
            $userAccountRecord=new UserAccountRecord();
            $userAccountRecord->userId=$userPublisher->userId;
            $userAccountRecord->money=$orderInfo->totalPrice;
            $userAccountRecord->balance=$balance;
            $userAccountRecord->info=$tripInfo['title'];
            $userAccountRecord->type=UserAccountRecord::USER_ACCOUNT_RECORD_TYPE_TRIP_SERVER;
            $this->userOrderDb->addUserAccountRecord($userAccountRecord);
            $this->commit($tran);
        }catch (Exception $e){
            $this->rollback($tran);
            throw $e;
        }finally{
            $this->closeLink();
        }
    }

    /**
     * 根据订单获取订单评论
     * @param $orderId
     * @return array|bool|mixed|null
     * @throws Exception
     * @throws \Exception
     */
    public function findUserOrderCommentByOrderId($orderId)
    {
        if(empty($orderId)){
            throw new Exception("OrderId Is Not Allow Empty");
        }
        $userOrderComment=null;
        try{
            $conn = $this->getConnection();
            $this->userOrderDb=new UserOrderDb($conn);
            $userOrderComment=$this->userOrderDb->findUserOrderCommentByOrderId($orderId);
            $userOrderComment=$this->arrayCastObject($userOrderComment,UserPublisher::class);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
        return $userOrderComment;
    }

    /**
     * 添加用户订单评论
     * @param UserOrderComment $userOrderComment
     * @throws Exception
     * @throws \Exception
     */
    public function addUserOrderComment(UserOrderComment $userOrderComment)
    {

        $conn = $this->getConnection();
        $tran=$conn->beginTransaction();
        try{

            $this->userOrderDb=new UserOrderDb($conn);
            $userPublisherDb=new UserPublisherDb($conn);
            $travelTripDb=new TravelTripDb($conn);
            $tripCommentDb=new TravelTripCommentDb($conn);
            //修改随游评分
            $tripInfo=$travelTripDb->findTravelTripById($userOrderComment->tripId);
            $tripInfo=$this->arrayCastObject($tripInfo,TravelTrip::class);
            $allScore=$tripInfo->score*($tripInfo->tripCount-1)+$userOrderComment->tripScore;
            $nowScore=$allScore/($tripInfo->tripCount);
            $tripInfo->score=$nowScore;
            $travelTripDb->updateTravelTrip($tripInfo);
            //修改随友评分
            $userPublisher=$userPublisherDb->findUserPublisherById($userOrderComment->publisherId);
            $userPublisher=$this->arrayCastObject($userPublisher,UserPublisher::class);
            $allScore=$userPublisher->score*($userPublisher->leadCount-1)+$userOrderComment->publisherScore;
            $nowScore=$allScore/($userPublisher->leadCount);
            $userPublisher->score=$nowScore;
            $userPublisherDb->updateUserPublisher($userPublisher);
            //添加评论信息
            $tripComment=new TravelTripComment();
            $tripComment->isTravel=TRUE;
            $tripComment->content=$userOrderComment->content;
            $tripComment->userSign=$userOrderComment->userId;
            $tripComment->tripId=$userOrderComment->tripId;
            $tripCommentDb->addTripComment($tripComment);

            $this->userOrderDb->addUserOrderComment($userOrderComment);
            $this->commit($tran);
        }catch (Exception $e){
            $this->rollback($tran);
            throw $e;
        }finally{
            $this->closeLink();
        }
    }




}