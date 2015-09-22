<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/8/27
 * Time : 下午7:52
 * Email: zhangxinmailvip@foxmail.com
 */

namespace app\modules\v1\entity;


class UserOrderContact {

    const PRIMARY_KEY="contactId";

    public $contactId;

    public $orderId;

    public $userId;

    public $username;

    public $phone;

    public $sparePhone;

    public $wechat;

    public $urgentUsername;

    public $urgentPhone;

    public $arriveFlyNumber;

    public $leaveFlyNumber;

    public $destination;


}