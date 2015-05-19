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
use frontend\services\UserOrderService;
use frontend\services\UserPayService;
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

            logResult($trade_status);
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
}