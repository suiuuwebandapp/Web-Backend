<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/6/4
 * Time: 下午2:34
 */

namespace common\entity;


class WeChatNewsList
{

    const TYPE_TEXT = 1;//文本
    const TYPE_NEWS = 2;//图文
    const TYPE_IMG = 3;//图片

    const STATUS_NORMAL=1;//正常
    const STATUS_DISABLED=2;//禁用
    /**
     * @var消息id
     */
    public $newsId;
    /**
     * @var相对类型 id
     */
    public $nTid=0;
    /**
     * @var标题
     */
    public $nTitle;
    /**
     * @var简介
     */
    public $nIntro;
    /**
     * @var封皮
     */
    public $nCover;
    /**
     * @var内容
     */
    public $nContent;
    /**
     * @var关键字
     */
    public $nAntistop;
    /**
     * @var 图文url
     */
    public $nUrl;
    /**
     * @var类型
     */
    public $nType;
    /**
     * @var状态
     */
    public $nStatus;
}