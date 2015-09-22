<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/7/22
 * Time : 上午11:31
 * Email: zhangxinmailvip@foxmail.com
 */

namespace app\modules\v1\entity;


class TravelTripSpecial {
    const PRIMARY_KEY="specialId";

    public $specialId;

    public $tripId;

    public $title;

    public $info;

    public $picUrl;

}