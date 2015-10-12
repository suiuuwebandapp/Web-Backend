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
use common\entity\UserMessageRemind;
use common\entity\UserPublisher;
use frontend\services\UserMessageRemindService;
use frontend\services\UserMessageService;
use yii\base\Exception;

class SysMessageUtils {

    /**
     * 随友取消订单change
     * @param $cUserSign
     * @param $rUserSign
     * @param $orderNumber
     */
    public function sendPublisherCancelOrderMessage($cUserSign,$rUserSign,$orderNumber)
    {
        //1.系统消息
        try{
            $messageRemindSer = new UserMessageRemindService();
            $content="亲爱的用户，您好，由于随友的原因，您的订单：".$orderNumber."已被取消，稍后客户或者随游将跟您联系，给您带来的不便，敬请谅解。";
            $url="/user-info?tab=myOrderManager";
            $messageRemindSer->addMessageRemind($orderNumber,UserMessageRemind::TYPE_PUBLISH_CANCEL,$cUserSign,$rUserSign,UserMessageRemind::R_TYPE_ORDER,$content,$url);
        }catch (Exception $messageException){
        }
        //2.短信消息
        //TODO 循环发送短信提醒
    }
    /**
     * 用户创建新订单，推送给随友
     * @param $cUserSign
     * @param $tripPublisherList
     * @param $orderNumber
     */
    public function sendNewOrderMessage($cUserSign,$tripPublisherList,$orderNumber)
    {
        try{
            $messageRemindSer = new UserMessageRemindService();
            foreach($tripPublisherList as $publisher){
                $content="您有新的可接随游订单，订单号：".$orderNumber.",点击查看详情";
                $url="/user-info?tab=tripManager";
                $messageRemindSer->addMessageRemind($orderNumber,UserMessageRemind::TYPE_NEW_ORDER,$cUserSign,$publisher['userSign'],UserMessageRemind::R_TYPE_ORDER,$content,$url);
            }

        }catch (Exception $messageException){

        }
        //2.短信消息
        //TODO 循环发送短信提醒
    }
    /**
     * 随友确认订单
     * @param $cUserSign
     * @param $rUserSign
     * @param $orderNumber
     */
    public function sendPublisherConfirmOrderMessage($cUserSign,$rUserSign,$orderNumber)
    {
        try{
            $messageRemindSer = new UserMessageRemindService();
            $content="亲爱的用户，您好，随友已经确认了您的订单：".$orderNumber."，我们将尽快给您打款。";
            $url="/user-info?tab=myOrderManager";
            $messageRemindSer->addMessageRemind($orderNumber,UserMessageRemind::TYPE_PUBLISH_CONFIRM_REFUND,$cUserSign,$rUserSign,UserMessageRemind::R_TYPE_ORDER,$content,$url);
        }catch (Exception $messageException){

        }
        //2.短信消息
        //TODO 循环发送短信提醒
    }


    /**
     * 用户确认游玩
     * @param $cUserSign
     * @param $rUserSign
     * @param $orderNumber
     */
    public function sendUserConfirmPlayMessage($cUserSign,$rUserSign,$orderNumber)
    {
        try{
            $messageRemindSer = new UserMessageRemindService();
            $content="亲爱的随游，您好，用户已经确认游玩订单：".$orderNumber."，我们将尽快给您打款。";
            $url="";
            $messageRemindSer->addMessageRemind($orderNumber,UserMessageRemind::TYPE_USER_CONFIRM,$cUserSign,$rUserSign,UserMessageRemind::R_TYPE_ORDER,$content,$url);
        }catch (Exception $messageException){
        }
        //2.短信消息
        //TODO 循环发送短信提醒
    }


    /**
     * 同意加入随游
     * @param $cUserSign
     * @param $rUserSign
     * @param $tripId
     * @param $tripTitle
     */
    public function sendAgreePublisherApplyMessage($cUserSign,$rUserSign,$tripId,$tripTitle)
    {
        try{
            $messageRemindSer = new UserMessageRemindService();
            $content="亲爱的随友，您申请加入的随游：【".$tripTitle."】已经通过审核。";
            $url=SiteUrl::getTripUrl($tripId);
            $messageRemindSer->addMessageRemind($tripId,UserMessageRemind::TYPE_PUBLISH_CONFIRM_JOIN,$cUserSign,$rUserSign,UserMessageRemind::R_TYPE_TRIP,$content,$url);
        }catch (Exception $messageException){
        }
    }


    /**
     * 拒绝加入随游的申请
     * @param $cUserSign
     * @param $rUserSign
     * @param $tripId
     * @param $tripTitle
     */
    public function sendOpposePublisherApplyMessage($cUserSign,$rUserSign,$tripId,$tripTitle)
    {
        //1.系统消息
        try{
            $messageRemindSer = new UserMessageRemindService();
            $content="亲爱的随友，您申请加入的随游：【".$tripTitle."】未能通过审核。";
            $url="";
            $messageRemindSer->addMessageRemind($tripId,UserMessageRemind::TYPE_PUBLISH_UN_CONFIRM_JOIN,$cUserSign,$rUserSign,UserMessageRemind::R_TYPE_TRIP,$content,$url);
        }catch (Exception $messageException){
        }
    }


    /**
     * 用户申请加入随游
     * @param $cUserSign
     * @param $rUserSign
     * @param $tripId
     * @param $tripTitle
     */
    public function sendUserJoinTripMessage($cUserSign,$rUserSign,$tripId,$tripTitle)
    {
        //1.系统消息
        try{
            $messageRemindSer = new UserMessageRemindService();
            $content="亲爱的随友，您的随游【".$tripTitle."】有的新申请，请注意审核.";
            $url="/trip/to-apply-list?trip=".$tripId;
            $messageRemindSer->addMessageRemind($tripId,UserMessageRemind::TYPE_JOIN,$cUserSign,$rUserSign,UserMessageRemind::R_TYPE_TRIP,$content,$url);
        }catch (Exception $messageException){
        }
    }


    /**
     * 用户被移出随游
     *  @param $cUserSign
     * @param $rUserSign
     * @param $tripId
     * @param $tripTitle
     */
    public function sendRemoveUserForTripMessage($cUserSign,$rUserSign,$tripId,$tripTitle)
    {
        try{
            $messageRemindSer = new UserMessageRemindService();
            $content="亲爱的随友，您已经被创建者移除随游【".$tripTitle."】.";
            $url="/user-info?tab=tripManager";
            $messageRemindSer->addMessageRemind($tripId,UserMessageRemind::TYPE_REMOVE_PUBLISH,$cUserSign,$rUserSign,UserMessageRemind::R_TYPE_TRIP,$content,$url);
        }catch (Exception $messageException){
        }
    }


    public function sendUserRegisterUserInfoMessage($rUserSign)
    {
        //1.系统消息
        try{
            $messageRemindSer = new UserMessageRemindService();
            $content="亲爱的用户，您的个人信息尚未完善，快去完善个人信息吧~";
            $url="/user-info?tab=userInfo";
            $messageRemindSer->addMessageRemind(1,UserMessageRemind::TYPE_PERFECT_USER_INFO,"sys",$rUserSign,UserMessageRemind::R_TYPE_SYS,$content,$url);
        }catch (Exception $messageException){
        }
    }

}