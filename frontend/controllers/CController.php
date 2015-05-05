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
use yii\web\Controller;


class CController extends Controller{

    public $userObj=null;
    public $userPublisherObj=null;


    public $userBaseService=null;
    public $enableCsrfValidation=false;


    public function __construct($id, $module = null)
    {
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

            $this->userBaseService=new UserBaseService();
            $currentUser=$this->userBaseService->findUserByUserSign($userSign);
            if(isset($currentUser)){
                $this->userObj=$currentUser;
                \Yii::$app->session->set(Code::USER_LOGIN_SESSION,$currentUser);
            }else{
                return $this->redirect(['/result', 'result' => '请登录过后再进行操作']);
            }

        }
        if($currentUser->isPublisher){
            if($this->userBaseService==null)$this->userBaseService=new UserBaseService();
            $userPublisherObj=$this->userBaseService->findUserPublisherByUserSign($this->userObj->userSign);
            $this->userPublisherObj=$userPublisherObj;
        }
        parent::__construct($id, $module);
    }
}