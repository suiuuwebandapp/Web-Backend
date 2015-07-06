<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/6/3
 * Time : 下午3:07
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\entity;


class UserAccountRecord {


    const USER_ACCOUNT_RECORD_TYPE_TRIP_SERVER=1;//随游服务
    const USER_ACCOUNT_RECORD_TYPE_TRIP_DIVIDED_INTO=2;//随游分成
    const USER_ACCOUNT_RECORD_TYPE_DRAW_MONEY=3;//提现
    const USER_ACCOUNT_RECORD_TYPE_OTHER=4;//其他
    const USER_ACCOUNT_RECORD_TYPE_REFUND=5;//退款

    /**
     * @var记录ID
     */
    public $recordId;

    /**
     * @var用户ID（userSign）
     */
    public $userId;

    /**
     * @var类型
     */
    public $type;

    /**
     * @var相对ID
     */
    public $relateId;

    /**
     * @var金额
     */
    public $money;

    /**
     * @var账户余额
     */
    public $balance;

    /**
     * @var详情
     */
    public $info;

    /**
     * @var时间
     */
    public $recordTime;


}