<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/3
 * Time : 上午11:34
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\components;

/**
 * 基础验证类
 * Class Validate
 * @package common\components
 */
class Validate {


    public static function validatePhone($phone)
    {
        $error='';
        if(empty($phone))
        {
            $error='手机格式不正确';
        }
        if(!is_numeric($phone)){
            $error='手机格式不正确';
        }
        if(strlen($phone)<6){
            $error='手机格式不正确';
        }
        return $error;
    }

    public static function validateEmail($email)
    {
        $error='';
        if(!preg_match("/([a-z0-9]*[-_\\.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\\.][a-z]{2,3}([\\.][a-z]{2})?/i",$email))
        {
            $error='邮箱格式不正确';
        }
        return $error;
    }

    public static  function validatePassword($password)
    {
        $error='';
        if(empty($password)||strlen($password)<6||strlen($password)>30){
            $error='密码格式不正确';
        }
        return $error;
    }

    public static function validateNickname($nickname)
    {
        $error='';
        if(empty($nickname)||strlen($nickname)>30){
            $error='昵称格式不正确';
        }
        return $error;
    }



}