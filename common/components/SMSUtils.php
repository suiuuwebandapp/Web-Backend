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

    public function __construct()
    {
        $this->accountSid = \Yii::$app->params['sms_account_sid'];
        $this->accountToken = \Yii::$app->params['sms_account_token'];
        $this->appId = \Yii::$app->params['sms_account_app_id'];
        $this->serverIP = \Yii::$app->params['sms_account_server_ip'];
        $this->serverPort = \Yii::$app->params['sms_account_server_port'];
        $this->softVersion = \Yii::$app->params['sms_account_soft_version'];

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
     * @return array|void
     */
    public function sendRegisterSMS($to, $code)
    {
        // $datas 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
        try {
            $datas = [$code, $this->validateTime];
            //param 模板Id $tempId (测试为1)
            $tempId = 1;
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
     * 发送找回密码短信
     * @param 手机号码集合 $to 手机号码集合 $to ,用英文逗号分开
     * @param $code
     * @return array|void
     */
    public function sendPasswordSMS($to, $code)
    {
        // $datas 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
        try {
            $datas = [$code, $this->validateTime];
            //param 模板Id $tempId (测试为1)
            $tempId = 1;
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
                return Code::statusDataReturn(Code::SUCCESS,'success');
            }
        } catch (Exception $e) {
            return Code::statusDataReturn(Code::FAIL, $e->getName());
        }

    }
    //Demo调用
    //sendTemplateSMS("手机号码","内容数据","模板Id");

}

