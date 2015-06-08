<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/6/8
 * Time : 下午2:18
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\entity;


class TravelTripDetail {

    const TRAVEL_TRIP_DETAIL_TYPE_INCLUDE=1;
    const TRAVEL_TRIP_DETAIL_TYPE_UN_INCLUDE=2;

    public $detailId;

    public $tripId;

    public $name;

    public $type;

}