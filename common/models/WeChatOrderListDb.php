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
             wOrderSite,wOrderTimeList,wOrderContent,wUserSign,wStatus,wRelativeId,wRelativeSign,wRelativeType,wCreateTime,wLastTime
            )
            VALUES
            (
            :wOrderSite,:wOrderTimeList,:wOrderContent,:wUserSign,:wStatus,:wRelativeId,:wRelativeSign,:wRelativeType,now(),now()
            )
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":wOrderSite", $weChatOrderList->wOrderSite, PDO::PARAM_STR);
        $command->bindParam(":wOrderTimeList", $weChatOrderList->wOrderTimeList, PDO::PARAM_STR);
        $command->bindParam(":wOrderContent", $weChatOrderList->wOrderContent, PDO::PARAM_STR);
        $command->bindParam(":wUserSign", $weChatOrderList->wUserSign, PDO::PARAM_STR);
        $command->bindValue(":wStatus", WeChatOrderList::STATUS_NORMAL, PDO::PARAM_INT);
        $command->bindParam(":wRelativeId", $weChatOrderList->wRelativeId, PDO::PARAM_INT);
        $command->bindParam(":wRelativeSign", $weChatOrderList->wRelativeSign, PDO::PARAM_STR);
        $command->bindParam(":wRelativeType", $weChatOrderList->wRelativeType, PDO::PARAM_INT);
        return $command->execute();
    }

    public function findWeChatOrderInfoById($wOrderId,$userSign){
        $sql = sprintf("
           SELECT * FROM wechat_order_list WHERE wOrderId=:wOrderId
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

    public function getWeChatOrderListByUserSign($userSign,$page)
    {
        $sql=sprintf("
        FROM wechat_order_list a
WHERE a.wUserSign=:userSign  ORDER BY a.wOrderId DESC
        ");
        $this->setParam("userSign", $userSign);
        $this->setSelectInfo('wOrderId,wOrderSite,wOrderTimeList,wOrderContent,wUserSign,wStatus,wRelativeId,wRelativeSign,wRelativeType');
        $this->setSql($sql);
        return $this->find($page);
    }

    public function updateWeChatOrderInfo(WeChatOrderList $weChatOrderList){
        $sql = sprintf("
            UPDATE wechat_order_list SET
            wOrderSite=:wOrderSite,wOrderTimeList=:wOrderTimeList,wOrderContent=:wOrderContent,
            wStatus=:wStatus,wRelativeId=:wRelativeId,wRelativeSign=:wRelativeSign,wRelativeType=:wRelativeType,wLastTime=now()
            WHERE wOrderId=:wOrderId
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":wOrderSite", $weChatOrderList->wOrderSite, PDO::PARAM_STR);
        $command->bindParam(":wOrderTimeList", $weChatOrderList->wOrderTimeList, PDO::PARAM_STR);
        $command->bindParam(":wOrderContent", $weChatOrderList->wOrderContent, PDO::PARAM_STR);
        $command->bindParam(":wStatus", $weChatOrderList->wStatus, PDO::PARAM_INT);
        $command->bindParam(":wRelativeId", $weChatOrderList->wRelativeId, PDO::PARAM_INT);
        $command->bindParam(":wRelativeSign", $weChatOrderList->wRelativeSign, PDO::PARAM_STR);
        $command->bindParam(":wRelativeType", $weChatOrderList->wRelativeType, PDO::PARAM_INT);
        return $command->execute();
    }

}