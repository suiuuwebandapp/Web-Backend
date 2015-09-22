<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/7
 * Time : 下午2:53
 * Email: zhangxinmailvip@foxmail.com
 */

namespace app\modules\v1\models;


use common\components\Code;
use app\modules\v1\entity\UserMessage;
use app\modules\v1\entity\UserMessageSession;
use app\modules\v1\entity\UserMessageSetting;
use common\models\ProxyDb;
use yii\db\mssql\PDO;

class UserMessageDb extends ProxyDb
{

    /**
     * 添加用户消息
     * @param UserMessage $userMessage
     * @throws \yii\db\Exception
     */
    public function addUserMessage(UserMessage $userMessage)
    {
        $sql=sprintf("
            INSERT INTO user_message
            (
              sessionKey,receiveId,senderId,url,content,sendTime,isRead,isShield
            )
            VALUES
            (
              :sessionKey,:receiveId,:senderId,:url,:content,now(),FALSE,:isShield
            )
        ");

        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":sessionKey", $userMessage->sessionKey, PDO::PARAM_STR);
        $command->bindParam(":receiveId", $userMessage->receiveId, PDO::PARAM_STR);
        $command->bindParam(":senderId", $userMessage->senderId, PDO::PARAM_STR);
        $command->bindParam(":content", $userMessage->content, PDO::PARAM_STR);
        $command->bindParam(":url", $userMessage->url, PDO::PARAM_STR);
        $command->bindParam(":isShield",$userMessage->isShield, PDO::PARAM_INT);

        $command->execute();
    }


    /**
     * 获取会话（根据Key）
     * @param $userId
     * @param $sessionKey
     * @return array|bool
     */
    public function findUserMessageSessionByKey($userId,$sessionKey)
    {
        $sql=sprintf("
            SELECT * FROM user_message_session
            WHERE sessionKey=:sessionKey AND  userId=:userId
        ");
        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":sessionKey", $sessionKey, PDO::PARAM_INT);
        $command->bindParam(":userId", $userId, PDO::PARAM_INT);

        return $command->queryOne();
    }


    /**
     * 添加用户私信session
     * @param UserMessageSession $userMessageSession
     * @throws \yii\db\Exception
     */
    public function addUserMessageSession(UserMessageSession $userMessageSession)
    {

        $sql=sprintf("
            INSERT INTO user_message_session
            (
              sessionKey,userId,relateId,lastConcatTime,lastContentInfo,isRead
            )
            VALUES
            (
              :sessionKey,:userId,:relateId,now(),:lastContentInfo,:isRead
            )
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":sessionKey", $userMessageSession->sessionKey, PDO::PARAM_STR);
        $command->bindParam(":lastContentInfo", $userMessageSession->lastContentInfo, PDO::PARAM_STR);
        $command->bindParam(":userId", $userMessageSession->userId, PDO::PARAM_STR);
        $command->bindParam(":relateId", $userMessageSession->relateUserId, PDO::PARAM_STR);
        $command->bindParam(":isRead", $userMessageSession->isRead, PDO::PARAM_INT);

        $command->execute();
    }


    /**
     * 更新用户session详情
     * @param $sessionId
     * @param $content
     * @param $isRead
     * @throws \yii\db\Exception
     */
    public function updateUserMessageSession($sessionId,$content,$isRead)
    {

        $sql=sprintf("
          UPDATE user_message_session SET
          lastConcatTime=now(),lastContentInfo=:lastContentInfo,isRead=:isRead
          WHERE sessionId=:sessionId
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":sessionId", $sessionId, PDO::PARAM_INT);
        $command->bindParam(":lastContentInfo", $content, PDO::PARAM_STR);
        $command->bindParam(":isRead", $isRead, PDO::PARAM_INT);

        $command->execute();
    }


    /**
     * 获取用户会话列表
     * @param $userId
     * @param null $isRead
     * @return array
     */
    public function getUserMessageSessionByUserId($userId,$isRead=null)
    {
        $sql=sprintf("
            SELECT DISTINCT ub.nickname,ub.headImg,s.* FROM
            (
                SELECT sessionId,sessionKey,userId,relateId,lastConcatTime,lastContentInfo,isRead
                FROM user_message_session
                WHERE userId=:userId
            )
            AS s
            LEFT JOIN user_base ub ON ub.userSign=s.relateId
            WHERE 1=1
        ");
        if(isset($isRead)){
            $sql.=" AND s.isRead=:isRead ";
        }
        $sql.=" ORDER BY s.isRead,s.lastConcatTime DESC ";
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":userId", $userId, PDO::PARAM_STR);

        if(isset($isRead)){
            $command->bindParam(":isRead", $isRead, PDO::PARAM_INT);
        }
        return $command->queryAll();
    }


    /**
     * 获取未读系统小心详情
     * @param $userSign
     * @return array
     */
    public function getUnReadSystemMessageList($userSign)
    {
        $sql=sprintf("
            SELECT * FROM user_message
            WHERE isRead=False AND senderId=:senderId AND receiveId=:receiveId
            ORDER BY sendTime DESC
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":receiveId", $userSign, PDO::PARAM_STR);
        $command->bindValue(":senderId", Code::USER_SYSTEM_MESSAGE_ID, PDO::PARAM_STR);

        return $command->queryAll();
    }


    /**
     * 获取用户聊天记录列表
     * @param $userId
     * @param $sessionKey
     * @return array
     */
    public function getUserMessageListByKey($userId,$sessionKey)
    {
        $sql=sprintf("
            SELECT * FROM user_message
            WHERE sessionKey=:sessionKey
            AND
            (
              (receiveId=:userId AND isShield!=TRUE ) OR (senderId=:userId)
            )
        ");
        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":userId", $userId, PDO::PARAM_STR);
        $command->bindParam(":sessionKey", $sessionKey, PDO::PARAM_STR);
        return $command->queryAll();
    }

    /**
     * 更新已读
     * @param $userId
     * @param $sessionKey
     * @throws \yii\db\Exception
     */
    public function updateUserMessageSessionRead($userId,$sessionKey)
    {
        $sql=sprintf("
          UPDATE user_message_session SET
          isRead=TRUE
          WHERE sessionKey=:sessionKey AND userId=:userId
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":sessionKey", $sessionKey, PDO::PARAM_STR);
        $command->bindParam(":userId",$userId);

        $command->execute();
    }

    /**
     * 更新已读
     * @param $sessionKey
     * @param $userSign
     * @throws \yii\db\Exception
     */
    public function updateUserMessageRead($sessionKey,$userSign)
    {
        $sql=sprintf("
          UPDATE user_message SET
          isRead=TRUE,readTime=now()
          WHERE  sessionKey=:sessionKey AND isRead=FALSE  AND receiveId=:userId
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":sessionKey", $sessionKey, PDO::PARAM_STR);
        $command->bindParam(":userId", $userSign, PDO::PARAM_STR);

        $command->execute();
    }

    /**
     * 更新系统消息已读
     * @param $messageId
     * @param $userSign
     * @throws \yii\db\Exception
     */
    public function updateSystemMessageRead($messageId,$userSign)
    {
        $sql=sprintf("
          UPDATE user_message SET
          isRead=TRUE,readTime=now()
          WHERE isRead=FALSE  AND messageId=:messageId AND  receiveId=:receiveId
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":messageId", $messageId, PDO::PARAM_INT);
        $command->bindParam(":receiveId", $userSign, PDO::PARAM_STR);

        $command->execute();
    }


    /**
     * 获取用户未读信息条数
     * @param $userSign
     * @param $count
     * @throws \yii\db\Exception
     */
    public function getUnReadMessageInfoList($userSign,$count)
    {
        $sql=sprintf("
          SELECT um.*,ub.nickname,ub.headImg FROM user_message um
          LEFT JOIN user_base ON um.receiveId=ub.userSign
          WHERE isRead=FALSE AND um.receiverId=:userId
          ORDER BY um.sendTime DESC
          LIMIT 0,".$count."

        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":sessionKey", $sessionKey, PDO::PARAM_STR);
        $command->bindParam(":userId", $userSign, PDO::PARAM_STR);

        $command->execute();
    }


    /**
     * 添加用户消息设置
     * @param UserMessageSetting $userMessageSetting
     * @throws \yii\db\Exception
     */
    public function addUserMessageSetting(UserMessageSetting $userMessageSetting)
    {
        $sql=sprintf("
            INSERT INTO user_message_setting
            (
              userId,status,shieldIds
            )
            VALUES
            (
              :userId,:status,:shieldIds
            )
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":userId", $userMessageSetting->userId, PDO::PARAM_STR);
        $command->bindParam(":status", $userMessageSetting->status, PDO::PARAM_INT);
        $command->bindParam(":shieldIds", $userMessageSetting->shieldIds, PDO::PARAM_STR);

        $command->execute();

    }


    /**
     * 更新用户消息设置
     * @param UserMessageSetting $userMessageSetting
     * @throws \yii\db\Exception
     */
    public function updateUserMessageSetting(UserMessageSetting $userMessageSetting)
    {
        $sql=sprintf("
            UPDATE user_message_setting SET
            status=:status,shieldIds=:shieldIds
            WHERE userId=:userId
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":userId", $userMessageSetting->userId, PDO::PARAM_STR);
        $command->bindParam(":status", $userMessageSetting->status, PDO::PARAM_INT);
        $command->bindParam(":shieldIds", $userMessageSetting->shieldIds, PDO::PARAM_STR);

        $command->execute();
    }


    /**
     * 获取用户消息设置列表
     * @param $userId
     * @return array|bool
     * @throws \yii\db\Exception
     */
    public function findUserMessageSettingByUserId($userId)
    {
        $sql=sprintf("
            SELECT * FROM user_message_setting
            WHERE userId=:userId
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":userId", $userId, PDO::PARAM_STR);

        return $command->queryOne();

    }



}