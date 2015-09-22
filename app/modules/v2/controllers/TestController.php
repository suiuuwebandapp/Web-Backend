<?php
namespace app\modules\v2\controllers;

use frontend\components\Page;
use frontend\services\UserMessageRemindService;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\rest\ActiveController;

class TestController extends ActiveController {

    public $modelClass = '';
    public function actions()
    {
        $actions = parent::actions();
        // 注销系统自带的实现方法
        unset($actions['index'], $actions['update'], $actions['create'], $actions['delete'], $actions['view']);
        return $actions;
    }

    public function actionIndex()
    {
        return array('Index');
        exit;
    }
    public function actionCreate()
    {
        return array('type'=>"Create");
    }

    public function actionUpdate($id)
    {
        return array('Update'=>$id);
    }

    public function actionDelete($id)
    {
        return array('Delete'=>$id);
    }

    public function actionView($id)
    {
        return array('View'=>$id);
        exit;
    }

    public function actionTest()
    {
        $id = \Yii::$app->request->get("id");
        return array('test'=>$id);
        exit;
    }

    public function actionTest2()
    {
        return "sss";
    }
}