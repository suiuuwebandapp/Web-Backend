<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/4/20
 * Time: 下午6:15
 */
namespace common\entity;

class CircleArticle{

    const ARTICLE_STATUS_NORMAL=1;//用户状态：正常
    const ARTICLE_STATUS_DISABLED=2;//用户状态：禁用

    /**
     * @var文章id
     */
    public $articleId;
    /**
     * @var后台对应圈子id
     */
    public $cId;
    /**
     * @var发布标题
     */
    public $aTitle;
    /**
     * @var发布内容
     */
    public $aContent;
    /**
     * @var图片封面
     */
    public $aImg;
    /**
     * @var评论总数
     */
    public $aCmtCount=0;
    /**
     * @var点赞总数
     */
    public $aSupportCount=0;
    /**
     * @var创建的用户
     */
    public $aCreateUserSign;
    /**
     * @var创建时间
     */
    public $aCreateTime;
    /**
     * @var最后发送时间
     */
    public $aLastUpdateTime;
    /**
     * @var状态
     */
    public $aStatus;
    /**
     * @var发送地址
     */
    public $aAddr;




}