<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/2
 * Time : 下午1:58
 * Email: zhangxinmailvip@foxmail.com
 */

namespace backend\controllers;


use common\components\Code;
use common\components\Aes;
use backend\services\SysUserService;
use frontend\entity\UserBase;
use yii\web\Controller;

class CController extends  Controller{

    public $userObj=null;

    public $layout=false;

    public $enableCsrfValidation=false;

    public $__sysUserService=null;

    public function __construct($id, $module = null)
    {
        //验证用户是否登录
        $currentUser=\Yii::$app->session->get(Code::SYS_USER_LOGIN_SESSION);
        $cookieSign=\Yii::$app->request->cookies->getValue(\Yii::$app->params['sys_suiuu_sign']);

        $enPassword = \Yii::$app->params['encryptPassword'];
        $enDigit = \Yii::$app->params['encryptDigit'];


        if(!isset($currentUser)&&empty($cookieSign)) {
            return $this->redirect('/login');
        }else if(isset($currentUser)){
            $this->userObj=$currentUser;
        }else if(!empty($cookieSign)){
            $aes=new Aes();
            $userSign=$aes->decrypt($cookieSign,$enPassword,$enDigit);

            $this->__sysUserService=new SysUserService();
            $currentUser=$this->__sysUserService->findUserByUserSign($userSign);
            if(isset($currentUser)){
                $this->userObj=$currentUser;
                \Yii::$app->session->set(Code::SYS_USER_LOGIN_SESSION,$currentUser);
            }else{
                return $this->redirect('/login');
            }

        }
        parent::__construct($id, $module);
    }
}