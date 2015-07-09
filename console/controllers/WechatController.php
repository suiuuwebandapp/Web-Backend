<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/7/7
 * Time: 下午5:40
 */

namespace console\controllers;


use frontend\interfaces\WechatInterface;
use yii\console\Controller;

class WechatController extends Controller {

    public function actionTest()
    {
        $i=\Yii::$app->redis->get('test_controller');
        echo $i;
        $i++;
       \Yii::$app->redis->set('test_controller',$i);

    }

    public function actionUpdateToken()
    {
        $wechatInterface=new WechatInterface();
        $wechatInterface->getToken();
    }
}