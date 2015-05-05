<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/4
 * Time : 下午7:17
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\controllers;


use frontend\services\UserBaseService;

class UserInfoController extends CController{

    public function __construct($id, $module = null)
    {
        $this->userBaseService = new UserBaseService();
        parent::__construct($id, $module);
    }


    public function actionIndex()
    {
        return $this->render("info");
    }

}