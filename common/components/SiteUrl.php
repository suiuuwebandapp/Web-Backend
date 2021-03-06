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
        $baseUrl=\Yii::$app->params['front_suiuu_url'];
        return $baseUrl."/view-trip/info/".$tripId.".html";
    }

    public static function getTripSearchUrl($keywords)
    {
        $baseUrl=\Yii::$app->params['front_suiuu_url'];
        return $baseUrl."/view-trip/list?s=".$keywords;
    }
    public static function getTripActivityUrl($keywords)
    {
        $baseUrl=\Yii::$app->params['front_suiuu_url'];
        return $baseUrl."/view-trip/list?a=".$keywords;
    }

    public static function getViewUserUrl($userId)
    {
        $baseUrl=\Yii::$app->params['front_suiuu_url'];
        return $baseUrl."/view-user/info/".$userId.".html";
    }

    public static function getEditTripUrl($tripId)
    {
        $baseUrl=\Yii::$app->params['front_suiuu_url'];
        return $baseUrl."/trip/edit-trip?trip=".$tripId;
    }

    public static function getOrderPayUrl($orderNumber)
    {
        $baseUrl=\Yii::$app->params['front_suiuu_url'];
        return $baseUrl."/user-order/info?orderNumber=".$orderNumber;

    }

}