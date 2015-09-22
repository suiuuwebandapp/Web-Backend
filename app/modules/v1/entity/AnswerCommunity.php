<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/7/10
 * Time: 下午2:42
 */

namespace app\modules\v1\entity;


class AnswerCommunity {

    const PRIMARY_KEY='aId';

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

    //3普通1最佳2官方

    /**
     * @var是否官网推荐
     */
    public $type;

    public $zan;
}