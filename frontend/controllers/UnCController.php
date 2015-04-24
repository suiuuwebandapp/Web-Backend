<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/24
 * Time : 下午2:41
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\controllers;


use backend\services\SysUserService;
use common\components\Aes;
use common\components\Code;
use yii\web\Controller;

class UnCController extends Controller{
    public $userObj=null;

    public $layout="main";

    //public $enableCsrfValidation=false;

    public $__sysUserService=null;

    public function __construct($id, $module = null)
    {
        //验证用户是否登录
        $currentUser=\Yii::$app->session->get(Code::USER_LOGIN_SESSION);
        $cookieSign=\Yii::$app->request->cookies->getValue(\Yii::$app->params['suiuu_sign']);

        $enPassword = \Yii::$app->params['encryptPassword'];
        $enDigit = \Yii::$app->params['encryptDigit'];

        if(isset($currentUser)){
            $this->userObj=$currentUser;
        }else if(!isset($currentUser)&&!empty($cookieSign)){
            $aes=new Aes();
            $userSign=$aes->decrypt($cookieSign,$enPassword,$enDigit);

            $this->__sysUserService=new SysUserService();
            $currentUser=$this->__sysUserService->findUserByUserSign($userSign);
            if(isset($currentUser)){
                $this->userObj=$currentUser;
                \Yii::$app->session->set(Code::USER_LOGIN_SESSION,$currentUser);
            }
        }
        parent::__construct($id, $module);
    }
}