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


    public function getAccessToken()
    {

    }

    /**
     * * 调用此方法前 确定有可用的调用过有效的Token
     * 获取腾讯的OpenID
     *
     * @param $tokenId
     * @return array
     */
    public function getOpenId($tokenId)
    {
        try{
            $openId=$this->QC->get_openid($tokenId);
            return Code::statusDataReturn(Code::SUCCESS,$openId);
        }catch (Exception $e){
            return Code::statusDataReturn(Code::FAIL,$e->getMessage());
        }
    }

    /**
     * 获取用户基本信息
     * @param $tokenId
     * @param $openId
     * @return array
     */
    public function getUserInfo($tokenId,$openId)
    {
        try{
            $this->QC->setToken($tokenId);
            $this->QC->setOpenId($openId);
            $userInfo=$this->QC->get_user_info();
            return Code::statusDataReturn(Code::SUCCESS,$userInfo);
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
    public function callBackGetToken($csrf,$code){

        try{
            $accessTokenUrl='https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->appId.
                '&secret='.$this->secret.
                '&code='.$code.
                '&grant_type=authorization_code';

            //-------请求参数列表
            $keysArr = array(
                "appid" => $this->appId,
                "secret" => $this->secret,
                "code" => $code,
                "grant_type" => 'authorization_code'
            );

            //------构造请求access_token的url

            $token_url = UrlUtil::combineURL($accessTokenUrl, $keysArr);
            $response = $this->urlUtils->get_contents($token_url);
            var_dump($response);exit;
            return Code::statusDataReturn(Code::SUCCESS,$token);
        }catch (Exception $e){
            return Code::statusDataReturn(Code::FAIL,$e->getMessage());
        }

    }



}