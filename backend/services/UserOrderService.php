<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/6/16
 * Time : 下午5:19
 * Email: zhangxinmailvip@foxmail.com
 */

namespace backend\services;


use backend\components\Page;
use backend\models\UserBaseDb;
use common\models\BaseDb;
use common\models\ProxyDb;
use common\models\UserOrderDb;
use yii\base\Exception;

class UserOrderService extends  BaseDb{

    private $userOrderDb;


    /**
     * 获取订单列表
     * @param Page $page
     * @param $search
     * @param $beginTime
     * @param $endTime
     * @param $status
     * @return Page
     * @throws Exception
     * @throws \Exception
     */
    public function getOrderList(Page $page,$search,$beginTime,$endTime,$status)
    {
        try{
            $conn=$this->getConnection();
            $this->userOrderDb=new UserOrderDb($conn);
            $page=$this->userOrderDb->getOrderList($page,$search,$beginTime,$endTime,$status);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
        return $page;
    }


    /**
     * 获取订单详情
     * @param $orderId
     * @return array
     * @throws Exception
     * @throws \Exception
     */
    public function getOrderInfo($orderId)
    {
        if(empty($orderId)){
            throw new Exception("OrderId Is Not Allow Empty");
        }
        $orderInfo=[];
        try{
            $conn=$this->getConnection();
            $this->userOrderDb=new UserOrderDb($conn);
            $userBase=new UserBaseDb($conn);
            $orderInfo['info']=$this->userOrderDb->findOrderById($orderId);
            $orderInfo['user']=$userBase->findByUserSign($orderInfo['info']['userId']);
            $orderInfo['publisher']=$this->userOrderDb->findPublisherByOrderId($orderId);

        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
        return $orderInfo;
    }
}