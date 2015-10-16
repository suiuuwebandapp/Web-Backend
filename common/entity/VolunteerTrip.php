<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/10/12
 * Time : 15:24
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\entity;


class VolunteerTrip {

    const PRIMARY_KEY='volunteerId';

    const VOLUNTEER_STATUS_ONLINE=1;//上线
    const VOLUNTEER_STATUS_OUTLINE=2;//下线
    const VOLUNTEER_STATUS_DELETE=3;//已删除


    public $volunteerId;

    public $sysUserId;

    public $kind;

    public $orgName;

    public $orgInfo;

    public $orgImg;

    public $title;

    public $titleImg;

    public $countryId;

    public $cityId;

    public $ageInfo;

    public $teamCount;

    public $beginSite;

    public $endDate;

    public $recommendInfo;

    public $info;

    public $prepare;

    public $scheduleIntro;

    public $scheduleList;

    public $eat;

    public $hotel;

    public $note;

    public $priceList;

    public $picList;

    public $includeList;

    public $unIncludeList;

    public $dateList;

    public $status;



}