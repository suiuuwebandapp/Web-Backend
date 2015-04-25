<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/4/25
 * Time: 上午9:47
 */
namespace common\entity;
class RecommendList {

    const RECOMMEND_STATUS_NORMAL=1;//用户状态：正常
    const RECOMMEND_STATUS_DISABLED=2;//用户状态：禁用
    /**
     *推荐类型为用户
     */
    const TYPE_FOR_USER = 1;
    /**
     *推荐类型为圈子文章
     */
    const TYPE_FOR_CIRCLE_ARTICLE = 2;
    /**
     * 推荐类型为随游
     */
    const TYPE_FOR_TRAVEL = 3;


    /**
     * @var 推荐id
     */
    public $recommendId;
    /**
     * @var 相对推荐id
     */
    public $relativeId;
    /**
     * @var 相对推荐类型
     */
    public $relativeType;
    /**
     * @var 状态 是否启用
     */
    public $status;
}