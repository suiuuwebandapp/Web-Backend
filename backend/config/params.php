<?php

//中华人民共和国时间@ma.Q
date_default_timezone_set('PRC');

return [
    'adminEmail' => 'xin.zhang@suiuu.com',
    'name'=>'随游后台管理系统',
    'version'=>'beat 1.0',
    'copyright'=>' &copy; 2015 Suiuu.com All Rights Reserved. 沪ICP备15030059号-1',
    'language'=>'zh-cn',
    'sourceLanguage'=>'zh-cn',
    'defaultController'=>'index',
    'charset'=>'utf-8',

    //基础信息
    'base_dir' => 'http://sys.suiuu.com',
    'url'=>'sys.suiuu.com',

    //前台url
    'suiuu_url' => 'http://www.suiuu.com',
    //app圈子图片目录
    'app_circle_img_url'=>'http://image.suiuu.com/suiuu_content',
    //资源URL
    'res_url'=>'',//http://image.suiuu.com/suiuu_sys_resources
    //Cookie
    'sys_suiuu_sign'=> 'sys_suiuu_sign',
    'cookie_domain' => '/',
    'cookie_expire' => 5,

    //加密
    'encryptPassword' => '-@##%^##$#@$#@iuu@#!%$#78@$@(-',
    'encryptDigit' => '256',
    'encryptChange' => 0,

    'moduleEncrypt'=> 'mqt_-%Et+gE2H-2ue+~)Jm!K}{hP,bmu',
    'AJAX_SALT'=>'XRpI9ocUJmd9JxRcC2L*&5L2ugHF1aEa%',

    'url_key'=>'&*^jk983^^%$@;',  //url加密


    'token_url'=>'&^ie:84Oke', //防止盗链

    //缓存
    'redis'=>[
        ['host'=>'localhost', 'port'=>6379],
    ],
];
