<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/19
 * Time: 下午5:14
 */
namespace common\pay\wxpay;

use common\components\Code;
use common\entity\UserOrderInfo;
class NativeDynamicQrcode {

    public function __construct()
    {

    }

    public function createCode(UserOrderInfo $orderInfo)
    {
        //return Code::statusDataReturn(Code::FAIL,"暂未开通");
        /**
         * Native（原生）支付-模式二-demo
         * ====================================================
         * 商户生成订单，先调用统一支付接口获取到code_url，
         * 此URL直接生成二维码，用户扫码后调起支付。
         *
         */
        include_once("WxPayPubHelper/WxPayPubHelper.php");

        //使用统一支付接口
        $unifiedOrder = new \UnifiedOrder_pub();

        //设置统一支付接口参数
        //设置必填参数
        //appid已填,商户无需重复填写
        //mch_id已填,商户无需重复填写
        //noncestr已填,商户无需重复填写
        //spbill_create_ip已填,商户无需重复填写
        //sign已填,商户无需重复填写
        $travelTripInfo=json_decode($orderInfo->tripJsonInfo,true);
        $tripInfo=$travelTripInfo['info'];
        $unifiedOrder->setParameter("body",$tripInfo['title']);//商品描述
        $money=$orderInfo->totalPrice*100;
        if($money>1)
        {
            $money=1;
        }
        //自定义订单号，此处仅作举例
        $timeStamp = time();
        $out_trade_no = $orderInfo->orderNumber;
        $unifiedOrder->setParameter("out_trade_no","$out_trade_no");//商户订单号
        $unifiedOrder->setParameter("total_fee",$money);//总金额
        $unifiedOrder->setParameter("notify_url",\WxPayConf_pub::NOTIFY_URL);//通知地址
        $unifiedOrder->setParameter("trade_type","NATIVE");//交易类型
        //非必填参数，商户可根据实际情况选填
        //$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号
        //$unifiedOrder->setParameter("device_info","XXXX");//设备号
        $unifiedOrder->setParameter("attach",$orderInfo->orderNumber);//附加数据
        //$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
        //$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间
        //$unifiedOrder->setParameter("goods_tag","XXXX");//商品标记
        //$unifiedOrder->setParameter("openid","XXXX");//用户标识
        //$unifiedOrder->setParameter("product_id","XXXX");//商品ID

        //获取统一支付接口结果
        $unifiedOrderResult = $unifiedOrder->getResult();

        //商户根据实际情况设置相应的处理流程
        if ($unifiedOrderResult["return_code"] == "FAIL")
        {
            //商户自行增加处理流程
            return Code::statusDataReturn(Code::FAIL,"通信出错：".$unifiedOrderResult['return_msg']);
        }
        elseif($unifiedOrderResult["result_code"] == "FAIL")
        {  //商户自行增加处理流程
            return Code::statusDataReturn(Code::FAIL,"错误代码：".$unifiedOrderResult['err_code']."错误代码描述：".$unifiedOrderResult['err_code_des']);

        }
        elseif($unifiedOrderResult["code_url"] != NULL)
        {
            return Code::statusDataReturn(Code::SUCCESS,$unifiedOrderResult["code_url"]);
            //从统一支付接口获取到code_url
            //商户自行增加处理流程
            //......
        }
    }
}