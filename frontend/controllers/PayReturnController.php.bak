<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/14
 * Time : 下午5:33
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\controllers;

use common\components\Code;
use common\entity\UserOrderInfo;
use common\entity\UserPayRecord;
use common\pay\alipay\create\AlipayConfig;
use common\pay\alipay\lib\AlipayNotify;
use common\pay\alipay\send\AlipaySendApi;
use common\pay\wxpay\Log_;
use frontend\services\UserOrderService;
use frontend\services\UserPayService;
use frontend\services\WeChatOrderListService;
use yii\base\Exception;
use yii\web\Controller;

class PayReturnController extends Controller {

    public $enableCsrfValidation=false;


    public function actionAlipayReturn()
    {

        //计算得出通知验证结果
        $alipayConfig=new AlipayConfig();
        $alipay_config=$alipayConfig->alipay_config;

        $alipayNotify = new AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();

        logResult($verify_result);


        if($verify_result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代


            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——

            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表

            //商户订单号
            $out_trade_no = $_POST['out_trade_no'];

            //支付宝交易号
            $trade_no = $_POST['trade_no'];

            //交易状态
            $trade_status = $_POST['trade_status'];
            logResult($out_trade_no."-".$trade_no."-".$trade_status);

            //logResult($trade_status);
            if($_POST['trade_status'] == 'WAIT_BUYER_PAY') {
                //该判断表示买家已在支付宝交易管理中产生了交易记录，但没有付款

                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //如果有做过处理，不执行商户的业务程序


                echo "success";		//请不要修改或删除

                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            }
            else if($_POST['trade_status'] == 'WAIT_SELLER_SEND_GOODS') {
                //该判断表示买家已在支付宝交易管理中产生了交易记录且付款成功，但卖家没有发货

                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //如果有做过处理，不执行商户的业务程序


                //改变订单状态为已付款
                //添加支付记录,并且更新状态为确认付款

                try{
                    $userPayService=new UserPayService();
                    $userPayService->addUserPay($out_trade_no,$trade_no,UserPayRecord::PAY_RECORD_TYPE_ALIPAY,UserOrderInfo::USER_ORDER_STATUS_PAY_SUCCESS);

                    //自动发货
                    $sendApi=new AlipaySendApi();
                    $sendApi->alipaySend($trade_no);

                    \Yii::$app->redis->set(Code::USER_ORDER_PAY_STATS.$out_trade_no,1);
                    \Yii::$app->redis->expire(Code::USER_ORDER_PAY_STATS.$out_trade_no,600);//设定保留时长 10分钟（600秒）

                    echo "success";		//请不要修改或删除
                }catch (Exception $e){
                    //验证失败
                    echo "fail";
                }


                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            }
            else if($_POST['trade_status'] == 'WAIT_BUYER_CONFIRM_GOODS') {
                //该判断表示卖家已经发了货，但买家还没有做确认收货的操作

                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //如果有做过处理，不执行商户的业务程序

                echo "success";		//请不要修改或删除

                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            }
            else if($_POST['trade_status'] == 'TRADE_FINISHED') {
                //该判断表示买家已经确认收货，这笔交易完成

                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //如果有做过处理，不执行商户的业务程序

                //卖家确认收货，证明订单完成
                $userOrderService=new UserOrderService();
                //如果用户确认收货，是不是自动改变订单状态为已完成
                $userOrderService->changeOrderStatus($out_trade_no,UserOrderInfo::USER_ORDER_STATUS_PLAY_SUCCESS);
                echo "success";		//请不要修改或删除

                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            }
            else {
                //其他状态判断
                echo "success";

                //调试用，写文本函数记录程序运行情况是否正常
                //logResult ("这里写入想要调试的代码变量值，或其他运行的结果记录");
            }

            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        }
        else {
            //验证失败
            echo "fail";

            //调试用，写文本函数记录程序运行情况是否正常
            //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
        }
    }

    public function actionWxpayReturn()
    {
       include_once("../../common/pay/wxpay/WxPayPubHelper/WxPayPubHelper.php");
        include_once("../../common/pay/wxpay/log_.php");
        //使用通用通知接口
        $notify = new \Notify_pub();

        //存储微信的回调
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $notify->saveData($xml);

        //验证签名，并回应微信。
        //对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
        //微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
        //尽可能提高通知的成功率，但微信不保证通知最终能成功。
        if($notify->checkSign() == FALSE){
            $notify->setReturnParameter("return_code","FAIL");//返回状态码
            $notify->setReturnParameter("return_msg","签名失败");//返回信息
        }else{
            $notify->setReturnParameter("return_code","SUCCESS");//设置返回码
        }
        $returnXml = $notify->returnXml();
        echo $returnXml;

        //==商户根据实际情况设置相应的处理流程，此处仅作举例=======

        //以log文件形式记录回调信息
        $log_ = new Log_();
        $log_name="wx_notify_url.log";//log文件路径
        $log_->log_result($log_name,"【接收到的notify通知】:\n".$xml."\n");

        if($notify->checkSign() == TRUE)
        {
            if ($notify->data["return_code"] == "FAIL") {
                //此处应该更新一下订单状态，商户自行增删操作
                $log_->log_result($log_name,"【通信出错】:\n".$xml."\n");
            }
            elseif($notify->data["result_code"] == "FAIL"){
                //此处应该更新一下订单状态，商户自行增删操作
                $log_->log_result($log_name,"【业务出错】:\n".$xml."\n");
            }
            else{
                //此处应该更新一下订单状态，商户自行增删操作
                $log_->log_result($log_name,"【支付成功】:\n".$xml."\n");
                $arr = $notify->xmlToArray($xml);
                if(!empty($arr))
                {
                    //商户订单号
                    $out_trade_no = $arr['out_trade_no'];
                    //交易号
                    $trade_no = $arr['transaction_id'];
                    if(isset($arr['attach'])&&$arr['attach']==1)
                    {
                        $money=$arr['total_fee']/100;
                        //微信订定制
                        $weChatOrderSer=new WeChatOrderListService();
                        $weChatOrderSer->orderPayEnd($out_trade_no,$trade_no,UserPayRecord::PAY_RECORD_TYPE_WXPAY,$money);
                    }else
                    {
                    $userPayService=new UserPayService();
                    $userPayService->addUserPay($out_trade_no,$trade_no,UserPayRecord::PAY_RECORD_TYPE_WXPAY,UserOrderInfo::USER_ORDER_STATUS_PAY_SUCCESS);
                    \Yii::$app->redis->set(Code::USER_ORDER_PAY_STATS.$out_trade_no,1);
                    \Yii::$app->redis->expire(Code::USER_ORDER_PAY_STATS.$out_trade_no,600);
                    }
                }
            }

            //商户自行增加处理流程,
            //例如：更新订单状态
            //例如：数据库操作
            //例如：推送支付完成信息
        }
    }



    public function actionWarning()
    {
        include_once("../../common/pay/wxpay/log_.php");
        $str=json_encode($_GET).json_encode($_POST);
        $log_ = new Log_();
        $log_name="wx_warning.txt";//log文件路径
        $log_->log_result($log_name,"【警告】:\n".$str."\n");
    }
}