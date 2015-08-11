<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/7/13
 * Time : 下午5:59
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\components;


class SiteUrl {


    public static function getTripUrl($tripId)
    {
        $baseUrl=\Yii::$app->params['base_dir'];
        return $baseUrl."/view-trip/info?trip=".$tripId;
    }

    public static function getTripSearchUrl($keywords)
    {
        $baseUrl=\Yii::$app->params['base_dir'];
        return $baseUrl."/view-trip/list?s=".$keywords;
    }

    public static function getViewUserUrl($userId)
    {
        $baseUrl=\Yii::$app->params['base_dir'];
        return $baseUrl."/view-user/info?u=".$userId;
    }


    public static function getEditTripUrl($tripId)
    {
        $baseUrl=\Yii::$app->params['base_dir'];
        return $baseUrl."/trip/edit-trip?trip=".$tripId;
    }

}