<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/8/19
 * Time: 下午4:55
 */

namespace app\modules\v1\entity;


class AppVersion {

    const VERSION_ID=1;
    const VERSION_MINI=20;
    const CLIENT_TYPE_ANDROID_PAD="androidPad";//安卓平板
    const CLIENT_TYPE_ANDROID_PHONE="androidPhone";//安卓手机

    const MUST_UPDATE=2;//必须更新
    const CAN_UPDATE=1;//可以更新
    const UN_NEED_UPDATE=0;//无需更新

    const DOWNLOAD_URL_ANDROID_PHONE="";

    public $id;
    public $appId;
    public $clientType;
    public $versionId;
    public $versionMini;
    public $createTime;
    public $updateTime;

    public static function versionCheck($versionId,$versionMini,$clientType)
    {
        $isUpdate = 0;
        $url='';
        switch($clientType)
        {
            case self::CLIENT_TYPE_ANDROID_PHONE:
                if($versionId<self::VERSION_ID)
                {
                    $isUpdate=self::MUST_UPDATE;
                    $url=self::DOWNLOAD_URL_ANDROID_PHONE;
                }else if($versionMini<self::VERSION_MINI)
                {
                    $isUpdate=self::CAN_UPDATE;
                    $url=self::DOWNLOAD_URL_ANDROID_PHONE;
                }else
                {
                    $isUpdate=self::UN_NEED_UPDATE;
                    $url="";
                }

                break;
        }

        return array("isUpdate"=>$isUpdate,'url'=>$url,);
    }
}