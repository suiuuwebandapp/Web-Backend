<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/7
 * Time : 下午5:15
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\controllers;


use common\components\Code;
use common\components\LogUtils;
use common\entity\UserMessage;
use frontend\services\UserMessageService;
use yii\base\Exception;

class UserMessageController extends  CController{

    private $userMessageService;

    public function __construct($id, $module = null)
    {
        $this->userMessageService = new UserMessageService();
        parent::__construct($id, $module);
    }


    /**
     * 添加发送消息
     */
    public function actionAddUserMessage()
    {
        $senderId=$this->userObj->userSign;
        $receiveId=trim(\Yii::$app->request->post("receiveId"));
        $content=trim(\Yii::$app->request->post("content"));

        if(empty($receiveId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"ReceiveId Is Not Allow Empty"));
        }
        if(empty($content)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"Content Is Not Allow Empty"));
        }

        //判断黑白名单

        $userMessage=new UserMessage();
        $userMessage->senderId=$senderId;
        $userMessage->receiveId=$receiveId;
        $userMessage->content=$content;

        try{
            $this->userMessageService->addUserMessage($userMessage);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }


    /**
     * 获取Message Session 会话 列表
     */
    public function actionMessageSessionList()
    {
        try{
            $userSign=$this->userObj->userSign;
            $list=$this->userMessageService->getUserMessageSessionList($userSign);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$list));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 获取用户未读消息会话
     */
    public function actionUnReadMessageSessionList()
    {
        try{
            $userSign=$this->userObj->userSign;
            $list=$this->userMessageService->getUnReadMessageSessionList($userSign);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$list));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }


    /**
     * 获取会话详情
     * @throws Exception
     * @throws \Exception
     */
    public function actionMessageSessionInfo(){
        $sessionKey=trim(\Yii::$app->request->post("sessionKey"));
        try{
            $userSign=$this->userObj->userSign;
            $list=$this->userMessageService->getUserMessageSessionInfo($userSign,$sessionKey);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$list));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 获取用户当前未读消息列表
     */
    public function actionUnReadMessageInfoList()
    {
        try{
            $userSign=$this->userObj->userSign;
            $list=$this->userMessageService->getUnReadMessageSessionList($userSign);
            $sysList=$this->userMessageService->getUnReadSystemMessageList($userSign);
            $rst=[];
            $rst['userList']=$list;
            $rst['sysList']=$sysList;
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$rst));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 更新系统消息已读
     */
    public function actionChangeSystemMessageRead()
    {
        $messageId=trim(\Yii::$app->request->post("messageId"));

        if(empty($messageId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的消息"));
        }
        try{
            $userSign=$this->userObj->userSign;
            $userMessageSetting=$this->userMessageService->changeSystemMessageRead($messageId,$userSign);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$userMessageSetting));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }


    /**
     * 获取用户消息设定
     */
    public function actionFindUserMessageSetting()
    {
        try{
            $userSign=$this->userObj->userSign;
            $userMessageSetting=$this->userMessageService->findUserMessageSettingByUserId($userSign);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$userMessageSetting));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 更新用户私信设置
     */
    public function actionUpdateMessageSettingStatus()
    {
        $status=\Yii::$app->getRequest()->post("status");
        $userSign=$this->userObj->userSign;
        try{
            $userMessageSetting=$this->userMessageService->findUserMessageSettingByUserId($userSign);
            $userMessageSetting->status=$status;
            $this->userMessageService->updateUserMessageSetting($userMessageSetting);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }


    /**
     * 添加用户屏蔽
     */
    public function actionAddUserMessageShield()
    {
        $shieldId=\Yii::$app->getRequest()->post("shieldId");
        $userSign=$this->userObj->userSign;
        try{
            $this->userMessageService->addUserMessageShield($userSign,$shieldId);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 移除用户屏蔽
     */
    public function actionDeleteUserMessageShield()
    {
        $shieldId=\Yii::$app->getRequest()->post("shieldId");
        $userSign=$this->userObj->userSign;
        try{
            $this->userMessageService->deleteUserMessageShield($userSign,$shieldId);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }
}