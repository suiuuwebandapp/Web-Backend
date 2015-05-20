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
    public function loginValid($db=true,$bo=true)
    {

            if ($bo) {
                //验证用户是否登录
                $session=\Yii::$app->session->get(Yii::$app->params['weChatSign']);
                if(!empty($session))
                {
                    $currentUser=json_decode($session);
                }
                 if (isset($currentUser)) {
                    if ($currentUser->status != UserBase::USER_STATUS_NORMAL) {
                        echo json_encode(Code::statusDataReturn(Code::FAIL, "用户已经被删除"));
                        exit;
                    } else {
                        if($db)
                        {
                            if(empty($this->userObj->userSign)){
                              return $this->redirect('/we-chat/binding');
                            }
                        }
                        $this->userObj = $currentUser;

                    }
                } else {
                    echo json_encode(Code::statusDataReturn(Code::UN_LOGIN, '登陆已过期请重新登陆'));
                    exit;
                }
                if($currentUser->isPublisher){
                    if($this->userBaseService==null)$this->userBaseService=new UserBaseService();
                    $userPublisherObj=$this->userBaseService->findUserPublisherByUserSign($this->userObj->userSign);
                    $this->userPublisherObj=$userPublisherObj;
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