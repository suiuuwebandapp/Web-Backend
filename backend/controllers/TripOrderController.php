<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/6/16
 * Time : 下午4:43
 * Email: zhangxinmailvip@foxmail.com
 */

namespace backend\controllers;



use backend\components\Page;
use backend\components\TableResult;
use backend\services\UserOrderService;

class TripOrderController extends CController{


    private $userOrderService;


    public function __construct($id, $module = null)
    {
        $this->userOrderService=new UserOrderService();
        parent::__construct($id, $module);
    }


    public function actionList()
    {
        return $this->render('orderList');
    }

    public function actionGetList()
    {
        $page=new Page(\Yii::$app->request);
        $page->sortName='createTime';
        $search=\Yii::$app->request->get('searchText','');
        $status=\Yii::$app->request->get('status','');
        $page=$this->userOrderService->getOrderList($page,$search,null,null,$status);
        $tableResult=new TableResult($page->draw,count($page->getList()),$page->totalCount,$page->getList());
        echo json_encode($tableResult);
    }


    public function actionInfo()
    {
        $orderId=\Yii::$app->request->get('orderId','');
        $orderInfo=$this->userOrderService->getOrderInfo($orderId);

        return $this->render('orderInfo',[
            'orderInfo'=>$orderInfo
        ]);
    }

}