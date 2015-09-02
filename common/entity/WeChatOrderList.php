<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/13
 * Time: 下午4:50
 */

namespace common\entity;


class WeChatOrderList{

    const STATUS_CANCEL=8;//取消
    const STATUS_REFUND_FAL=7;//拒绝退款
    const STATUS_REFUND_SUCCESS=6;//结束退款
    const STATUS_APPLY_REFUND=5;//申请退款中
    const STATUS_END = 4;//游玩结束
    const STATUS_PAY_SUCCESS = 3;//状态：已支付
    const STATUS_PROCESSED = 2;//状态：已经处理待支付
    const STATUS_NORMAL = 1;//状态：下单 待处理
    const IS_DEL_N = 0;//状态：未删除
    const IS_DEL_Y = 1;//状态：删除
    /**
     * @var主键id
     */
    public $wOrderId;
    /**
     * @var订单的地点
     */
    public $wOrderSite;
    /**
     * @var订单中时间列表
     */
    public $wOrderTimeList;
    /**
     * @var订单内容
     */
    public $wOrderContent;
    /**
     * @var订单创建人
     */
    public $wUserSign;
    /**
     * @var状态1为下单2为已经处理
     */
    public $wStatus;
    /**
     * @var相对用户 或指派给谁
     */
    public $wRelativeSign;
    /**
     * @var创建时间
     */
    public $wCreateTime;
    /**
     * @var最后时间
     */
    public $wLastTime;
    /**
     * @var订单号
     */
    public $wOrderNumber;
    /**
     * @var订购人数
     */
    public $wUserNumber;

    /**
     * @var订单详情
     */
    public $wDetails;
    /**
     * @var联系电话
     */
    public $wPhone;
    /**
     * @var微信id
     */
    public $openId;
    /**
     * @var价钱
     */
    public $wMoney;
    /**
     * @var是否删除
     */
    public $isDel;

    /**
     * @var随友联系方式
     */
    public $tripContact;
}