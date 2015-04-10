<?php
return [
    'adminEmail' => 'xin.zhang@suiuu.com',
    'name'=>'随游',
    'version'=>'beat 1.0',
    'copyright'=>' 2015 &copy; Suiuu. Admin Dashboard Template.',
    'language'=>'zh-cn',
    'sourceLanguage'=>'zh-cn',
    'defaultController'=>'index',
    'charset'=>'utf-8',

    //基础信息
    'base_dir' => 'http://local.suiuu.com',
    'url'=>'local.suiuu.com',
    //Cookie
    'suiuu_sign'=> 'suiuu_sign',
    'cookie_domain' => '/',
    'cookie_expire' => 5,

    //加密API
    'emailEncryptPassword'=>'$I#(*@)*#$sui@@@UU.com))*@@#',
    'encryptPassword' => '^&^*^&^%%@@@-@##%^##$#@$#@iuu@#!%$#78@$@(-',
    'encryptDigit' => '256',
    'encryptChange' => 0,


    'moduleEncrypt'=> '!@!##@$@$@mqt_-%Et+gE2H-2ue+~)Jm!K}{hP,bmu',
    'AJAX_SALT'=>'XRpI9ocUJmd9JxRcC2L*&5L2ugHF1aEa%@#@#@!!@',

    'url_key'=>'&*^jk983^^%$@;@@@@#@!',  //url加密


    'token_url'=>'&^ie:84Oke@#@$$@%@@', //防止盗链

    //access（第三方接入）

    //微博
    'weibo_app_key'=>'2541477432',
    'weibo_app_secret'=>'606307461e286842fe941dfbf4e92b01',
    'weibo_auth_callback'=>'http://local.suiuu.com/access/weibo-login',
    'weibo_auth_exit_call_back'=>'http://local.suiuu.com/access/weibo-logout',

    //qq
    'qq_app_id'=>'101206430',
    'qq_app_key'=>'a80bf172ce9c35c3822363af5b38c741',
    'qq_callback'=>'http://local.suiuu.com/access/qq-login',

    //缓存
    'redis'=>[
        ['host'=>'localhost', 'port'=>6379],
    ],
];
