<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/5
 * Time : 上午11:04
 * Email: zhangxinmailvip@foxmail.com
 */

namespace app\modules\v1\entity;


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
    const USER_ORDER_STATUS_PUBLISHER_CANCEL=10;//随友取消订单

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


    /**
     * 获取订单状态描述
     * @param $status
     * @return string
     */
    public static function getOrderStatusDes($status)
    {
        switch($status){
            case self::USER_ORDER_STATUS_PAY_WAIT:
                return "待付款";break;
            case self::USER_ORDER_STATUS_PAY_SUCCESS:
                return "待接单";break;
            case self::USER_ORDER_STATUS_CONFIRM:
                return "已接单";break;
            case self::USER_ORDER_STATUS_CANCELED:
                return "已取消";break;
            case self::USER_ORDER_STATUS_REFUND_WAIT:
                return "待退款";break;
            case self::USER_ORDER_STATUS_REFUND_SUCCESS:
                return "退款成功";break;
            case self::USER_ORDER_STATUS_PLAY_SUCCESS:
                return "确认游玩";break;
            case self::USER_ORDER_STATUS_PLAY_FINISH:
                return "游玩结束";break;
            case self::USER_ORDER_STATUS_REFUND_VERIFY:
                return "退款审核中";break;
            case self::USER_ORDER_STATUS_REFUND_FAIL:
                return "退款失败";break;
            case self::USER_ORDER_STATUS_PUBLISHER_CANCEL:
                return"随友取消订单";break;
            default:
                return "无效的订单状态";break;
        }
    }

}