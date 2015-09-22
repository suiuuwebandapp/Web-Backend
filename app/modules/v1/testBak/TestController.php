<?php
namespace app\modules\v1\controllers;
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/3
 * Time : 上午10:02
 * Email: zhangxinmailvip@foxmail.com
 */
use app\modules\v1\models\Customer;
use app\modules\v1\models\User;
use common\components\Code;
use frontend\components\Page;
use frontend\services\UserMessageRemindService;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;
use yii\rest\Controller;

class TestController extends Controller{

    public $modelClass = '';

   /* public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['rateLimiter']['enableRateLimitHeaders'] = true;
        return $behaviors;
    }*/
    public function actionTest()
    {

        $ser = new UserMessageRemindService();
        $rst = $ser->getSysMessage("085963dc0af031709b032725e3ef18f5",new Page(),"");
        return $rst['data'];
    }


}