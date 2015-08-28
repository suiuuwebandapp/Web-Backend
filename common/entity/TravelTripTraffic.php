<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/8/25
 * Time : 下午3:56
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\entity;


class TravelTripTraffic {

    const PRIMARY_KEY="trafficId";
    /**
     * @var主键
     */
    public $trafficId;

    public $tripId;

    public $driverLicenseDate;

    public $carType;

    public $seatCount;

    public $spaceInfo;

    public $allowSmoke;

    public $allowPet;

    public $childSeat;

    public $serviceTime;

    public $serviceMileage;

    public $overTimePrice;

    public $overMileagePrice;

    public $carPrice;

    public $airplanePrice;

    public $nightTimeStart;

    public $nightTimeEnd;

    public $nightServicePrice;



}