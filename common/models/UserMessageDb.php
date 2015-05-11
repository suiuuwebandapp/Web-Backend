<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/7
 * Time : 下午2:53
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\models;


use common\entity\UserMessage;
use common\entity\UserMessageSession;
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
              sessionKeyOne,sessionKeyTwo,receiveId,senderId,content,sendTime,isRead
            )
            VALUES
            (
              :sessionKeyOne,:sessionKeyTwo,:receiveId,:senderId,:content,now(),FALSE
            )
        ");

        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":sessionKeyOne", $userMessage->sessionKeyOne, PDO::PARAM_STR);
        $command->bindParam(":sessionKeyTwo", $userMessage->sessionKeyTwo, PDO::PARAM_STR);
        $command->bindParam(":receiveId", $userMessage->receiveId, PDO::PARAM_STR);
        $command->bindParam(":senderId", $userMessage->senderId, PDO::PARAM_STR);
        $command->bindParam(":content", $userMessage->content, PDO::PARAM_STR);

        $command->execute();
    }


    /**
     * 获取会话（根据Key）
     * @param $sessionKey
     * @return array|bool
     */
    public function findUserMessageSessionByKey($sessionKey)
    {
        $sql=sprintf("
            SELECT * FROM user_message_session
            WHERE sessionKey=:sessionKey
        ");
        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":sessionKey", $sessionKey, PDO::PARAM_INT);
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
              sessionKey,senderId,receiveId,lastConcatTime,lastContentInfo,isRead
            )
            VALUES
            (
              :sessionKey,:senderId,:receiveId,now(),:lastContentInfo,:isRead
            )
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":sessionKey", $userMessageSession->sessionKey, PDO::PARAM_STR);
        $command->bindParam(":lastContentInfo", $userMessageSession->lastContentInfo, PDO::PARAM_STR);
        $command->bindParam(":senderId", $userMessageSession->senderId, PDO::PARAM_STR);
        $command->bindParam(":receiveId", $userMessageSession->receiveId, PDO::PARAM_STR);
        $command->bindParam(":isRead", $userMessageSession->isRead, PDO::PARAM_INT);

        $command->execute();
    }


    /**
     * 更新用户session详情
     * @param $sessionKey
     * @param $content
     * @param $isRead
     * @throws \yii\db\Exception
     */
    public function updateUserMessageSession($sessionKey,$content,$isRead)
    {

        $sql=sprintf("
          UPDATE user_message_session SET
          lastConcatTime=now(),lastContentInfo=:lastContentInfo,isRead=:isRead
          WHERE sessionKey=:sessionKey
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":sessionKey", $sessionKey, PDO::PARAM_STR);
        $command->bindParam(":lastContentInfo", $content, PDO::PARAM_STR);
        $command->bindParam(":isRead", $isRead, PDO::PARAM_INT);

        $command->execute();
    }


    /**
     * 获取用户会话列表
     * @param $userSign
     * @param null $isRead
     * @return array
     */
    public function getUserMessageSessionByUserSign($userSign,$isRead=null)
    {
        $sql=sprintf("
            SELECT DISTINCT ub.nickname,ub.headImg,s.* FROM
            (
                SELECT sessionId,sessionKey,senderId as userId,lastConcatTime,lastContentInfo,isRead
                FROM user_message_session
                WHERE receiveId=:userSign
            )
            AS s
            LEFT JOIN user_base ub ON ub.userSign=s.userId
        ");
        if(isset($isRead)){
            $sql.=" WHERE s.isRead=:isRead ";
        }
        $sql.=" ORDER BY s.lastConcatTime ";

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":userSign", $userSign, PDO::PARAM_STR);
        if(isset($isRead)){
            $command->bindParam(":isRead", $isRead, PDO::PARAM_INT);
        }
        return $command->queryAll();
    }


    /**
     * 获取用户聊天记录列表
     * @param $userSign
     * @param $sessionKey
     * @return array
     */
    public function getUserMessageListByKey($userSign,$sessionKey)
    {
        $sql=sprintf("
            SELECT * FROM user_message
            WHERE ( sessionKeyOne=:sessionKey OR sessionKeyTwo=:sessionKey ) AND  (senderId=:userId OR receiveId=:userId)
        ");
        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":userId", $userSign, PDO::PARAM_STR);
        $command->bindParam(":sessionKey", $sessionKey, PDO::PARAM_STR);
        return $command->queryAll();
    }

    /**
     * 更新已读
     * @param $sessionKey
     * @throws \yii\db\Exception
     */
    public function updateUserMessageSessionRead($sessionKey)
    {
        $sql=sprintf("
          UPDATE user_message_session SET
          isRead=TRUE
          WHERE sessionKey=:sessionKey
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":sessionKey", $sessionKey, PDO::PARAM_STR);

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
          WHERE  ( sessionKeyOne=:sessionKey OR sessionKeyTwo=:sessionKey ) AND isRead=FALSE  AND receiveId=:userId
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":sessionKey", $sessionKey, PDO::PARAM_STR);
        $command->bindParam(":userId", $userSign, PDO::PARAM_STR);

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



}