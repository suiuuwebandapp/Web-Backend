<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/4/25
 * Time: 上午11:11
 */

namespace common\entity;
class UserAttention
{

    const ATTENTION_STATUS_NORMAL = 1;//状态：正常
    const ATTENTION_STATUS_DISABLED = 2;//状态：禁用
    /**
     *推荐类型为用户
     */
    const TYPE_FOR_USER = 1;
    /**
     * 推荐类型为圈子内容
     */
    const TYPE_FOR_CIRCLE_ARTICLE = 2;
    /**
     * 推荐类型为随游
     */
    const TYPE_FOR_TRAVEL = 3;

    /**
     * @var 关注id
     */
    public $attentionId;
    /**
     * @var 相对关注id
     */
    public $relativeId;
    /**
     * @var 相对关注类型
     */
    public $relativeType;
    /**
     * @var 状态 是否启用
     */
    public $status;

    /**
     * @var 关注时间
     */
    public $addTime;
    /**
     * @var 取消关注时间
     */
    public $deleteTime;

    /**
     * @var关注用户
     */
    public $userSign;
}