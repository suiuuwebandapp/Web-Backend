<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/19
 * Time: 下午5:14
 */
namespace common\pay\wxpay;

use common\components\Aes;
use common\components\Code;
use common\entity\UserOrderInfo;
use common\entity\WeChatOrderList;
use common\entity\WeChatUserInfo;
use frontend\services\WeChatOrderListService;
use frontend\services\WeChatService;

class JsApiCall {

    public function __construct()
    {

    }

    public function createCode($orderNumber,$type)
    {
        /**
         * JS_API支付demo
         * ====================================================
         * 在微信浏览器里面打开H5网页中执行JS调起支付。接口输入输出数据格式为JSON。
         * 成功调起支付需要三个步骤：
         * 步骤1：网页授权获取用户openid
         * 步骤2：使用统一支付接口，获取prepay_id
         * 步骤3：使用jsapi调起支付
         */
        include_once("WxPayPubHelper/WxPayPubHelper.php");

//使用jsapi接口
        $jsApi = new \JsApi_pub();

//=========步骤1：网页授权获取用户openid============
//通过code获得openid
        if (!isset($_GET['code']))
        {
            //触发微信返回code码
            $url = $jsApi->createOauthUrlForCode(\WxPayConf_pub::JS_API_CALL_URL."?n=".$orderNumber);
            header("Location: $url");
            exit;
        }else
        {
            //获取code码，以获取openid
            $code = $_GET['code'];
            $jsApi->setCode($code);
            $openid = $jsApi->getOpenId();

        }

        $wxSer=new WeChatService();
        $userInfo=new WeChatUserInfo();
        $userInfo->openId=$openid;
        if(empty($openid))
        {
            return Code::statusDataReturn(Code::FAIL,"无法获取openId");
        }
        $user = $wxSer->getUserInfo($userInfo);
        if(empty($user))
        {
            return Code::statusDataReturn(Code::FAIL,"无效用户");
        }

        if(empty($type))
        {
            return Code::statusDataReturn(Code::FAIL,"无效支付类型");
        }
        if($type==1){
            $orderSer=new WeChatOrderListService();
            $orderInfo =$orderSer->getOrderInfoByOrderNumber($orderNumber,$user['userSign']);
            if(empty($orderInfo))
            {
                return Code::statusDataReturn(Code::FAIL,"订单不存在");
            }
            if($orderInfo['wStatus']!=WeChatOrderList::STATUS_PROCESSED)
            {
                return Code::statusDataReturn(Code::FAIL,"订单状态不为待支付");
            }
        }else
        {
            return Code::statusDataReturn(Code::FAIL,"暂无该类型支付");
        }

//=========步骤2：使用统一支付接口，获取prepay_id============
//使用统一支付接口
        $unifiedOrder = new \UnifiedOrder_pub();
//设置统一支付接口参数
//设置必填参数
//appid已填,商户无需重复填写
//mch_id已填,商户无需重复填写
//noncestr已填,商户无需重复填写
//spbill_create_ip已填,商户无需重复填写
//sign已填,商户无需重复填写

        //自定义订单号，此处仅作举例
        $out_trade_no = $orderInfo['wOrderNumber'];
        $money=$orderInfo['wMoney']*100;
        $body=$orderInfo['wOrderSite']."定制旅行";
        if(empty($money))
        {
            return Code::statusDataReturn(Code::FAIL,"金额不能为空");
        }
        $unifiedOrder->setParameter("openid","$openid");//商品描述
        $unifiedOrder->setParameter("body","$body");//商品描述
        $unifiedOrder->setParameter("out_trade_no","$out_trade_no");//商户订单号
        $unifiedOrder->setParameter("total_fee","$money");//总金额
        $unifiedOrder->setParameter("notify_url",\WxPayConf_pub::NOTIFY_URL);//通知地址
        $unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
        $unifiedOrder->setParameter("attach","$type");//附加数据
//非必填参数，商户可根据实际情况选填
//$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号
//$unifiedOrder->setParameter("device_info","XXXX");//设备号

//$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
//$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间
//$unifiedOrder->setParameter("goods_tag","XXXX");//商品标记
//$unifiedOrder->setParameter("openid","XXXX");//用户标识
//$unifiedOrder->setParameter("product_id","XXXX");//商品ID

        $prepay_id = $unifiedOrder->getPrepayId();
//=========步骤3：使用jsapi调起支付============
        $jsApi->setPrepayId($prepay_id);

        $jsApiParameters = $jsApi->getParameters();
         return Code::statusDataReturn(Code::SUCCESS,$jsApiParameters);
    }
}