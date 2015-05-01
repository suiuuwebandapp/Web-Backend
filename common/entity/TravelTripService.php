<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/29
 * Time : 下午2:02
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\entity;


class TravelTripService {

    const TRAVEL_TRIP_SERVICE_TYPE_PEOPLE=1;
    const TRAVEL_TRIP_SERVICE_TYPE_COUNT=0;

    public $serviceId;

    public $tripId;

    public $title;

    public $money;

    public $type;

}