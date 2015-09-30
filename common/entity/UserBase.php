<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/2
 * Time : 下午4:12
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\entity;


class UserBase {

    const USER_SEX_SECRET=2;//保密
    const USER_SEX_MALE=1;//男
    const USER_SEX_FEMALE=0;//女

    const USER_STATUS_NORMAL=1;//用户状态：正常
    const USER_STATUS_DISABLED=2;//用户状态：禁用

    const PRIMARY_KEY='userId';
    /**
     * @var用户Id
     */
    public $userId;
    /**
     * @var昵称
     */
    public $nickname;
    /**
     * @var姓氏
     */
    public $surname;
    /**
     * @var名字
     */
    public $name;
    /**
     * @var密码
     */
    public $password;
    /**
     * @var邮箱
     */
    public $email;
    /**
     * @var手机
     */
    public $phone;
    /**
     * @var手机区号
     */
    public $areaCode;
    /**
     * @var性别
     */
    public $sex;
    /**
     * @var生日
     */
    public $birthday;
    /**
     * @var用户头像
     */
    public $headImg;
    /**
     * @var爱好
     */
    public $hobby;
    /**
     * @var职业
     */
    public $profession;
    /**
     * @var学校
     */
    public $school;
    /**
     * @varQQ
     */
    public $qq;
    /**
     * @var微信
     */
    public $wechat;
    /**
     * @var简介
     */
    public $intro;
    /**
     * @var详细介绍
     */
    public $info;
    /**
     * @var参与随游次数
     */
    public $travelCount;
    /**
     * @var注册IP
     */
    public $registerIp;
    /**
     * @var注册时间
     */
    public $registerTime;
    /**
     * @var最后登录IP
     */
    public $lastLoginIp;
    /**
     * @var最后登录时间
     */
    public $lastLoginTime;
    /**
     * @var 用户Cookie 串
     */
    public $userSign;
    /**
     * @var状态
     */
    public $status;
    /**
     * @var是否是随友
     */
    public $isPublisher;
    /**
     * @var国家Id
     */
    public $countryId;
    /**
     * @var城市ID
     */
    public $cityId;
    /**
     * @var经度
     */
    public $lon;
    /**
     * @var维度
     */
    public $lat;
    /**
     * @var余额
     */
    public $balance;
    /**
     * @var版本号
     */
    public $version;


    public static function isDefaultHeadImg($headImg)
    {
        if($headImg=='http://www.suiuu.com/assets/images/user_default.png'){
            return true;
        }else{
            return false;
        }
    }

}
