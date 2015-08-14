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
     * @param $rType
     * @param $content
     * @param $url
     * @return int
     */
    public function addUserMessageRemind($relativeId,$relativeType,$userSign,$relativeUserSign,$rType,$content,$url)
    {
        $sql=sprintf("
          INSERT INTO user_message_remind (relativeId,relativeUserSign,relativeType,createUserSign,createTime,rStatus,rType,content,url)
          VALUES (:relativeId,:relativeUserSign,:relativeType,:userSign,now(),:rStatus,:rType,:content,:url);
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":relativeId", $relativeId, PDO::PARAM_INT);
        $command->bindParam(":relativeUserSign", $relativeUserSign, PDO::PARAM_STR);
        $command->bindParam(":relativeType", $relativeType, PDO::PARAM_INT);
        $command->bindParam(":userSign", $userSign, PDO::PARAM_STR);
        $command->bindValue(":rStatus", UserMessageRemind::REMIND_STATUS_NORMAL, PDO::PARAM_INT);
        $command->bindParam(":rType", $rType, PDO::PARAM_INT);
        $command->bindParam(":content", $content, PDO::PARAM_STR);
        $command->bindParam(":url", $url, PDO::PARAM_STR);
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
        $this->setParam("userSign", $userSign);
        $this->setParam("relativeType", $type);
        $this->setSelectInfo('a.relativeId,a.relativeType,a.createUserSign,a.remindId,a.rType,a.content,a.url,b.headImg,b.nickname');
        $this->setSql($sql);
        return $this->find($page);
    }

    /**
     * 查找订单消息
     * @param $userSign
     * @param $page
     * @param $type
     * @return array
     * @throws \yii\db\Exception
     */
    public function getOrderRemind($userSign,$page,$type)
    {
        $sql=sprintf("
        FROM user_message_remind a
            LEFT JOIN user_base b ON b.userSign = a.createUserSign
            LEFT JOIN user_order_info c ON c.orderNumber=a.relativeId
            WHERE a.relativeUserSign=:userSign AND a.rStatus=:rStatus AND b.status=:userStatus AND rType=:rType
        ");
        if(!empty($type))
        {
            $sql.= " AND relativeType=:relativeType ";
            $this->setParam("relativeType", $type);
        }
        $this->setParam("userStatus", UserBase::USER_STATUS_NORMAL);
        $this->setParam("rStatus", UserMessageRemind::REMIND_STATUS_NORMAL);
        $this->setParam("rType", UserMessageRemind::R_TYPE_ORDER);
        $this->setParam("userSign", $userSign);
        $this->setSelectInfo('a.relativeId,a.relativeType,a.createUserSign,a.remindId,a.content,a.url,a.rType,b.headImg,b.nickname,c.tripJsonInfo');
        $this->setSql($sql);
        return $this->find($page);
    }
    /**
     * 查找随游消息
     * @param $userSign
     * @param $page
     * @param $type
     * @return array
     * @throws \yii\db\Exception
     */
    public function getTripRemind($userSign,$page,$type)
    {
        $sql=sprintf("
        FROM user_message_remind a
            LEFT JOIN user_base b ON b.userSign = a.createUserSign
            LEFT JOIN travel_trip c ON c.tripId=a.relativeId
            WHERE a.relativeUserSign=:userSign AND a.rStatus=:rStatus AND b.status=:userStatus AND rType=:rType
        ");
        if(!empty($type))
        {
            $sql.= " AND relativeType=:relativeType ";
            $this->setParam("relativeType", $type);
        }
        $this->setParam("userStatus", UserBase::USER_STATUS_NORMAL);
        $this->setParam("rStatus", UserMessageRemind::REMIND_STATUS_NORMAL);
        $this->setParam("rType", UserMessageRemind::R_TYPE_TRIP);
        $this->setParam("userSign", $userSign);
        $this->setSelectInfo('a.relativeId,a.relativeType,a.createUserSign,a.remindId,a.rType,a.content,a.url,b.headImg,b.nickname,c.title');
        $this->setSql($sql);
        return $this->find($page);
    }
    /**
     * 查找旅图消息
     * @param $userSign
     * @param $page
     * @param $type
     * @return array
     * @throws \yii\db\Exception
     */
    public function getTpRemind($userSign,$page,$type)
    {
        $sql=sprintf("
        FROM user_message_remind a
            LEFT JOIN user_base b ON b.userSign = a.createUserSign
            LEFT JOIN travel_picture c ON a.relativeId=c.id
            WHERE a.relativeUserSign=:userSign AND a.rStatus=:rStatus AND b.status=:userStatus AND rType=:rType
        ");
        if(!empty($type))
        {
            $sql.= " AND relativeType=:relativeType ";
            $this->setParam("relativeType", $type);
        }
        $this->setParam("userStatus", UserBase::USER_STATUS_NORMAL);
        $this->setParam("rStatus", UserMessageRemind::REMIND_STATUS_NORMAL);
        $this->setParam("rType", UserMessageRemind::R_TYPE_TRAVEL_PICTURE);
        $this->setParam("userSign", $userSign);
        $this->setSelectInfo('a.relativeId,a.relativeType,a.createUserSign,a.remindId,a.rType,a.content,a.url,b.headImg,b.nickname,c.id,c.title');
        $this->setSql($sql);
        return $this->find($page);
    }
    /**
     * 查找问答消息
     * @param $userSign
     * @param $page
     * @param $type
     * @return array
     * @throws \yii\db\Exception
     */
    public function getQaRemind($userSign,$page,$type)
    {
        $sql=sprintf("
        FROM user_message_remind a
            LEFT JOIN user_base b ON b.userSign = a.createUserSign
            LEFT JOIN question_community c ON a.relativeId=c.qId
            WHERE a.relativeUserSign=:userSign AND a.rStatus=:rStatus AND b.status=:userStatus AND rType=:rType
        ");
        if(!empty($type))
        {
            $sql.= " AND relativeType=:relativeType ";
            $this->setParam("relativeType", $type);
        }
        $this->setParam("userStatus", UserBase::USER_STATUS_NORMAL);
        $this->setParam("rStatus", UserMessageRemind::REMIND_STATUS_NORMAL);
        $this->setParam("rType", UserMessageRemind::R_TYPE_QUESTION_ANSWER);
        $this->setParam("userSign", $userSign);
        $this->setSelectInfo('a.relativeId,a.relativeType,a.createUserSign,a.remindId,a.rType,a.content,a.url,b.headImg,b.nickname,c.qId,c.qTitle');
        $this->setSql($sql);
        return $this->find($page);
    }


    /**
     * 查找系统消息
     * @param $userSign
     * @param $page
     * @param $type
     * @return array
     * @throws \yii\db\Exception
     */
    public function getSysRemind($userSign,$page,$type)
    {
        $sql=sprintf("
        FROM user_message_remind a
            WHERE a.relativeUserSign=:userSign AND a.rStatus=:rStatus AND rType=:rType
        ");
        if(!empty($type))
        {
            $sql.= " AND relativeType=:relativeType ";
            $this->setParam("relativeType", $type);
        }
        $this->setParam("rStatus", UserMessageRemind::REMIND_STATUS_NORMAL);
        $this->setParam("rType", UserMessageRemind::R_TYPE_SYS);
        $this->setParam("userSign", $userSign);
        $this->setSelectInfo('a.relativeId,a.relativeType,a.createUserSign,a.remindId,a.rType,a.content,a.url');
        $this->setSql($sql);
        return $this->find($page);
    }
    /**
     * 查找通知消息
     * @param $userSign
     * @param $page
     * @param $type
     * @return array
     * @throws \yii\db\Exception
     */
    public function getNoticeMessage($userSign,$page,$type)
    {
        $sql=sprintf("
        FROM user_message_remind a
            LEFT JOIN user_base b ON b.userSign = a.createUserSign
            LEFT JOIN travel_trip c ON c.tripId=a.relativeId
            WHERE a.relativeUserSign=:userSign AND a.rStatus=:rStatus  AND (rType=:rType OR rType=:sysType)
        ");
        if(!empty($type))
        {
            $sql.= " AND relativeType=:relativeType ";
            $this->setParam("relativeType", $type);
        }
        $this->setParam("rStatus", UserMessageRemind::REMIND_STATUS_NORMAL);
        $this->setParam("rType", UserMessageRemind::R_TYPE_TRIP);
        $this->setParam("sysType", UserMessageRemind::R_TYPE_SYS);
        $this->setParam("userSign", $userSign);
        $this->setSelectInfo('a.relativeId,a.relativeType,a.createUserSign,a.remindId,a.rType,a.content,a.url,b.headImg,b.nickname,c.title');
        $this->setSql($sql);
        return $this->find($page);
    }

    /**
     * 查找web系统消息
     * @param $userSign
     * @param $page
     * @param $type
     * @return array
     * @throws \yii\db\Exception
     */
    public function getWebSysMessage($userSign,$page,$type)
    {
        $sql=sprintf("
        FROM user_message_remind a
            LEFT JOIN user_base b ON b.userSign = a.createUserSign
            WHERE a.relativeUserSign=:userSign AND a.rStatus=:rStatus AND (rType=:rType OR rType=:sysType OR rType=:orderType)
        ");
        if(!empty($type))
        {
            $sql.= " AND relativeType=:relativeType ";
            $this->setParam("relativeType", $type);
        }
        $this->setParam("rStatus", UserMessageRemind::REMIND_STATUS_NORMAL);
        $this->setParam("rType", UserMessageRemind::R_TYPE_TRIP);
        $this->setParam("sysType", UserMessageRemind::R_TYPE_SYS);
        $this->setParam("orderType", UserMessageRemind::R_TYPE_ORDER);
        $this->setParam("userSign", $userSign);
        $this->setSelectInfo('a.relativeId,a.relativeType,a.createUserSign,a.remindId,a.rType,a.content,a.url,b.headImg,b.nickname');
        $this->setSql($sql);
        return $this->find($page);
    }
}