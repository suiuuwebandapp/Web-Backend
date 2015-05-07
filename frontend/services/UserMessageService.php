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

            $sessionKey=$this->getMessageSessionKey($userMessage->senderId,$userMessage->receiveId);
            $userMessage->sessionKey=$sessionKey;
            $this->userMessageDb=new UserMessageDb($conn);
            $userMessageSession=$this->userMessageDb->findUserMessageSessionByKey($sessionKey);
            if($userMessageSession==null||$userMessageSession === false){
                $userMessageSession=new UserMessageSession();
                $userMessageSession->sessionKey=$sessionKey;
                $userMessageSession->userOne=$userMessage->senderId;
                $userMessageSession->userTwo=$userMessage->receiveId;
                $userMessageSession->lastContentInfo=$userMessage->content;
                $this->userMessageDb->addUserMessageSession($userMessageSession);
            }else{
                $this->userMessageDb->updateUserMessageSession($sessionKey,$userMessage->content);
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
     * 生成SessionKey
     * @param $senderId
     * @param $receiveId
     * @return string
     */
    private function getMessageSessionKey($senderId,$receiveId)
    {
        if($senderId>$receiveId){
           return md5($senderId.$receiveId);
        }else{
            return md5($receiveId.$senderId);
        }
    }




}