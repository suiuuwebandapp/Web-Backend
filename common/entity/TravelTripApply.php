<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/4
 * Time : 下午1:56
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\entity;


class TravelTripApply {

    const TRAVEL_TRIP_APPLY_STATUS_WAIT=0;//待处理
    const TRAVEL_TRIP_APPLY_STATUS_AGREE=1;//同意
    const TRAVEL_TRIP_APPLY_STATUS_OPPOSE=2;//未通过

    public $applyId;

    public $tripId;

    public $publisherId;

    public $sendTime;

    public $info;

    public $status;

}