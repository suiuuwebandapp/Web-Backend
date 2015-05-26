<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/26
 * Time: 下午2:17
 */

namespace common\models;


use common\entity\WeChatOrderRefund;
use yii\db\mssql\PDO;

class WeChatOrderRefundDb extends ProxyDb {

    public function addWeChatOrderRefund(WeChatOrderRefund $weChatOrderRefund)
    {
        $sql = sprintf("
            INSERT INTO wechat_order_refund
            (
             refundReason,userSign,orderNumber,refundTime,status,lastTime
            )
            VALUES
            (
            :refundReason,:userSign,:orderNumber,now(),:status,now()
            )
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":refundReason", $weChatOrderRefund->refundReason, PDO::PARAM_STR);
        $command->bindParam(":userSign", $weChatOrderRefund->userSign, PDO::PARAM_STR);
        $command->bindParam(":orderNumber", $weChatOrderRefund->orderNumber, PDO::PARAM_STR);
        $command->bindValue(":status", $weChatOrderRefund::STATUS_APPLY_REFUND, PDO::PARAM_INT);
        return $command->execute();
    }

    public function findRefundInfoByOrderNumber($orderNumber)
    {
        $sql = sprintf("
            SELECT * FROM wechat_order_refund WHERE orderNumber=:orderNumber
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":orderNumber",$orderNumber, PDO::PARAM_STR);
        return $command->queryOne();
    }

}