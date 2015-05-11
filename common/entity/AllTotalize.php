<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/11
 * Time: 下午4:45
 */
namespace common\entity;

class AllTotalize {

    /**
     * 随游评论
     */
    const TYPE_COMMENT_FOR_TRIP=1;
    /**
     * 随游收藏
     */
    const TYPE_COLLECT_FOR_TRIP=2;
    /**
     * 专栏评论
     */
    const TYPE_COMMENT_FOR_ARTICLE=3;
    /**
     * 圈子文章评论
     */
    const TYPE_COMMENT_FOR_CIRCLE_ARTICLE=5;



    /**
     * @var主键
     */
    public $totalId;

    /**
     * @var总数
     */
    public $totalize=1;

    /**
     * @var相对类型
     */
    public $tType;

    /**
     * @var相对id
     */
    public $rId;
}