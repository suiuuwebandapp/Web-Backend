<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/4/22
 * Time: 上午9:45
 */
namespace common\entity;

class CircleComment{
    const COMMENT_STATUS_NORMAL=1;//用户状态：正常
    const COMMENT_STATUS_DISABLED=2;//用户状态：禁用
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
     * @var状态1为正常2为删除
     */
    public $cStatus;
    /**
     * @var最后更改时间
     */
    public $cLastTime;
    /**
     * @var针对文章id
     */
    public $articleId;
}