<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/22
 * Time : 下午3:39
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\components;


use common\entity\UserMessage;
use common\entity\UserPublisher;
use frontend\services\UserMessageService;
use yii\base\Exception;

class SysMessageUtils {


    /**
     * 随友取消订单
     * @param $userId
     * @param $orderNumber
     */
    public function sendPublisherCancelOrderMessage($userId,$orderNumber)
    {
        //1.系统消息
        $userMessage=new UserMessage();
        $userMessage->senderId=Code::USER_SYSTEM_MESSAGE_ID;
        $userMessage->receiveId=$userId;
        $userMessage->content="亲爱的用户，您好，由于随友的原因，您的订单：".$orderNumber."已被取消，稍后客户或者随游将跟您联系，给您带来的不便，敬请谅解。";
        $userMessage->url="/user-info?tab=myOrderManager";
        try{
            $userMessageService=new UserMessageService();
            $userMessageService->addUserMessage($userMessage);
        }catch (Exception $messageException){
        }
        //2.短信消息
        //TODO 循环发送短信提醒
    }

    /**
     * 用户创建新订单，推送给随友
     * @param $tripPublisherList
     * @param $orderNumber
     */
    public function sendNewOrderMessage($tripPublisherList,$orderNumber)
    {
        //1.系统消息
        $messageList=[];
        foreach($tripPublisherList as $publisher){
            $userMessage=new UserMessage();
            $userMessage->senderId=Code::USER_SYSTEM_MESSAGE_ID;
            $userMessage->receiveId=$publisher['userSign'];
            $userMessage->content="您有新的可接随游订单，订单号：".$orderNumber.",点击查看详情";
            $userMessage->url="/user-info?tab=tripManager";

            $messageList[]=$userMessage;
        }
        try{
            $userMessageService=new UserMessageService();
            $userMessageService->addSysMessageList($messageList);
        }catch (Exception $messageException){
        }
        //2.短信消息
        //TODO 循环发送短信提醒
    }


    /**
     * 随友确认订单
     * @param $userId
     * @param $orderNumber
     */
    public function sendPublisherConfirmOrderMessage($userId,$orderNumber)
    {
        //给随友发送消息
        //1.系统消息
        $userMessage=new UserMessage();
        $userMessage->senderId=Code::USER_SYSTEM_MESSAGE_ID;
        $userMessage->receiveId=$userId;
        $userMessage->content="亲爱的用户，您好，随友已经确认了您的订单：".$orderNumber."，我们将尽快给您打款。";
        $userMessage->url="/user-info?tab=myOrderManager";
        try{
            $userMessageService=new UserMessageService();
            $userMessageService->addUserMessage($userMessage);
        }catch (Exception $messageException){
        }
        //2.短信消息
        //TODO 循环发送短信提醒
    }


    /**
     * 用户确认游玩
     * @param $publisherUserId
     * @param $orderNumber
     */
    public function sendUserConfirmPlayMessage($publisherUserId,$orderNumber)
    {
        //1.系统消息
        $userMessage=new UserMessage();
        $userMessage->senderId=Code::USER_SYSTEM_MESSAGE_ID;
        $userMessage->receiveId=$publisherUserId;
        $userMessage->content="亲爱的随游，您好，用户已经确认游玩订单：".$orderNumber."，我们将尽快给您打款。";
        $userMessage->url="";
        try{
            $userMessageService=new UserMessageService();
            $userMessageService->addUserMessage($userMessage);
        }catch (Exception $messageException){
        }
        //2.短信消息
        //TODO 循环发送短信提醒
    }


    /**
     * 同意加入随游
     * @param $publisherUserId
     * @param $tripId
     * @param $tripTitle
     */
    public function sendAgreePublisherApplyMessage($publisherUserId,$tripId,$tripTitle)
    {
        //1.系统消息
        $userMessage=new UserMessage();
        $userMessage->senderId=Code::USER_SYSTEM_MESSAGE_ID;
        $userMessage->receiveId=$publisherUserId;
        $userMessage->content="亲爱的随友，您申请加入的随游：【".$tripTitle."】已经通过审核。";
        $userMessage->url="/view-trip/info?trip=".$tripId;
        try{
            $userMessageService=new UserMessageService();
            $userMessageService->addUserMessage($userMessage);
        }catch (Exception $messageException){
        }
    }


    /**
     * 拒绝加入随游的申请
     * @param $publisherUserId
     * @param $tripId
     * @param $tripTitle
     */
    public function sendOpposePublisherApplyMessage($publisherUserId,$tripId,$tripTitle)
    {
        //1.系统消息
        $userMessage=new UserMessage();
        $userMessage->senderId=Code::USER_SYSTEM_MESSAGE_ID;
        $userMessage->receiveId=$publisherUserId;
        $userMessage->content="亲爱的随友，您申请加入的随游：【".$tripTitle."】未能通过审核.";
        $userMessage->url="";
        try{
            $userMessageService=new UserMessageService();
            $userMessageService->addUserMessage($userMessage);
        }catch (Exception $messageException){
        }
    }


    /**
     * 用户申请加入随游
     * @param $publisherUserId
     * @param $tripId
     * @param $tripTitle
     */
    public function sendUserJoinTripMessage($publisherUserId,$tripId,$tripTitle)
    {
        //1.系统消息
        $userMessage=new UserMessage();
        $userMessage->senderId=Code::USER_SYSTEM_MESSAGE_ID;
        $userMessage->receiveId=$publisherUserId;
        $userMessage->content="亲爱的随友，您的随游【".$tripTitle."】有的新申请，请注意审核.";
        $userMessage->url="/trip/to-apply-list?trip=".$tripId;
        try{
            $userMessageService=new UserMessageService();
            $userMessageService->addUserMessage($userMessage);
        }catch (Exception $messageException){
        }
    }


    /**
     * 用户被移出随游
     * @param $userId
     * @param $tripTitle
     */
    public function sendRemoveUserForTripMessage($userId,$tripTitle)
    {
        //1.系统消息
        $userMessage=new UserMessage();
        $userMessage->senderId=Code::USER_SYSTEM_MESSAGE_ID;
        $userMessage->receiveId=$userId;
        $userMessage->content="亲爱的随友，您已经被创建者移除随游【".$tripTitle."】.";
        $userMessage->url="/user-info?tab=tripManager";
        try{
            $userMessageService=new UserMessageService();
            $userMessageService->addUserMessage($userMessage);
        }catch (Exception $messageException){
        }
    }


    public function sendUserRegisterUserInfoMessage($userId)
    {
        //1.系统消息
        $userMessage=new UserMessage();
        $userMessage->senderId=Code::USER_SYSTEM_MESSAGE_ID;
        $userMessage->receiveId=$userId;
        $userMessage->content="亲爱的用户，您的个人信息尚未完善，快去完善个人信息吧~";
        $userMessage->url="/user-info?tab=userInfo";
        try{
            $userMessageService=new UserMessageService();
            $userMessageService->addUserMessage($userMessage);
        }catch (Exception $messageException){
        }
    }

}