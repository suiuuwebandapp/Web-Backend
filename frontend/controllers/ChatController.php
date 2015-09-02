<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/8/19
 * Time : 下午3:48
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\controllers;



use common\components\Code;

class ChatController extends CController{


    public function actionIndex()
    {

        //echo md5("gkin70mhrpcvq1sc3i8m9aiqn1");exit;
        return $this->renderPartial("index");
    }

}