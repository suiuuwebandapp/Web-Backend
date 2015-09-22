<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/7/29
 * Time: 下午5:10
 */

namespace app\modules\v1\controllers;


use app\modules\v1\services\UserBaseService;
use app\modules\v1\services\UserMessageService;
use common\components\Code;
use common\components\LogUtils;
use app\components\Page;
use app\modules\v1\services\UserMessageRemindService;
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
            $type = \Yii::$app->request->get('type');
            $data =$this->msgSer->getNoticeMessage($userSign,$page,$type);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }
    }

    //得到订单提醒
    public function actionGetOrderMessages()
    {
        $this->loginValid();
        try{
            $page = new Page(\Yii::$app->request);
            $userSign = $this->userObj->userSign;
            $type = \Yii::$app->request->get('type');
            $data =$this->msgSer->getOrderMessage($userSign,$page,$type);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }
    }

    //得到旅图提醒
    public function actionGetTpMessages()
    {
        $this->loginValid();
        try{
            $page = new Page(\Yii::$app->request);
            $userSign = $this->userObj->userSign;
            $type = \Yii::$app->request->get('type');
            $data =$this->msgSer->getTpMessage($userSign,$page,$type);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }
    }
    //得到问答提醒
    public function actionGetQaMessages()
    {
        $this->loginValid();
        try{
            $page = new Page(\Yii::$app->request);
            $userSign = $this->userObj->userSign;
            $type = \Yii::$app->request->get('type');
            $data =$this->msgSer->getQaMessage($userSign,$page,$type);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }
    }
    //得到系统提醒
    public function actionGetSysMessages()
    {
        $this->loginValid();
        try{
            $page = new Page(\Yii::$app->request);
            $userSign = $this->userObj->userSign;
            $type = \Yii::$app->request->get('type');
            $data =$this->msgSer->getSysMessage($userSign,$page,$type);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
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
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }
    }

    public function actionGetUserSessionList()
    {
        try{
            $this->loginValid();
            $userSign=$this->userObj->userSign;
            //用户会话列表
            $userMessageService=new UserMessageService();
            $sessionList=$userMessageService->getUserMessageSessionList($userSign);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$sessionList));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }
    }

    public function actionGetUserMessageInfo()
    {
        try{
            $this->loginValid();
            $rUserSign =\Yii::$app->request->get("rUserSign");
            $userSign=$this->userObj->userSign;
            $sessionKey = $this->getMessageSessionKey($userSign,$rUserSign);
            //用户会话列表
            $userMessageService=new UserMessageService();
            //$userBaseService = new UserBaseService();
            //$rInfo = $userBaseService->findBaseInfoBySign($rUserSign);
            $list=$userMessageService->getUserMessageSessionInfo($userSign,$sessionKey);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$list));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
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


}