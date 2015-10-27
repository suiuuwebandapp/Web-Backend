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

class WechatVolunteerController extends WController {

    public $layout="wechat";
    public $enableCsrfValidation=false;
    public $volunteerService;


    public function __construct($id, $module = null)
    {
        $this->volunteerService=new VolunteerService();
        parent::__construct($id, $module);
    }


    public function actionView()
    {
        $volunteerId=\Yii::$app->request->get('vId');

        if (!RequestValidate::is_mobile_request()) {
            return $this->redirect(['/volunteer/view',"vId"=>$volunteerId]);
        }
        $this->bgWhite=true;
        $this->loginValid();
        if(empty($volunteerId)){
            return $this->redirect(['/we-chat/error', 'str' => '系统未知异常']);
        }
        $volunteerInfo=$this->volunteerService->findById($volunteerId);
        return $this->render('info',[
           'volunteerInfo'=>$volunteerInfo,'userObj'=>$this->userObj
        ]);

    }
}