<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/4
 * Time : 下午7:17
 * Email: zhangxinmailvip@foxmail.com
 */

namespace app\modules\v1\controllers;


use app\components\Page;
use app\modules\v1\entity\UserBase;
use app\modules\v1\services\UserBaseService;
use app\modules\v1\services\UserInfoService;
use common\components\Code;
use common\components\LogUtils;
use yii\base\Exception;

class AppUserInfoController extends AController
{

    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
    }

    public function actionGetInfo()
    {
        //确实当前用户是否关注
        $this->loginValid();
        try{
            $userSign=\Yii::$app->request->get('userSign');
            if(empty($userSign))
            {
                return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,'无法得到未知用户主页'));
            }
            $userInfoService = new UserInfoService();
            $data=$userInfoService->getUserInfo($userSign);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }
    }
    public function actionUpdateUserInfo()
    {
        $this->loginValid();
        $userSign =$this->userObj->userSign;
        $userService=new UserBaseService();
        $sex = trim(\Yii::$app->request->post('sex',UserBase::USER_SEX_SECRET));
        $nickname = trim(\Yii::$app->request->post('nickname'));
        $headImg = trim(\Yii::$app->request->post('headImg'));
        $birthday = trim(\Yii::$app->request->post('birthday'));
        $intro = trim(\Yii::$app->request->post('intro'));
        $info = trim(\Yii::$app->request->post('info'));
        $countryId = \Yii::$app->request->post('countryId');
        $cityId = \Yii::$app->request->post('cityId');
        $lon = \Yii::$app->request->post('lon');
        $lat = \Yii::$app->request->post('lat');
        $profession = trim(\Yii::$app->request->post('profession'));

        if(empty($nickname)||strlen($nickname)>30){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"昵称格式不正确"));
        }
        if(empty($countryId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"请选择居住地国家"));
        }
        if(empty($cityId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"请选择居住地城市"));
        }
        try{
            $userInfo=$userService->findUserByUserSign($userSign);
            if(!empty($sex)){
            $userInfo->sex=$sex;
            }
            if(!empty($headImg)){
                $userInfo->headImg=$headImg;
            }
            if(!empty($birthday)){
                $userInfo->birthday=$birthday;
            }
            if(!empty($intro)){
                $userInfo->intro=$intro;
            }
            if(!empty($info)){
                $userInfo->info=$info;
            }
            if(!empty($lon)){
                $userInfo->lon=$lon;
            }
            if(!empty($lat)){
                $userInfo->lat=$lat;
            }
            if(!empty($profession)){
                $userInfo->profession=$profession;
            }
            $userService->updateUserBase($userInfo);
            $this->appRefreshUserInfo();
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$userInfo));
        }catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

}