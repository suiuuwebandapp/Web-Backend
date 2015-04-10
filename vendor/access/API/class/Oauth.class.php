<?php
/* PHP SDK
 * @version 2.0.0
 * @author connect@qq.com
 * @copyright © 2013, Tencent Corporation. All rights reserved.
 */

require_once(CLASS_PATH."Recorder.class.php");
require_once(CLASS_PATH."URL.class.php");
require_once(CLASS_PATH."ErrorCase.class.php");

class Oauth{

    const VERSION = "2.0";
    const GET_AUTH_CODE_URL = "https://graph.qq.com/oauth2.0/authorize";
    const GET_ACCESS_TOKEN_URL = "https://graph.qq.com/oauth2.0/token";
    const GET_OPENID_URL = "https://graph.qq.com/oauth2.0/me";

    public $urlUtils;
    protected $error;


    /**
     * @var Int Id
     */
    public $appId;

    /**
     * @var String Key
     */
    public $appKey;

    /**
     * @var String 回调方法
     */
    public $callBack;

    /**
     * @var String 作用域
     */
    public $scope;

    /**
     * @var String CSRF验证
     */
    public $state;


    function __construct(){

        $this->appId=Yii::$app->params['qq_app_id'];
        $this->appKey=Yii::$app->params['qq_app_key'];
        $this->callBack=Yii::$app->params['qq_callback'];
        $this->urlUtils = new URL();
        $this->error = new ErrorCase();
    }

    public function qq_login(){
        $appId = $this->appId;
        $callback = $this->callBack;
        $scope = $this->scope;

        //-------生成唯一随机串防CSRF攻击
        $state = md5(uniqid(rand(), TRUE));
        Yii::$app->session->set('state',$state);

        //-------构造请求参数列表
        $keysArr = array(
            "response_type" => "code",
            "client_id" => $appId,
            "redirect_uri" => $callback,
            "state" => $state,
            "scope" => $scope
        );

        $login_url =  $this->urlUtils->combineURL(self::GET_AUTH_CODE_URL, $keysArr);

        header("Location:$login_url");
    }


    /**
     * 回调获取 accessToken
     * @param $csrf
     * @param $code
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function qq_callback($csrf,$code){
        $state=Yii::$app->session->get('state');

        //--------验证state防止CSRF攻击
        if($csrf != $state){
            $this->error->showError("30001");
        }

        //-------请求参数列表
        $keysArr = array(
            "grant_type" => "authorization_code",
            "client_id" => $this->appId,
            "redirect_uri" => $this->callBack,
            "client_secret" => $this->appKey,
            "code" => $code
        );

        //------构造请求access_token的url
        $token_url = $this->urlUtils->combineURL(self::GET_ACCESS_TOKEN_URL, $keysArr);
        $response = $this->urlUtils->get_contents($token_url);

        if(strpos($response, "callback") !== false){

            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response  = substr($response, $lpos + 1, $rpos - $lpos -1);
            $msg = json_decode($response);

            if(isset($msg->error)){
                $this->error->showError($msg->error, $msg->error_description);
            }
        }

        $params = array();
        parse_str($response, $params);

        return $params["access_token"];

    }

    public function get_openid($accessToken){

        //-------请求参数列表
        $keysArr = array(
            "access_token" => $accessToken
        );

        $graph_url = $this->urlUtils->combineURL(self::GET_OPENID_URL, $keysArr);
        $response = $this->urlUtils->get_contents($graph_url);

        //--------检测错误是否发生
        if(strpos($response, "callback") !== false){

            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response = substr($response, $lpos + 1, $rpos - $lpos -1);
        }

        $user = json_decode($response);
        if(isset($user->error)){
            $this->error->showError($user->error, $user->error_description);
        }

        return $user->openid;
    }
}
