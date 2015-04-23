<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/17
 * Time : 上午11:03
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\entity;


class DestinationInfo {

    /**
     * 上线
     */
    const DES_STATUS_ONLINE=1;
    /**
     * 下线
     */
    const DES_STATUS_OUTLINE=2;

    public $destinationId;

    public $countryId;

    public $cityId;

    public $title;

    public $titleImg;

    public $createUserId;

    public $createTime;

    public $lastUpdateTime;

    public $status;

}