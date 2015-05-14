<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/13
 * Time: 下午4:50
 */

namespace common\entity;


class WeChatOrderList{

    const STATUS_PROCESSED = 2;//状态：已经处理
    const STATUS_NORMAL = 1;//状态：下单
    const STATUS_DISABLED = 0;//状态：删除

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
     * @var相对id关联另外的主键
     */
    public $wRelativeId;
    /**
     * @var相对用户 或指派给谁
     */
    public $wRelativeSign;
    /**
     * @var相对类型
     */
    public $wRelativeType;
    /**
     * @var创建时间
     */
    public $wCreateTime;
    /**
     * @var最后时间
     */
    public $wLastTime;
}