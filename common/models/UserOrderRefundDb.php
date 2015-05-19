<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/18
 * Time : 下午10:24
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\models;


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
    public function updateUserOrderRefundApplys(UserOrderRefundApply $userOrderRefundApply)
    {
        $sql=sprintf("
            UPDATE user_order_refund_apply SET
            replyTime=:now(),replyContent=:replyContent,status=:status
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
              refundNumber,orderId,userId,tripId,applyId,refundTime,content,status
            )
            VALUES
            (
              :refundNumber,:orderId,:userId,:tripId,:applyId,now(),:content,:status
            )

        ");
        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":refundNumber", $userOrderRefund->refundNumber, PDO::PARAM_STR);
        $command->bindParam(":orderId", $userOrderRefund->orderId, PDO::PARAM_INT);
        $command->bindParam(":userId", $userOrderRefund->userId, PDO::PARAM_STR);
        $command->bindParam(":tripId", $userOrderRefund->tripId, PDO::PARAM_INT);
        $command->bindParam(":applyId", $userOrderRefund->applyId, PDO::PARAM_INT);
        $command->bindParam(":content", $userOrderRefund->content, PDO::PARAM_STR);
        $command->bindParam(":status", $userOrderRefund->status, PDO::PARAM_INT);

        $command->execute();
    }


}