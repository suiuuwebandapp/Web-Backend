<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/5
 * Time : 上午11:47
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\services;


use common\components\Code;
use common\entity\UserMessage;
use common\entity\UserMessageSession;
use common\entity\UserMessageSetting;
use common\models\BaseDb;
use common\models\UserMessageDb;
use frontend\models\UserBaseDb;
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

        //系统不能作为消息的收信人
        if($userMessage->receiveId==Code::USER_SYSTEM_MESSAGE_ID){
            throw new Exception("Invalid User System Message");
        }
        $receiveSetting=$this->findUserMessageSettingByUserId($userMessage->receiveId);
        $conn=$this->getConnection();
        $tran=$conn->beginTransaction();
        try{

            $senderKey=$this->getMessageSessionKey($userMessage->senderId,$userMessage->receiveId);
            $receiveKey=$this->getMessageSessionKey($userMessage->receiveId,$userMessage->senderId);

            $userMessage->sessionKeyOne=$senderKey;
            $userMessage->sessionKeyTwo=$receiveKey;


            $this->userMessageDb=new UserMessageDb($conn);
            $refuseFlag=false;//是否屏蔽接受消息
            if($receiveSetting->status==UserMessageSetting::USER_MESSAGE_SETTING_STATUS_ALLOW_ALL){
                if(!empty($receiveSetting->shieldIds)){
                    $shieldArr=explode(",",$receiveSetting->shieldIds);
                    if(in_array($userMessage->senderId,$shieldArr)){$refuseFlag=true;}
                }
            }else if($receiveSetting->status==UserMessageSetting::USER_MESSAGE_SETTING_STATUS_REFUSE_ALL){
                $refuseFlag=true;
            }
            $userMessage->isRefused=$refuseFlag;
            if(!$refuseFlag){
                $sendMessageSession=$this->userMessageDb->findUserMessageSessionByKey($senderKey);
                //发信人 Session
                if($sendMessageSession==null||$sendMessageSession === false){
                    $userMessageSession=new UserMessageSession();
                    $userMessageSession->sessionKey=$senderKey;
                    $userMessageSession->senderId=$userMessage->senderId;
                    $userMessageSession->receiveId=$userMessage->receiveId;
                    $userMessageSession->lastContentInfo=$userMessage->content;
                    $userMessageSession->isRead=false;

                    $this->userMessageDb->addUserMessageSession($userMessageSession);
                }else{
                    $this->userMessageDb->updateUserMessageSession($senderKey,$userMessage->content,false);
                }
            }
            $receiveMessageSession=$this->userMessageDb->findUserMessageSessionByKey($receiveKey);
            //收信人 Session
            if($receiveMessageSession==null||$receiveMessageSession === false){
                $userMessageSessionTwo=new UserMessageSession();
                $userMessageSessionTwo->sessionKey=$receiveKey;
                $userMessageSessionTwo->senderId=$userMessage->receiveId;
                $userMessageSessionTwo->receiveId=$userMessage->senderId;
                $userMessageSessionTwo->lastContentInfo=$userMessage->content;
                $userMessageSessionTwo->isRead=true;

                $this->userMessageDb->addUserMessageSession($userMessageSessionTwo);
            }else{
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
     * 同时添加多条用户消息
     * @param $messageList
     * @throws Exception
     * @throws \Exception
     */
    public function addUserMessageList($messageList)
    {
        if($messageList==null&&count($messageList)==0){
            return;
        }
        $conn=$this->getConnection();
        $tran=$conn->beginTransaction();
        $this->userMessageDb=new UserMessageDb($conn);

        try{

            foreach($messageList as $userMessage){
                //系统不能作为消息的收信人
                if($userMessage->receiveId==Code::USER_SYSTEM_MESSAGE_ID){
                    throw new Exception("Invalid User System Message");
                }
                $senderKey=$this->getMessageSessionKey($userMessage->senderId,$userMessage->receiveId);
                $receiveKey=$this->getMessageSessionKey($userMessage->receiveId,$userMessage->senderId);

                $userMessage->sessionKeyOne=$senderKey;
                $userMessage->sessionKeyTwo=$receiveKey;

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
            }
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
            $messageSetting=$this->userMessageDb->findUserMessageSettingByUserId($userSign);
            $shieldIds='';
            if(!empty($messageSetting['shieldIds'])){
                $shieldArr=explode(",",$messageSetting['shieldIds']);
                $shieldIds="'".implode("','",$shieldArr)."'";
            }
            return $this->userMessageDb->getUserMessageSessionByUserSign($userSign,null,$shieldIds,$messageSetting['status']);
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
            $messageSetting=$this->userMessageDb->findUserMessageSettingByUserId($userSign);
            $shieldIds='';
            if(!empty($messageSetting['shieldIds'])){
                $shieldArr=explode(",",$messageSetting['shieldIds']);
                $shieldIds="'".implode("','",$shieldArr)."'";
            }
            return $this->userMessageDb->getUserMessageSessionByUserSign($userSign,0,$shieldIds,$messageSetting['status']);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }

    /**
     * 获取未读系统消息
     * @param $userSign
     * @return array
     * @throws Exception
     * @throws \Exception
     */
    public function getUnReadSystemMessageList($userSign)
    {
        try{
            $conn=$this->getConnection();
            $this->userMessageDb=new UserMessageDb($conn);
            return $this->userMessageDb->getUnReadSystemMessageList($userSign);
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
            $tempMessage=null;
            $refusedFlag=false;
            $newList=[];
            if($list!=null&&count($list)>0){
                $tempMessage=$list[0];
            }
            $messageSetting=$this->userMessageDb->findUserMessageSettingByUserId($userSign);
            if($messageSetting['status']==UserMessageSetting::USER_MESSAGE_SETTING_STATUS_ALLOW_ALL){
                if(!empty($messageSetting['shieldIds'])){
                    $shieldArr=explode(",",$messageSetting['shieldIds']);
                    if($tempMessage!=null&&in_array($tempMessage['senderId'],$shieldArr)){
                        $refusedFlag=true;
                    }
                }
            }else{
                $refusedFlag=true;
            }

            foreach($list as $message){
                if($refusedFlag==false){
                    $newList[]=$message;
                }else if($message['isRefused']==true){
                    $newList[]=$message;
                }
            }
            $this->commit($tran);
            return $newList;
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


    /**
     * 获取用户系统消息设置
     * @param $userId
     * @return array|bool|UserMessageSetting|mixed|null
     * @throws Exception
     * @throws \Exception
     */
    public function findUserMessageSettingByUserId($userId)
    {
        if(empty($userId)){
            throw new Exception("Invalid UserId");
        }
        $messageSetting=null;
        try{
            $conn=$this->getConnection();
            $this->userMessageDb=new UserMessageDb($conn);
            //判断是否存在消息设置，如果没有，添加默认
            $messageSetting=$this->userMessageDb->findUserMessageSettingByUserId($userId);
            $messageSetting=$this->arrayCastObject($messageSetting,UserMessageSetting::class);
            if($messageSetting==null){
                $messageSetting=new UserMessageSetting();
                $messageSetting->userId=$userId;
                $messageSetting->shieldIds="";
                $messageSetting->status=UserMessageSetting::USER_MESSAGE_SETTING_STATUS_ALLOW_ALL;//默认接收所有消息
                $this->userMessageDb->addUserMessageSetting($messageSetting);
                $messageSetting->settingId=$this->getLastInsertId();
            }
            $userBaseDb=new UserBaseDb($conn);
            if(!empty($messageSetting->shieldIds)){
                $shieldArr=explode(",",$messageSetting->shieldIds);
                $shieldIds="'".implode("','",$shieldArr)."'";
                $userBaseList=$userBaseDb->getUserBaseByUserIds($shieldIds);
                $messageSetting->userBaseList=$userBaseList;
            }
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
        return $messageSetting;
    }


    /**
     * 更新用户设置
     * @param UserMessageSetting $userMessageSetting
     * @throws Exception
     * @throws \Exception
     */
    public function updateUserMessageSetting(UserMessageSetting $userMessageSetting)
    {
        try{
            $conn=$this->getConnection();
            $this->userMessageDb=new UserMessageDb($conn);
            $this->userMessageDb->updateUserMessageSetting($userMessageSetting);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }


    /**
     * 添加用户屏蔽
     * @param $userId
     * @param $shieldId
     * @throws Exception
     * @throws \Exception
     */
    public function addUserMessageShield($userId,$shieldId)
    {
        if(empty($userId)){
            throw new Exception("Invalid UserId");
        }
        if(empty($shieldId)){
            throw new Exception("Invalid ShieldId");
        }
        if($shieldId==Code::USER_SYSTEM_MESSAGE_ID){
            throw new Exception("Invalid ShieldId System Message Id");
        }
        try{
            $messageSetting=$this->findUserMessageSettingByUserId($userId);
            $shieldIds=$messageSetting->shieldIds;
            $shieldIdArr=[];
            if($shieldIds!=''){
                $shieldIdArr=explode(",",$shieldIds);
            }
            if(!in_array($shieldId,$shieldIdArr)){
                $shieldIdArr[]=$shieldId;
                $shieldIds=implode(",",$shieldIdArr);
                $conn=$this->getConnection();
                $this->userMessageDb=new UserMessageDb($conn);
                $messageSetting->shieldIds=$shieldIds;
                $this->userMessageDb->updateUserMessageSetting($messageSetting);
            }
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }

    /**
     * 删除用户屏蔽
     * @param $userId
     * @param $shieldId
     * @throws Exception
     * @throws \Exception
     */
    public function deleteUserMessageShield($userId,$shieldId)
    {
        if(empty($userId)){
            throw new Exception("Invalid UserId");
        }
        if(empty($shieldId)){
            throw new Exception("Invalid ShieldId");
        }
        try{
            $messageSetting=$this->findUserMessageSettingByUserId($userId);
            $shieldIds=$messageSetting->shieldIds;
            $shieldIdArr=explode(",",$shieldIds);
            if(in_array($shieldId,$shieldIdArr)){
                array_splice($shieldIdArr,array_search($shieldId,$shieldIdArr));
                $shieldIds=implode(",",$shieldIdArr);
                $conn=$this->getConnection();
                $this->userMessageDb=new UserMessageDb($conn);
                $messageSetting->shieldIds=$shieldIds;
                $this->userMessageDb->updateUserMessageSetting($messageSetting);
            }
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }


    /**
     * 更新系统消息已读
     * @param $messageId
     * @param $userSign
     * @throws Exception
     * @throws \Exception
     */
    public function changeSystemMessageRead($messageId,$userSign)
    {
        if(empty($messageId)){
            throw new Exception("Invalid MessageId");
        }
        try{
            $conn=$this->getConnection();
            $this->userMessageDb=new UserMessageDb($conn);
            $this->userMessageDb->updateSystemMessageRead($messageId,$userSign);

        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }

}