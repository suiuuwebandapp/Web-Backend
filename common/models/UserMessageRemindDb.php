<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/4/28
 * Time: 下午2:30
 */
namespace common\models;
use common\entity\UserBase;
use common\entity\UserMessageRemind;
use yii\db\mssql\PDO;

/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/4/24
 * Time: 下午5:52
 */
class UserMessageRemindDb extends ProxyDb
{
    /**
     * 添加消息
     * @param $relativeId
     * @param $relativeType
     * @param $relativeUserSign
     * @param $userSign
     * @return int
     */
    public function addUserMessageRemind($relativeId,$relativeType,$userSign,$relativeUserSign,$rType=0)
    {
        $sql=sprintf("
          INSERT INTO user_message_remind (relativeId,relativeUserSign,relativeType,createUserSign,createTime,rStatus,rType) VALUES (:relativeId,:relativeUserSign,:relativeType,:userSign,now(),:rStatus,:rType);
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":relativeId", $relativeId, PDO::PARAM_INT);
        $command->bindParam(":relativeUserSign", $relativeUserSign, PDO::PARAM_STR);
        $command->bindParam(":relativeType", $relativeType, PDO::PARAM_INT);
        $command->bindParam(":userSign", $userSign, PDO::PARAM_STR);
        $command->bindValue(":rStatus", UserMessageRemind::REMIND_STATUS_NORMAL, PDO::PARAM_INT);
        $command->bindParam(":rType", $rType, PDO::PARAM_INT);
        $command->execute();
        return $this->getConnection()->lastInsertID;
    }

    /**
     * 取消消息提醒
     * @param $remindId
     * @param $userSign
     */
    public function deleteUserMessageRemind($remindId,$userSign)
    {
        $sql=sprintf("
           UPDATE user_message_remind SET rStatus=:rStatus,readTime=now() WHERE remindId = :remindId AND createUserSign=:createUserSign
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindValue(":status", UserMessageRemind::REMIND_STATUS_DISABLED, PDO::PARAM_INT);
        $command->bindParam(":remindId", $remindId, PDO::PARAM_INT);
        $command->bindParam(":createUserSign", $userSign, PDO::PARAM_STR);
        $command->execute();
    }

    /**
     * 查找消息
     * @param $userSign
     * @param $page
     * @param $type
     * @return array
     * @throws \yii\db\Exception
     */
    public function getAttentionCircleArticleRemind($userSign,$page,$type)
    {
        $sql=sprintf("
        FROM user_message_remind a
            LEFT JOIN user_base b ON b.userSign = a.createUserSign
            WHERE a.relativeUserSign=:userSign AND a.rStatus=:rStatus AND b.status=:userStatus AND relativeType=:relativeType
        ");
        $this->setParam("userStatus", UserBase::USER_STATUS_NORMAL);
        $this->setParam("rStatus", UserMessageRemind::REMIND_STATUS_NORMAL);
        $this->setParam("rStatus", UserMessageRemind::REMIND_STATUS_NORMAL);
        $this->setParam("userSign", $userSign);
        $this->setParam("relativeType", $type);
        $this->setSelectInfo('a.relativeId,a.relativeType,a.createUserSign,a.remindId,a.rType,b.headImg,b.nickname');
        $this->setSql($sql);
        return $this->find($page);
    }
}