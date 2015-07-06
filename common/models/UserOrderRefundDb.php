<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/18
 * Time : 下午10:24
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\models;


use backend\components\Page;
use common\entity\UserOrderRefund;
use common\entity\UserOrderRefundApply;
use yii\db\mssql\PDO;

class UserOrderRefundDb extends ProxyDb{


    /**
     * 添加用户退款申请
     * @param UserOrderRefundApply $userOrderRefundApply
     * @throws \yii\db\Exception
     */
    public function addUserOrderRefundApply(UserOrderRefundApply $userOrderRefundApply)
    {
        $sql = sprintf("
            INSERT  INTO user_order_refund_apply
            (
              orderId,userId,tripId,applyContent,applyTime,status
            )
            VALUES
            (
              :orderId,:userId,:tripId,:applyContent,now(),:status
            )

        ");
        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":orderId", $userOrderRefundApply->orderId, PDO::PARAM_INT);
        $command->bindParam(":userId", $userOrderRefundApply->userId, PDO::PARAM_STR);
        $command->bindParam(":tripId", $userOrderRefundApply->tripId, PDO::PARAM_INT);
        $command->bindParam(":applyContent", $userOrderRefundApply->applyContent, PDO::PARAM_STR);
        $command->bindParam(":status", $userOrderRefundApply->status, PDO::PARAM_INT);

        $command->execute();
    }


    /**
     * 更新用户退款申请
     * @param UserOrderRefundApply $userOrderRefundApply
     * @throws \yii\db\Exception
     */
    public function updateUserOrderRefundApply(UserOrderRefundApply $userOrderRefundApply)
    {
        $sql=sprintf("
            UPDATE user_order_refund_apply SET
            replyTime=now(),replyContent=:replyContent,status=:status
            WHERE refundApplyId=:refundApplyId
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":replyContent", $userOrderRefundApply->replyContent, PDO::PARAM_STR);
        $command->bindParam(":status", $userOrderRefundApply->status, PDO::PARAM_INT);
        $command->bindParam(":refundApplyId", $userOrderRefundApply->refundApplyId, PDO::PARAM_INT);

        $command->execute();
    }


    /**
     * 添加用户退款记录
     * @param UserOrderRefund $userOrderRefund
     * @throws \yii\db\Exception
     */
    public function addUserOrderRefund(UserOrderRefund $userOrderRefund)
    {
        $sql = sprintf("
            INSERT  INTO user_order_refund
            (
              accountInfo,refundNumber,orderId,userId,tripId,applyId,refundTime,content,money
            )
            VALUES
            (
              :accountInfo,:refundNumber,:orderId,:userId,:tripId,:applyId,now(),:content,:money
            )

        ");
        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":refundNumber", $userOrderRefund->refundNumber, PDO::PARAM_STR);
        $command->bindParam(":accountInfo", $userOrderRefund->accountInfo, PDO::PARAM_STR);
        $command->bindParam(":orderId", $userOrderRefund->orderId, PDO::PARAM_INT);
        $command->bindParam(":userId", $userOrderRefund->userId, PDO::PARAM_STR);
        $command->bindParam(":tripId", $userOrderRefund->tripId, PDO::PARAM_INT);
        $command->bindParam(":applyId", $userOrderRefund->applyId, PDO::PARAM_INT);
        $command->bindParam(":content", $userOrderRefund->content, PDO::PARAM_STR);
        $command->bindParam(":money", $userOrderRefund->money, PDO::PARAM_STR);

        $command->execute();
    }


    /**
     * 获取用户申请退款列表
     * @param Page $page
     * @param $search
     * @param $status
     * @return Page|null
     */
    public function getOrderRefundApplyList(Page $page,$search,$status)
    {
        $sql=sprintf("
            FROM user_order_refund_apply uof
            LEFT JOIN user_order_info uoi ON uof.orderId=uoi.orderId
            LEFT JOIN user_base ub ON uoi.userId=ub.userSign
            WHERE 1=1
        ");

        if(!empty($search)){
            $sql.=' AND  ( uoi.orderNumber like :search OR ub.phone like :search OR ub.nickname like :search  )';
            $this->setParam('search',$search.'%');
        }
        if($status!==""){
            $sql.=' AND uof.status=:status ';
            $this->setParam('status',$status);
        }
        $this->setSelectInfo(' uof.*,uoi.orderNumber,uoi.totalPrice,uoi.createTime,uoi.tripJsonInfo,ub.nickname AS userNickname,ub.phone AS userPhone ');
        $this->setSql($sql);
        return $this->find($page);

    }


    /**
     * 获取随游退款申请详情
     * @param $refundApplyId
     * @return array|bool
     */
    public function findOrderRefundApplyById($refundApplyId)
    {
        $sql = sprintf("
            SELECT * FROM  user_order_refund_apply
            WHERE refundApplyId=:refundApplyId
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":refundApplyId", $refundApplyId, PDO::PARAM_INT);

        return $command->queryOne();
    }


    /**
     * 获取订单退款详情
     * @param $applyId
     * @return array|bool
     */
    public function findOrderRefundByApplyId($applyId)
    {
        $sql = sprintf("
            SELECT * FROM  user_order_refund
            WHERE applyId=:applyId
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":applyId", $applyId, PDO::PARAM_INT);

        return $command->queryOne();
    }



}