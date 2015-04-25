<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/25
 * Time : 下午2:00
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\controllers;


use frontend\services\UserBaseService;

class ResultController extends UnCController{


    private $userBaseService=null;

    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        $this->userBaseService=new UserBaseService();
    }


    public function actionIndex()
    {
        $result=\Yii::$app->request->get("result");

        return $this->render("result",[
           'result'=>$result
        ]);
    }


}