<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/29
 * Time : 下午1:50
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\entity;


class TravelTrip {

    const TRAVEL_TRIP_STATUS_NORMAL=1;//随游状态：正常
    const TRAVEL_TRIP_STATUS_DRAFT=2; //随游状态：草稿
    const TRAVEL_TRIP_STATUS_DELETE=3;//随游状态：已删除

    const TRAVEL_TRIP_TIME_TYPE_DAY=0; //随游时长：天
    const TRAVEL_TRIP_TIME_TYPE_HOUR=1;//随游时长：小时

    const TRAVEL_TRIP_BASE_PRICE_TYPE_PERSON=1;//随游基础价格类别：每人
    const TRAVEL_TRIP_BASE_PRICE_TYPE_COUNT=2; //随游基础价格类别：每次

    const PRIMARY_KEY="tripId";
    /**
     * @var主键
     */
    public $tripId;

    /**
     * @var创建人（随友）主键
     */
    public $createPublisherId;

    /**
     * @var创建时间
     */
    public $createTime;

    /**
     * @var随游标题
     */
    public $title;

    /**
     * @var随游封面图
     */
    public $titleImg;

    /**
     * @var国家Id
     */
    public $countryId;

    /**
     * @var城市Id
     */
    public $cityId;

    /**
     * @var经度
     */
    public $lon;

    /**
     * @var维度
     */
    public $lat;

    /**
     * @var基础价格
     */
    public $basePrice;

    /**
     * @var基础价格类别
     */
    public $basePriceType;

    /**
     * @var用户最大数量
     */
    public $maxUserCount;

    /**
     * @var是否接机
     */
    public $isAirplane;

    /**
     * @var是否提供住宿
     */
    public $isHotel;

    /**
     * @var随游评分
     */
    public $score;

    /**
     * @var随游次数
     */
    public $tripCount;

    /**
     * @var起始时间
     */
    public $startTime;

    /**
     * @var结束时间
     */
    public $endTime;

    /**
     * @var随游时长
     */
    public $travelTime;

    /**
     * @var随游时长类型
     */
    public $travelTimeType;

    /**
     * @var随游简介
     */
    public $intro;

    /**
     * @var随游详情
     */
    public $info;

    /**
     * @var随游标签
     */
    public $tags;

    /**
     * @var随游状态
     */
    public $status;

    /**
     * @var随游评论数
     */
    public $commentCount;
    /**
     * @var随游收藏数
     */
    public $collectCount;

}