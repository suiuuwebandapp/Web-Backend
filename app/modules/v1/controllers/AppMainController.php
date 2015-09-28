<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/12
 * Time: 上午11:42
 */

namespace app\modules\v1\controllers;



use app\modules\v1\entity\AppLog;
use app\modules\v1\entity\AppVersion;
use app\modules\v1\services\AppLogService;
use app\modules\v1\services\AppVersionService;
use common\components\Code;
use app\modules\v1\services\UserAttentionService;
use app\modules\v1\services\UserOrderService;
use app\modules\v1\services\WeChatOrderListService;
use common\components\LogUtils;
use Yii;
use yii\base\Exception;

class AppMainController extends AController
{
    private $AttentionService;

    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        $this->AttentionService = new UserAttentionService();
    }

    public function actionTest()
    {
exit;
        $appSign=Yii::$app->request->get("appSign");
        $time=time();
        var_dump($time);
        echo md5($time.$appSign.\Yii::$app->params['apiPassword']);
    }
    public function actionError()
    {
        return $this->apiReturn(Code::statusDataReturn(Code::ERROR,"错误请求"));
    }


    public function actionGetTime()
    {
        $time=time();
        return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$time));
    }

    public function actionGetToken()
    {
        $timestamp=Yii::$app->request->get("timestamp");
        $appSign = Yii::$app->request->get("appSign");
        $sign = Yii::$app->request->get("sign");
        $time=time();
        if(abs($time-$timestamp)>600)
        {
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"时间已过期"));
        }
        if(empty($sign))
        {
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"sign不正确"));
        }
        if($sign==md5($timestamp.$appSign.\Yii::$app->params['apiPassword']))
        {
            $token =md5($appSign.$time.Code::APP_TOKEN_KEY);
            \Yii::$app->redis->set(Code::APP_TOKEN  . $token, $appSign);
            \Yii::$app->redis->expire(Code::APP_TOKEN . $token, Code::APP_TOKEN_TIME);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$token));
        }else
        {
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,"sign不正确"));
        }
    }

    //检查是否更新;
    public function actionCheckVersion()
    {
        try {
            $appId = Yii::$app->request->post("appId");
            $clientType = Yii::$app->request->post("clientType");
            $versionId = Yii::$app->request->post("versionId");
            $versionMini = Yii::$app->request->post("versionMini");
            $version = new AppVersion();
            $version->appId=$appId;
            $version->clientType=$clientType;
            $version->versionId=$versionId;
            $version->versionMini=$versionMini;
            $appVersionSer = new AppVersionService();
            $versionRst = $appVersionSer->getVersion($version);
            if(empty($versionRst))
            {
                $id = $appVersionSer->addVersion($version);
            }else
            {
                $id=$versionRst['id'];
            }

            $rst = AppVersion::versionCheck($versionId,$versionMini,$clientType);
            $rst['vId']=$id;
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$rst));
        }catch (Exception $e) {
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,"提交问题异常"));
        }
    }
    public function actionAddLog()
    {
        try {
            $appLog = Yii::$app->request->post("log");
            $vId = Yii::$app->request->post("vId");
            if(empty($appLog))
            {
                return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"错误日志不能为空"));
            }
            if(empty($vId))
            {
                return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"版本信息不能为空"));
            }
            $log = new AppLog();
            $log->appVersionId=$vId;
            $log->userSign=Yii::$app->request->post("userSign");
            $log->log=$appLog;
            $appLogSer  = new AppLogService();
            $appLogSer->addLog($log);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,""));
        }catch (Exception $e) {
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,"提交log异常"));
        }
    }


    //ping++支付
    public function actionPay()
    {
        $this->loginValid();
        require_once(dirname(__FILE__) . '/../../../../common/pay/pingpp/init.php');
        $channel = Yii::$app->request->post("channel");
        $number = Yii::$app->request->post("orderNumber");
        $type = Yii::$app->request->post("type");
        if(empty(trim($number))){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,'无效的订单号'));
        }
//$extra 在使用某些渠道的时候，需要填入相应的参数，其它渠道则是 array() .具体见以下代码或者官网中的文档。其他渠道时可以传空值也可以不传。
        $extra = array();


        \Pingpp\Pingpp::setApiKey('sk_live_dMQIp9BoYtDRtbYIfylNUxnT');//sk_test_8m9Wr9OerbrDG08GC40qTWvL  sk_live_dMQIp9BoYtDRtbYIfylNUxnT  //正式key 和测试key
        try {
            //description 附加参数1定制2随游
            $userSign = $this->userObj->userSign;
            if($type==1)
            {
                $wxOrderServer = new WeChatOrderListService();
                $orderInfo = $wxOrderServer->getOrderInfoByOrderNumber(trim($number),$userSign);
                if(empty($orderInfo))
                {
                    return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,'无效的订单号'));
                }
                $showUrl='http://www.suiuu.com/we-chat-order-list/order-manage';
                $amount = intval($orderInfo['wMoney']*100);//价格
                $subject=htmlspecialchars($orderInfo['wOrderSite']."定制旅行");
                $body=htmlspecialchars("您的专属".$orderInfo['wOrderSite']."行程");
            }else
            {
                $orderService=new UserOrderService();
                $orderInfo=$orderService->findOrderByOrderNumber(trim($number));
                if(empty($orderInfo)){
                    return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,'无效的订单号'));
                }
                if($orderInfo->userId!=$userSign){
                    return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,'订单用户不匹配'));
                }
                $amount = intval($orderInfo->totalPrice*100);//价格
                $travelTripInfo=json_decode($orderInfo->tripJsonInfo,true);
                $tripInfo=$travelTripInfo['info'];
                $showUrl='http://www.suiuu.com/wechat-trip/info?tripId='.$orderInfo->tripId;
                $subject=htmlspecialchars($tripInfo['title']);
                $body=htmlspecialchars($tripInfo['intro']);
            }
            switch ($channel) {
                //这里值列举了其中部分渠道的，具体的extra所需参数请参见官网中的 API 文档
                case 'alipay_wap':
                    $extra = array(
                        'success_url' => $showUrl,
                        'cancel_url' => $showUrl
                    );
                    break;
                case 'upmp_wap':
                    $extra = array(
                        'result_url' => 'http://www.suiuu.com/result?code='
                    );
                    break;
                case 'bfb_wap':
                    $extra = array(
                        'result_url' => 'http://www.suiuu.com/result?code='
                    );
                    break;
                case 'upacp_wap':
                    $extra = array(
                        'result_url' => 'http://www.suiuu.com/result?code='
                    );
                    break;
                case 'wx_pub':
                    $extra = array(
                        'open_id' => 'open_id'
                    );
                    break;
                case 'wx_pub_qr':
                    $extra = array(
                        'product_id' => 'Productid'
                    );
                    break;
                case 'jdpay_wap':
                    $extra = array(
                        'success_url'=>'http://www.suiuu.com',
                        'fail_url'=>'http://www.suiuu.com'
                    );
                    break;
            }
            $ch = \Pingpp\Charge::create(
                array(
                    "subject"   => $subject,
                    "body"      =>$body,
                    "amount"    => $amount,
                    "order_no"  => $number,
                    "currency"  => "cny",
                    "extra"     => $extra,
                    "channel"   => $channel,
                    "client_ip" => $_SERVER["REMOTE_ADDR"],
                    "app"       => array("id" => "app_04Ky94P0Wf1Km1uz"),
                    "description"=>$type
                )
            );
            echo $ch;exit;
            return $ch;
        } catch (\Pingpp\Error\Base $e) {
            header('Status: ' . $e->getHttpStatus());
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,$e->getHttpBody()));
        }
    }

}