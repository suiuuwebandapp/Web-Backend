<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/24
 * Time : 下午2:31
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\controllers;


use yii\web\Controller;

class ErrorController extends Controller{

    public function actionIndex()
    {
        echo "error";
    }


    public function actionAccessError()
    {
        echo "接入异常";
    }
}