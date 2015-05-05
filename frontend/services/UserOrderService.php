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
use common\models\BaseDb;
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
     * @return array
     * @throws Exception
     * @throws \Exception
     */
    public function getUnFinishOrderList($userSign)
    {
        try{
            $conn = $this->getConnection();
            $this->userOrderDb=new UserOrderDb($conn);
            return $this->userOrderDb->getUnFinishOrderList($userSign);
        }catch (Exception $e){
            throw $e;
        }
    }

    /**
     * 获取已完成订单列表
     * @return array
     * @throws Exception
     * @throws \Exception
     */
    public function getFinishOrderList($userSign)
    {
        try{
            $conn = $this->getConnection();
            $this->userOrderDb=new UserOrderDb($conn);
            return $this->userOrderDb->getFinishOrderList($userSign);
        }catch (Exception $e){
            throw $e;
        }
    }


}