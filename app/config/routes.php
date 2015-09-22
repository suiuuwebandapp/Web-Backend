<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/8/13
 * Time: 上午10:19
 */
return [
    'PUT,PATCH users/<id>' => 'user/update',
    'DELETE users/<id>' => 'user/delete',
    'GET,HEAD users/<id>' => 'user/view',
    'POST users' => 'user/create',
    'GET,HEAD users' => 'user/index',
    'users/<id>' => 'user/options',
    'users' => 'user/options',
];