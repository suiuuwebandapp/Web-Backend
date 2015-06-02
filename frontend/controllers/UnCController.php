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
use frontend\services\CountryService;
use frontend\services\UserBaseService;

class UnCController extends SController{

    public $userObj=null;
    public $userPublisherObj=null;
    public $__userBaseService=null;

    public $layout="main";

    //public $enableCsrfValidation=false;

    public $countryList;
    public $areaCode='+86';

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

            $this->__userBaseService=new UserBaseService();
            $currentUser=$this->__userBaseService->findUserByUserSign($userSign);

            if(isset($currentUser)){
                $this->userObj=$currentUser;
                \Yii::$app->session->set(Code::USER_LOGIN_SESSION,$currentUser);
            }else{
                $countrySer=new CountryService();
                $this->countryList=$countrySer->getCountryList();
                $this->areaCode='0086';
            }
        }else{
            $countrySer=new CountryService();
            $this->countryList=$countrySer->getCountryList();
            $this->areaCode='0086';
        }
        if($currentUser!=null&&$currentUser->isPublisher){
            if($this->__userBaseService==null)$this->__userBaseService=new UserBaseService();
            $userPublisherObj=$this->__userBaseService->findUserPublisherByUserSign($this->userObj->userSign);
            $this->userPublisherObj=$userPublisherObj;
        }
        parent::__construct($id, $module);
    }

    public function refreshUserInfo()
    {
        $this->__userBaseService=new UserBaseService();
        $currentUser=$this->__userBaseService->findUserByUserSign($this->userObj->userSign);
        $this->userObj=$currentUser;
        \Yii::$app->session->set(Code::USER_LOGIN_SESSION,$currentUser);
    }



}