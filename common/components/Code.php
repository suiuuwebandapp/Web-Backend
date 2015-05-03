<?php
namespace common\components;

use Faker\Provider\Uuid;
use yii\base\Exception;
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/1
 * Time : 上午10:18
 * Email: zhangxinmailvip@foxmail.com
 */

/**
 * 返回状态码和部分系统常量
 * Class Code
 */
class Code
{


    //***************************************************通用************************************************************

    /**
     * 错误码
     */
    const SUCCESS = 1;
    const FAIL = -1;
    const PARAMS_ERROR = -2;
    const UN_LOGIN = -3;
    /**
     * 请求错误信息
     */
    const REQUEST_FAIL = '请求失败';
    const UPLOAD_YUN_OSS_ERROR='上传文件至云OSS失败';

    //***************************************************APP************************************************************
    /**
     *系统常量（APP）
     */
    const APP_USER_LOGIN_SESSION='A_U_L_S';//用户是否登录 SESSION KEY
    const APP_USER_LOGIN_ERROR_COUNT_PREFIX='A_U_L_C_P';//用户登录错误次数前缀 使用方法 前缀+用户名
    const APP_USER_LOGIN_VERIFY_CODE='A_U_L_V_C'; //用户登录验证码 SESSION KEY
    const APP_USER_LOGIN_VERIFY_CODE_EXPIRE_TIME='86400';

    const APP_USER_LOGOUT_SUCCESS_STR='注销成功';
    const APP_USER_LOGOUT_FAIL_STR='注销失败';
    //***************************************************前台************************************************************

    /**
     *系统常量（前台）
     */
    const USER_NAME_SESSION = 'U_N_S';//用户名session
    const USER_LOGIN_SESSION='U_L_S';//用户是否登录 SESSION KEY
    const USER_LOGIN_ERROR_COUNT_PREFIX='U_L_C_P';//用户登录错误次数前缀 使用方法 前缀+用户名
    const USER_LOGIN_VERIFY_CODE='U_L_V_C'; //用户登录验证码 SESSION KEY
    const USER_LOGIN_VERIFY_CODE_EXPIRE_TIME='86400';
    const USER_SEND_ERROR_COUNT_PREFIX='U_S_E_C_P';//用户发送错误次数前缀 使用方法 前缀+用户名
    const USER_SEND_COUNT_PREFIX='U_S_C_P';//用户发送次数 使用方法 前缀+用户名
    const MAX_SEND_COUNT=10;//每天最大发送数
    const USER_PHONE_VALIDATE_CODE_AND_PHONE_FOR_PASSWORD='U_P_V_C_A_P_F_P';//用户发送的手机验证码找回密码
    const USER_PHONE_VALIDATE_CODE_AND_PHONE='U_P_V_C_A_P';//用户发送的手机验证码
    const USER_PHONE_VALIDATE_CODE_EXPIRE_TIME='600';//验证码有效期 10分钟
    const USER_EMAIL_VALIDATE_CODE_EXPIRE_TIME='86400';//邮箱验证有效时长 (24小时)

    const USER_REGISTER_TIMER=120;//用户注册手机或邮件时长（秒）
    const USER_REGISTER_EMAIL_TIMER='U_R_E_T';//用户注册邮件时间控制
    const USER_REGISTER_PHONE_TIMER='U_R_P_T';//用户注册手机时间控制


    const USER_COMMENT_SUPPORT='USER_COMMENT_SUPPORT';


    /**
     * 异常信息（后台）
     */
    const SYSTEM_EXCEPTION='系统未知异常,请与管理员联系';
    const USER_PHONE_EXISTED='此手机号码已经注册';
    const USER_EMAIL_EXISTED='此邮箱地址已经注册';
    const USER_PHONE_CODE_ERROR='无效的验证码或验证码已经失效';

    //***************************************************后台************************************************************
    /**
     *系统常量（后台）
     */
    const SYS_USER_LOGIN_SESSION='S_U_L_S';//用户是否登录 SESSION KEY
    const SYS_USER_LOGIN_ERROR_COUNT_PREFIX='S_U_L_C_P';//用户登录错误次数前缀 使用方法 前缀+用户名
    const SYS_USER_LOGIN_VERIFY_CODE='S_U_L_V_C'; //用户登录验证码 SESSION KEY
    const SYS_USER_LOGIN_VERIFY_CODE_EXPIRE_TIME='86400';

    /**
     * 标签key Tag Redis Key
     */
    const SYS_TAGS_REDIS_KEY='S_T_R_K';


    /**
     * 异常信息（后台）
     */
    const SYS_SYSTEM_EXCEPTION='系统未知异常,请与管理员联系';
    const SYS_USER_NAME_EXISTED='用户名已存在';



    /**
     * 登录
     */
    const SYS_LOGIN_ERROR_COUNT = 3;  //三次错误出现验证码
    const MAX_LOGIN_ERROR_COUNT=20;//连续出现次数 则用户当日不能登录
    /**
     * 手机号为空
     */
    const USER_PHONE_NULL = -3;

    const USER_ACCOUNT_TRUE = 3;

    const LOGIN_ERROR = -4; //登录失败
    const USER_EMAIL_NULL = -5;
    const LOGIN_EXCEPTION = -6;//登录异常

    const REGISTER_IP_SUM = 50;  //一天一个ip注册几次
    const REGISTER_IP_NUM = -7; //一天注册ip上限
    const REGISTER_IP_MESSAGE = "此ip当天注册上限！！";
    const REGISTER_IP_BLACK = "此ip被封，请联系管理员解封！！";



    /**
     * 错误信息
     */
    const USER_PHONE_EMAIL_EMPTY = '用户手机或者邮箱不能为空';
    const USER_PHONE_EMAIL_ERROR = '用户手机或者邮箱错误';
    const USER_NAME_ERROR = '用户名错误';
    const USER_EMAIL_ERROR = '用户邮箱错误';
    const USER_EMAIL_EXIST = '此邮箱已存在';
    const USER_EMAIL_EXIST_NOT = '邮箱不存在';
    const USER_PHONE_ERROR = '用户手机号码错误';
    const USER_PHONE_EXIST = '用户手机号码存在';
    const USER_PHONE_EXIST_NOT = '用户手机号码不存在';
    const USER_IS_NULL = '用户基本信息为空';
    const USER_PASSWORD_EMPTY = '密码不能为空';
    const CODE_EMPTY = '验证码不能为空';
    const CODE_ERROR = '验证码输入错误';
    const CODE_NULL = '请输入验证码';
    const USER_PASSWORD_ERROR = "用户名或密码错误";
    const USER_LOGIN_ERROR_NUM = "登录失败次数";
    const LOGIN_FAIL = '登陆失败';
    const USER_IM_REGISTER_ERROR='注册环信im出错';


    /**
     * order error info
     */
    const ORDER_NUMBER_NULL = '订单编号为空';
    const ORDER_MONEY_NULL = '订单金额为空';
    const ORDER_UNIT_NULL = '订单单元id为空';



    /**
     * 返回数据和状态
     * @param $infoCode       信息码
     * @param string $data 返回的数据
     * @param string $msg 返回的消息
     * @return array
     */
    public static function statusDataReturn($infoCode, $data = '', $msg = '')
    {
        return [
            'status' => $infoCode,
            'data' => $data,
            'message' => $msg,
            'token' => md5(json_encode($infoCode).json_encode($data).json_encode($msg).\Yii::$app->params['apiPassword']),
        ];

    }

    //替换数据库插入特殊字符
    public static function TextReplace($str)
    {
        $str = str_replace("'", "|", $str);
        return $str;
    }

    public static function TextReplaceR($str)
    {
        $str = str_replace("|", "'", $str);
        return $str;
    }

    public static function showException(Exception $e)
    {
        echo '<pre>';
        var_dump($e->getTrace());
        echo '</pre>';
        exit;
    }

    public static function getUUID()
    {
        return  str_replace('-','',Uuid::uuid());
    }


}