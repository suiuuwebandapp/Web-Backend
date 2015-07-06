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
use common\entity\UserOrderInfo;
use common\entity\UserOrderRefund;
use common\entity\UserOrderRefundApply;
use common\models\BaseDb;
use common\models\UserOrderDb;
use common\models\UserOrderRefundDb;
use yii\base\Exception;

class UserOrderService extends BaseDb
{

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
    public function getOrderList(Page $page, $search, $beginTime, $endTime, $status)
    {
        try {
            $conn = $this->getConnection();
            $this->userOrderDb = new UserOrderDb($conn);
            $page = $this->userOrderDb->getAllOrderList($page, $search, $beginTime, $endTime, $status);
        } catch (Exception $e) {
            throw $e;
        } finally {
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
    public function findOrderInfo($orderId)
    {
        if (empty($orderId)) {
            throw new Exception("OrderId Is Not Allow Empty");
        }
        $orderInfo = [];
        try {
            $conn = $this->getConnection();
            $this->userOrderDb = new UserOrderDb($conn);
            $userBase = new UserBaseDb($conn);
            $orderInfo['info'] = $this->userOrderDb->findOrderById($orderId);
            $orderInfo['user'] = $userBase->findByUserSign($orderInfo['info']['userId']);
            $orderInfo['publisher'] = $this->userOrderDb->findPublisherByOrderId($orderId);

        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $orderInfo;
    }


    /**
     * 获取订单申请详情
     * @param $refundApplyId
     * @return array
     * @throws Exception
     * @throws \Exception
     */
    public function findRefundApplyInfo($refundApplyId)
    {
        if (empty($refundApplyId)) {
            throw new Exception("RefundApplyId Is Not Allow Empty");
        }
        $refundApplyInfo = [];
        try {
            $conn = $this->getConnection();
            $this->userOrderDb = new UserOrderDb($conn);
            $userRefundDb=new UserOrderRefundDb($conn);
            $refundApplyInfo['info']=$userRefundDb->findOrderRefundApplyById($refundApplyId);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $refundApplyInfo;
    }

    /**
     * 获取用户申请退款列表
     * @param Page $page
     * @param $search
     * @param $status
     * @return Page|null
     * @throws Exception
     * @throws \Exception
     */
    public function getOrderRefundApplyList(Page $page, $search, $status)
    {
        try {
            $conn = $this->getConnection();
            $userRefundDb=new UserOrderRefundDb($conn);
            $page = $userRefundDb->getOrderRefundApplyList($page, $search, $status);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $page;
    }


    /**
     * 针对退款申请回复
     * @param $refundApplyId
     * @param $status
     * @param $content
     * @throws Exception
     * @throws \Exception
     */
    public function returnRefundApply($refundApplyId, $status, $content)
    {
        if (empty($refundApplyId)) {
            throw new Exception("RefundApplyId Is Not Allow Empty");
        }
        if ($status != UserOrderRefundApply::USER_ORDER_REFUND_APPLY_STATUS_WAIT
            && $status != UserOrderRefundApply::USER_ORDER_REFUND_APPLY_STATUS_SUCCESS
            && $status != UserOrderRefundApply::USER_ORDER_REFUND_APPLY_STATUS_PAY
            && $status != UserOrderRefundApply::USER_ORDER_REFUND_APPLY_STATUS_FAIL) {
            throw new Exception("Invalid Status");
        }
        if ($status == UserOrderRefundApply::USER_ORDER_REFUND_APPLY_STATUS_FAIL) {
            if (empty($content)) {
                throw new Exception("Content Is Not Allow Empty");
            }
        }
        $conn=$this->getConnection();
        $tran=$conn->beginTransaction();
        try{
            $userRefundDb=new UserOrderRefundDb($conn);
            $this->userOrderDb=new UserOrderDb($conn);
            $refundApplyInfo=$userRefundDb->findOrderRefundApplyById($refundApplyId);
            $refundApplyInfo=$this->arrayCastObject($refundApplyInfo,UserOrderRefundApply::class);
            if(empty($refundApplyInfo)){
                throw new Exception("Invalid RefundApplyId");
            }
            $refundApplyInfo->status=$status;
            if($status==UserOrderRefundApply::USER_ORDER_REFUND_APPLY_STATUS_FAIL){
                $refundApplyInfo->replyContent=$content;
                //改变订单状态为待退款
                $this->userOrderDb->changeOrderStatus($refundApplyInfo->orderId,UserOrderInfo::USER_ORDER_STATUS_REFUND_WAIT);
            }
            $userRefundDb->updateUserOrderRefundApply($refundApplyInfo);
            $this->commit($tran);
        }catch (Exception $e){
            $this->rollback($tran);
            throw $e;
        }finally{
            $this->closeLink();
        }

    }


    /**
     * 确认退款
     * @param $refundApplyId
     * @param $accountInfo
     * @param $refundNumber
     * @param $money
     * @param $content
     * @throws Exception
     * @throws \Exception
     */
    public function confirmRefund($refundApplyId,$accountInfo,$refundNumber,$money,$content)
    {
        if (empty($refundApplyId)) {
            throw new Exception("RefundApplyId Is Not Allow Empty");
        }
        if (empty($accountInfo)) {
            throw new Exception("AccountInfo Is Not Allow Empty");
        }
        if (empty($refundNumber)) {
            throw new Exception("RefundNumber Is Not Allow Empty");
        }
        if (empty($money)) {
            throw new Exception("Money Is Not Allow Empty");
        }
        if (!is_numeric($money)) {
            throw new Exception("Invalid Money");
        }
        $conn=$this->getConnection();
        $tran=$conn->beginTransaction();

        try{
            $userRefundDb=new UserOrderRefundDb($conn);
            $this->userOrderDb=new UserOrderDb($conn);
            $refundApplyInfo=$userRefundDb->findOrderRefundApplyById($refundApplyId);
            $refundApplyInfo=$this->arrayCastObject($refundApplyInfo,UserOrderRefundApply::class);
            if(empty($refundApplyInfo)){
                throw new Exception("Invalid RefundApplyId");
            }
            if($refundApplyInfo->status!=UserOrderRefundApply::USER_ORDER_REFUND_APPLY_STATUS_SUCCESS){
                throw new Exception("Invalid RefundApply Status");
            }
            $refundApplyInfo->status=UserOrderRefundApply::USER_ORDER_REFUND_APPLY_STATUS_PAY;
            //更新退款申请状态，退款成功
            $userRefundDb->updateUserOrderRefundApply($refundApplyInfo);
            //更新用户订单状态，退款成功
            $this->userOrderDb->changeOrderStatus($refundApplyInfo->orderId,UserOrderInfo::USER_ORDER_STATUS_REFUND_SUCCESS);
            //添加退款记录
            $userOrderRefund=new UserOrderRefund();
            $userOrderRefund->applyId=$refundApplyId;
            $userOrderRefund->accountInfo=$accountInfo;
            $userOrderRefund->content=$content;
            $userOrderRefund->money=$money;
            $userOrderRefund->refundNumber=$refundNumber;
            $userOrderRefund->orderId=$refundApplyInfo->orderId;
            $userOrderRefund->userId=$refundApplyInfo->userId;
            $userOrderRefund->tripId=$refundApplyInfo->tripId;
            //添加退款记录
            $userRefundDb->addUserOrderRefund($userOrderRefund);

            $this->commit($tran);
        }catch (Exception $e){
            $this->rollback($tran);
            throw $e;
        }finally{
            $this->closeLink();
        }
    }


    /**
     * @param $refundApplyId
     * @return array
     * @throws Exception
     * @throws \Exception
     */
    public function findRefundInfo($refundApplyId)
    {
        if (empty($refundApplyId)) {
            throw new Exception("RefundApplyId Is Not Allow Empty");
        }
        $refundInfo = [];
        try {
            $conn = $this->getConnection();
            $this->userOrderDb = new UserOrderDb($conn);
            $userRefundDb=new UserOrderRefundDb($conn);
            $refundInfo['info']=$userRefundDb->findOrderRefundByApplyId($refundApplyId);
            $refundInfo['orderInfo']=$this->userOrderDb->findOrderById($refundInfo['info']['orderId']);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $refundInfo;
    }



}