<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/4/21
 * Time: 下午1:33
 */
namespace frontend\controllers;


use backend\services\CountryService;
use common\components\Code;
use common\components\Aes;
use common\entity\UserBase;
use frontend\services\UserBaseService;
use yii\web\Controller;


class AController extends Controller{

    public $userObj=null;

    public $layout=false;

    public $userService=null;
    public $enableCsrfValidation=false;

    public function __construct($id, $module = null)
    {

        parent::__construct($id, $module);
    }
    public function loginValid($bo=true,$isApp=true)
    {
        if($isApp) {
            if ($bo) {
                //验证用户是否登录
                $appSign = \Yii::$app->request->post(\Yii::$app->params['app_suiuu_sign']);

                $currentUser = json_decode(stripslashes(\Yii::$app->redis->get(Code::APP_USER_LOGIN_SESSION . $appSign)));

                if (!isset($currentUser) && empty($appSign)) {
                    echo json_encode(Code::statusDataReturn(Code::UN_LOGIN, 'appSign不能为空'));
                    exit;
                } else if (isset($currentUser)) {
                    if ($currentUser->status != UserBase::USER_STATUS_NORMAL) {
                        echo json_encode(Code::statusDataReturn(Code::FAIL, "用户已经被删除"));
                        exit;
                    } else {
                        $this->userObj = $currentUser;
                    }
                } else {
                    echo json_encode(Code::statusDataReturn(Code::UN_LOGIN, '登陆已过期请重新登陆'));
                    exit;
                }
            } else {
                $appSign = \Yii::$app->request->post(\Yii::$app->params['app_suiuu_sign']);
                //$appSign='6wE3hviwUVUcV6amFN0mODP1N8Yn95fly8oIznYYw+x/LIL/s1ygug==';
                $currentUser = json_decode(stripslashes(\Yii::$app->redis->get(Code::APP_USER_LOGIN_SESSION . $appSign)));
                if (isset($currentUser)) {
                    if ($currentUser->status != UserBase::USER_STATUS_NORMAL) {
                        $this->userObj=new UserBase();
                        $this->userObj->userSign='';
                    } else {
                        $this->userObj = $currentUser;
                    }
                }else
                {
                    $this->userObj=new UserBase();
                    $this->userObj->userSign='';
                }
            }
        }else
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
                    /* $countrySer=new CountryService();
                     $this->countryList=$countrySer->getCountryList();
                     $this->areaCode='0086';*/
                }
            }else{
                /*$countrySer=new CountryService();
                $this->countryList=$countrySer->getCountryList();
                $this->areaCode='0086';*/
            }
            if($currentUser!=null&&$currentUser->isPublisher){
                if($this->__userBaseService==null)$this->__userBaseService=new UserBaseService();
                $userPublisherObj=$this->__userBaseService->findUserPublisherByUserSign($this->userObj->userSign);
                $this->userPublisherObj=$userPublisherObj;
            }
        }
    }

    public function appRefreshUserInfo()
    {
        $this->userService=new UserBaseService();
        $currentUser=$this->userService->findUserByUserSign($this->userObj->userSign);
        $this->userObj=$currentUser;
        $enPassword = \Yii::$app->params['encryptPassword'];
        $enDigit = \Yii::$app->params['encryptDigit'];
        $aes=new Aes();
        $sysSign=$aes->encrypt( $this->userObj->userSign,$enPassword,$enDigit);
        \Yii::$app->redis->set(Code::APP_USER_LOGIN_SESSION.$sysSign,json_encode($this->userObj));
        \Yii::$app->redis->expire(Code::APP_USER_LOGIN_SESSION.$sysSign,Code::APP_USER_LOGIN_VERIFY_CODE_EXPIRE_TIME);
    }


}