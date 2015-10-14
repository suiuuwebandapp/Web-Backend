<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/10/14
 * Time: 下午3:11
 */

namespace common\entity;


class WechatVote {

    const PRIMARY_KEY='id';

    /**
     * @var主键id
     */
    public $id;

    /**
     * @var主键id
     */
    public $openId;

    /**
     * @var创建时间
     */
    public $createTime;

    /**
     * @var类型
     */
    public $type;
}