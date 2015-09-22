<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/7/14
 * Time: 下午6:16
 */

namespace  app\modules\v1\entity;


class TravelPictureComment {
    public $id;
    public $tpId;
    public $comment;
    public $userSign;
    public $createTime;
    public $supportCount=0;
}