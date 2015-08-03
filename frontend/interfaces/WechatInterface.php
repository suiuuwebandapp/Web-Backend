<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/9
 * Time : 下午2:13
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\interfaces;

use common\components\Code;
use common\components\Common;
use common\components\UrlUtil;
use common\entity\WeChat;
use common\entity\WeChatUserInfo;
use frontend\services\UserOrderService;
use frontend\services\WeChatOrderListService;
use frontend\services\WeChatService;
use yii\base\Exception;

class WechatInterface {



    private $appId;
    private $secret;



    public function __construct()
    {
        $this->appId='wxa33b47d647d7b8f6';
        $this->secret='12270b139db59f139082beffffc4ded9';
    }


    /**
     * 获取用户基本信息
     * @param $accessToken
     * @param $openId
     * @return array
     */
    public function getUserInfo($accessToken,$openId)
    {
        try{
            $getUserInfoUrl='https://api.weixin.qq.com/sns/userinfo';

            //-------请求参数列表
            $keysArr = array(
                'access_token'=>$accessToken,
                "openid" => $openId
            );

            //------构造请求access_token的url

            $user_info_url = UrlUtil::combineURL($getUserInfoUrl, $keysArr);
            $response = UrlUtil::get_contents($user_info_url);
            $rst=json_decode($response,true);
            if(array_key_exists('errcode',$rst)){
                return Code::statusDataReturn(Code::FAIL,$rst);
            }else{
                return Code::statusDataReturn(Code::SUCCESS,$rst);
            }
        }catch (Exception $e){
            return Code::statusDataReturn(Code::FAIL,$e->getMessage());
        }
    }

    /**
     * 跳转到QQ接入页面
     */
    public function toConnectWechat(){
        $returnUrl='http://www.suiuu.com/access/weixin-login';

        $url='https://open.weixin.qq.com/connect/qrconnect?'.
             'appid='.$this->appId.
             '&redirect_uri='.urlencode($returnUrl).
             '&response_type=code'.
             '&scope=snsapi_login'.
             '&state=';

        header("Location:$url");
    }


    /**
     * 回调函数获取Token
     * @param $csrf
     * @param $code
     * @return array
     */
    public function callBackGetTokenInfo($csrf,$code){

        try{
            $accessTokenUrl='https://api.weixin.qq.com/sns/oauth2/access_token';

            //-------请求参数列表
            $keysArr = array(
                "appid" => $this->appId,
                "secret" => $this->secret,
                "code" => $code,
                "grant_type" => 'authorization_code'
            );

            //------构造请求access_token的url

            $token_url = UrlUtil::combineURL($accessTokenUrl, $keysArr);
            $response = UrlUtil::get_contents($token_url);
            $rst=json_decode($response,true);
            if(array_key_exists('errcode',$rst)){
                return Code::statusDataReturn(Code::FAIL,$rst);
            }else{
                return Code::statusDataReturn(Code::SUCCESS,$rst);
            }
        }catch (Exception $e){
            return Code::statusDataReturn(Code::FAIL,$e->getMessage());
        }

    }



    /**
     * 跳转到微信js接入页面
     */
    public function toConnectWechatJs(){
        $returnUrl='http://www.suiuu.com/access/weixin-login-js';

        $url=sprintf(WeChat::GET_OAUTH2_CODE,WeChat::APP_ID,urlencode($returnUrl));

        header("Location:$url");
    }

    /**
     * 写入文件信息
     * @param $file
     * @param $str
     */
    public function write_to_log($file, $str)
    {
        if ($fd = @fopen(getcwd().'/../runtime/suiuu/'.$file, "a")) {
            fputs($fd, $str);
            fclose($fd);
        }
    }

    /**
     * 读取token
     * @return string
     */
    public function readToken()
    {
        //调用计划任务设置的Token
        $access_token = \Yii::$app->redis->get(WeChat::TOKEN_FILE_NAME);
        if (empty($access_token)) {
            $this->getToken();
            $access_token = \Yii::$app->redis->get(WeChat::TOKEN_FILE_NAME);
            //创建菜单  只有当token 为空的时候,又重写创建的时候,再重写创建菜单
            $weChatSer=new WeChatService();
            $weChatSer->createMenuInfo($access_token);
        }
        return $access_token;
    }
    /**
     * 获取token校验信息
     * @return mixed
     */
    public function getToken()
    {

        $app_id = WeChat::APP_ID;
        $app_secret = WeChat::APP_SECRET;
        $url = WeChat::TOKEN_LINK . 'client_credential&appid=' . $app_id . '&secret=' . $app_secret;

        $rst =  Common::CurlHandel($url);
        if ($rst['status'] == Code::SUCCESS) {
            $rstJson = json_decode($rst['data']);
            if (isset($rstJson->access_token)) {
                \Yii::$app->redis->set(WeChat::TOKEN_FILE_NAME,$rstJson->access_token);
                \Yii::$app->redis->expire(WeChat::TOKEN_FILE_NAME,$rstJson->expires_in);
                echo "ok";
            } else {
                $file_str = '错误发生时间：[' . date('Y-m-d H:i:s') . ']错误内容:{' . $rst['data'] . '}';
                $this->write_to_log(WeChat::LOG_XXX_NAME, $file_str);
                echo "fail";
            }
        }
    }

    public function getWechatUserOpenId($code)
    {
        try{
             $url = sprintf(WeChat::GET_OAUTH2_OPENID, WeChat::APP_ID, WeChat::APP_SECRET, $code);
             $rst = Common::CurlHandel($url);
                if ($rst['status'] == Code::SUCCESS) {
                    $rstJson = json_decode($rst['data'],true);
                    if(!isset($rstJson['openid'])){
                        return Code::statusDataReturn(Code::FAIL,$rstJson);
                    }else{
                        return Code::statusDataReturn(Code::SUCCESS,$rstJson);
                    }

                } else {
                    return Code::statusDataReturn(Code::FAIL,'无法获取用户信息');
                }
        }catch (Exception $e){
            return Code::statusDataReturn(Code::FAIL,$e->getMessage());
        }
    }


    /**获取用户信息
     * @param $openId 用户id
     * @param $isSave 是否保存
     * @return array
     */
    public function getWeChatUserInfo($openId, $isSave)
    {

        $access_token = $this->readToken();

        $url = WeChat::GET_USER_INFO . $access_token . "&openid=" . $openId . "&lang=zh_CN";

        $rst =  Common::CurlHandel($url);
        if ($rst['status'] == Code::SUCCESS) {
            $rstJson = json_decode($rst['data'],true);
            if (isset($rstJson['nickname'])) {
                if ($isSave) {
                    $weChatUserInfo=new WeChatUserInfo();
                    $weChatUserInfo->openId=$openId;
                    $weChatSer=new WeChatService();
                    $WeChatRst = $weChatSer->getUserInfo($weChatUserInfo);
                    if (!empty($WeChatRst)) {
                        $weChatSer->upDateWeChatInfo($rstJson,$WeChatRst['userSign']);
                        //可以改成更新信息。但是没法判断用户是否更新了
                    } else {
                        $weChatSer->insertWeChatInfo($rstJson);
                    }
                }
                return Code::statusDataReturn(Code::SUCCESS, $rstJson);
            } else {

                $file_str = '错误发生时间：[' . date('Y-m-d H:i:s') . ']错误内容:{获取用户信息' . $rst['data'] . '}';
                $this->write_to_log(WeChat::LOG_XXX_NAME, $file_str);
                exit;
            }
        }
        return Code::statusDataReturn(Code::FAIL);
    }

    public function sendStatusChangeTemplateMessage($userSign,$type,$orderNumber)
    {
        $wechatSer = new WeChatService();
        $wechatInfo = new WeChatUserInfo();
        $wechatInfo->userSign=$userSign;
        $userInfo = $wechatSer->getUserInfo($wechatInfo);
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
        if($type==1)
        {
            $first="亲爱的用户，您的定制已变更";
            //定制
            $backUrl=\Yii::$app->params['weChatUrl']."/we-chat-order-list/order-info?orderNumber=".$orderNumber;
            $orderSer = new WeChatOrderListService();
            $info = $orderSer->getOrderInfoByOrderNumber($orderNumber,null);
            if(empty($info))
            {
                return Code::statusDataReturn(Code::FAIL,"未知订单");
            }
            $orderPrice=$info['wMoney'];
            $productName=$info['wOrderSite']."定制";
            $status=$info['wStatus'];
            switch($status)
            {
                case 1:
                    $orderStatus="待处理";
                    break;
                case 2:
                    $orderStatus="已经处理待支付";
                    break;
                case 3:
                    $orderStatus="已支付";
                    break;
                case 4:
                    $orderStatus="游玩结束";
                    break;
                case 5:
                    $orderStatus="申请退款中";
                    break;
                case 6:
                    $orderStatus="退款结束";
                    break;
                case 7:
                    $orderStatus="拒绝退款";
                    break;
            }
        }else
        {
            if($userInfo['isPublisher'])
            {
                $first="亲爱的随友，您接的订单已改变";
            }else
            {
                $first="亲爱的随游，您的订单已改变";
            }
            $backUrl=\Yii::$app->params['weChatUrl']."/wechat-user-center/my-order-info?id=".$orderNumber;
            $orderSer = new UserOrderService();
            $info = $orderSer->findOrderByOrderNumber($orderNumber);
            if(empty($info))
            {
                return Code::statusDataReturn(Code::FAIL,"未知订单");
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
                    $orderStatus="随友已经接待";
                    break;
                case 3:
                    $orderStatus="已取消";
                    break;
                case 4:
                    $orderStatus="待退款";
                    break;
                case 5:
                    $orderStatus="退款成功";
                    break;
                case 6:
                    $orderStatus="游玩结束,待等待结算";
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
        }
        $remark="如有任何问题请及时联系";
        $url = WeChat::MESSAGE_SEN_TEMPLATE . $this->readToken();
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