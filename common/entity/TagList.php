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
    const TYPE_TRIP_USER=2;//随游用户标签
    const TYPE_TRIP_PIC_SYS=3;//旅图系统标签
    const TYPE_TRIP_PIC_USER=4;//旅图用户标签
    const TYPE_Q_A_SYS=5;//问答社区系统标签
    const TYPE_Q_A_USER=6;//问答社区用户标签
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