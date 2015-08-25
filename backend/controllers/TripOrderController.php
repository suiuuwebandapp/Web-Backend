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
use common\components\Code;
use common\components\LogUtils;
use common\entity\UserOrderInfo;
use common\entity\UserOrderRefundApply;
use yii\base\Exception;

class TripOrderController extends CController{


    private $userOrderService;


    public function __construct($id, $module = null)
    {
        $this->userOrderService=new UserOrderService();
        parent::__construct($id, $module);
    }


    /**
     * 跳转到订单列表页面
     * @return string
     */
    public function actionList()
    {
        return $this->render('orderList');
    }

    /**
     * 获取订单列表
     * @throws \Exception
     * @throws \yii\base\Exception
     */
    public function actionGetList()
    {
        $page=new Page(\Yii::$app->request);
        $page->sortName='createTime';
        $page->sortType='desc';
        $search=trim(\Yii::$app->request->get('searchText',''));
        $status=trim(\Yii::$app->request->get('status',''));
        $page=$this->userOrderService->getOrderList($page,$search,null,null,$status);
        $tableResult=new TableResult($page->draw,count($page->getList()),$page->totalCount,$page->getList());
        echo json_encode($tableResult);
    }


    /**
     * 跳转到订单详情
     * @return string
     * @throws \Exception
     * @throws \yii\base\Exception
     */
    public function actionInfo()
    {
        $orderId=\Yii::$app->request->get('orderId','');

        $orderInfo=$this->userOrderService->findOrderInfo($orderId);
        return $this->render('orderInfo',[
            'orderInfo'=>$orderInfo
        ]);
    }

    /**
     * 跳转到订单列表页面
     * @return string
     */
    public function actionRefundApplyList()
    {
        return $this->render('refundApplyList');
    }

    public function actionGetRefundApplyList()
    {
        $page=new Page(\Yii::$app->request);
        //$page->sortName='createTime';
        $search=trim(\Yii::$app->request->get('searchText',''));
        $status=trim(\Yii::$app->request->get('status',''));

        $page=$this->userOrderService->getOrderRefundApplyList($page,$search,$status);
        $tableResult=new TableResult($page->draw,count($page->getList()),$page->totalCount,$page->getList());
        echo json_encode($tableResult);
    }


    /**
     * 同意退款申请
     * @return string
     */
    public function actionAgreeRefundApply()
    {
        $refundApplyId=\Yii::$app->request->post('refundApplyId');
        try{
            $this->userOrderService->returnRefundApply($refundApplyId,UserOrderRefundApply::USER_ORDER_REFUND_APPLY_STATUS_SUCCESS,"");
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }

    /**
     * 拒绝退款申请
     * @return string
     */
    public function actionRefuseRefundApply()
    {
        $refundApplyId=\Yii::$app->request->post('refundApplyId');
        $content=\Yii::$app->request->post('content');

        try{
            $this->userOrderService->returnRefundApply($refundApplyId,UserOrderRefundApply::USER_ORDER_REFUND_APPLY_STATUS_FAIL,$content);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }


    /**
     * 跳转到确认退款页面
     * @return string
     * @throws Exception
     * @throws \Exception
     */
    public function actionToConfirmRefund()
    {
        $refundApplyId=\Yii::$app->request->get('refundApplyId');
        $refundApplyInfo=$this->userOrderService->findRefundApplyInfo($refundApplyId);
        return $this->render("confirmRefund",[
            'refundApplyInfo'=>$refundApplyInfo
        ]);

    }

    /**
     * 确认退款
     * @return string
     */
    public function actionConfirmRefund()
    {
        $refundApplyId=\Yii::$app->request->post('refundApplyId');
        $money=\Yii::$app->request->post('money');
        $accountInfo=\Yii::$app->request->post('accountInfo');
        $refundNumber=\Yii::$app->request->post('refundNumber');
        $content=\Yii::$app->request->post('content');

        try{
            $this->userOrderService->confirmRefund($refundApplyId,$accountInfo,$refundNumber,$money,$content);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }


    /**
     * 跳转到退款详情
     * @return string
     * @throws Exception
     * @throws \Exception
     */
    public function actionToRefundInfo()
    {
        $refundApplyId=\Yii::$app->request->get('refundApplyId');
        $refundInfo=$this->userOrderService->findRefundInfo($refundApplyId);
        return $this->render("refundInfo",[
            'refundInfo'=>$refundInfo
        ]);

    }



}