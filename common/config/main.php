<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'timeZone' => 'Asia/Chongqing',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<controller:view-trip>/<action:info>/<trip:\d+>.html'=>'<controller>/<action>',
                '<controller:view-user>/<action:info>/<u:\w+>.html'=>'<controller>/<action>',
                '<controller:volunteer>/<action:view>/<vId:\d+>.html'=>'<controller>/<action>'
            ],
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 0,
        ],
        'session' => array(
            'class' => 'yii\web\Session',
            'cookieParams' => ['domain' => '.' . "suiuu.com", 'lifetime' => 0],
        ),
    ],
];
