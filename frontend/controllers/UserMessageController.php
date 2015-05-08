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

        //判断黑白名单

        if(empty($receiveId)){
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"ReceiveId Is Not Allow Empty"));
            return;
        }
        if(empty($content)){
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"Content Is Not Allow Empty"));
            return;
        }

        $userMessage=new UserMessage();
        $userMessage->senderId=$senderId;
        $userMessage->receiveId=$receiveId;
        $userMessage->content=$content;

        try{
            $this->userMessageService->addUserMessage($userMessage);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            echo json_encode(Code::statusDataReturn(Code::FAIL));
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
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$list));
        }catch (Exception $e){
            echo json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    public function actionUnReadMessageSessionList()
    {
        try{
            $userSign=$this->userObj->userSign;
            $list=$this->userMessageService->getUnReadMessageSessionList($userSign);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$list));
        }catch (Exception $e){
            echo json_encode(Code::statusDataReturn(Code::FAIL));
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
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$list));
        }catch (Exception $e){
            echo json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }


}