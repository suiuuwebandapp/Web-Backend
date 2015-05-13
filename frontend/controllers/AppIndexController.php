<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/4/24
 * Time: 下午5:45
 */

namespace frontend\controllers;


use common\components\Aes;
use common\components\Code;
use common\entity\UserAccess;
use common\entity\UserBase;
use frontend\components\ValidateCode;
use frontend\services\UserBaseService;
use yii\base\Exception;
use yii\web\Controller;

class AppLoginController extends Controller
{

    private $userBaseService;
    public $enableCsrfValidation = false;
    public $layout = false;

    public function __construct($id, $module)
    {
        parent::__construct($id, $module);
        $this->userBaseService = new UserBaseService();
    }

    public function actionGetList()
    {

    }

}