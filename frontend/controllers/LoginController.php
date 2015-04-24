<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/4/21
 * Time: 下午2:19
 */
namespace frontend\controllers;


use common\components\Code;
use common\components\ValidateCode;
use frontend\services\UserBaseService;
use yii\base\Exception;
use yii\rest\Controller;

class LoginController extends Controller
{
    private $userBaseService;
    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        $this->userBaseService=new UserBaseService();
    }

    public function actionAppLogin()
    {


        $username=\Yii::$app->request->get('username');
        $password=\Yii::$app->request->get('password');
        $username='519414839@qq.com';
        $password='qwe123';
        $error="";
        $code=\Yii::$app->request->post('validateCode');//验证码
        $errorCount=0;
        //从Redis 获取用户名登录错误次数
        $cacheCount=\Yii::$app->redis->get(Code::APP_USER_LOGIN_ERROR_COUNT_PREFIX.$username);
        if(!empty($cacheCount)){
            $errorCount=$cacheCount;
        }
        //判断登录错误次数 是否验证 验证码
        if($errorCount>=Code::SYS_LOGIN_ERROR_COUNT){
            $serCode=\Yii::$app->session->get(Code::APP_USER_LOGIN_VERIFY_CODE);
            if($serCode!=$code)
            {
                $error='验证码不正确';
            }

        }else if(empty($username)||strlen($username)>20||strlen($username)<5){
            $error="用户名格式不正确";
        }else if(empty($password)||strlen($password)>20||strlen($password)<5){
            $error="密码格式不正确";
        }
        //用户输入信息验证
        if($error!=''){
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
            exit;
        }
        try{
            //验证用户名是否存在
            $result=$this->userBaseService->findUserByUserNameAndPwd($username,$password);
            if(isset($result)){

                //设置Session
                \Yii::$app->session->set(Code::APP_USER_LOGIN_SESSION,$result);
                //如果用户点击记住密码，设置Cookie

                //清除错误登录次数
                \Yii::$app->redis->del(Code::APP_USER_LOGIN_ERROR_COUNT_PREFIX.$username);

                echo json_encode(Code::statusDataReturn(Code::SUCCESS,$result));
                exit;
            }else{

                \Yii::$app->redis->set(Code::APP_USER_LOGIN_ERROR_COUNT_PREFIX.$username,++$errorCount);
                \Yii::$app->redis->expire(Code::APP_USER_LOGIN_ERROR_COUNT_PREFIX.$username,Code::APP_USER_LOGIN_VERIFY_CODE_EXPIRE_TIME);
                $error="用户名或密码错误";
                echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
                exit;
            }
        }catch (Exception $e){
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
            exit;
        }
    }


    public function actionLogout()
    {
        \Yii::$app->session->remove(Code::USER_LOGIN_SESSION);
        return $this->redirect("/");
    }



    public function actionGetCode()
    {
        $ValidateCode=new ValidateCode();
        $ValidateCode->doimg();
        \Yii::$app->session->set(Code::USER_LOGIN_VERIFY_CODE,$ValidateCode->getCode());
    }

}