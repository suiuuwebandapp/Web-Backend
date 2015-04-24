<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/4/21
 * Time: 下午1:33
 */
namespace frontend\controllers;


use common\components\Code;
use common\components\Aes;
use common\entity\UserBase;
use frontend\services\UserBaseService;
use yii\web\Controller;


class AController extends Controller{

    public $userObj=null;

    public $layout=false;

    public $userService=null;
    public $enableCsrfValidation=false;

    public function __construct($id, $module = null)
    {
        //验证用户是否登录
        $appSign=\Yii::$app->request->post(\Yii::$app->params['app_suiuu_sign']);
        $currentUser=json_decode(\Yii::$app->redis->get(Code::APP_USER_LOGIN_SESSION.$appSign));


        $enPassword = \Yii::$app->params['encryptPassword'];
        $enDigit = \Yii::$app->params['encryptDigit'];

        if(!isset($currentUser)&&empty($appSign)) {
            echo json_encode(Code::statusDataReturn(Code::UN_LOGIN,'appSign不能为空'));
            exit;
        }else if(isset($currentUser)){
            if($currentUser->status!=UserBase::USER_STATUS_NORMAL){
                echo json_encode(Code::statusDataReturn(Code::FAIL,"User Status Is Disabled"));
            }else {
                $this->userObj = $currentUser;
            }
        }else {
            echo json_encode(Code::statusDataReturn(Code::UN_LOGIN,'登陆已过期请重新登陆'));
            exit;
        }

        /*if(!empty($appSign)){
            $aes=new Aes();
            $userSign=$aes->decrypt($appSign,$enPassword,$enDigit);
            $this->userService=new UserBaseService();
            $currentUser=$this->userService->findUserByUserSign($userSign);
            if(isset($currentUser)){
                $this->userObj=$currentUser;
                \Yii::$app->redis->set(Code::APP_USER_LOGIN_SESSION.$appSign,$currentUser);
                \Yii::$app->redis->expire(Code::APP_USER_LOGIN_SESSION.$appSign,Code::APP_USER_LOGIN_VERIFY_CODE_EXPIRE_TIME);
            }else{
                echo json_encode(Code::statusDataReturn(Code::FAIL,'错误appSign，无法获取用户'));
                exit;
            }

        }*/
        parent::__construct($id, $module);
    }
}