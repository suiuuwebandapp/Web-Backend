<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/10
 * Time : 上午9:54
 * Email: zhangxinmailvip@foxmail.com
 */

namespace backend\controllers;


class SysUserController extends CController{

    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
    }


    public function actionList()
    {
        echo 'this is list';
    }
}