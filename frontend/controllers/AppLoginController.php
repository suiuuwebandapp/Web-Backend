<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/23
 * Time : 下午2:11
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\controllers;


use common\components\Code;
use common\entity\UserAccess;
use common\entity\UserBase;
use frontend\services\UserBaseService;
use yii\base\Exception;
use yii\web\Controller;

class AppLoginController extends Controller{

    private $userBaseService;


    public function __construct($id, $module)
    {
        parent::__construct($id, $module);
        $this->userBaseService=new UserBaseService();
    }

    /**
     * App 第三方登录接口
     * @return null
     */
    public function actionAccessLogin()
    {
        $openId=\Yii::$app->request->post("openId");
        $nickname=\Yii::$app->request->post("nickname");
        $sex=\Yii::$app->request->post("sex");
        $headImg=\Yii::$app->request->post("headImg");
        $type=\Yii::$app->request->post("type");
        $sign=\Yii::$app->request->post("sign");

//        $openId=Code::getUUID();
//        $nickname="测试";
//        $sex=1;
//        $headImg="http://www.baidu.com";
//        $type=1;
//        $sign=md5($openId.$type.\Yii::$app->params['apiPassword']);

        \Yii::$app->session->id();
        if(empty($openId)){
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"OpenId Is Not Allow Empty"));
            return null;
        }
        if(empty($sign)){
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"Sign Is Not Allow Empty"));
            return null;
        }
        if(empty($type)){
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"Type Is Not Allow Empty"));
            return null;
        }
        if(!$this->validateLoginParamSign($openId,$type,$sign)){
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"Invalid Sign Value"));
            return null;
        }

        /**
         * 判断用户是否之前接入过
         */
        $userBase=$this->userBaseService->findUserAccessByOpenIdAndType($openId,$type);
        if($userBase!=null){
            if($userBase->status!=UserBase::USER_STATUS_NORMAL){
                echo json_encode(Code::statusDataReturn(Code::FAIL,"User Status Is Disabled"));
            }else{
                \Yii::$app->session->set(Code::APP_USER_LOGIN_SESSION,$userBase);
                echo json_encode(Code::statusDataReturn(Code::SUCCESS,$userBase));
            }
            return null;
        }
        if($sex!=UserBase::USER_SEX_MALE&&$sex!=UserBase::USER_SEX_FEMALE&&$sex!=UserBase::USER_SEX_SECRET){
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"Invalid Sex Value"));
            return null;
        }
        if($type!=UserAccess::ACCESS_TYPE_QQ&&$type!=UserAccess::ACCESS_TYPE_WECHAT&&$type!=UserAccess::ACCESS_TYPE_SINA_WEIBO){
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"Invalid Type Value"));
            return null;
        }

        $userBase=null;
        try{
            $userBase=new UserBase();
            $userBase->nickname=$nickname;
            $userBase->headImg=$headImg;
            $userBase->sex=$sex;

            $userAccess=new UserAccess();
            $userAccess->openId=$openId;
            $userAccess->type=$type;
            $userBase=$this->userBaseService->addUser($userBase,$userAccess);
            \Yii::$app->session->set(Code::APP_USER_LOGIN_SESSION,$userBase);

        }catch (Exception $e){
            echo json_encode(Code::statusDataReturn(Code::FAIL,$e->getName()));
            return null;
        }
        echo json_encode(Code::statusDataReturn(Code::SUCCESS,$userBase));
        return null;

    }


    private function validateLoginParamSign($openId,$type,$sign)
    {
        $valSign=md5($openId.$type.\Yii::$app->params['apiPassword']);
        return $valSign==$sign?true:false;
    }


    public function actionSinaLogin()
    {

    }


    public function actionWechatLogin()
    {

    }



}