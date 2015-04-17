<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/17
 * Time : 上午11:01
 * Email: zhangxinmailvip@foxmail.com
 */

namespace backend\controllers;


class DestinationController extends  CController{



    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
    }


    public function actionList()
    {
        echo "list";
    }


    public function actionAdd()
    {
        return $this->render("add");
    }
}