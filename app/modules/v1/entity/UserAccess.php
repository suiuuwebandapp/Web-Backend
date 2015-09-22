<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/23
 * Time : 下午4:32
 * Email: zhangxinmailvip@foxmail.com
 */

namespace app\modules\v1\entity;


class UserAccess {

    /**
     * 接入类型 QQ
     */
    const ACCESS_TYPE_QQ=1;

    /**
     * 接入类型 微信
     */
    const ACCESS_TYPE_WECHAT=2;

    /**
     * 接入类型 微博
     */
    const ACCESS_TYPE_SINA_WEIBO=3;


    public $accessId;

    public $userId;

    public $openId;

    public $type;
}