<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/13
 * Time : 下午5:37
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\pay\alipay\create;

use common\components\Aes;
use common\entity\UserBase;
use common\entity\UserOrderInfo;
use common\entity\WeChatOrderList;
use common\pay\alipay\lib\AlipaySubmit;


class AlipayCreateApi {


    //支付类型
    private $payment_type = "1";

    //必填，不能修改
    //服务器异步通知页面路径
    //需http://格式的完整路径，不能加?id=123这类自定义参数
    private $notify_url ="";

    //页面跳转同步通知页面路径
    private $return_url = "";
    //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/

    //商户订单号
    private $out_trade_no;
    //商户网站订单系统中唯一订单号，必填

    //订单名称
    private $subject;
    //必填

    //付款金额
    private $price;
    //必填

    //商品数量
    private $quantity = "1";
    //必填，建议默认为1，不改变值，把一次交易看成是一次下订单而非购买一件商品
    //物流费用
    private $logistics_fee = "0.00";
    //必填，即运费
    //物流类型
    private $logistics_type = "EXPRESS";
    //必填，三个值可选：EXPRESS（快递）、POST（平邮）、EMS（EMS）
    //物流支付方式
    private $logistics_payment = "SELLER_PAY";
    //必填，两个值可选：SELLER_PAY（卖家承担运费）、BUYER_PAY（买家承担运费）

    //订单描述
    private $body;
    //商品展示地址
    private $show_url;
    //需以http://开头的完整路径，如：http://www.商户网站.com/myorder.html

    //收货人姓名
    private $receive_name;
    //如：张三

    //收货人地址
    private $receive_address ='北京市';
    //如：XX省XXX市XXX区XXX路XXX小区XXX栋XXX单元XXX号

    //收货人邮编
    private $receive_zip ="100011";
    //如：123456

    //收货人电话号码
    private $receive_phone='';
    //如：0571-88158090

    //收货人手机号码
    private $receive_mobile;
    //如：13312341234



    public function __construct()
    {

    }


    public function createOrder(UserOrderInfo $order,UserBase $userBase)
    {

        $travelTripInfo=json_decode($order->tripJsonInfo,true);
        $tripInfo=$travelTripInfo['info'];
        $this->notify_url=\Yii::$app->params['base_dir']."/pay-return/alipay-return";
        $this->out_trade_no=$order->orderNumber;
        $this->subject=htmlspecialchars($tripInfo['title']);
        $this->price=$order->totalPrice;
        $this->body=htmlspecialchars($tripInfo['intro']);;//暂时写成随游详情 详情内容中不能有空格等参数
        $this->show_url=\Yii::$app->params['base_dir']."/view-trip/info?trip=".$tripInfo['tripId'];
        $this->receive_name=htmlspecialchars($userBase->nickname);
        $this->receive_mobile=$userBase->phone;


        $alipayConfig=new AlipayConfig();
        $alipay_config=$alipayConfig->alipay_config;



        /************************************************************/

        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "create_partner_trade_by_buyer",
            "partner" => trim($alipay_config['partner']),
            "seller_email" => trim($alipay_config['seller_email']),
            "payment_type"	=> $this->payment_type,
            "notify_url"	=> $this->notify_url,
            "return_url"	=> $this->return_url,
            "out_trade_no"	=> $this->out_trade_no,
            "subject"	=> $this->subject,
            "price"	=> $this->price,
            "quantity"	=> $this->quantity,
            "logistics_fee"	=> $this->logistics_fee,
            "logistics_type"	=> $this->logistics_type,
            "logistics_payment"	=> $this->logistics_payment,
            "body"	=> $this->body,
            "show_url"	=> $this->show_url,
            "receive_name"	=> $this->receive_name,
            "receive_address"	=> $this->receive_address,
            "receive_zip"	=> $this->receive_zip,
            "receive_phone"	=> $this->receive_phone,
            "receive_mobile"	=> $this->receive_mobile,
            "_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
        );


        //建立请求

        $alipaySubmit = new AlipaySubmit($alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter,"get", "正在提交订单，请稍后。。。");
        echo $html_text;


    }
    public function createWxOrder($userSign,WeChatOrderList $orderInfo)
    {
        $o = Aes::encrypt($orderInfo->wOrderNumber,"suiuu9527",128);
        $this->notify_url=\Yii::$app->params['base_dir']."/pay-return/wxOrder-return?type=1";
        $this->out_trade_no=$orderInfo->wOrderNumber;
        $this->subject=htmlspecialchars($orderInfo->wOrderSite);
        $this->price=$orderInfo->wMoney;
        $this->body=htmlspecialchars($orderInfo->wOrderContent);;//暂时写成随游详情 详情内容中不能有空格等参数
        $this->show_url=\Yii::$app->params['base_dir']."/we-chat-order-list/show-order?o=".$o;
        //$this->receive_name=htmlspecialchars($userBase->nickname);
        //$this->receive_mobile=$userBase->phone;


        $alipayConfig=new AlipayConfig();
        $alipay_config=$alipayConfig->alipay_config;



        /************************************************************/

        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "create_partner_trade_by_buyer",
            "partner" => trim($alipay_config['partner']),
            "seller_email" => trim($alipay_config['seller_email']),
            "payment_type"	=> $this->payment_type,
            "notify_url"	=> $this->notify_url,
            "return_url"	=> $this->return_url,
            "out_trade_no"	=> $this->out_trade_no,
            "subject"	=> $this->subject,
            "price"	=> $this->price,
            "quantity"	=> $this->quantity,
            "logistics_fee"	=> $this->logistics_fee,
            "logistics_type"	=> $this->logistics_type,
            "logistics_payment"	=> $this->logistics_payment,
            "body"	=> $this->body,
            "show_url"	=> $this->show_url,
            "receive_name"	=> $this->receive_name,
            "receive_address"	=> $this->receive_address,
            "receive_zip"	=> $this->receive_zip,
            "receive_phone"	=> $this->receive_phone,
            "receive_mobile"	=> $this->receive_mobile,
            "_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
        );


        //建立请求

        $alipaySubmit = new AlipaySubmit($alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter,"get", "正在提交订单，请稍后。。。");
        echo $html_text;


    }

}