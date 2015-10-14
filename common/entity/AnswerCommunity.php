<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/7/10
 * Time: 下午2:42
 */

namespace common\entity;


class AnswerCommunity {

    const PRIMARY_KEY='aId';

    const SYS_TYPE=2;//系统回答
    const USER_TYPE=3;//普通回答
    /**
     * @var主键回答id
     */
    public $aId;
    /**
     * @var外键问题id
     */
    public $qId;
    /**
     * @var回答内容
     */
    public $aContent;
    /**
     * @var回答用户
     */
    public $aUserSign;
    /**
     * @var回答时间
     */
    public $aCreateTime;

    public $type;

    public $zan;



}