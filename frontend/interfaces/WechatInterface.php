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
use common\components\LogUtils;
use common\components\UrlUtil;
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
     * 跳转到微信接入页面
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
            LogUtils::log($e);
            return Code::statusDataReturn(Code::FAIL,$e->getMessage());
        }

    }



}