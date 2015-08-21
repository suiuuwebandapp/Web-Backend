<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/20
 * Time : 下午5:40
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\entity;


/**
 * 用户订单评论表
 * Class UserOrderComment
 * @package common\entity
 */
class UserOrderComment {

    /**
     * @var主键
     */
    public $orderCommentId;

    /**
     * @var订单Id
     */
    public $orderId;

    /**
     * @var用户Id
     */
    public $userId;

    /**
     * @var随游Id
     */
    public $tripId;

    /**
     * @var 随友Id
     */
    public $publisherId;

    /**
     * @var评论内容
     */
    public $content;

    /**
     * @var其他反馈
     */
    public $otherContent;

    /**
     * @var评论时间
     */
    public $commentTime;

    /**
     * @var随游评分
     */
    public $tripScore;

    /**
     * @var随友综合评分
     */
    public $publisherScore;

    /**
     * @var熟练评分
     */
    public $familiarScore;

    /**
     * @var专注度评分
     */
    public $absorbedScore;

    /**
     * @var守时评分
     */
    public $punctualScore;


}