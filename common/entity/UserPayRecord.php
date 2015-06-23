<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/14
 * Time : 下午2:36
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\entity;


class UserPayRecord {

    const PAY_RECORD_TYPE_ALIPAY=1;
    const PAY_RECORD_TYPE_WXPAY=2;
    const PAY_RECORD_TYPE_PINGPP=3;
    public $payId;

    public $orderNumber;

    public $payNumber;

    public $type;

    public $money;

}