<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/6
 * Time : 下午10:36
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\controllers;


use yii\web\Controller;

class StaticController extends UnCController{


    public function __construct($id,$module)
    {
        parent::__construct($id, $module);
    }

    public function actionIndex(){
        return $this->render("index");
    }

    public function actionProduct()
    {
        return $this->renderPartial('product');
    }
}