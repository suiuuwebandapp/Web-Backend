<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/10/15
 * Time : 15:48
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\controllers;


use common\components\RequestValidate;
use frontend\services\VolunteerService;

class VolunteerController extends UnCController {


    public $volunteerService;


    public function __construct($id, $module = null)
    {
        $this->volunteerService=new VolunteerService();
        parent::__construct($id, $module);
    }


    public function actionView()
    {
        $volunteerId=\Yii::$app->request->get('vId');
        if (RequestValidate::is_mobile_request()) {
            return $this->redirect(['/wechat-volunteer/view',"vId"=>$volunteerId]);
        }
        $volunteerInfo=$this->volunteerService->findById($volunteerId);

        return $this->render('info',[
           'volunteerInfo'=>$volunteerInfo
        ]);

    }
}