<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/14
 * Time: 下午1:28
 */

namespace frontend\controllers;


use common\components\Code;
use common\entity\WeChatOrderList;
use frontend\components\Page;
use frontend\services\WeChatOrderListService;
use yii;
use yii\base\Exception;
class WeChatOrderListController extends WController {

    public $orderListSer;
    public $layout=false;
    public $enableCsrfValidation=false;
    public function __construct($id, $module = null)
    {
        $this->orderListSer = new WeChatOrderListService();
        parent::__construct($id, $module);
    }

    /**
     * 添加订购记录
     */
    public function actionAddOrder()
    {
        $this->loginValidJson(false);
        $site=Yii::$app->request->post('site');
        $content=Yii::$app->request->post('content');
        $timeList=Yii::$app->request->post('timeList');
        $userNumber=Yii::$app->request->post('userNumber');
        $userPhone=Yii::$app->request->post('phone');
        $userSign=$this->userObj->userSign;
        $openId=$this->userObj->openId;
        if(empty($openId))
        {
            return json_encode(Code::statusDataReturn(Code::FAIL, "无效的微信用户"));
        }
        if(empty($site))
        {
            return json_encode(Code::statusDataReturn(Code::FAIL, "订购地点不能为空"));
        }
        if(empty($content))
        {
            return json_encode(Code::statusDataReturn(Code::FAIL, "订购内容不能为空"));

        }
        if(empty($timeList))
        {
            return json_encode(Code::statusDataReturn(Code::FAIL, "订购时间不能为空"));
        }
        if(empty($userPhone))
        {
            return json_encode(Code::statusDataReturn(Code::FAIL, "联系电话不能为空"));
        }
        if(empty($userNumber)||$userNumber<1)
        {
            return json_encode(Code::statusDataReturn(Code::FAIL, "订购人数不能为空"));
        }
        $orderNumber=Code::createWxOrderNumber();
        $orderEntity=new WeChatOrderList();
        $orderEntity->wOrderSite=$site;
        $orderEntity->wOrderContent=$content;
        $orderEntity->wOrderTimeList=$timeList;
        $orderEntity->wUserSign=$userSign;
        $orderEntity->wOrderNumber=$orderNumber;
        $orderEntity->wUserNumber=$userNumber;
        $orderEntity->wPhone=$userPhone;
        $orderEntity->openId=$openId;
        $this->orderListSer->insertWeChatInfo($orderEntity);
        if(empty($userSign))
        {
            return json_encode(Code::statusDataReturn(Code::UN_LOGIN, '/we-chat-order-list/binding'));
        }
        return json_encode(Code::statusDataReturn(Code::SUCCESS, '/we-chat-order-list/order-success'));
    }


    //得到用户订购列表
    public function actionGetUserOrderList()
    {
        try {
            $this->loginValidJson();
            $userSign=$this->userObj->userSign;
            $page = new Page(Yii::$app->request);
            $data = $this->orderListSer->getOrderListByUserSign($userSign,$page);
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }

    //得到订购详情
    public function actionGetUserOrderInfo()
    {
        try {
            $this->loginValidJson();
            $id=Yii::$app->request->post('id');
            $userSign=$this->userObj->userSign;
            $data = $this->orderListSer->getOrderInfoById($id,$userSign);
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }

    public function actionIndex()
    {
        return $this->renderPartial('index');
    }

    public function actionOrderView()
    {
        $c=Yii::$app->request->get('c');
        $n=Yii::$app->request->get('n');
        if(empty($n))
        {
            $n='目的地城市';
        }
        return $this->renderPartial('orderView',['c'=>$c,'n'=>$n]);
    }
    public function actionOrderManage()
    {
        $this->loginValid(false);
        $userSign=$this->userObj->userSign;
        //$userSign="085963dc0af031709b032725e3ef18f5";
        if(empty($userSign))
        {
            return $this->renderPartial('noOrder');
        }
        $page = new Page(Yii::$app->request);
        $data = $this->orderListSer->getOrderListByUserSign($userSign,$page);
        if(empty($data->getList())){
            return $this->renderPartial('noOrder');
        }else{
            return $this->renderPartial('orderList',['list'=>$data->getList()]);
        }
    }

    public function actionDeleteOrder()
    {
        $this->loginValidJson();
        $orderNumber=Yii::$app->request->post('orderNumber');
        $userSign=$this->userObj->userSign;
        if(empty($userSign))
        {
            return json_encode(Code::statusDataReturn(Code::FAIL, "用户名不能为空"));
        }
        if(empty($orderNumber))
        {
            return json_encode(Code::statusDataReturn(Code::FAIL, "订单号不能为空"));
        }
        $data = $this->orderListSer->deleteOrder($orderNumber,$userSign);
        if($data==1)
        {
            return json_encode(Code::statusDataReturn(Code::SUCCESS, "删除成功"));
        }else{
            return json_encode(Code::statusDataReturn(Code::FAIL, "删除异常"));
        }
    }

    public function actionApplyRefund()
    {
        $this->loginValidJson();
        $userSign=$this->userObj->userSign;
        $orderNumber=Yii::$app->request->post('orderNumber');
        /*$userSign="085963dc0af031709b032725e3ef18f5";
        $orderNumber='wx2015052154555548';*/
        if(empty($userSign))
        {
            return json_encode(Code::statusDataReturn(Code::FAIL, "用户名不能为空"));
        }
        if(empty($orderNumber))
        {
            return json_encode(Code::statusDataReturn(Code::FAIL, "订单号不能为空"));
        }
        $orderInfo = $this->orderListSer->getOrderInfoByOrderNumber($orderNumber,$userSign);
        if(empty($orderInfo))
        {
            return json_encode(Code::statusDataReturn(Code::FAIL, "未找到该订单"));
        }
        if($orderInfo['wStatus']!=WeChatOrderList::STATUS_PAY_SUCCESS)
        {
            return json_encode(Code::statusDataReturn(Code::FAIL, "未支付订单或游玩过该订单"));
        }
        $data = $this->orderListSer->updateOrderStatus($orderNumber,WeChatOrderList::STATUS_APPLY_REFUND,$userSign);
        if($data==1)
        {
            return json_encode(Code::statusDataReturn(Code::SUCCESS, "成功"));
        }else{
            return json_encode(Code::statusDataReturn(Code::FAIL, "异常"));
        }
    }
    public function actionOrderSuccess()
    {
        return $this->renderPartial('orderSuccess',['str2'=>'返回微信','url'=>"javascript:WeixinJSBridge.call('closeWindow')"]);
    }
    public function actionBinding()
    {
        return $this->renderPartial('binding');
    }
    public function actionTest()
    {
    }
}