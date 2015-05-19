<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/5
 * Time : 上午11:04
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\entity;


class UserOrderInfo {

    const USER_ORDER_STATUS_PAY_WAIT=0;//待支付
    const USER_ORDER_STATUS_PAY_SUCCESS=1;//已支付 待确认
    const USER_ORDER_STATUS_CONFIRM=2;//已支付 已确认
    const USER_ORDER_STATUS_CANCELED=3;//未支付 已取消
    const USER_ORDER_STATUS_REFUND_WAIT=4;//待退款
    const USER_ORDER_STATUS_REFUND_SUCCESS=5;//退款成功
    const USER_ORDER_STATUS_PLAY_SUCCESS=6;//游玩结束 待付款给随友
    const USER_ORDER_STATUS_PLAY_FINISH=7;//结束，已经付款给随友
    const USER_ORDER_STATUS_REFUND_VERIFY=8;//退款审核中
    const USER_ORDER_STATUS_REFUND_FAIL=9;//退款审核失败

    public $orderId;

    public $orderNumber;

    public $userId;

    public $tripId;

    public $personCount;

    public $beginDate;

    public $startTime;

    public $basePrice;

    public $servicePrice;

    public $totalPrice;

    public $serviceInfo;

    public $tripJsonInfo;

    public $createTime;

    public $status;

    public $isDel;

}