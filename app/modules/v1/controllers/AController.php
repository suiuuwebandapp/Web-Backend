<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/8/12
 * Time: 下午3:36
 */

namespace app\modules\v1\controllers;


use common\components\Aes;
use common\components\Code;
use common\components\RequestValidate;
use common\entity\UserBase;
use common\entity\UserPublisher;
use frontend\services\UserBaseService;
use yii\rest\ActiveController;
use yii\rest\Controller;

class AController extends Controller{

    public $userObj=null;

    public $layout=false;

    public $userService=null;
    public $enableCsrfValidation=false;
    public $userPublisherObj=null;
    public $__userBaseService=null;

    public function __construct($id, $module = null)
    {
        $rv=new RequestValidate();
        $rv->validate();
        parent::__construct($id, $module);
    }
    public function loginValid($bo=true)
    {

        $token=\Yii::$app->request->get("token");
        $appSign = \Yii::$app->redis->get(Code::APP_TOKEN . $token);
        if(empty($appSign))
        {
            echo json_encode(Code::statusDataReturn(Code::TOKEN_ERROR, 'token已过期'));
            exit;
        }
            if ($bo) {
                //验证用户是否登录
                //$appSign = \Yii::$app->request->post(\Yii::$app->params['app_suiuu_sign']);

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
                //$appSign = \Yii::$app->request->post(\Yii::$app->params['app_suiuu_sign']);
                $currentUser = json_decode(stripslashes(\Yii::$app->redis->get(Code::APP_USER_LOGIN_SESSION . $appSign)));
                if (isset($currentUser)) {
                    if ($currentUser->status != UserBase::USER_STATUS_NORMAL) {
                        $this->userObj=new UserBase();
                        $this->userObj->userSign='';//085963dc0af031709b032725e3ef18f5
                    } else {
                        $this->userObj = $currentUser;
                    }
                }else
                {
                    $this->userObj=new UserBase();
                    $this->userObj->userSign='';//085963dc0af031709b032725e3ef18f5
                }
            }
            if($currentUser!=null&&$currentUser->isPublisher){
                if($this->__userBaseService==null)$this->__userBaseService=new UserBaseService();
                $userPublisherObj=$this->__userBaseService->findUserPublisherByUserSign($this->userObj->userSign);
                $this->userPublisherObj=$userPublisherObj;
            }
            if($this->userPublisherObj==null)
            {
                $this->userPublisherObj=new UserPublisher();
                $this->userPublisherObj->userPublisherId=0;
            }


    }

    public function apiReturn($arr)
    {
        return $arr;
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