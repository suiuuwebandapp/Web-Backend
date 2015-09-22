<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/20
 * Time : 上午11:39
 * Email: zhangxinmailvip@foxmail.com
 */

namespace app\modules\v1\entity;


class UserOrderPublisherCancel {

    const USER_ORDER_PUBLISHER_CANCEL_STATUS_WAIT=0;//等待审核
    const USER_ORDER_PUBLISHER_CANCEL_STATUS_SUCCESS=1;//同意
    const USER_ORDER_PUBLISHER_CANCEL_STATUS_FAIL=2;//不同意

    public $publisherCancelId;

    public $publisherId;

    public $orderId;

    public $cancelTime;

    public $content;

    public $status;

}