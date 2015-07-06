<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/6/3
 * Time : 下午4:38
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\entity;


class UserCashRecord {


    const USER_CASH_RECORD_STATUS_WAIT=1;//等待打款
    const USER_CASH_RECORD_STATUS_SUCCESS=2;//打款成功
    const USER_CASH_RECORD_STATUS_FAIL=3;//打款失败

    const PRIMARY_KEY='cashId';

    /**
     * @var提现ID
     */
    public $cashId;

    /**
     * @var提现流水号
     */
    public $cashNumber;

    /**
     * @var用户ID
     */
    public $userId;

    /**
     * @var金额
     */
    public $money;

    /**
     * @var余额
     */
    public $balance;

    /**
     * @var转出账户
     */
    public $account;

    /**
     * @var转出用户名
     */
    public $username;

    /**
     * @var转出类型
     */
    public $type;

    /**
     * @var创建时间
     */
    public $createTime;

    /**
     * @var完成时间
     */
    public $finishTime;

    /**
     * @var状态
     */
    public $status;

}