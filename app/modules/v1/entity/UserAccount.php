<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/6/4
 * Time : 下午5:18
 * Email: zhangxinmailvip@foxmail.com
 */

namespace app\modules\v1\entity;


class UserAccount {

    const USER_ACCOUNT_TYPE_WECHAT=1;//微信
    const USER_ACCOUNT_TYPE_ALIPAY=2;//支付宝

    /**
     * @var用户账户编号
     */
    public $accountId;

    /**
     * @var用户Id
     */
    public $userId;

    /**
     * @var用户账号
     */
    public $account;

    /***
     * @var用户名
     */
    public $username;

    /**
     * @var类型
     */
    public $type;

    /**
     * @var创建时间
     */
    public $createTime;

    /**
     * @var更新时间
     */
    public $updateTime;

    /**
     * @var是否删除
     */
    public $isDel;


    /**
     * 获取账户类型
     * @param $type
     * @return string
     */
    public static function getAccountType($type)
    {
        if($type==self::USER_ACCOUNT_TYPE_WECHAT){
            return "微信";
        }else if(self::USER_ACCOUNT_TYPE_ALIPAY){
            return "支付宝";
        }else{
            return "未知类型";
        }
    }

}