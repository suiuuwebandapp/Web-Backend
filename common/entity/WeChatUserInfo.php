<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/13
 * Time: 下午4:50
 */

namespace common\entity;


class WeChatUserInfo {

    /**
     * @var主键id
     */
    public $id;
    /**
     * @var用户对用服务号id
     */
    public $openId;
    /**
     * @var用户标示
     */
    public $userSign;
    /**
     * @var用户微信唯一标示
     */
    public $unionID;
    /**
     * @var昵称
     */
    public $v_nickname;
    /**
     * @var值为1时是男性，值为2时是女性，值为0时是未知
     */
    public $v_sex;
    /**
     * @var城市
     */
    public $v_city;
    /**
     * @var国家
     */
    public $v_country;
    /**
     * @var省份
     */
    public $v_province;
    /**
     * @var用户的语言，简体中文为zh_CN
     */
    public $v_language;
    /**
     * @var用户头像
     */
    public $v_headimgurl;
    /**
     * @var关注时间
     */
    public $v_subscribe_time;
    /**
     * @var备注
     */
    public $v_remark;
    /**
     * @var分组
     */
    public $v_groupid;
    /**
     * @var学校
     */
    public $v_school;

}