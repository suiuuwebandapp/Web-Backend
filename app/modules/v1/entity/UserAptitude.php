<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/8/6
 * Time : 上午11:10
 * Email: zhangxinmailvip@foxmail.com
 */

namespace app\modules\v1\entity;


class UserAptitude {

    const PRIMARY_KEY="aptitudeId";

    const USER_APTITUDE_STATUS_WAIT=0;

    CONST USER_APTITUDE_STATUS_SUCCESS=1;

    const USER_APTITUDE_STATUS_FAIL=2;

    public $aptitudeId;

    public $userId;

    public $applyTime;

    public $status;

}