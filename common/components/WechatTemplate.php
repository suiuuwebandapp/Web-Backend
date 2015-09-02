<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/9/1
 * Time: 下午2:00
 */

namespace common\components;


use common\entity\WeChat;
use common\entity\WeChatUserInfo;
use frontend\interfaces\WechatInterface;
use frontend\services\UserBaseService;
use frontend\services\UserOrderService;
use frontend\services\WeChatOrderListService;
use frontend\services\WeChatService;

class WechatTemplate {

    public $wechatSer;
    public $wechatOrderSer;
    public $orderSer;
    public $wechatInterface;
    public $userOrderService;
    public function __construct()
    {
        $this->wechatSer = new WeChatService();
        $this->wechatOrderSer = new WeChatOrderListService();
        $this->orderSer = new UserOrderService();
        $this->wechatInterface =new WechatInterface();
        $this->userOrderService = new UserOrderService();
    }


    public function sendStatusChangeTemplateMessage($orderNumber)
    {
        $info = $this->orderSer->findOrderByOrderNumber($orderNumber);
        if(empty($info))
        {
            return Code::statusDataReturn(Code::FAIL,"未知订单");
        }
        $userSign = $info->userId;
        $wechatInfo = new WeChatUserInfo();
        $wechatInfo->userSign=$userSign;
        $userInfo = $this->wechatSer->getUserInfo($wechatInfo);
        if(empty($userInfo))
        {
            return Code::statusDataReturn(Code::FAIL,"未知用户");
        }
        $toUser=$userInfo['openId'];
        if(empty($toUser))
        {
            return Code::statusDataReturn(Code::FAIL,"未知用户");
        }

        $orderStatus="状态已变更";
        $first="您的订单状态已改变";
        $remark="如有任何问题请及时联系";
        $backUrl=\Yii::$app->params['weChatUrl']."/wechat-user-center/my-order-info?id=".$orderNumber;

        $orderId=$info->orderId;
        $publisherInfo =$this->userOrderService->findPublisherByOrderId($orderId);
        $publisherBase=null;
        if(!empty($publisherInfo))
        {
            $sign=$publisherInfo->userId;
            $userBaseService = new UserBaseService();
            $publisherBase=$userBaseService->findUserByUserSign($sign);
        }
        $orderPrice=$info->totalPrice;
        $jsonInfo = $info->tripJsonInfo;
        $infoArr=json_decode($jsonInfo,true);
        $productName=$infoArr['info']['title'];
        $status=$info->status;
            switch($status)
            {
                case 0:
                    $orderStatus="待支付";
                    break;
                case 1:
                    $orderStatus="等待随友接单";
                    break;
                case 2:
                    $first="您好，已经有随友小伙伴接受了您的订单！";
                    $orderStatus="已接单";
                    if(!isset($publisherBase->nickname)){
                    $remark="随友已经接受了您的订单，记得和接单随友保持沟通哦。";
                    }else
                    {
                        $remark="随友".$publisherBase->nickname."已经接受了您的订单,记得和接单随友保持沟通哦。";
                    }
                    break;
                case 3:
                    $orderStatus="已取消";
                    break;
                case 4:
                    $first="您的退款申请已经提交，我们会尽快进行审核并回复您。";
                    $orderStatus="申请退款";
                    $remark="请您稍后登陆您的个人中心查看订单最新状态，感谢您的耐心等待。";
                    break;
                case 5:
                    $first="您的退款申请已经成功。 ";
                    $orderStatus="退款成功";
                    $remark="请您登陆您的个人中心查看订单最新状态，感谢您的耐心等待。";
                    break;
                case 6:
                    $first="您好，您有已经完成的订单";
                    $orderStatus="游玩结束";
                    $remark="请您登陆您的个人中心及时确认订单，并且给您的体验提供评价。";
                    break;
                case 7:
                    $orderStatus="结算完成，订单关闭";
                    break;
                case 8:
                    $orderStatus="退款审核中";
                    break;
                case 9:
                    $orderStatus="拒绝退款";
                    break;
                case 10:
                    $orderStatus="随友取消订单";
                    break;
            }

        $url = WeChat::MESSAGE_SEN_TEMPLATE .  $this->wechatInterface->readToken();
        $templateId=WeChat::TEMPLATE_ID_FOR_STATUS_CHANGE;
        $data =  $this->getStatusChangeTemplate($toUser,$templateId,$backUrl,$first,$orderNumber,$orderPrice,$orderStatus,$productName,$remark);
        $rst =  $this->curlHandel($url,$data);
        return $rst;
    }

    private function getStatusChangeTemplate($toUser,$templateId,$backUrl,$first,$orderNumber,$orderPrice,$orderStatus,$productName,$remark)
    {
        $arr=array(
            "touser"=>$toUser,
            "template_id"=>$templateId,
            "url"=>$backUrl,
            "topcolor"=>"#FF0000",
            "data"=>array(
                "first"=>array("value"=>$first,"color"=>"#173177"),
                "orderId"=>array("value"=>$orderNumber,"color"=>"#173177"),
                "orderPrice"=>array("value"=>$orderPrice,"color"=>"#173177"),
                "orderStatus"=>array("value"=>$orderStatus,"color"=>"#173177"),
                "productName"=>array("value"=>$productName,"color"=>"#173177"),
                "remark"=>array("value"=>$remark,"color"=>"#173177")
            )
        );
        return json_encode($arr);
    }


//支付成功
    public function sendOrderPaySuccess($orderNumber)
    {
        $info = $this->orderSer->findOrderByOrderNumber($orderNumber);
        if(empty($info))
        {
            return Code::statusDataReturn(Code::FAIL,"未知订单");
        }
        $userSign = $info->userId;
        $wechatInfo = new WeChatUserInfo();
        $wechatInfo->userSign=$userSign;
        $userInfo = $this->wechatSer->getUserInfo($wechatInfo);
        if(empty($userInfo))
        {
            return Code::statusDataReturn(Code::FAIL,"未知用户");
        }
        $toUser=$userInfo['openId'];
        if(empty($toUser))
        {
            return Code::statusDataReturn(Code::FAIL,"未知用户");
        }
        $first = "您的订单已经支付成功啦！";
        $nickName = $userInfo["nickname"];

        $backUrl=\Yii::$app->params['weChatUrl']."/wechat-user-center/my-order-info?id=".$orderNumber;

        $jsonInfo = $info->tripJsonInfo;
        $infoArr=json_decode($jsonInfo,true);
        $tripTitle=$infoArr['info']['title'];

        $userNumber=$info->personCount;
        $travelTime=gmdate('Y-m-d H:i:s', time() + 3600 * 8);

        $remark="随游小伙伴已经收到您的旅行申请，他们会在最快的时间内处理您的订单";
        $url = WeChat::MESSAGE_SEN_TEMPLATE .  $this->wechatInterface->readToken();
        $templateId=WeChat::TEMPLATE_ID_FOR_ORDER_PAY_SUCCESS;
        $data =  $this->getPaySuccessTemplate($toUser,$templateId,$backUrl,$first,$nickName,$tripTitle,$userNumber,$travelTime,$remark);
        $rst =  $this->curlHandel($url,$data);
        return $rst;
    }
//定制支付成功
    public function sendWechatOrderPaySuccess($orderNumber)
    {
        $info = $this->wechatOrderSer->getWeChatOrderListByOrderNumber($orderNumber);
        if(empty($info))
        {
            return Code::statusDataReturn(Code::FAIL,"未知订单");
        }
        $userSign = $info['wUserSign'];
        $wechatInfo = new WeChatUserInfo();
        $wechatInfo->userSign=$userSign;
        $userInfo = $this->wechatSer->getUserInfo($wechatInfo);
        if(empty($userInfo))
        {
            return Code::statusDataReturn(Code::FAIL,"未知用户");
        }
        $toUser=$userInfo['openId'];
        if(empty($toUser))
        {
            return Code::statusDataReturn(Code::FAIL,"未知用户");
        }
        $first = "您的订单已经支付成功啦！";
        $nickName = $userInfo["nickname"];

        $backUrl=\Yii::$app->params['weChatUrl']."/we-chat-order-list/order-info?orderNumber=".$orderNumber;

        $title=$info['wOrderSite']."定制";

        $userNumber=$info['wUserNumber']."人";
        $time=gmdate('Y-m-d H:i:s', time() + 3600 * 8);

        $remark="您的定制订单已经付款成功，收拾好心情准备出发吧";
        $url = WeChat::MESSAGE_SEN_TEMPLATE .  $this->wechatInterface->readToken();
        $templateId=WeChat::TEMPLATE_ID_FOR_ORDER_PAY_SUCCESS;
        $data =  $this->getPaySuccessTemplate($toUser,$templateId,$backUrl,$first,$nickName,$title,$userNumber,$time,$remark);
        $rst =  $this->curlHandel($url,$data);
        return $rst;
    }
    private function getPaySuccessTemplate($toUser,$templateId,$backUrl,$first,$nickName,$title,$userNumber,$time,$remark)
    {
        $arr=array(
            "touser"=>$toUser,
            "template_id"=>$templateId,
            "url"=>$backUrl,
            "topcolor"=>"#FF0000",
            "data"=>array(
                "first"=>array("value"=>$first,"color"=>"#173177"),
                "keyword1"=>array("value"=>$nickName,"color"=>"#173177"),
                "keyword2"=>array("value"=>$title,"color"=>"#173177"),
                "keyword3"=>array("value"=>$userNumber,"color"=>"#173177"),
                "keyword4"=>array("value"=>$time,"color"=>"#173177"),
                "remark"=>array("value"=>$remark,"color"=>"#173177")
            )
        );
        return json_encode($arr);
    }


//行程即将开始
    public function sendTripBegin($orderNumber)
    {
        $info = $this->orderSer->findOrderByOrderNumber($orderNumber);
        if(empty($info))
        {
            return Code::statusDataReturn(Code::FAIL,"未知订单");
        }
        $userSign = $info->userId;
        $wechatInfo = new WeChatUserInfo();
        $wechatInfo->userSign=$userSign;
        $userInfo = $this->wechatSer->getUserInfo($wechatInfo);
        if(empty($userInfo))
        {
            return Code::statusDataReturn(Code::FAIL,"未知用户");
        }
        $toUser=$userInfo['openId'];
        if(empty($toUser))
        {
            return Code::statusDataReturn(Code::FAIL,"未知用户");
        }
        $first = "您好，您预订的随游明天即将开启， 把您的心情打包，准备出发吧！";

        $backUrl=\Yii::$app->params['weChatUrl']."/wechat-user-center/my-order-info?id=".$orderNumber;

        $jsonInfo = $info->tripJsonInfo;
        $infoArr=json_decode($jsonInfo,true);
        $tripTitle=$infoArr['info']['title'];

        $travelTime=$info->beginDate;

        $remark="记得要和随友提前确定见面地点等旅行细节。祝您拥有一段难忘的旅程！";
        $url = WeChat::MESSAGE_SEN_TEMPLATE .  $this->wechatInterface->readToken();
        $templateId=WeChat::TEMPLATE_ID_FOR_TRIP_BEGIN;
        $data =  $this->getTripBeginTemplate($toUser,$templateId,$backUrl,$first,$tripTitle,$travelTime,$remark);
        $rst =  $this->curlHandel($url,$data);
        return $rst;
    }

    private function getTripBeginTemplate($toUser,$templateId,$backUrl,$first,$tripTitle,$travelTime,$remark)
    {
        $arr=array(
            "touser"=>$toUser,
            "template_id"=>$templateId,
            "url"=>$backUrl,
            "topcolor"=>"#FF0000",
            "data"=>array(
                "first"=>array("value"=>$first,"color"=>"#173177"),
                "keyword1"=>array("value"=>$tripTitle,"color"=>"#173177"),
                "keyword2"=>array("value"=>$travelTime,"color"=>"#173177"),
                "remark"=>array("value"=>$remark,"color"=>"#173177")
            )
        );
        return json_encode($arr);
    }


//定制反馈
    public function sendWechatOrderFeedback($wechatOrderNumber)
    {
        $info = $this->wechatOrderSer->getOrderInfoByOrderNumber($wechatOrderNumber,null);
        if(empty($info))
        {
            return Code::statusDataReturn(Code::FAIL,"未知订单");
        }
        $userSign = $info["wUserSign"];
        $wechatInfo = new WeChatUserInfo();
        $wechatInfo->userSign=$userSign;
        $userInfo = $this->wechatSer->getUserInfo($wechatInfo);
        if(empty($userInfo))
        {
            return Code::statusDataReturn(Code::FAIL,"未知用户");
        }
        $toUser=$userInfo['openId'];
        if(empty($toUser))
        {
            return Code::statusDataReturn(Code::FAIL,"未知用户");
        }
        $first = "当地行程规划专家已经为您提供了旅行方案！";
        $backUrl=\Yii::$app->params['weChatUrl']."/we-chat-order-list/order-info?orderNumber=".$wechatOrderNumber;

        $str =$info['wDetails'];//'rrrr######qweqweqwe###09###ssssss######qqqqqqqq###asd###asdasdasd';
        $contentTitle="";
        if(!empty($str)){
            $arr=explode('###',$str);
            $contentTitle=$arr[0];
        }
        $site=$info['wOrderSite']."定制";

        $time=$info['wOrderTimeList'];
        $arrTime=explode(",",$time);
        if(isset($arrTime[0]))
        {
            $time=$arrTime[0];
        }
        $userNumber=$info['wUserNumber']."人";
        $remark="如有问题请及时联系";
        $url = WeChat::MESSAGE_SEN_TEMPLATE .  $this->wechatInterface->readToken();
        $templateId=WeChat::TEMPLATE_ID_FOR_WECHAT_ORDER_FEEDBACK;
        $data =  $this->getWechatOrderFeedbackTemplate($toUser,$templateId,$backUrl,$first,$contentTitle,$site,$time,$userNumber,$remark);
        $rst =  $this->curlHandel($url,$data);
        return $rst;
    }

    private function getWechatOrderFeedbackTemplate($toUser,$templateId,$backUrl,$first,$contentTitle,$site,$time,$userNumber,$remark)
    {
        $arr=array(
            "touser"=>$toUser,
            "template_id"=>$templateId,
            "url"=>$backUrl,
            "topcolor"=>"#FF0000",
            "data"=>array(
                "first"=>array("value"=>$first,"color"=>"#173177"),
                "keyword1"=>array("value"=>$contentTitle,"color"=>"#173177"),
                "keyword2"=>array("value"=>$site,"color"=>"#173177"),
                "keyword3"=>array("value"=>$time,"color"=>"#173177"),
                "keyword4"=>array("value"=>$userNumber,"color"=>"#173177"),
                "remark"=>array("value"=>$remark,"color"=>"#173177")
            )
        );
        return json_encode($arr);
    }



    //消息提醒
    public function sendMessageRemind($userSign,$info)
    {

        $wechatInfo = new WeChatUserInfo();
        $wechatInfo->userSign=$userSign;
        $userInfo = $this->wechatSer->getUserInfo($wechatInfo);
        if(empty($userInfo))
        {
            return Code::statusDataReturn(Code::FAIL,"未知用户");
        }
        $toUser=$userInfo['openId'];
        if(empty($toUser))
        {
            return Code::statusDataReturn(Code::FAIL,"未知用户");
        }
        $first = "您有一条新的消息！";
        $source=$info;
        $backUrl="";
        $remark="";
        $time=gmdate('Y-m-d H:i:s', time() + 3600 * 8);
        $url = WeChat::MESSAGE_SEN_TEMPLATE .  $this->wechatInterface->readToken();
        $templateId=WeChat::TEMPLATE_ID_FOR_MESSAGE_REMIND;
        $data =  $this->getMessageRemind($toUser,$templateId,$backUrl,$first,$source,$info,$time,$remark);
        $rst =  $this->curlHandel($url,$data);
        return $rst;
    }

    private function getMessageRemind($toUser,$templateId,$backUrl,$first,$source,$info,$time,$remark)
    {
        $arr=array(
            "touser"=>$toUser,
            "template_id"=>$templateId,
            "url"=>$backUrl,
            "topcolor"=>"#FF0000",
            "data"=>array(
                "first"=>array("value"=>$first,"color"=>"#173177"),
                "keyword1"=>array("value"=>$source,"color"=>"#173177"),
                "keyword2"=>array("value"=>$info,"color"=>"#173177"),
                "keyword3"=>array("value"=>$time,"color"=>"#173177"),
                "remark"=>array("value"=>$remark,"color"=>"#173177")
            )
        );
        return json_encode($arr);
    }

    private function curlHandel($url,$data)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return Code::statusDataReturn(Code::SUCCESS,$output);
    }
}