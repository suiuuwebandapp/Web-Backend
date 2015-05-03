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
     *关注类型为用户
     */
    const TYPE_FOR_USER = 1;
    /**
     * 关注类型为圈子内容
     */
    const TYPE_FOR_CIRCLE_ARTICLE = 2;
    /**
     * 关注类型为随游
     */
    const TYPE_FOR_TRAVEL = 3;
    /**
     * 关注类型为圈子主题
     */
    const TYPE_FOR_CIRCLE = 4;

    /**
     * 收藏类型为圈子主题
     */
    const TYPE_COLLECT_FOR_ARTICLE = 5;
    /**
     * 收藏类型为随游
     */
    const TYPE_COLLECT_FOR_TRAVEL = 6;

    /**
     * 支持类型为目的地评论
     */
    const TYPE_COMMENT_FOR_ARTICLE_MDD = 8;
    /**
     * 支持类型为圈子文章评论
     */
    const TYPE_COMMENT_FOR_CIRCLE_ARTICLE = 9;
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
     * @var 状态 是否启用 /或
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