<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/7/10
 * Time: 下午2:47
 */

namespace common\entity;


class TagList {

    const TYPE_TRIP_SYS=1;//随游系统标签
    const TYPE_TRIP_PIC_SYS=2;//旅图系统标签
    const TYPE_TRIP_PIC_USER=3;//旅图用户标签
    /**
     * @var标签id
     */
    public $tId;
    /**
     * @var标签内容
     */
    public $tName;
    /**
     * @var标签类型
     */
    public $tType;

}