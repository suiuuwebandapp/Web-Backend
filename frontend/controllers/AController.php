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
use frontend\services\UserBaseService;
use yii\web\Controller;


class AController extends Controller{

    public $userObj=null;

    public $layout=false;

    public $userService=null;
    public $enableCsrfValidation=false;

    public function __construct($id, $module = null)
    {
        //验证用户是否登录
        $currentUser=\Yii::$app->session->get(Code::APP_USER_LOGIN_SESSION);
        $appSign=\Yii::$app->request->get(\Yii::$app->params['app_suiuu_sign']);

        $enPassword = \Yii::$app->params['encryptPassword'];
        $enDigit = \Yii::$app->params['encryptDigit'];

        if(!isset($currentUser)&&empty($appSign)) {
            echo json_encode(Code::statusDataReturn(Code::FAIL,'appSign不能为空'));
            exit;
        }else if(isset($currentUser)){
            $this->userObj=$currentUser;
        }else if(!empty($appSign)){
            $aes=new Aes();
            $userSign=$aes->decrypt($appSign,$enPassword,$enDigit);
            $this->userService=new UserBaseService();
            $currentUser=$this->userService->findUserByUserSign($userSign);
            if(isset($currentUser)){
                $this->userObj=$currentUser;
                \Yii::$app->session->set(Code::APP_USER_LOGIN_SESSION,$currentUser);
            }else{
                echo json_encode(Code::statusDataReturn(Code::FAIL,'错误appSign，无法获取用户'));
                exit;
            }

        }
        parent::__construct($id, $module);
    }
}