<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/4/21
 * Time: 下午6:49
 */
namespace common\entity;

class CircleSort{

    const CIRCLE_TYPE_THEME=1;//主题
    /**
     * @var地方
     */
    const CIRCLE_TYPE_PLACE=2;

    const STATUS_NORMAL=1;//用户状态：正常
    const STATUS_DISABLED=2;//用户状态：禁用
    /**
     * @var 圈子id
     */
    public $cId;
    /**
     * @var 圈子类型
     */
    public $cType;
    /**
     * @var 圈子名字
     */
    public $cName;
    /**
     * @var 圈子图片
     */
    public $cpic;
    /**
     * @var状态
     */
    public $cStatus;
}