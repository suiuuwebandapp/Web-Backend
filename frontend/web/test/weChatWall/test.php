<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/8/3
 * Time: 下午2:58
 */
require_once "WeiXinUtil.php";
$t= new WeiXinUtil();
$t->GetMessage();
exit;
$t->login('suiuu_service@163.com','Suiuu123');
