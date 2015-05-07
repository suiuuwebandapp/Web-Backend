<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/6
 * Time: 下午4:09
 */
namespace common\entity;

class TravelTripComment{


    const TYPE_SUPPORT=1;

    const TYPE_IS_TRAVEL_Y=1;//已经有玩过
    const TYPE_IS_TRAVEL_N=2;//尚未游玩
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
    public $replayCommentId=0;
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
    public $tripId;
    /**
     * @var是否玩过
     */
    public $isTravel;
    /**
     * @var评论的标题变色部分
     */
    public $rTitle;
}