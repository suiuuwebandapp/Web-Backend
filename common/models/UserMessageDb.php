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
              sessionKey,receiveId,senderId,content,sendTime,isRead
            )
            VALUES
            (
              :sessionKey,:receiveId,:senderId,:content,now(),FALSE
            )
        ");

        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":sessionKey", $userMessage->sessionKey, PDO::PARAM_STR);
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
              sessionKey,userOne,userTwo,lastConcatTime,lastContentInfo,isRead
            )
            VALUES
            (
              :sessionKey,:userOne,:userTwo,now(),:lastContentInfo,FALSE
            )
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":sessionKey", $userMessageSession->sessionKey, PDO::PARAM_STR);
        $command->bindParam(":lastContentInfo", $userMessageSession->lastContentInfo, PDO::PARAM_STR);
        $command->bindParam(":userOne", $userMessageSession->userOne, PDO::PARAM_STR);
        $command->bindParam(":userTwo", $userMessageSession->userTwo, PDO::PARAM_STR);

        $command->execute();
    }


    /**
     * 更新用户session详情
     * @param $sessionKey
     * @param $content
     * @throws \yii\db\Exception
     */
    public function updateUserMessageSession($sessionKey,$content)
    {

        $sql=sprintf("
          UPDATE user_message_session SET
          lastConcatTime=now(),lastContentInfo=:lastContentInfo,isRead=FALSE
          WHERE sessionKey=:sessionKey
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":sessionKey", $sessionKey, PDO::PARAM_STR);
        $command->bindParam(":lastContentInfo", $content, PDO::PARAM_STR);

        $command->execute();
    }


    /**
     * 获取用户会话列表
     * @param $userSign
     * @return array
     */
    public function getUserMessageSessionByUserSign($userSign)
    {
        $sql=sprintf("
            SELECT DISTINCT ub.nickname,ub.headImg,s.* FROM
            (
                SELECT sessionId,sessionKey,userTwo as userId,lastConcatTime,lastContentInfo,isRead
                FROM user_message_session
                WHERE userOne=:userSign
                UNION
                SELECT sessionId,sessionKey,userOne as userId,lastConcatTime,lastContentInfo,isRead
                FROM user_message_session
                WHERE userTwo=:userSign
            )
            AS s
            LEFT JOIN user_base ub ON ub.userSign=s.userId
            ORDER BY s.lastConcatTime
        ");

        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":userSign", $userSign, PDO::PARAM_STR);

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
            WHERE sessionKey=:sessionKey AND  (senderId=:userId OR receiverId=:userId)
        ");
        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":userId", $userSign, PDO::PARAM_STR);
        $command->bindParam(":sessionKey", $sessionKey, PDO::PARAM_STR);
        return $command->queryAll();

    }


}