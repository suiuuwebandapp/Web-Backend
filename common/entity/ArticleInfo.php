<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/10
 * Time : 下午4:45
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\entity;


class ArticleInfo {

    //const ARTICLE_STATUS_NORMAL=0;
    /**
     * 上线
     */
    const ARTICLE_STATUS_ONLINE=1;
    /**
     * 下线
     */
    const ARTICLE_STATUS_OUTLINE=2;

    public $articleId;

    public $title;

    public $titleImg;

    public $name;

    public $content;

    public $createUserId;

    public $createTime;

    public $lastUpdateTime;

    public $status;


}