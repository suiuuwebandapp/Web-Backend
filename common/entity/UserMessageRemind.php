<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/4/28
 * Time: 下午2:16
 */

namespace common\entity;


class UserMessageRemind
{
    const REMIND_STATUS_NORMAL = 1;//用户状态：正常
    const REMIND_STATUS_DISABLED = 2;//用户状态：禁用


    const TYPE_AT = 1;//类型为@我的

    const TYPE_COMMENT=2;//类型为评论的

    const  TYPE_REPLY=3;//类型为回复

    const  TYPE_ATTENTION=4;//类型为关注

    const  TYPE_JOIN=5 ;//加入

    const  TYPE_BUY=6;//购买

    const  TYPE_ANSWER=7;//回答

    const  TYPE_INVITED=8;//邀请



    const R_TYPE_TRIP=1;//随游
    const R_TYPE_CIRCLE_ARTICLE=2;//圈子文章
    const R_TYPE_USER=3;//用户
    const R_TYPE_TRAVEL_PICTURE=4;//旅图
    const R_TYPE_QUESTION_ANSWER=5;//问答

    /**
     * @var消息id
     */
    public $remindId;

    /**
     * @var相对id
     */
    public $relativeId;
    /**
     * @var相对用户
     */
    public $relativeUserSign;
    /**
     * @var相对类型
     */
    public $relativeType;
    /**
     * @var创建用户
     */
    public $createUserSign;
    /**
     * @var创建时间
     */
    public $createTime;
    /**
     * @var消息的状态
     */
    public $rStatus;
    /**
     * @var读取时间
     */
    public $readTime;
    /**
     * @var随游还是圈子
     */
    public $rType;
}