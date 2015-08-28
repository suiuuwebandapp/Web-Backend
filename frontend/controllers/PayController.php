<?php

namespace frontend\controllers;

use common\components\Code;
use common\entity\UserOrderInfo;
use common\entity\UserPayRecord;
use common\pay\alipay\create\AlipayCreateApi;
use common\pay\alipay\send\AlipaySendApi;
use common\pay\alipaywap\create\AlipaywapCreateApi;
use common\pay\wxpay\NativeDynamicQrcode;
use frontend\services\UserOrderService;
use frontend\services\UserPayService;
use yii\base\Exception;

/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/13
 * Time : 下午5:13
 * Email: zhangxinmailvip@foxmail.com
 */

class PayController extends CController{


    /**
     * 跳转到不同的支付页面
     * @return \yii\web\Response
     * @throws Exception
     * @throws \Exception
     */
    public function actionIndex()
    {

        $number=\Yii::$app->request->get("number");
        $payType=\Yii::$app->request->get("type");

        if(empty($number)){
            return $this->redirect(['/result', 'result' => '无效的订单号']);
        }
        $alipayCreateApi=new AlipayCreateApi();

        $orderService=new UserOrderService();
        $orderInfo=$orderService->findOrderByOrderNumber(trim($number));
        $contact=$orderService->getOrderContactByOrderId($orderInfo->orderId);

        if(empty($orderInfo)){
            return $this->redirect(['/result', 'result' => '无效的订单号']);
        }
        if($orderInfo->userId!=$this->userObj->userSign){
            return $this->redirect(['/result', 'result' => '订单用户不匹配']);
        }
        if($contact==null){
            return $this->redirect(['/result', 'result' => '无效的订单联系方式']);
        }


        if($payType==UserPayRecord::PAY_RECORD_TYPE_ALIPAY){
            $alipayCreateApi->createOrder($orderInfo,$this->userObj);
        }elseif($payType==UserPayRecord::PAY_RECORD_TYPE_WXPAY){
            $wxpay = new NativeDynamicQrcode();
            $rst = $wxpay->createCode($orderInfo);
            return json_encode($rst);
        }else{
            return $this->redirect(['/result', 'result' => '无效的支付方式']);
        }

    }


    /**
     * 查询订单支付状态
     */
    public function actionStatus(){
        $number=\Yii::$app->request->get("number");
        if(empty($number)){
            echo json_encode(Code::statusDataReturn(Code::FAIL));
        }else{
            $status=\Yii::$app->redis->get(Code::USER_ORDER_PAY_STATS.$number);
            if($status==1){
                echo json_encode(Code::statusDataReturn(Code::SUCCESS));
            }else{
                echo json_encode(Code::statusDataReturn(Code::FAIL));
            }
        }
    }


    public function actionTest()
    {

        //$out_trade_no="2015051499575755";
        //$trade_no="2015051400001000880050810772";
        //$userPayService=new UserPayService();
        //$rst=$userPayService->addUserPay($out_trade_no,$trade_no,UserPayRecord::PAY_RECORD_TYPE_ALIPAY,UserOrderInfo::USER_ORDER_STATUS_PAY_SUCCESS);
    }


    public function actionSend()
    {
        //$sendApi=new AlipaySendApi();
        //$sendApi->alipaySend("2015051400001000880050824241");
    }


    public function actionConfirm()
    {
        //$userOrderService=new UserOrderService();
        //如果用户确认收货，是不是自动改变订单状态为已完成
        //$userOrderService->changeOrderStatus('2015051499575757',UserOrderInfo::USER_ORDER_STATUS_PLAY_SUCCESS);
    }



}