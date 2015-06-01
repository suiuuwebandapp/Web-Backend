<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/29
 * Time : 下午6:03
 * Email: zhangxinmailvip@foxmail.com
 */

namespace backend\controllers;


use common\components\Code;
use common\entity\UserBase;
use common\entity\UserPublisher;
use frontend\services\UserBaseService;
use yii\base\Exception;

class UserBaseController extends CController{



    public function actionAddSysPublisher()
    {
        $nickname=trim(\Yii::$app->request->post("nickname",""));
        $phone=trim(\Yii::$app->request->post("phone",null));
        $email=trim(\Yii::$app->request->post("email",null));

        if(empty($nickname)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"随友昵称不能为空"));
        }
        if(empty($phone)&&empty($email)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"手机邮箱不能同时为空"));
        }
        if(empty($phone)){
            $phone=null;
        }
        if(empty($email)){
            $email=null;
        }

        $userBase=new UserBase();
        $userBase->nickname=$nickname;
        $userBase->phone=$phone;
        $userBase->email=$email;
        $userBase->password="suiuu";

        $userPublisher=new UserPublisher();


        $userBaseService=new UserBaseService();
        try{
            $userBaseService->addUser($userBase,null,$userPublisher);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            return json_encode(Code::statusDataReturn(Code::FAIL,$e->getMessage()));
        }
    }


    public function actionToAddPublisher()
    {
        return $this->render("addPublisher");
    }
}