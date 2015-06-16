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
             refundReason,userSign,orderNumber,refundTime,status,lastTime,isDel
            )
            VALUES
            (
            :refundReason,:userSign,:orderNumber,now(),:status,now(),0
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
            SELECT a.*,b.nickname as nickName,c.nickname as rName FROM wechat_order_refund a
            LEFT JOIN user_base b ON a.userSign=b.userSign
            LEFT JOIN sys_user c ON a.updateUserSign=c.userSign
             WHERE orderNumber=:orderNumber
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":orderNumber",$orderNumber, PDO::PARAM_STR);
        return $command->queryOne();
    }

    public function sysGetList($page,$searchText,$status)
    {
        $sql=sprintf("
        FROM wechat_order_refund a
        LEFT JOIN user_base b ON a.userSign=b.userSign
        LEFT JOIN sys_user c ON a.updateUserSign=c.userSign
        WHERE 1=1 AND isDel=0
        ");
        if(!empty($searchText)){
            $sql.=" AND (b.nickname like :search OR a.orderNumber like :search ) ";
            $this->setParam("search","%".$searchText."%");
        }
        if(!empty($status)){
            $sql.=" AND a.status=:status ";
            $this->setParam("status",$status);
        }
        $this->setSelectInfo('a.*,b.nickname as nickName,c.nickname as rName');
        $this->setSql($sql);
        return $this->find($page);
    }

    public function sysUpdateInfo($orderNumber,$money,$updateUserSign,$updateReason,$status)
    {
        $sql = sprintf("
          UPDATE wechat_order_refund a  SET a.money=:money ,a.updateUserSign=:updateUserSign,a.updateReason=:updateReason,a.status=:status,lastTime=now()
          WHERE a.orderNumber=:orderNumber;
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":orderNumber",$orderNumber, PDO::PARAM_STR);
        $command->bindParam(":money",$money, PDO::PARAM_INT);
        $command->bindParam(":updateUserSign",$updateUserSign, PDO::PARAM_STR);
        $command->bindParam(":updateReason",$updateReason, PDO::PARAM_STR);
        $command->bindParam(":status",$status, PDO::PARAM_INT);
        return $command->execute();
    }

    public function deleteRefund($orderNumber)
    {
        $sql = sprintf("
          UPDATE wechat_order_refund a  SET a.isDel=1,lastTime=now()
          WHERE a.orderNumber=:orderNumber;
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":orderNumber",$orderNumber, PDO::PARAM_STR);
        return $command->execute();
    }

}