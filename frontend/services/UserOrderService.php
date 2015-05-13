<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/5
 * Time : 上午11:47
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\services;


use common\entity\UserOrderInfo;
use common\entity\UserOrderPublisher;
use common\entity\UserOrderPublisherIgnore;
use common\models\BaseDb;
use common\models\TravelTripDb;
use common\models\UserOrderDb;
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
                    $publisherFlag=true;
                    break;
                }
            }
            if(!$publisherFlag){
                throw new Exception("Invalid Publisher");
            }

            $userOrderPublisher=new UserOrderPublisher();
            $userOrderPublisher->orderId=$orderId;
            $userOrderPublisher->publisherId=$publisherId;

            $this->userOrderDb->addUserOrderPublisher($userOrderPublisher);//添加随友接单信息
            $this->userOrderDb->changeOrderStatus($orderId,UserOrderInfo::USER_ORDER_STATUS_CONFIRM);//改变订单状态

            $this->commit($tran);
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


}