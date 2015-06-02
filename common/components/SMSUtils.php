<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/27
 * Time : 上午10:08
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\components;

use yii\base\Exception;

include_once("../../vendor/sms/SDK/CCPRestSDK.php");


class SmsUtils
{

    /**
     * 注册
     */
    const SEND_MESSAGE_TYPE_REGISTER=1;

    /**
     * 忘记密码
     */
    const SEND_MESSAGE_TYPE_PASSWORD=2;

    //主帐号
    private $accountSid;

    //主帐号Token
    private $accountToken;

    //应用Id
    private $appId;

    //请求地址，格式如下，不需要写https://
    private $serverIP;

    //请求端口
    private $serverPort;

    //REST版本号
    private $softVersion = '2013-12-26';

    //Rest实例
    private $rest;

    //验证码有效期
    private $validateTime;



    private $foreignUsername;

    private $foreignPassword;


    public function __construct()
    {
        $this->accountSid = \Yii::$app->params['sms_account_sid'];
        $this->accountToken = \Yii::$app->params['sms_account_token'];
        $this->appId = \Yii::$app->params['sms_account_app_id'];
        $this->serverIP = \Yii::$app->params['sms_account_server_ip'];
        $this->serverPort = \Yii::$app->params['sms_account_server_port'];
        $this->softVersion = \Yii::$app->params['sms_account_soft_version'];

        $this->foreignUsername=\Yii::$app->params['sms_foreign_username'];
        $this->foreignPassword=\Yii::$app->params['sms_foreign_password'];

        $this->validateTime = Code::USER_PHONE_VALIDATE_CODE_EXPIRE_TIME / 60;//将秒转换为分钟

        // 初始化REST SDK
        $this->rest = new \REST($this->serverIP, $this->serverPort, $this->softVersion);
        $this->rest->setAccount($this->accountSid, $this->accountToken);
        $this->rest->setAppId($this->appId);
    }

    /**
     * 发送模板短信
     * @param 手机号码集合 $to 手机号码集合 $to ,用英文逗号分开
     * @param $code
     * @param $type
     * @return array|void
     */
    private function sendChinaSMS($to, $code,$type)
    {
        // $datas 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
        try {
            $datas = [$code, $this->validateTime];
            //param 模板Id $tempId (测试为1)
            $tempId=1;
            if($type==self::SEND_MESSAGE_TYPE_REGISTER){
                $tempId = 21176;
            }else if($type==self::SEND_MESSAGE_TYPE_PASSWORD){
                $tempId = 20766;
            }

            // 发送模板短信
            $result = $this->rest->sendTemplateSMS($to, $datas, $tempId);
            if ($result == NULL) {
                return Code::statusDataReturn(Code::FAIL, "result error!");
            }
            if ($result->statusCode != 0) {
                return Code::statusDataReturn(Code::FAIL, $result->statusMsg);
            } else {
                // 获取返回信息
                $smsMessage = $result->TemplateSMS;
                return Code::statusDataReturn(Code::SUCCESS, $smsMessage);
            }
        } catch (Exception $e) {
            return Code::statusDataReturn(Code::FAIL, $e->getName());
        }

    }


    /**
     * 海外验证码发送
     * @param $phone
     * @param $areaCode
     * @param $code
     * @param $type
     * @return array
     */
    private function sendForeignMessage($phone,$areaCode,$code,$type)
    {
        $message="";
        if($type==self::SEND_MESSAGE_TYPE_REGISTER){
            $message ="Thank you for registering with Suiuu, your verification code is ".$code;
        }else if($type==self::SEND_MESSAGE_TYPE_PASSWORD){
            $message ="Password Code Is ".$code;
        }
        $data = array (
            'src' => $this->foreignUsername, // 用户名   与登录用户名相同
            'pwd' => $this->foreignPassword, // 你的密码 与登录用户密码相同
            'ServiceID' => 'SEND',
            'dest' => $areaCode.$phone, // 你的目的号码 手机号码之间必须用英文逗号分割,最后一个手机号后不加逗号, 群发时一次最多可以同时提交100个号码
            'codec' => '3', // 编码  8 (BigEndianUnicode)是中文、韩文、日文等 ，3 (ISO-8859-1)是英文、拉丁文0:(ASCII)
            'msg' => $this->encodeHexStr(3,$message)
        );
        try{
            $uri = "http://210.51.190.233:8085/mt/mt3.ashx";
            $ch = curl_init();
            curl_setopt ( $ch, CURLOPT_URL, $uri );
            curl_setopt ( $ch, CURLOPT_POST, 1 );
            curl_setopt ( $ch, CURLOPT_HEADER, 0 );
            curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
            curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );

            $return = curl_exec ( $ch );
            curl_close ( $ch );
            if($return>0){
                return Code::statusDataReturn(Code::SUCCESS);
            }else{
                return Code::statusDataReturn(Code::FAIL,$return);
            }
        }catch (Exception $e){
            return Code::statusDataReturn(Code::FAIL,$e);
        }

    }

    /**
     * 编码
     * @param $dataCoding
     * @param $realStr
     * @return string
     */
    private function encodeHexStr($dataCoding, $realStr){

        if ($dataCoding == 15)
        {
            return strtoupper(bin2hex(iconv('UTF-8', 'GBK', $realStr)));
        }
        else if ($dataCoding == 3)
        {
            return strtoupper(bin2hex(iconv('UTF-8', 'ISO-8859-1', $realStr)));
        }
        else if ($dataCoding == 8)
        {
            return strtoupper(bin2hex(iconv('UTF-8', 'UCS-2', $realStr)));
        }
        else
        {
            return strtoupper(bin2hex(iconv('UTF-8', 'ASCII', $realStr)));
        }
    }


    /**
     * 发送短信统一入口
     * @param $phone
     * @param $areaCode
     * @param $code
     * @param $type
     * @return array|void
     * @throws Exception
     */
    public function sendMessage($phone,$areaCode,$code,$type)
    {
        $ip = $_SERVER["REMOTE_ADDR"];
        try{
            if(empty($phone)){
                throw new Exception("Phone Is Not Allow Empty");
            }
            if(empty($areaCode)){
                throw new Exception("AreaCode Is Not Allow Empty");
            }
            if(empty($code)){
                throw new Exception("Code Is Not Allow Empty");
            }

            $areaCode=trim(str_replace("+","",$areaCode));
            $ipCount=\Yii::$app->redis->get(Code::USER_SEND_MESSAGE_IP.$ip);
            if(empty($ipCount)){
                \Yii::$app->redis->set(Code::USER_SEND_MESSAGE_IP . $ip, 1);
                \Yii::$app->redis->expire(Code::USER_SEND_MESSAGE_IP . $ip, Code::USER_SEND_MESSAGE_IP_EXPIRE_TIME);
            }else{
                if($ipCount>Code::USER_SEND_MESSAGE_IP_COUNT){
                    return Code::statusDataReturn(Code::FAIL,"IP 超出发送次数");
                }
                \Yii::$app->redis->set(Code::USER_SEND_MESSAGE_IP . $ip, ++$ipCount);
            }
            if($areaCode=='0086'||$areaCode=='86'){
                return $this->sendChinaSMS($phone,$code,$type);
            }else{
                return $this->sendForeignMessage($phone,$areaCode,$code,$type);
            }
        }catch (Exception $e){
            return Code::statusDataReturn(Code::FAIL,$e);
        }
    }


    /**
     * @param $phoneList[['areaCode'=>'','phone'=>phone]]
     * @param $type
     * @param $paramList
     */
    public function sendContentMessage($phoneList,$type,$paramList)
    {

        foreach($phoneList as $phoneInfo)
        {
            if($phoneInfo['areaCode']=="+86"||$phoneInfo['areaCode']=="0086"){

            }
        }
    }


    public function sendPublisherCancelOrderMessage($phoneList,$type,$param)
    {

    }
}

