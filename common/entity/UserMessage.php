<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/7
 * Time : 下午2:51
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\entity;


class UserMessage {

    public $messageId;

    public $sessionKeyOne;

    public $sessionKeyTwo;

    public $receiveId;

    public $senderId;

    public $url;

    public $content;

    public $sendTime;

    public $readTime;

    public $isRead;

    public $isRefused;

}