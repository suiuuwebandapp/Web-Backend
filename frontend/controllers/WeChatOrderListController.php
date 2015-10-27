<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/14
 * Time: 下午1:28
 */

namespace frontend\controllers;


use backend\services\WechatService;
use common\components\Aes;
use common\components\Code;
use common\entity\WeChatOrderList;
use common\entity\WeChatOrderRefund;
use common\entity\WeChatUserInfo;
use common\pay\alipay\create\AlipayCreateApi;
use frontend\components\Page;
use frontend\services\WeChatOrderListService;
use frontend\services\WeChatOrderRefundService;
use yii;
use yii\base\Exception;
class WeChatOrderListController extends WController {

    public $orderListSer;
    public $layout="wechat";
    public $enableCsrfValidation=true;
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
        $this->loginValidJson();
        $site=Yii::$app->request->post('site');
        $content=Yii::$app->request->post('content');
        $timeList=Yii::$app->request->post('timeList');
        $userNumber=Yii::$app->request->post('userNumber');
        $userPhone=Yii::$app->request->post('phone');
        $userName=Yii::$app->request->post('userName');
        $userSign=$this->userObj->userSign;
        if(empty($userSign))
        {
            return json_encode(Code::statusDataReturn(Code::FAIL, "无效的用户"));
        }
        if(empty($userName))
        {
            return json_encode(Code::statusDataReturn(Code::FAIL, "用户姓名不能为空"));
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
        $wechatSer = new WechatService();
        $userInfo=new WeChatUserInfo();
        $userInfo->userSign=$userSign;
        $wechatUserInfo=$wechatSer->getUserInfo($userInfo);
        $openId="";
        if(isset($wechatUserInfo['openId'])){
            $openId=$wechatUserInfo['openId'];
        }
        $arr=explode(",",$timeList);
        sort($arr);
        $timeList = join(",",$arr);
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
        $orderEntity->wUserName=$userName;
        $this->orderListSer->insertWeChatInfo($orderEntity);

        return json_encode(Code::statusDataReturn(Code::SUCCESS, '/we-chat-order-list/order-success'));
    }

    /**
     * 修改订购记录
     */
    public function actionUpdateOrder()
    {
        $this->loginValidJson();
        $orderId = Yii::$app->request->post('orderId');
        $site=Yii::$app->request->post('site');
        $content=Yii::$app->request->post('content');
        $timeList=Yii::$app->request->post('timeList');
        $userNumber=Yii::$app->request->post('userNumber');
        $userPhone=Yii::$app->request->post('phone');
        $userName=Yii::$app->request->post('userName');
        $userSign=$this->userObj->userSign;
        if(empty($orderId))
        {
            return json_encode(Code::statusDataReturn(Code::FAIL, "无效的订购订单"));
        }
        if(empty($userName))
        {
            return json_encode(Code::statusDataReturn(Code::FAIL, "用户姓名不能为空"));
        }
        if(empty($userSign))
        {
            return json_encode(Code::statusDataReturn(Code::FAIL, "无效的用户"));
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
        $orderEntity=new WeChatOrderList();
        $orderEntity->wOrderSite=$site;
        $orderEntity->wOrderContent=$content;
        $orderEntity->wOrderTimeList=$timeList;
        $orderEntity->wUserNumber=$userNumber;
        $orderEntity->wPhone=$userPhone;
        $orderEntity->wOrderId=$orderId;
        $orderEntity->wUserSign=$userSign;
        $orderEntity->wUserName=$userName;
        $this->orderListSer->updateOrderInfo($orderEntity);
        return json_encode(Code::statusDataReturn(Code::SUCCESS, '/we-chat-order-list/order-success'));
    }
    /*//得到用户订购列表
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
    }*/

    public function actionSysShowOrder()
    {
        try {

            //待定 左栏
            $this->loginValid();
            $password=Yii::$app->request->get('password');
            if($password!="9527suiuu")
            {
                return $this->redirect('/we-chat/error?str=无效订单');
            }
            $id=Yii::$app->request->get('id');
            $data = $this->orderListSer->sysOrderInfo($id);
            if(empty($data)){
                return $this->redirect('/we-chat/error?str=无效订单');
            }else {
                return $this->renderPartial('sysOrderInfo', ['val' => $data,'userObj'=>$this->userObj]);
            }
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }
    public function actionIndex()
    {
        $this->activeIndex=2;
        $this->loginValid();
        return $this->renderPartial('index',['userObj'=>$this->userObj]);
    }

    public function actionOrderView()
    {
        $login = $this->loginValid();
        if(!$login){
            return $this->redirect(['/we-chat/login']);
        }
        $c=Yii::$app->request->get('c');
        $n=Yii::$app->request->get('n');
        if(empty($n))
        {
            $n='目的地城市';
        }
        $this->activeIndex=2;
        return $this->render('orderView',['c'=>$c,'n'=>$n,'userObj'=>$this->userObj]);
    }

    public function actionEditOrder()
    {
        $this->activeIndex=2;
        $login = $this->loginValid();
        if(!$login){
            return $this->redirect(['/we-chat/login']);
        }
        $userSign=$this->userObj->userSign;
        $orderNumber=Yii::$app->request->get("orderNumber");
        $data = $this->orderListSer->getOrderInfoByOrderNumber($orderNumber,$userSign);
        if(empty($data)){
            return $this->redirect('/we-chat/error?str=订单用户不匹配');
        }
        if($data['wStatus']!=WeChatOrderList::STATUS_NORMAL){
            return $this->redirect('/we-chat/error?str=已处理无法修改');
        }
        return $this->render('editOrder',['info'=>$data,'userObj'=>$this->userObj]);
    }

    public function actionOrderManage()
    {
        $this->activeIndex=2;
        $this->bgWhite=true;
        $login = $this->loginValid();
        if(!$login){
            return $this->redirect(['/we-chat/login']);
        }
        $userSign=$this->userObj->userSign;
        if(empty($userSign))
        {
            return $this->render('noOrder',['userObj'=>$this->userObj]);
        }
        $page = new Page(Yii::$app->request);
        $data = $this->orderListSer->getOrderListByUserSign(  $userSign,$page);
        if(empty($data->getList())){
            return $this->render('noOrder',['userObj'=>$this->userObj]);
        }else{
            $this->bgWhite=false;
            return $this->render('orderList',['list'=>$data->getList(),'userObj'=>$this->userObj]);
        }
    }

    public function actionOrderInfo()
    {
        $this->bgWhite=true;
        $this->activeIndex=2;
        $login = $this->loginValid();
        if(!$login){
            return $this->redirect(['/we-chat/login']);
        }
        $userSign=$this->userObj->userSign;
        $orderNumber=Yii::$app->request->get("orderNumber");
        $data = $this->orderListSer->getWeChatOrderListByOrderNumber($orderNumber,$userSign);
        if(empty($data)){
            return $this->redirect('/we-chat/error?str=订单用户不匹配');
        }
        return $this->render('orderInfo',['info'=>$data,'userObj'=>$this->userObj]);
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
        $info = $this->orderListSer->getOrderInfoByOrderNumber($orderNumber,$userSign);
        if(empty($info))
        {
            return json_encode(Code::statusDataReturn(Code::FAIL, "订单用户不匹配"));
        }
        if($info['wStatus']==WeChatOrderList::STATUS_NORMAL||$info['wStatus']==WeChatOrderList::STATUS_PROCESSED
            ||$info['wStatus']==WeChatOrderList::STATUS_PAY_SUCCESS||$info['wStatus']==WeChatOrderList::STATUS_APPLY_REFUND){
            return json_encode(Code::statusDataReturn(Code::FAIL, "订单状态不可删除"));
        }
        $data = $this->orderListSer->deleteOrder($orderNumber,$userSign);
        if($data==1)
        {
            return json_encode(Code::statusDataReturn(Code::SUCCESS, "删除成功"));
        }else{
            return json_encode(Code::statusDataReturn(Code::FAIL, "删除异常"));
        }
    }

    public function actionOverOrder()
    {

        $this->loginValidJson();
        $userSign=$this->userObj->userSign;
        $orderNumber=Yii::$app->request->post('o');
        $info = $this->orderListSer->getOrderInfoByOrderNumber($orderNumber,$userSign);
        if(empty($info))
        {
            return json_encode(Code::statusDataReturn(Code::FAIL, "订单用户不匹配"));
        }
        if($info['wStatus']!=WeChatOrderList::STATUS_PAY_SUCCESS){
            return json_encode(Code::statusDataReturn(Code::FAIL, "订单状态不可确认"));
        }

        $data = $this->orderListSer->updateOrderStatus($orderNumber,WeChatOrderList::STATUS_END,$userSign);
        if($data==1)
        {
            return json_encode(Code::statusDataReturn(Code::SUCCESS, "确认成功"));
        }else{
            return json_encode(Code::statusDataReturn(Code::FAIL, "确认异常"));
        }
    }
    public function actionCancelOrder()
    {

        $this->loginValidJson();
        $userSign=$this->userObj->userSign;
        $orderNumber=Yii::$app->request->post('o');
        $info = $this->orderListSer->getOrderInfoByOrderNumber($orderNumber,$userSign);
        if(empty($info))
        {
            return json_encode(Code::statusDataReturn(Code::FAIL, "订单用户不匹配"));
        }
        if($info['wStatus']!=WeChatOrderList::STATUS_NORMAL&&$info['wStatus']!=WeChatOrderList::STATUS_PROCESSED){
            return json_encode(Code::statusDataReturn(Code::FAIL, "订单状态不可取消"));
        }

        $data = $this->orderListSer->updateOrderStatus($orderNumber,WeChatOrderList::STATUS_CANCEL,$userSign);
        if($data==1)
        {
            return json_encode(Code::statusDataReturn(Code::SUCCESS, "取消成功"));
        }else{
            return json_encode(Code::statusDataReturn(Code::FAIL, "取消异常"));
        }
    }
    public function actionApplyRefund()
    {
        $this->loginValidJson();
        $userSign=$this->userObj->userSign;
        $orderNumber=Yii::$app->request->post('orderNumber');
        $refundReason=Yii::$app->request->post('refundReason');
        $phone=Yii::$app->request->post('phone');

       /* $userSign="085963dc0af031709b032725e3ef18f5";
        $orderNumber='wx2015052152515398';
        $refundReason="asd";
        $phone="132";*/
        if(empty($userSign))
        {
            return json_encode(Code::statusDataReturn(Code::FAIL, "用户名不能为空"));
        }
        if(empty($orderNumber))
        {
            return json_encode(Code::statusDataReturn(Code::FAIL, "订单号不能为空"));
        }
         if(empty($refundReason))
         {
             return json_encode(Code::statusDataReturn(Code::FAIL, "退款理由不能为空"));
         }
        if(empty($phone))
        {
            return json_encode(Code::statusDataReturn(Code::FAIL, "确认手机不能为空"));
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
        if($orderInfo['wPhone']!=$phone)
        {
            return json_encode(Code::statusDataReturn(Code::FAIL, "手机号不是该订单手机号"));
        }
        $orderRefundSer = new WeChatOrderRefundService();
        $rfInfo=$orderRefundSer->findRefundInfoByOrderNumber($orderNumber);
        if(empty($rfInfo)||$rfInfo==false)
        {}else
        {
            return json_encode(Code::statusDataReturn(Code::FAIL, "该订单已申请退款"));
        }
        $refundEntity=new WeChatOrderRefund();
        $refundEntity->userSign=$userSign;
        $refundEntity->orderNumber=$orderNumber;
        $refundEntity->refundReason=$refundReason;
        $rst = $orderRefundSer->insertWeChatInfo($refundEntity);
        if($rst !=1)
        {
            return json_encode(Code::statusDataReturn(Code::FAIL, "异常"));
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
        $this->bgWhite=true;
        $this->activeIndex=2;
        $this->loginValid();
        return $this->render('orderSuccess',['userObj'=>$this->userObj]);
    }
    public function actionRefundSuccess()
    {
        $this->bgWhite=true;
        $this->activeIndex=2;
        $this->loginValid();
        return $this->render('refundSuccess',['userObj'=>$this->userObj]);
    }
    public function actionBinding()
    {
        $this->activeIndex=2;
        $this->loginValid();
        return $this->render('binding',['userObj'=>$this->userObj]);
    }

    public function actionShowOrder()
    {
        $this->activeIndex=2;
        $this->loginValid();
        $a=Yii::$app->request->get('o');
        $v=Aes::decrypt($a,"suiuu9527",128);
        $data = $this->orderListSer->getWeChatOrderListByOrderNumber($v);
        return $this->render('orderInfo',['info'=>$data,'userObj'=>$this->userObj]);
    }


    public function actionShowRefund()
    {
        $this->activeIndex=2;
        $this->loginValid();
        $orderNumber=Yii::$app->request->get('o');
        return $this->render('applyRefund',['orderNumber'=>$orderNumber,'userObj'=>$this->userObj]);
    }

    public function actionTest()
    {
    }
    private function is_weixin()
    {

        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return true;
        }
        return false;
    }
}