<?php
namespace backend\entity;
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/3/31
 * Time : 下午3:03
 * Email: zhangxinmailvip@foxmail.com
 */

class SysUser {

    const USER_SEX_SECRET=2;//保密
    const USER_SEX_MALE=1;//男
    const USER_SEX_FEMALE=0;//女

    /**
     * @var用户Id （主键）
     */
    public $userId;
    /**
     * @var用户名
     */
    public $username;
    /**
     * @var密码
     */
    public $password;
    /**
     * @var手机
     */
    public $phone;
    /**
     * @var邮箱
     */
    public $email;
    /**
     * @var昵称
     */
    public $nickname;
    /**
     * @var注册时间
     */
    public $registerTime;
    /**
     * @var注册Ip
     */
    public $registerIp;
    /**
     * @var最后登录时间
     */
    public $lastLoginTime;
    /**
     * @var最后登录Ip
     */
    public $lastLoginIp;
    /**
     * @var性别
     */
    public $sex;
    /**
     * @var生日
     */
    public $birthday;
    /**
     * @var是否是管理员
     */
    public $isAdmin;
    /**
     * @var是否启用
     */
    public $isEnabled;
    /**
     * @var是否删除
     */
    public $isDelete;
    /**
     * 用户cookieSign 加密标识
     * @var
     */
    public $userSign;


}