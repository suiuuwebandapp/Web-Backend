<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/8
 * Time: 上午11:17
 */
namespace frontend\controllers;

use common\components\Code;
use common\entity\UserFeedback;
use frontend\services\UserFeedbackService;
use yii\base\Exception;
use yii;
class UserFeedbackController extends AController
{
    private  $userFeedbackSer;
    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        $this->userFeedbackSer =new UserFeedbackService();

    }

    public function actionAppCreateFeedback()
    {
        $this->loginValid(false);

        try{
            $feedback = new UserFeedback();
            $feedback->content=Yii::$app->request->post('content');
            if( empty($feedback->content)){return json_encode(Code::statusDataReturn(Code::FAIL,'不能反馈空信息')); }
            $feedback->userSign=isset($this->userObj->userSign)?$this->userObj->userSign:'';
            $feedback->imgList=Yii::$app->request->post('imgList');
            $feedback->fLevel=Yii::$app->request->post('level');
            $feedback->fType=UserFeedback::TYPE_APP;
            $this->userFeedbackSer->createFeedback($feedback);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }
    public function actionWebCreateFeedback()
    {
        $this->loginValid(false,false);
        try{
            $feedback = new UserFeedback();
            $feedback->content=Yii::$app->request->post('content');
            if(empty($feedback->content))
            {return json_encode(Code::statusDataReturn(Code::FAIL,'不能反馈空信息'));}

            $feedback->userSign=isset($this->userObj->userSign)?$this->userObj->userSign:'';
            $feedback->imgList=Yii::$app->request->post('imgList');
            $feedback->fLevel=Yii::$app->request->post('level');
            $feedback->fType=UserFeedback::TYPE_WEB;
            $this->userFeedbackSer->createFeedback($feedback);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }
}