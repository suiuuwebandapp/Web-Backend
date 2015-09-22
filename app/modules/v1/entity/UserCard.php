<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/8/6
 * Time : 上午9:56
 * Email: zhangxinmailvip@foxmail.com
 */

namespace app\modules\v1\entity;


class UserCard {

    const PRIMARY_KEY="cardId";

    const USER_CARD_STATUS_WAIT=0;

    CONST USER_CARD_STATUS_SUCCESS=1;

    const USER_CARD_STATUS_FAIL=2;

    public $cardId;

    public $userId;

    public $name;

    public $number;

    public $img;

    public $authHistory;

    public $updateTime;

    public $status;

}