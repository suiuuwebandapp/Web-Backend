<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/6/1
 * Time: 上午10:40
 */

namespace backend\controllers;


use backend\components\Page;
use backend\components\TableResult;
use backend\services\WechatOrderPayService;
use common\components\Code;
use Yii;

class WechatOrderPayController extends CController {

    private $wechatOrderPaySer;


    public function __construct($id, $module = null)
    {
        $this->wechatOrderPaySer=new WechatOrderPayService();
        parent::__construct($id, $module);
    }

    public function actionList()
    {
        return $this->render('list');
    }

    public function actionGetList()
    {
        $page=new Page(Yii::$app->request);
        //$page->sortName="status";
        $search=\Yii::$app->request->get("searchText","");
        $type=Yii::$app->request->get('type');
        $page = $this->wechatOrderPaySer->getList($page,$search,$type);
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