<?php
/**
 * Created by PhpStorm.
 * User: jl
 * Date: 15/4/30
 * Time: 上午9:45
 */
namespace common\entity;

class ArticleComment{
    /**
     * @var 评论id
     */
    public $commentId;
    /**
     * @var 发表评论用户
     */
    public $userSign;
    /**
     * @var 评论内容
     */
    public $content;
    /**
     * @var 相对id
     */
    public $relativeCommentId=0;
    /**
     * @var 支持 点赞人数
     */
    public $supportCount = 0;
    /**
     * @var反对人数
     */
    public $opposeCount = 0;

    /**
     * @var创建时间
     */
    public $cTime;
    /**
     * @var针对文章id
     */
    public $articleId;
}