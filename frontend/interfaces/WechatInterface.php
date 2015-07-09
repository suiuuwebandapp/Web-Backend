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
}