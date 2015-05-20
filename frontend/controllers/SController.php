<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/19
 * Time: 下午3:05
 */

namespace frontend\controllers;


use common\components\RequestValidate;
use yii\web\Controller;

class SController extends Controller {

    public function __construct($id, $module = null)
    {
        $rv=new RequestValidate();
        $rv->validate();
        parent::__construct($id, $module);
    }
}