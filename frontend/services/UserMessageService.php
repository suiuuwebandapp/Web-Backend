<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/5
 * Time : 上午11:47
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\services;


use common\entity\UserMessage;
use common\entity\UserMessageSession;
use common\models\BaseDb;
use common\models\UserMessageDb;
use yii\base\Exception;

class UserMessageService extends BaseDb
{

    private $userMessageDb;


    /**
     * 添加用户消息
     * @param UserMessage $userMessage
     * @throws Exception
     * @throws \Exception
     */
    public function addUserMessage(UserMessage $userMessage)
    {
        $conn=$this->getConnection();
        $tran=$conn->beginTransaction();
        try{

            $senderKey=$this->getMessageSessionKey($userMessage->senderId,$userMessage->receiveId);
            $receiveKey=$this->getMessageSessionKey($userMessage->receiveId,$userMessage->senderId);

            $userMessage->sessionKeyOne=$senderKey;
            $userMessage->sessionKeyTwo=$receiveKey;

            $this->userMessageDb=new UserMessageDb($conn);
            $userMessageSession=$this->userMessageDb->findUserMessageSessionByKey($senderKey);
            if($userMessageSession==null||$userMessageSession === false){
                $userMessageSession=new UserMessageSession();
                $userMessageSession->sessionKey=$senderKey;
                $userMessageSession->senderId=$userMessage->senderId;
                $userMessageSession->receiveId=$userMessage->receiveId;
                $userMessageSession->lastContentInfo=$userMessage->content;
                $userMessageSession->isRead=false;

                $userMessageSessionTwo=new UserMessageSession();
                $userMessageSessionTwo->sessionKey=$receiveKey;
                $userMessageSessionTwo->senderId=$userMessage->receiveId;
                $userMessageSessionTwo->receiveId=$userMessage->senderId;
                $userMessageSessionTwo->lastContentInfo=$userMessage->content;
                $userMessageSessionTwo->isRead=true;

                $this->userMessageDb->addUserMessageSession($userMessageSession);
                $this->userMessageDb->addUserMessageSession($userMessageSessionTwo);

            }else{

                $this->userMessageDb->updateUserMessageSession($senderKey,$userMessage->content,false);
                $this->userMessageDb->updateUserMessageSession($receiveKey,$userMessage->content,true);

            }

            $this->userMessageDb->addUserMessage($userMessage);
            $this->commit($tran);
        }catch (Exception $e){
            $this->rollback($tran);
            throw $e;
        }finally{
            $this->closeLink();
        }
    }


    /**
     * 获取用户会话列表
     * @param $userSign
     * @return array
     * @throws Exception
     * @throws \Exception
     */
    public function getUserMessageSessionList($userSign)
    {
        try{
            $conn=$this->getConnection();
            $this->userMessageDb=new UserMessageDb($conn);
            return $this->userMessageDb->getUserMessageSessionByUserSign($userSign);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }

    /**
     * 获取用户未读会话列表
     * @param $userSign
     * @return array
     * @throws Exception
     * @throws \Exception
     */
    public function getUnReadMessageSessionList($userSign)
    {
        try{
            $conn=$this->getConnection();
            $this->userMessageDb=new UserMessageDb($conn);
            return $this->userMessageDb->getUserMessageSessionByUserSign($userSign,0);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }


    /**
     * 获取详情
     * @param $userSign
     * @param $sessionKey
     * @return array
     * @throws Exception
     * @throws \Exception
     */
    public function getUserMessageSessionInfo($userSign,$sessionKey)
    {
        $conn=$this->getConnection();
        $tran=$conn->beginTransaction();
        try{
            $this->userMessageDb=new UserMessageDb($conn);
            $this->userMessageDb->updateUserMessageRead($sessionKey,$userSign);
            $this->userMessageDb->updateUserMessageSessionRead($sessionKey);
            $list=$this->userMessageDb->getUserMessageListByKey($userSign,$sessionKey);
            $this->commit($tran);
            return $list;
        }catch (Exception $e){
            $this->rollback($tran);
            throw $e;
        }finally{
            $this->closeLink();
        }
    }

    /**
     * 获悉用户未读消息列表详情
     * @param $userSign
     * @param $count
     * @throws Exception
     * @throws \Exception
     */
    public function getUnReadMessageInfoList($userSign,$count)
    {
        try{
            $conn=$this->getConnection();
            $this->userMessageDb=new UserMessageDb($conn);
            return $this->userMessageDb->getUnReadMessageInfoList($userSign,$count);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }


    /**
     * 生成SessionKey
     * @param $senderId
     * @param $receiveId
     * @return string
     */
    private function getMessageSessionKey($senderId,$receiveId)
    {
        //暂时修改为两个会话
        return md5($senderId.$receiveId);
        if($senderId>$receiveId){
           return md5($senderId.$receiveId);
        }else{
            return md5($receiveId.$senderId);
        }
    }







}