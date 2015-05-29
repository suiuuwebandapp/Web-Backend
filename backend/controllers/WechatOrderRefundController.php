<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/28
 * Time: 下午5:31
 */

namespace backend\controllers;


use backend\components\TableResult;
use backend\services\WechatOrderRefundService;
use backend\components\Page;
use common\components\Code;
use Yii;

class WechatOrderRefundController  extends CController {

    private $wechatOrderRefundSer;


    public function __construct($id, $module = null)
    {
        $this->wechatOrderRefundSer=new WechatOrderRefundService();
        parent::__construct($id, $module);
    }

    public function actionList()
    {
        return $this->render('list');
    }

    public function actionGetList()
    {
        $page=new Page(Yii::$app->request);
        $page->sortName="status";
        $search=\Yii::$app->request->get("searchText","");
        $status=Yii::$app->request->get('status');
        $page = $this->wechatOrderRefundSer->getList($page,$search,$status);
        $tableResult=new TableResult($page->draw,count($page->getList()),$page->totalCount,$page->getList());
        echo json_encode($tableResult);
    }
    public function actionEdit()
    {
        $orderNumber=Yii::$app->request->get('o');
        $data = $this->wechatOrderRefundSer->getOrderInfo($orderNumber);
        return $this->render("edit",['info'=>$data]);
    }

    public function actionEditOrder()
    {
        $orderNumber=Yii::$app->request->post('orderNumber');
        $money=Yii::$app->request->post('money');
        $updateReason=Yii::$app->request->post('updateReason');
        $status=Yii::$app->request->post('status');

        if(empty($orderNumber)){return json_encode(Code::statusDataReturn(Code::FAIL,"未知订单号"));}
        if(empty($money)){return json_encode(Code::statusDataReturn(Code::FAIL,"未知金额"));}
        if(empty($status)){return json_encode(Code::statusDataReturn(Code::FAIL,"未知状态变更"));}
        if(empty($updateReason)){return json_encode(Code::statusDataReturn(Code::FAIL,"未知操作理由"));}
        $userSign = $this->userObj->userSign;
        if(empty($userSign)){return json_encode(Code::statusDataReturn(Code::FAIL,"未知用户"));}
        $this->wechatOrderRefundSer->sysUpdateInfo($orderNumber,$money,$userSign,$updateReason,$status);
        return json_encode(Code::statusDataReturn(Code::SUCCESS,"更新成功"));
    }
    public function actionDeleteRefund()
    {
        $orderNumber=Yii::$app->request->post('orderNumber');
        $this->wechatOrderRefundSer->deleteRefund($orderNumber);
        echo json_encode(Code::statusDataReturn(Code::SUCCESS,"删除成功"));
    }


}