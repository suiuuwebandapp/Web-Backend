<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/7/10
 * Time: 下午2:30
 */

namespace common\entity;

/**
 * 问答社区提问实体
 * Class QuestionCommunity
 * @package common\entity
 */
class QuestionCommunity {
    /**
     * @var主键id
     */
    public $qId;
    /**
     * @var提问标题
     */
    public $qTitle;
    /**
     * @var提问内容
     */
    public $qContent;
    /**
     * @var提问地点藐视
     */
    public $qAddr;
    /**
     * @var提问国家
     */
    public $qCountryId;
    /**
     * @var提问城市
     */
    public $qCityId;
    /**
     * @var问题标签串
     */
    public $qTag;
    /**
     * @var提问用户
     */
    public $qUserSign;
    /**
     * @var创建时间
     */
    public $qCreateTime;
    /**
     * @var邀请回答的用户
     */
    public $qInviteAskUser;
    /**
     * @var浏览量
     */
    public $pvNumber=0;
}