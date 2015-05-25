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

    const TRAVEL_TRIP_STATUS_NORMAL=1;
    const TRAVEL_TRIP_STATUS_DRAFT=2;
    const TRAVEL_TRIP_STATUS_DELETE=3;


    const TRAVEL_TRIP_TIME_TYPE_DAY=0;
    const TRAVEL_TRIP_TIME_TYPE_HOUR=1;

    public $tripId;

    public $createPublisherId;

    public $createTime;

    public $title;

    public $titleImg;

    public $countryId;

    public $cityId;

    public $lon;

    public $lat;

    public $basePrice;

    public $maxUserCount;

    public $isAirplane;

    public $isHotel;

    public $score;

    public $tripCount;

    public $startTime;

    public $endTime;

    public $travelTime;

    public $travelTimeType;

    public $intro;

    public $info;

    public $tags;

    public $status;

}