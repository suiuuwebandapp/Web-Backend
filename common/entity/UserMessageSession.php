<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/7
 * Time : 下午4:16
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\entity;


class UserMessageSession {

    public $sessionId;

    public $senderId;

    public $receiveId;

    public $sessionKey;

    public $lastConcatTime;

    public $lastContentInfo;

    public $isRead;

}