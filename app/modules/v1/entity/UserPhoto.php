<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/8/5
 * Time : 下午2:51
 * Email: zhangxinmailvip@foxmail.com
 */

namespace app\modules\v1\entity;


class UserPhoto {


    const PRIMARY_KEY='photoId';

    public $photoId;

    public $userId;

    public $url;

    public $createTime;

}