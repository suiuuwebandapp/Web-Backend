<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/19
 * Time : 上午11:38
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\entity;

/**
 * 用户系统消息设定
 * Class UserMessageSetting
 * @package common\entity
 */
class UserMessageSetting {

    const USER_MESSAGE_SETTING_STATUS_ALLOW_ALL=1;//允许所有人发送私信

    const USER_MESSAGE_SETTING_STATUS_REFUSE_ALL=2;//拒绝所有人发送的私信（系统除外）

    /**
     * @var主键
     */
    public $settingId;

    /**
     * @var用户Id（userSign）
     */
    public $userId;

    /**
     * @var用户消息设定状态
     */
    public $status;

    /**
     * @var屏蔽用户列表
     */
    public $shieldIds;


    /**
     * @var屏蔽用户的基本信息列表
     */
    public $userBaseMap;


    public function setUserBaseList ($userBaseList){
        $map=[];
        if($userBaseList!=null&&count($userBaseList)>0){
            foreach($userBaseList as $userBase){
                $map[$userBase['userSign']]=$userBase;
            }
        }
        $this->userBaseMap=$map;
    }
}