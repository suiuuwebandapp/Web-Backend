<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/6/5
 * Time : 下午3:22
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\components;


use yii\base\Exception;
use yii\web\ErrorHandler;

class LogUtils {

    public static function log(Exception $e)
    {
        $errorHandler=new ErrorHandler();
        $errorHandler->logException($e);
    }
}