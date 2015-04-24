<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/8
 * Time : 下午5:11
 * Email: zhangxinmailvip@foxmail.com
 */
namespace frontend\interfaces;

use common\components\Code;
use yii\base\Exception;


/**
 * @ignore
 */
class OAuthException extends Exception {
    // pass
}

class WeiboInterface {


    /**
     * @ignore
     */
    public $client_id;
    /**
     * @ignore
     */
    public $client_secret;
    /**
     * @ignore
     */
    public $call_back_url;
    /**
     * @ignore
     */
    public $access_token;
    /**
     * @ignore
     */
    public $refresh_token;
    /**
     * Contains the last HTTP status code returned.
     *
     * @ignore
     */
    public $http_code;
    /**
     * Contains the last API call.
     *
     * @ignore
     */
    public $url;
    /**
     * Set up the API root URL.
     *
     * @ignore
     */
    public $host = "https://api.weibo.com/2/";
    /**
     * Set timeout default.
     *
     * @ignore
     */
    public $timeout = 30;
    /**
     * Set connect timeout.
     *
     * @ignore
     */
    public $connect_timeout = 30;
    /**
     * Verify SSL Cert.
     *
     * @ignore
     */
    public $ssl_verify_peer = FALSE;
    /**
     * Response format.
     *
     * @ignore
     */
    public $format = 'json';
    /**
     * Decode returned json data.
     *
     * @ignore
     */
    public $decode_json = TRUE;
    /**
     * Contains the last HTTP headers returned.
     *
     * @ignore
     */
    public $http_info;

    /**
     * print the debug info
     *
     * @ignore
     */
    public $debug = FALSE;

    /**
     * boundary of multipart
     * @ignore
     */
    public static $boundary = '';


    /**
     * Set API URLS
     */
    /**
     * @ignore
     */
    private function accessTokenURL()  { return 'https://api.weibo.com/oauth2/access_token'; }
    /**
     * @ignore
     */
    private function authorizeURL()    { return 'https://api.weibo.com/oauth2/authorize'; }

    /**
     * WeiboOAuth object
     *
     * @param null $access_token
     * @param null $refresh_token
     */
    public function __construct($access_token = NULL, $refresh_token = NULL) {
        $this->client_id = \Yii::$app->params['weibo_app_key'];
        $this->client_secret = \Yii::$app->params['weibo_app_secret'];
        $this->call_back_url=\Yii::$app->params['weibo_auth_callback'];
        $this->access_token = $access_token;
        $this->refresh_token = $refresh_token;
    }


    public function toConnectWeibo()
    {
        $url=$this->getAuthorizeURL();
        header("Location:$url");
    }



    /**
     * authorize接口
     *
     * 对应API：{@link http://open.weibo.com/wiki/Oauth2/authorize Oauth2/authorize}
     *
     * @param string $response_type 支持的值包括 code 和token 默认值为code
     * @param string $state 用于保持请求和回调的状态。在回调时,会在Query Parameter中回传该参数
     * @param string $display 授权页面类型 可选范围:
     *  - default		默认授权页面
     *  - mobile		支持html5的手机
     *  - popup			弹窗授权页
     *  - wap1.2		wap1.2页面
     *  - wap2.0		wap2.0页面
     *  - js			js-sdk 专用 授权页面是弹窗，返回结果为js-sdk回掉函数
     *  - app_on_weibo	站内应用专用,站内应用不传display参数,并且response_type为token时,默认使用改display.授权后不会返回access_token，只是输出js刷新站内应用父框架
     * @return array
     */
    public function getAuthorizeURL($response_type = 'code', $state = NULL, $display = NULL ) {
        $params = array();
        $params['client_id'] = $this->client_id;
        $params['redirect_uri'] = $this->call_back_url;
        $params['response_type'] = $response_type;
        $params['state'] = $state;
        $params['display'] = $display;
        return $this->authorizeURL() . "?" . http_build_query($params);
    }


    /**
     * 获取微博用户Uid
     *
     * 对应API：{@link http://open.weibo.com/wiki/OAuth2/access_token OAuth2/access_token}
     * @param $code
     * @return array
     */
    public function getWeiboUid($code) {
        if(empty($code)){
            return Code::statusDataReturn(Code::PARAMS_ERROR,'$Code 不允许为空');
        }
        $params = array();
        $params['client_id'] = $this->client_id;
        $params['client_secret'] = $this->client_secret;
        $params['grant_type'] = 'authorization_code';
        $params['code'] = $code;
        $params['redirect_uri'] = $this->call_back_url;

        $response = $this->oAuthRequest($this->accessTokenURL(), 'POST', $params);
        $token = json_decode($response, true);
        if ( is_array($token) && !isset($token['error']) ) {
            $this->access_token = $token['access_token'];
            return Code::statusDataReturn(Code::SUCCESS,$token['uid']);
        } else {
            return Code::statusDataReturn(Code::FAIL,$token['error']);
        }
    }


    /**
     * 根据用户UID或昵称获取用户资料
     *
     * 按用户UID或昵称返回用户资料，同时也将返回用户的最新发布的微博。
     * <br />对应API：{@link http://open.weibo.com/wiki/2/users/show users/show}
     *
     * @access public
     * @param int  $uid 用户UID。
     * @return array
     */
    function getUserById( $uid )
    {
        if(empty($uid)){
            return Code::statusDataReturn(Code::PARAMS_ERROR,'$uid 不允许为空');
        }
        $params=array();
        if ( $uid !== NULL ) {
            $this->id_format($uid);
            $params['uid'] = $uid;
        }
        $result=$this->get('users/show', $params);
        if ( is_array($result) && !isset($result['error']) ) {
            return Code::statusDataReturn(Code::SUCCESS,$result);
        } else {
            return Code::statusDataReturn(Code::FAIL,$result['error']);
        }
    }


    /**
     * 用户Id 格式化
     * @ignore
     */
    protected function id_format(&$id) {
        if ( is_float($id) ) {
            $id = number_format($id, 0, '', '');
        } elseif ( is_string($id) ) {
            $id = trim($id);
        }
    }

    /**
     *
     * Format and sign an OAuth / API request
     *
     * @param $url
     * @param $method
     * @param $parameters
     * @param bool $multi
     * @return string
     */
    private function oAuthRequest($url, $method, $parameters, $multi = false) {

        if (strrpos($url, 'http://') !== 0 && strrpos($url, 'https://') !== 0) {
            $url = "{$this->host}{$url}.{$this->format}";
        }

        switch ($method) {
            case 'GET':
                $url = $url . '?' . http_build_query($parameters);
                return $this->http($url, 'GET');
            default:
                $headers = array();
                if (!$multi && (is_array($parameters) || is_object($parameters)) ) {
                    $body = http_build_query($parameters);
                } else {
                    $body = self::build_http_query_multi($parameters);
                    $headers[] = "Content-Type: multipart/form-data; boundary=" . self::$boundary;
                }
                return $this->http($url, $method, $body, $headers);
        }
    }

    /**
     * @ignore
     */
    public static function build_http_query_multi($params) {
        if (!$params) return '';

        uksort($params, 'strcmp');

        self::$boundary = $boundary = uniqid('------------------');
        $MP_boundary = '--'.$boundary;
        $endMP_boundary = $MP_boundary. '--';
        $multipart_body = '';

        foreach ($params as $parameter => $value) {

            if( in_array($parameter, array('pic', 'image')) && $value{0} == '@' ) {
                $url = ltrim( $value, '@' );
                $content = file_get_contents( $url );
                $array = explode( '?', basename( $url ) );
                $filename = $array[0];

                $multipart_body .= $MP_boundary . "\r\n";
                $multipart_body .= 'Content-Disposition: form-data; name="' . $parameter . '"; filename="' . $filename . '"'. "\r\n";
                $multipart_body .= "Content-Type: image/unknown\r\n\r\n";
                $multipart_body .= $content. "\r\n";
            } else {
                $multipart_body .= $MP_boundary . "\r\n";
                $multipart_body .= 'content-disposition: form-data; name="' . $parameter . "\"\r\n\r\n";
                $multipart_body .= $value."\r\n";
            }

        }

        $multipart_body .= $endMP_boundary;
        return $multipart_body;
    }

    /**
     * Make an HTTP request 发送一个HTTP 请求
     *
     * @param $url
     * @param $method
     * @param null $post_fields
     * @param array $headers
     * @return mixed
     */
    private function http($url, $method, $post_fields = NULL, $headers = array()) {
        $this->http_info = array();
        $ci = curl_init();
        /* Curl settings */
        curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connect_timeout);
        curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ci, CURLOPT_ENCODING, "");
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verify_peer);
        curl_setopt($ci, CURLOPT_HEADER, FALSE);

        switch ($method) {
            case 'POST':
                curl_setopt($ci, CURLOPT_POST, TRUE);
                if (!empty($post_fields)) {
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $post_fields);
                    $this->postdata = $post_fields;
                }
                break;
            case 'DELETE':
                curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
                if (!empty($post_fields)) {
                    $url = "{$url}?{$post_fields}";
                }
        }

        if ( isset($this->access_token) && $this->access_token )
            $headers[] = "Authorization: OAuth2 ".$this->access_token;


        curl_setopt($ci, CURLOPT_URL, $url );
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers );
        curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE );

        $response = curl_exec($ci);
        $this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
        $this->http_info = array_merge($this->http_info, curl_getinfo($ci));
        $this->url = $url;

        if ($this->debug) {
            echo "=====post data======\r\n";
            var_dump($post_fields);

            echo "=====headers======\r\n";
            print_r($headers);

            echo '=====request info====='."\r\n";
            print_r( curl_getinfo($ci) );

            echo '=====response====='."\r\n";
            print_r( $response );
        }
        curl_close ($ci);
        return $response;
    }

    /**
     * GET for oAuthRequest.
     *
     * @param $url
     * @param array $parameters
     * @return mixed|string
     */
    function get($url, $parameters = array()) {
        $response = $this->oAuthRequest($url, 'GET', $parameters);
        if ($this->format === 'json' && $this->decode_json) {
            return json_decode($response, true);
        }
        return $response;
    }

    /**
     * POST for oAuthRequest.
     *
     * @param $url
     * @param array $parameters
     * @param bool $multi
     * @return mixed|string
     */
    function post($url, $parameters = array(), $multi = false) {
        $response = $this->oAuthRequest($url, 'POST', $parameters, $multi );
        if ($this->format === 'json' && $this->decode_json) {
            return json_decode($response, true);
        }
        return $response;
    }

    /**
     * DELETE for oAuthRequest.
     *
     * @param $url
     * @param array $parameters
     * @return mixed|string
     */
    function delete($url, $parameters = array()) {
        $response = $this->oAuthRequest($url, 'DELETE', $parameters);
        if ($this->format === 'json' && $this->decode_json) {
            return json_decode($response, true);
        }
        return $response;
    }

}