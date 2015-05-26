<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/26
 * Time: 下午2:02
 */

namespace common\entity;


class WeChatOrderRefund {

    const STATUS_REFUND_SUCCESS=3;//退款成功
    const STATUS_REFUND_FAIL=2;//退款失败
    const STATUS_APPLY_REFUND=1;//申请
    public $refundId;
    /**
     * @var退款理由
     */
    public $refundReason;
    /**
     * @var申请人
     */
    public $userSign;
    /**
     * @var订单号
     */
    public $orderNumber;
    /**
     * @var申请时间
     */
    public $refundTime;
    /**
     * @var退款
     */
    public $money;
    /**
     * @var状态
     */
    public $status;
    /**
     * @var操作人
     */
    public $updateUserSign;
    /**
     * @var最后操作时间
     *
     */
    public $lastTime;
    /**
     * @var操作理由
     */
    public $updateReason;
}