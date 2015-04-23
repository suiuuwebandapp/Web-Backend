<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/23
 * Time : 下午2:11
 * Email: zhangxinmailvip@foxmail.com
 */

namespace backend\controllers;


use common\components\Code;

class AppLoginController {


    public function actionTencentLogin()
    {
        $openId=\Yii::$app->request->post("openId");
        $nickname=\Yii::$app->request->post("nickname");
        $sex=\Yii::$app->request->post("sex");
        $headImg=\Yii::$app->request->post("headImg");
        $sign=\Yii::$app->request->post("sign");

        if(empty($openId)){
            return Code::statusDataReturn(Code::PARAMS_ERROR,"OpenId Is Not Allow Empty");
        }
        if(empty($sign)){
            return Code::statusDataReturn(Code::PARAMS_ERROR,"Sign Is Not Allow Empty");
        }



    }


    public function actionSinaLogin()
    {

    }


    public function actionWechatLogin()
    {

    }



}