<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/27
 * Time : 上午11:46
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\entity;


class UserPublisher {

    /**
     * 用户中证件类型 1：护照
     */
    const USER_PUBLISHER_CARD_KIND_PASSPORT=1;
    const USER_PUBLISHER_CARD_KIND_NO=0;

    /**
     * @var主键Id
     */
    public $userPublisherId;

    /**
     * @var用户关联ID userSign
     */
    public $userId;

    /**
     * @var用户国家ID
     */
    public $countryId;

    /**
     * @var用户城市Id
     */
    public $cityId;

    /**
     * @var用户所在地经度
     */
    public $lon;

    /**
     * @var用户所在地维度
     */
    public $lat;

    /**
     * @var证件Id
     */
    public $idCard;

    /**
     * @var证件图片
     */
    public $idCardImg;

    /**
     * @var证件类型
     */
    public $kind;

    /**
     * @var发布线路此时
     */
    public $tripCount;

    /**
     * @var带队次数
     */
    public $leadCount;

    /**
     * @var注册时间
     */
    public $registerTime;

    /**
     * @var最后更新时间
     */
    public $lastUpdateTime;
    /**
     * @var评分
     */
    public $score;






}