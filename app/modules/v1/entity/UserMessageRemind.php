<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/4/28
 * Time: 下午2:16
 */

namespace app\modules\v1\entity;


class UserMessageRemind
{
    const REMIND_STATUS_NORMAL = 1;//用户状态：正常
    const REMIND_STATUS_DISABLED = 2;//用户状态：禁用


    const TYPE_AT = 1;//类型为@我的

    const TYPE_COMMENT=2;//类型为评论的

    const  TYPE_REPLY=3;//类型为回复

    const  TYPE_ATTENTION=4;//类型为关注

    const  TYPE_JOIN=5 ;//加入 //申请加入

    const  TYPE_BUY=6;//购买

    const  TYPE_ANSWER=7;//回答

    const  TYPE_INVITED=8;//邀请

    const TYPE_PUBLISH_CANCEL=9;//随友取消

    const TYPE_NEW_ORDER=10;//用户新下单

    const TYPE_PUBLISH_CONFIRM_REFUND=11;//随友确认退款
    const TYPE_USER_CONFIRM=12;//用户确认
    const TYPE_PUBLISH_CONFIRM_JOIN=13; //随友同意加入
    const TYPE_PUBLISH_UN_CONFIRM_JOIN=14; //随友拒绝加入
    const TYPE_REMOVE_PUBLISH=15;//移除随游
    const TYPE_PERFECT_USER_INFO=16;//完善用户资料

    const R_TYPE_TRIP=1;//随游
    const R_TYPE_CIRCLE_ARTICLE=2;//圈子文章
    const R_TYPE_USER=3;//用户
    const R_TYPE_TRAVEL_PICTURE=4;//旅图
    const R_TYPE_QUESTION_ANSWER=5;//问答
    const R_TYPE_SYS=6;//系统
    const R_TYPE_ORDER=7;//订单

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