<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/14
 * Time: 下午1:58
 */

namespace frontend\controllers;


use common\components\Code;
use common\entity\UserBase;
use frontend\services\UserBaseService;
use yii\web\Controller;
use yii;

class WController extends SController {

    public $userObj=null;
    public $userPublisherObj=null;
    public $userBaseService=null;

    public $layout=false;
    public $enableCsrfValidation=false;

    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
    }
    public function loginValid($bd=true,$bo=true)
    {

            if ($bo) {
                //验证用户是否登录
                $session=\Yii::$app->session->get(Yii::$app->params['weChatSign']);
                if(!empty($session))
                {
                    $currentUser=json_decode($session);
                }
                 if (isset($currentUser)) {
                     if($bd)
                     {
                        if ($currentUser->status != UserBase::USER_STATUS_NORMAL) {
                            return $this->redirect('/we-chat/binding-main');
                        }
                    }
                     $this->userObj = $currentUser;
                } else {
                     return $this->redirect('/we-chat/error?str="登陆已经过期请重新打开"');

                }

            }else {
                $currentUser=\Yii::$app->session->get(Yii::$app->params['weChatSign']);
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

    }
    public function loginValidJson($bd=true,$bo=true)
    {
        if ($bo) {
            //验证用户是否登录
            $session=\Yii::$app->session->get(Yii::$app->params['weChatSign']);
            if(!empty($session))
            {
                $currentUser=json_decode($session);
            }
            if (isset($currentUser)) {
                if($bd)
                {
                    if ($currentUser->status != UserBase::USER_STATUS_NORMAL) {
                        echo json_encode(Code::statusDataReturn(Code::UN_LOGIN, '/we-chat/binding-main'));
                        exit;
                    }
                }
                $this->userObj = $currentUser;
            } else {
                echo json_encode(Code::statusDataReturn(Code::FAIL, '无效的用户'));
                exit;
            }
        }else {
            $currentUser=\Yii::$app->session->get(Yii::$app->params['weChatSign']);
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

    }
    public function appRefreshUserInfo()
    {
        $this->userBaseService=new UserBaseService();
        $currentUser=$this->userBaseService->findUserByUserSign($this->userObj->userSign);
        $this->userObj=$currentUser;
        \Yii::$app->session->set(Yii::$app->params['weChatSign'],$currentUser);
    }
}