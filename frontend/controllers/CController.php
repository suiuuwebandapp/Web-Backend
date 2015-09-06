<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/4/21
 * Time: 下午1:33
 */
namespace frontend\controllers;


use common\components\Code;
use common\components\Aes;
use common\entity\UserBase;
use frontend\services\UserBaseService;
use frontend\services\UserMessageService;
use yii\web\Controller;


class CController extends SController{

    public $userObj=null;
    public $userPublisherObj=null;


    public $userBaseService=null;
    public $enableCsrfValidation=false;


    public function __construct($id, $module = null)
    {
        $this->userBaseService=new UserBaseService();
        //验证用户是否登录
        $currentUser=\Yii::$app->session->get(Code::USER_LOGIN_SESSION);
        $cookieSign=\Yii::$app->request->cookies->getValue(\Yii::$app->params['www_suiuu_sign']);

        $enPassword = \Yii::$app->params['encryptPassword'];
        $enDigit = \Yii::$app->params['encryptDigit'];

        if(!isset($currentUser)&&empty($cookieSign)) {
            return $this->redirect(['/result', 'result' => '请登录过后再进行操作']);
        }else if(isset($currentUser)){
            $this->userObj=$currentUser;
        }else if(!empty($cookieSign)){
            $aes=new Aes();
            $userSign=$aes->decrypt($cookieSign,$enPassword,$enDigit);

            $currentUser=$this->userBaseService->findUserByUserSign($userSign);
            if(isset($currentUser)){
                $this->userObj=$currentUser;
                \Yii::$app->session->set(Code::USER_LOGIN_SESSION,$currentUser);

            }else{
                return $this->redirect(['/result', 'result' => '请登录过后再进行操作']);
            }
        }
        //设置聊天SESSION
        $sessionId=\Yii::$app->getSession()->id;
        $chatUser=\Yii::$app->redis->get(Code::USER_LOGIN_SESSION_CHAT.$sessionId);
        if(empty($chatUser)){
            \Yii::$app->redis->set(Code::USER_LOGIN_SESSION_CHAT.$sessionId,json_encode($currentUser));
            \Yii::$app->redis->expire(Code::USER_LOGIN_SESSION_CHAT.$sessionId,24*60*60);
        }

        if($currentUser->isPublisher){
            $userPublisherObj=$this->userBaseService->findUserPublisherByUserSign($this->userObj->userSign);
            $this->userPublisherObj=$userPublisherObj;
        }
        parent::__construct($id, $module);
    }



    public function refreshUserInfo()
    {
        $currentUser=$this->userBaseService->findUserByUserSign($this->userObj->userSign);
        $this->userObj=$currentUser;
        \Yii::$app->session->set(Code::USER_LOGIN_SESSION,$currentUser);
        //设置聊天SESSION
        $sessionId=\Yii::$app->getSession()->id;
        \Yii::$app->redis->set(Code::USER_LOGIN_SESSION_CHAT.$sessionId,json_encode($currentUser));
    }
}