<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/18
 * Time : 下午10:22
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\entity;

/**
 * 用户申请退款表
 * Class UserOrderRefundApply
 * @package common\entity
 */
class UserOrderRefundApply {

    const USER_ORDER_REFUND_APPLY_STATUS_WAIT=0;//待审核
    const USER_ORDER_REFUND_APPLY_STATUS_SUCCESS=1;//审核通过
    const USER_ORDER_REFUND_APPLY_STATUS_FAIL=2;//审核不同过
    const USER_ORDER_REFUND_APPLY_STATUS_PAY=3;//已支付

    public $refundApplyId;

    public $orderId;

    public $userId;

    public $tripId;

    public $applyContent;

    public $applyTime;

    public $replyTime;

    public $replyContent;

    public $status;


}