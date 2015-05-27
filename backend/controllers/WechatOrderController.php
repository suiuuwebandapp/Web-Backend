<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/27
 * Time: 下午3:00
 */

namespace backend\controllers;


use backend\components\Page;
use backend\components\TableResult;
use backend\services\WechatOrderService;
use Yii;

class WechatOrderController extends CController {


    private $wechatOrderSer;


    public function __construct($id, $module = null)
    {
        $this->wechatOrderSer=new WechatOrderService();
        parent::__construct($id, $module);
    }

    public function actionList()
    {
        return $this->render('list');
    }

    public function actionGetList()
    {
        $page=new Page(Yii::$app->request);
        $searchName=Yii::$app->request->post('name');
        $status=Yii::$app->request->post('status');
        $isDel=Yii::$app->request->post('del');
        $phone=Yii::$app->request->post('phone');
        $page = $this->wechatOrderSer->getList($page,$searchName,$status,$isDel,$phone);
        $tableResult=new TableResult($page->draw,count($page->getList()),$page->totalCount,$page->getList());
        echo json_encode($tableResult);
    }
}