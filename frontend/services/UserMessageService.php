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
use frontend\components\Page;
use frontend\models\UserBaseDb;
use yii\base\Exception;

class UserMessageService extends BaseDb
{

    private $userMessageDb;


    /**
     * 添加用户消息
     * 1.获取sessionKey
     * 2.判断发送方的session是否存在
     * 3.判断接收方的session是否存在
     * 4.判断收件方是否对发送方进行屏蔽
     * 5.设置消息内容，根据是否屏蔽 设置 消息 状态
     * 6.更新双方session内容
     *
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
            //1.获取是否有对应的两个 sessionKey
            $sessionKey=$this->getMessageSessionKey($userMessage->senderId,$userMessage->receiveId);

            $userMessage->sessionKey=$sessionKey;

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

            //如果收件方 屏蔽了 发件方  无需更新收件方session
            if(!$refuseFlag){
                $receiverMessageSession=$this->userMessageDb->findUserMessageSessionByKey($userMessage->receiveId,$sessionKey);
                //发信人 Session
                if($receiverMessageSession==null||$receiverMessageSession === false){
                    $receiverMessageSession=new UserMessageSession();
                    $receiverMessageSession->sessionKey=$sessionKey;
                    $receiverMessageSession->userId=$userMessage->receiveId;
                    $receiverMessageSession->relateUserId=$userMessage->senderId;
                    $receiverMessageSession->lastContentInfo=$userMessage->content;
                    $receiverMessageSession->isRead=false;
                    $receiverMessageSession->unReadCount=1;

                    $this->userMessageDb->addUserMessageSession($receiverMessageSession);
                }else{
                    $unReadCount=$receiverMessageSession['unReadCount']+1;
                    $this->userMessageDb->updateUserMessageSession($receiverMessageSession['sessionId'],$userMessage->content,false,$unReadCount);
                }
            }else{
                //如果屏蔽了，那么要设置userMessage 屏蔽状态
                $userMessage->isShield=true;
            }
            //无论收信人是否屏蔽发信人 发信人的 Session 一定要更新
            $senderMessageSession=$this->userMessageDb->findUserMessageSessionByKey($userMessage->senderId,$sessionKey);
            if($senderMessageSession==null||$senderMessageSession === false){
                $senderMessageSession=new UserMessageSession();
                $senderMessageSession->sessionKey=$sessionKey;
                $senderMessageSession->userId=$userMessage->senderId;
                $senderMessageSession->relateUserId=$userMessage->receiveId;
                $senderMessageSession->lastContentInfo=$userMessage->content;
                $senderMessageSession->isRead=true;
                $senderMessageSession->unReadCount=0;

                $this->userMessageDb->addUserMessageSession($senderMessageSession);
            }else{
                $this->userMessageDb->updateUserMessageSession($senderMessageSession['sessionId'],$userMessage->content,true,0);
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
     * 注意：这里指的都是系统发送的消息
     * 同时添加多条用户消息
     * @param $messageList
     * @throws Exception
     * @throws \Exception
     */
    public function addSysMessageList($messageList)
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
                if($userMessage->senderId!=Code::USER_SYSTEM_MESSAGE_ID){
                    throw new Exception("Invalid SenderId");
                }
                $sessionKey=$this->getMessageSessionKey($userMessage->senderId,$userMessage->receiveId);

                $userMessage->sessionKey=$sessionKey;

                $userMessageSession=$this->userMessageDb->findUserMessageSessionByKey($userMessage->receiveId,$sessionKey);
                if($userMessageSession==null||$userMessageSession === false){
                    $userMessageSession=new UserMessageSession();
                    $userMessageSession->sessionKey=$sessionKey;
                    $userMessageSession->senderId=$userMessage->senderId;
                    $userMessageSession->receiveId=$userMessage->receiveId;
                    $userMessageSession->lastContentInfo=$userMessage->content;
                    $userMessageSession->isRead=false;
                    $userMessageSession->unReadCount=1;

                    $this->userMessageDb->addUserMessageSession($userMessageSession);
                }else{
                    $unReadCount=$userMessageSession['unReadCount']+1;
                    $this->userMessageDb->updateUserMessageSession($userMessageSession['sessionId'],$userMessage->content,false,$unReadCount);
                }
                $userMessage->isShield=false;
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
     * @param $userId
     * @return array
     * @throws Exception
     * @throws \Exception
     */
    public function getUserMessageSessionList($userId)
    {
        try{
            $conn=$this->getConnection();
            $this->userMessageDb=new UserMessageDb($conn);
            return $this->userMessageDb->getUserMessageSessionByUserId($userId,null);
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
            return $this->userMessageDb->getUserMessageSessionByUserId($userSign,0);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }

    /**
     * 获取用户未读列表消息
     * @param $userId
     * @return array|null
     * @throws Exception
     * @throws \Exception
     */
    public function getUnReadMessageList($userId)
    {
        $rst=[];
        $userList=[];
        $sysList=[];
        try{
            $conn=$this->getConnection();
            $this->userMessageDb=new UserMessageDb($conn);
            $list= $this->userMessageDb->getUnReadMessageList($userId);

            if(!empty($list)){
                foreach($list as $message)
                {
                    if($message['senderId']==Code::USER_SYSTEM_MESSAGE_ID){
                        $sysList[]=$message;
                    }else{
                        $userList[]=$message;
                    }
                }
            }
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
        $userMessageRemindService=new UserMessageRemindService();
        $page=new Page();
        $page->showAll;
        $sys=$userMessageRemindService->getWebSysMessage($userId,$page,null);
        if(!empty($sys['data'])){
            foreach($sys['data'] as $message){
                $sessionKey=self::getMessageSessionKey(Code::USER_SYSTEM_MESSAGE_ID,$userId);

                $sysMessage=[];
                $sysMessage['messageId']=$message['remindId'];

                $sysMessage['sessionKey']=$sessionKey;
                $sysMessage['receiveId']=$userId;
                $sysMessage['senderId']=Code::USER_SYSTEM_MESSAGE_ID;
                $sysMessage['url']=$message['url'];
                $sysMessage['content']=$message['content'];
                $sysMessage['sendTime']=$message['createTime'];
                $sysMessage['readTime']=$message['readTime'];
                $sysMessage['isRead']=$message['rStatus']==1?false:true;
                $sysMessage['isShield']=false;
                $sysList[]=$sysMessage;
            }
        }
        $rst['userList']=$userList;
        $rst['sysList']=$sysList;

        return $rst;
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
     * @param $userId
     * @param $sessionKey
     * @return array
     * @throws Exception
     * @throws \Exception
     */
    public function getUserMessageSessionInfo($userId,$sessionKey)
    {
        $conn=$this->getConnection();
        $tran=$conn->beginTransaction();
        try{
            $this->userMessageDb=new UserMessageDb($conn);
            $senderMessageSession=$this->userMessageDb->findUserMessageSessionByKey($userId,$sessionKey);
            $this->userMessageDb->updateUserMessageRead($sessionKey,$userId);
            $this->userMessageDb->updateUserMessageSessionRead($senderMessageSession['userId'],$sessionKey);
            $list=$this->userMessageDb->getUserMessageListByKey($userId,$sessionKey);

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
                array_splice($shieldIdArr,array_search($shieldId,$shieldIdArr),1);
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