<?php
return [
    'adminEmail' => 'admin@example.com',
    'redis'=>[
        ['host'=>'localhost', 'port'=>6379],
    ],
    //基础信息
    'base_dir' => 'http://www.suiuu.com',//'base_dir' => 'http://www.suiuu.com'
    'url'=>'www.suiuu.com',//'url'=>'www.suiuu.com'

    //www
    'www_suiuu_sign'=>'www_suiuu_sign',
    //app
    'app_suiuu_sign'=>'app_suiuu_sign',
    'app_circle_article_img'=>'http://image.suiuu.com/suiuu_content',
    //Cookie
    'suiuu_sign'=> 'suiuu_sign',
    'cookie_domain' => '/',
    'cookie_expire' => 5,

    //加密API
    'emailEncryptPassword'=>'$I#(*@)*#$sui@@@UU.com))*@@#',
    'encryptPassword' => '^&^*^&^%%@@@-@##%^##$#@$#@iuu@#!%$#78@$@(-',
    'encryptDigit' => '256',
    'encryptChange' => 0,
];
