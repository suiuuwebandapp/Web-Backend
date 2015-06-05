<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/4/21
 * Time: 下午2:19
 */
namespace frontend\controllers;


use common\components\Code;
use common\components\LogUtils;
use common\components\ValidateCode;
use frontend\services\UserBaseService;
use yii\base\Exception;
use yii\rest\Controller;

class LoginController extends Controller
{
    private $userBaseService;
    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        $this->userBaseService=new UserBaseService();
    }


    /**
     * 安全退出
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        \Yii::$app->session->remove(Code::USER_LOGIN_SESSION);
        \Yii::$app->response->cookies->remove(\Yii::$app->params['suiuu_sign']);
        return $this->redirect("/");
    }

}