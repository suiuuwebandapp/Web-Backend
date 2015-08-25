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
        $this->loginValidJson();
        $site=Yii::$app->request->post('site');
        $content=Yii::$app->request->post('content');
        $timeList=Yii::$app->request->post('timeList');
        $userNumber=Yii::$app->request->post('userNumber');
        $userPhone=Yii::$app->request->post('phone');
        $userSign=$this->userObj->userSign;
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
        $wechatSer = new WechatService();
        $userInfo=new WeChatUserInfo();
        $userInfo->userSign=$userSign;
        $wechatUserInfo=$wechatSer->getUserInfo($userInfo);
        $openId="";
        if(isset($wechatUserInfo['openId'])){
            $openId=$wechatUserInfo['openId'];
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

        return json_encode(Code::statusDataReturn(Code::SUCCESS, '/we-chat-order-list/order-success'));
    }

    /**
     * 修改订购记录
     */
    public function actionUpdateOrder()
    {
        $this->loginValidJson();
        $site=Yii::$app->request->post('site');
        $content=Yii::$app->request->post('content');
        $timeList=Yii::$app->request->post('timeList');
        $userNumber=Yii::$app->request->post('userNumber');
        $userPhone=Yii::$app->request->post('phone');
        $userSign=$this->userObj->userSign;
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
        $userInfo=new WeChatUserInfo();
        $userInfo->userSign=$userSign;
        $orderEntity=new WeChatOrderList();
        $orderEntity->wOrderSite=$site;
        $orderEntity->wOrderContent=$content;
        $orderEntity->wOrderTimeList=$timeList;
        $orderEntity->wUserNumber=$userNumber;
        $orderEntity->wPhone=$userPhone;
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
                return $this->renderPartial('sysOrderInfo', ['val' => $data]);
            }
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
        return $this->renderPartial('orderView',['c'=>$c,'n'=>$n,'userObj'=>$this->userObj,'active'=>2,'newMsg'=>0]);
    }

    public function actionEditOrder()
    {
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
        return $this->renderPartial('editOrder',['info'=>$data,'userObj'=>$this->userObj,'active'=>2,'newMsg'=>0]);
    }

    public function actionOrderManage()
    {
        $login = $this->loginValid();
        if(!$login){
            return $this->redirect(['/we-chat/login']);
        }
        $userSign=$this->userObj->userSign;
        if(empty($userSign))
        {
            return $this->renderPartial('noOrder',['userObj'=>$this->userObj,'active'=>2,'newMsg'=>0]);
        }
        $page = new Page(Yii::$app->request);
        $data = $this->orderListSer->getOrderListByUserSign(  $userSign,$page);
        if(empty($data->getList())){
            return $this->renderPartial('noOrder',['userObj'=>$this->userObj,'active'=>2,'newMsg'=>0]);
        }else{
            return $this->renderPartial('orderList',['list'=>$data->getList(),'userObj'=>$this->userObj,'active'=>2,'newMsg'=>0]);
        }
    }

    public function actionOrderInfo()
    {
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
        return $this->renderPartial('orderInfo',['info'=>$data,'userObj'=>$this->userObj,'active'=>2,'newMsg'=>0]);
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

    public function actionOverOrder()
    {

        $this->loginValidJson();
        $userSign=$this->userObj->userSign;
        $orderNumber=Yii::$app->request->post('o');
        $data = $this->orderListSer->updateOrderStatus($orderNumber,WeChatOrderList::STATUS_END,$userSign);
        if($data==1)
        {
            return json_encode(Code::statusDataReturn(Code::SUCCESS, "确认成功"));
        }else{
            return json_encode(Code::statusDataReturn(Code::FAIL, "确认异常"));
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
        return $this->renderPartial('orderSuccess',['str2'=>'返回','url'=>"javascript:history.go(-1);"]);
    }
    public function actionRefundSuccess()
    {
        return $this->renderPartial('refundSuccess',['str2'=>'返回','url'=>"javascript:history.go(-1);"]);
    }
    public function actionBinding()
    {
        return $this->renderPartial('binding');
    }

    public function actionShowOrder()
    {
        $a=Yii::$app->request->get('o');
        $v=Aes::decrypt($a,"suiuu9527",128);
        $data = $this->orderListSer->getWeChatOrderListByOrderNumber($v);
        return $this->renderPartial('orderInfo',['info'=>$data]);
    }


    public function actionShowRefund()
    {
        $orderNumber=Yii::$app->request->get('o');
        return $this->renderPartial('applyRefund',['orderNumber'=>$orderNumber]);
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