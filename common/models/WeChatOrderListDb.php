<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/14
 * Time: 上午11:26
 */

namespace common\models;


use common\entity\WeChatOrderList;
use yii\db\mssql\PDO;

class WeChatOrderListDb extends ProxyDb{


    public function addWeChatOrderList(WeChatOrderList $weChatOrderList)
    {
        $sql = sprintf("
            INSERT INTO wechat_order_list
            (
             wOrderSite,wOrderTimeList,wOrderContent,wUserSign,wStatus,wCreateTime,wLastTime,wOrderNumber,wUserNumber,wPhone,openId,isDel
            )
            VALUES
            (
            :wOrderSite,:wOrderTimeList,:wOrderContent,:wUserSign,:wStatus,now(),now(),:wOrderNumber,:wUserNumber,:wPhone,:openId,FALSE
            )
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":wOrderSite", $weChatOrderList->wOrderSite, PDO::PARAM_STR);
        $command->bindParam(":wOrderTimeList", $weChatOrderList->wOrderTimeList, PDO::PARAM_STR);
        $command->bindParam(":wOrderContent", $weChatOrderList->wOrderContent, PDO::PARAM_STR);
        $command->bindParam(":wUserSign", $weChatOrderList->wUserSign, PDO::PARAM_STR);
        $command->bindValue(":wStatus", WeChatOrderList::STATUS_NORMAL, PDO::PARAM_INT);
        $command->bindParam(":wOrderNumber", $weChatOrderList->wOrderNumber, PDO::PARAM_STR);
        $command->bindParam(":wUserNumber", $weChatOrderList->wUserNumber, PDO::PARAM_INT);
        $command->bindParam(":wPhone", $weChatOrderList->wPhone, PDO::PARAM_STR);
        $command->bindParam(":openId", $weChatOrderList->openId, PDO::PARAM_STR);
        return $command->execute();
    }

    public function findWeChatOrderInfoById($wOrderId,$userSign){
        $sql = sprintf("
           SELECT * FROM wechat_order_list WHERE wOrderId=:wOrderId AND isDel=FALSE
        ");
        if(!empty($userSign))
        {
            $sql.=' AND wUserSign=:wUserSign';
        }
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":wOrderId", $wOrderId, PDO::PARAM_INT);
        if(!empty($userSign))
        {
            $command->bindParam(":wUserSign", $userSign, PDO::PARAM_STR);
        }
        return $command->queryOne();
    }

    public function findWeChatOrderInfoByNumber($wOrderNumber,$userSign)
    {
        $sql = sprintf("
           SELECT * FROM wechat_order_list WHERE wOrderNumber=:wOrderNumber AND isDel=FALSE
        ");
        if(!empty($userSign))
        {
            $sql.=' AND wUserSign=:wUserSign';
        }
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":wOrderNumber", $wOrderNumber, PDO::PARAM_STR);
        if(!empty($userSign))
        {
            $command->bindParam(":wUserSign", $userSign, PDO::PARAM_STR);
        }
        return $command->queryOne();
    }

    public function deleteOrder($wOrderNumber,$userSign)
    {
        $sql = sprintf("
            UPDATE wechat_order_list SET
            isDel=TRUE,wLastTime=now()
            WHERE wOrderNumber=:wOrderNumber
        ");
        if(!empty($userSign))
        {
            $sql.=' AND wUserSign=:wUserSign';
        }
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":wOrderNumber", $wOrderNumber, PDO::PARAM_STR);
        if(!empty($userSign))
        {
            $command->bindParam(":wUserSign", $userSign, PDO::PARAM_STR);
        }
        return $command->execute();
    }

    public function getWeChatOrderListByUserSign($userSign,$page)
    {
        $sql=sprintf("
        FROM wechat_order_list a
        LEFT JOIN user_base b ON a.wRelativeSign=b.userSign
WHERE a.wUserSign=:userSign  AND isDel=FALSE ORDER BY a.wOrderId DESC
        ");
        $this->setParam("userSign", $userSign);
        $this->setSelectInfo('wOrderId,wOrderSite,wOrderTimeList,wOrderContent,wUserSign,wStatus,wRelativeSign,wOrderNumber,wCreateTime,wLastTime,wUserNumber,b.headImg,b.nickName,wMoney,wDetails');
        $this->setSql($sql);
        return $this->find($page);
    }
    public function getWeChatOrderListByOrderNumber($wOrderNumber)
    {
        $sql=sprintf("
        SELECT wOrderId,wOrderSite,wOrderTimeList,wOrderContent,wUserSign,wStatus,wRelativeSign,wOrderNumber,wCreateTime,wLastTime,wUserNumber,b.headImg,b.nickName,wMoney,wDetails
        FROM wechat_order_list a
        LEFT JOIN user_base b ON a.wRelativeSign=b.userSign
WHERE a.wOrderNumber=:wOrderNumber  AND isDel=FALSE ORDER BY a.wOrderId DESC
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":wOrderNumber", $wOrderNumber, PDO::PARAM_STR);
        return $command->queryOne();
    }
   /* public function updateWeChatOrderInfo(WeChatOrderList $weChatOrderList){
        $sql = sprintf("
            UPDATE wechat_order_list SET
            wOrderSite=:wOrderSite,wOrderTimeList=:wOrderTimeList,wOrderContent=:wOrderContent,
            wStatus=:wStatus,wRelativeSign=:wRelativeSign,wLastTime=now()
            WHERE wOrderId=:wOrderId
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":wOrderSite", $weChatOrderList->wOrderSite, PDO::PARAM_STR);
        $command->bindParam(":wOrderTimeList", $weChatOrderList->wOrderTimeList, PDO::PARAM_STR);
        $command->bindParam(":wOrderContent", $weChatOrderList->wOrderContent, PDO::PARAM_STR);
        $command->bindParam(":wStatus", $weChatOrderList->wStatus, PDO::PARAM_INT);
        $command->bindParam(":wRelativeSign", $weChatOrderList->wRelativeSign, PDO::PARAM_STR);
        return $command->execute();
    }*/

    public function updateOrderUserSign($openId,$wUserSign)
    {
        $sql = sprintf("
            UPDATE wechat_order_list SET
            wUserSign=:wUserSign,wLastTime=now()
            WHERE openId=:openId AND isDel=FALSE
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":openId", $openId, PDO::PARAM_STR);
        $command->bindParam(":wUserSign", $wUserSign, PDO::PARAM_STR);
        return $command->execute();
    }

    public function orderPayEnd($wOrderNumber)
    {
        $sql = sprintf("
           UPDATE wechat_order_list SET
            wStatus=:wStatus,wLastTime=now()
            WHERE wOrderNumber=:wOrderNumber
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindValue(":wStatus", WeChatOrderList::STATUS_PAY_SUCCESS, PDO::PARAM_INT);
        $command->bindParam(":wOrderNumber", $wOrderNumber, PDO::PARAM_STR);
        return $command->execute();
    }
    public function addWxOrderPay($orderNumber,$payNumber,$type,$money)
    {
        $sql = sprintf("
          INSERT INTO wechat_pay_record (orderNumber,payNumber,type,money,payTime)
		VALUES (:orderNumber,:pNumber,:type,:money,NOW() )
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":orderNumber", $orderNumber, PDO::PARAM_STR);
        $command->bindParam(":pNumber", $payNumber, PDO::PARAM_STR);
        $command->bindParam(":type", $type, PDO::PARAM_INT);
        $command->bindParam(":money", $money, PDO::PARAM_STR);
        return $command->execute();
    }

    public function updateOrderStatus($wOrderNumber,$status,$userSign)
    {
        $sql = sprintf("
           UPDATE wechat_order_list SET
            wStatus=:wStatus,wLastTime=now()
            WHERE wOrderNumber=:wOrderNumber
        ");
        if(!empty($userSign))
        {
            $sql.=' AND wUserSign=:wUserSign';
        }

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":wStatus", $status, PDO::PARAM_INT);
        $command->bindParam(":wOrderNumber", $wOrderNumber, PDO::PARAM_STR);
        if(!empty($userSign))
        {
            $command->bindParam(":wUserSign", $userSign, PDO::PARAM_STR);
        }
        return $command->execute();
    }
}