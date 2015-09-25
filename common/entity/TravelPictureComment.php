<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/7/14
 * Time: 下午6:16
 */

namespace common\entity;


class TravelPictureComment {

    const PRIMARY_KEY='id';

    public $id;
    public $tpId;
    public $comment;
    public $userSign;
    public $createTime;
    public $supportCount=0;
}