<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/14
 * Time : 下午6:52
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\pay\alipay\send;

use common\pay\alipay\lib\AlipaySubmit;

class AlipaySendApi {


    //支付宝交易号
    //必填
    private $trade_no="";

    //物流公司名称
    private $logistics_name="顺丰快递";

    //物流发货单号
    private $invoice_no="";

    //物流运输类型
    //三个值可选：POST（平邮）、EXPRESS（快递）、EMS（EMS）
    private $transport_type="POST";


    public function alipaySend($alipayNumber)
    {
        /**************************请求参数**************************/

        $this->trade_no=$alipayNumber;

        $alipayConfig=new AlipayConfig();
        $alipay_config=$alipayConfig->alipay_config;

        /************************************************************/

        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "send_goods_confirm_by_platform",
            "partner" => trim($alipay_config['partner']),
            "trade_no"	=> $this->trade_no,
            "logistics_name"	=> $this->logistics_name,
            "invoice_no"	=> $this->invoice_no,
            "transport_type"	=> $this->transport_type,
            "_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
        );

        //建立请求
        $alipaySubmit = new AlipaySubmit($alipay_config);
        $html_text = $alipaySubmit->buildRequestHttp($parameter);
        //解析XML
        //注意：该功能PHP5环境及以上支持，需开通curl、SSL等PHP配置环境。建议本地调试时使用PHP开发软件
        $doc = new \DOMDocument();
        $doc->loadXML($html_text);

        //请在这里加上商户的业务逻辑程序代码

        //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——

        //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

        //解析XML
        if( ! empty($doc->getElementsByTagName( "alipay" )->item(0)->nodeValue) ) {
            $alipay = $doc->getElementsByTagName( "alipay" )->item(0)->nodeValue;
            echo $alipay;
        }

        //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
    }
}