<?php
use backend\components\SecurityCode;

/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/1
 * Time : 下午3:54
 * Email: zhangxinmailvip@foxmail.com
 */


//先把类包含进来，实际路径根据实际情况进行修改。
require '../components/SecurityCode.php';
require '../../common/components/Code.php';

$securityCode=new SecurityCode();
$securityCode->doimg();//生成验证码，并且加入Session
