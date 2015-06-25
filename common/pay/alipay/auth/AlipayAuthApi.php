<?php
namespace common\pay\alipay\auth;
use common\pay\alipay\lib\AlipaySubmit;

/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/6/23
 * Time : 上午11:18
 * Email: zhangxinmailvip@foxmail.com
 */

class AlipayAuthApi {

    //目标服务地址
    private $target_service = "user.auth.quick.login";

    //必填
    //必填，页面跳转同步通知页面路径
    private $return_url = "http://www.suiuu.com/access/alipay-auth-return";
    //需http://格式的完整路径，不允许加?id=123这类自定义参数

    //防钓鱼时间戳
    private $anti_phishing_key = "";
    //若要使用请调用类文件submit中的query_timestamp函数

    //客户端的IP地址
    //非局域网的外网IP地址，如：221.0.0.1
    private $exter_invoke_ip = "";


    public function auth()
    {
        $alipayConfig=new AlipayConfig();
        $alipay_config=$alipayConfig->alipay_config;
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "alipay.auth.authorize",
            "partner" => trim($alipay_config['partner']),
            "target_service"	=>$this->target_service,
            "return_url"	=> $this->return_url,
            "anti_phishing_key"	=> $this->anti_phishing_key,
            "exter_invoke_ip"	=> $this->exter_invoke_ip,
            "_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
        );

        //建立请求
        $alipaySubmit = new AlipaySubmit($alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
        echo $html_text;
    }



}