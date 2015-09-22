<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/7/14
 * Time: 下午6:09
 */

namespace  app\modules\v1\entity;


class TravelPicture {

    public $id;
    public $title;
    public $contents;
    public $picList;
    public $country;
    public $city;
    public $lon;
    public $lat;
    public $tags;
    public $userSign;
    public $createTime;
    public $commentCount=0;
    public $attentionCount=0;
    public $titleImg;
    public $address;
}