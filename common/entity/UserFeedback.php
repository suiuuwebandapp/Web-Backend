<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/8
 * Time: 上午10:10
 */

namespace common\entity;


class UserFeedback
{
    const RESULT_UN_DISPOSE= 1;//未处理
    const RESULT_DISPOSE_ING= 2;//处理中
    const RESULT_DISPOSE = 3;//处理



    const TYPE_APP = 1;//类型为app 普通

    const TYPE_WEB=2;//类型为web 普通

    /**
     * @var反馈id
     */
    public $feedbackId;

    /**
     * @var反馈用户
     */
    public $userSign;
    /**
     * @var反馈内容
     */
    public $content;
    /**
     * @var图片列表
     */
    public $imgList;
    /**
     * @var反馈时间
     */
    public $createTime;
    /**
     * @var反馈类型
     */
    public $fType;
    /**
     * @var反馈级别
     */
    public $fLevel;
    /**
     * @var反馈结果
     */
    public $fResult;
    /**
     * @var联系人
     */
    public $fName;
    /**
     * @var联系方式
     */
    public $contact;
    /**
     * @var联系地址
     */
    public $fAddr;

}