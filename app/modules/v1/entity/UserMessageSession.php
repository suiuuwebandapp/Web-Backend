<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/7
 * Time : 下午4:16
 * Email: zhangxinmailvip@foxmail.com
 */

namespace app\modules\v1\entity;


class UserMessageSession {



    public $sessionId;

    public $userId;

    public $relateUserId;

    public $sessionKey;

    public $lastConcatTime;

    public $lastContentInfo;

    public $isRead;

}