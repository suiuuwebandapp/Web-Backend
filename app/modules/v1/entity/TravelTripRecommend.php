<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/8/11
 * Time : 下午6:19
 * Email: zhangxinmailvip@foxmail.com
 */

namespace app\modules\v1\entity;


class TravelTripRecommend {

    const PRIMARY_KEY="recommendId";

    public $recommendId;

    public $tripId;

    public $userId;

    public $content;


}