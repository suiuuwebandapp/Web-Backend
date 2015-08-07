<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/7/29
 * Time: 下午5:10
 */

namespace frontend\controllers;


use common\components\Code;
use common\components\LogUtils;
use frontend\components\Page;
use frontend\services\UserMessageRemindService;
use yii\base\Exception;

class AppUserMessageController extends AController {

    private $msgSer;
    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        $this->msgSer=new UserMessageRemindService();
    }
    //得到通知
    public function actionGetNoticeMessages()
    {
        $this->loginValid();
        try{
            $page = new Page(\Yii::$app->request);
            $userSign = $this->userObj->userSign;
            $type = \Yii::$app->request->post('type');
            $data =$this->msgSer->getNoticeMessage($userSign,$page,$type);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    //得到订单提醒
    public function actionGetOrderMessages()
    {
        $this->loginValid();
        try{
            $page = new Page(\Yii::$app->request);
            $userSign = $this->userObj->userSign;
            $type = \Yii::$app->request->post('type');
            $data =$this->msgSer->getOrderMessage($userSign,$page,$type);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    //得到旅图提醒
    public function actionGetTpMessages()
    {
        $this->loginValid();
        try{
            $page = new Page(\Yii::$app->request);
            $userSign = $this->userObj->userSign;
            $type = \Yii::$app->request->post('type');
            $data =$this->msgSer->getTpMessage($userSign,$page,$type);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }
    //得到问答提醒
    public function actionGetQaMessages()
    {
        $this->loginValid();
        try{
            $page = new Page(\Yii::$app->request);
            $userSign = $this->userObj->userSign;
            $type = \Yii::$app->request->post('type');
            $data =$this->msgSer->getQaMessage($userSign,$page,$type);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }
    //得到系统提醒
    public function actionGetSysMessages()
    {
        $this->loginValid();
        try{
            $page = new Page(\Yii::$app->request);
            $userSign = $this->userObj->userSign;
            $type = \Yii::$app->request->post('type');
            $data =$this->msgSer->getSysMessage($userSign,$page,$type);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }
    //取消消息 即阅读消息
    public function actionDeleteUserMessageRemind()
    {
        $this->loginValid();
        try{
            $rid = \Yii::$app->request->post('rid');
            $userSign = $this->userObj->userSign;
            $this->msgSer->deleteUserMessageRemind($rid,$userSign);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

}