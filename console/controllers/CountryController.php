<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/7/8
 * Time: 下午4:15
 */

namespace console\controllers;


use common\components\Code;
use frontend\services\CountryService;
use yii\console\Controller;

class CountryController extends Controller  {

    public function actionUpdateCc()
    {
        $countrySer=new CountryService();
        $cc = $countrySer->getAllTripCC();
        \Yii::$app->redis->set(Code::TRIP_COUNTRY_CITY,json_encode($cc));
    }
}