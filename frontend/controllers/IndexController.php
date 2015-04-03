<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/3
 * Time : 上午10:02
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\controllers;

use common\components\Aes;
use common\components\Validate;
use frontend\entity\UserBase;
use frontend\services\UserBaseService;
use common\components\Code;
use yii\base\Exception;
use yii\web\Controller;

class IndexController extends Controller{


    private $userBaseService;

    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        $this->userBaseService=new UserBaseService();
    }

    public function actionIndex()
    {

    }

    public function actionLogin()
    {
        $username=\Yii::$app->request->post('username');
        $password=\Yii::$app->request->post('password');
        $passwordConfirm=\Yii::$app->request->post('passwordConfirm');
        $validateCode=\Yii::$app->request->post('validateCode');


        $error="";
        if(empty($username)||strlen($username)>30)
        {
            $error='用户名格式不正确';
        }else if(empty($password)||strlen($password)>30)
        {
            $error='密码格式不正确';
        }else if($password!=$passwordConfirm)
        {
            $error='两次密码输入不一致';
        }

    }

    /**
     * 用户手机注册
     * @return mixed
     */
    public function actionPhoneRegister()
    {

        $sendCode=\Yii::$app->session->get(Code::USER_PHONE_VALIDATE_CODE_AND_PHONE);

        if(empty($sendCode)){
            return json_decode(Code::statusDataReturn(Code::PARAMS_ERROR,Code::USER_PHONE_CODE_ERROR));
        }
        $array=explode('-',$sendCode,3);
        $phone=$array[0];
        $areaCode=$array[1];
        $validateCode=$array[2];
        $password=$array[3];

        $error="";//错误信息
        $valMsg=Validate::validatePhone($phone);
        if(!empty($valMsg))
        {
            $error=$valMsg;
        }else if(empty($password)||strlen($password)>30)
        {
            $error='密码格式不正确';
        }
        else if(empty($areaCode))
        {
            $error='手机区号格式不正确';
        }
        else if($sendCode!=$validateCode)
        {
            $error='验证码输入有误，请查证后再试';
        }

        if(!empty($error)){
            return json_decode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }

        try{
            $userBase=new UserBase();
            $userBase->phone=$phone;
            $userBase->password=$password;
            $userBase=$this->userBaseService->addUser($userBase);
            //添加用户登录状态
            \Yii::$app->session->set(Code::USER_LOGIN_SESSION,$userBase);

        }catch (Exception $e){
            return json_decode(Code::statusDataReturn(Code::FAIL,$e->getMessage()));
        }

        return json_decode(Code::SUCCESS);

    }

    public function actionEmailRegister()
    {

    }

    /**
     * 给用户发送短信
     */
    public function actionSendMessage()
    {
        $phone=\Yii::$app->request->post('phone');//发送给用户的手机
        $areaCode=\Yii::$app->request->post('areaCode');//区号
        $password=\Yii::$app->request->post('password');
        $passwordConfirm=\Yii::$app->request->post('passwordConfirm');

        $error="";//错误信息
        $valMsg=Validate::validatePhone($phone);
        if(!empty($valMsg))
        {
            $error=$valMsg;
        }else if(empty($password)||strlen($password)>30)
        {
            $error='密码格式不正确';
        }else if($password!=$passwordConfirm)
        {
            $error='两次密码输入不一致';
        }else if(empty($areaCode))
        {
            $error='手机区号格式不正确';
        }

        if(!empty($error)){
            return json_decode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
        $code=$this->randomPhoneCode();//验证码
        //分割可能会有问题，测试阶段
        \Yii::$app->session->set(Code::USER_PHONE_VALIDATE_CODE_AND_PHONE,$phone."-".$areaCode."-".$code."-".$password);

        //调用发送短信接口

    }

    /**
     * 给用户发送邮件
     * @return mixed
     */
    public function actionSendEmail()
    {
        $email=\Yii::$app->request->post('email');//用户输入的邮箱
        $password=\Yii::$app->request->post('password');//用户输入的密码
        $passwordConfirm=\Yii::$app->request->post('passwordConfirm');

        $error="";//错误信息
        $valMsg=Validate::validateEmail($email);
        if(!empty($valMsg))
        {
            $error=$valMsg;
        }else if(empty($password)||strlen($password)>30)
        {
            $error='密码格式不正确';
        }else if($password!=$passwordConfirm)
        {
            $error='两次密码输入不一致';
        }
        if(!empty($error)){
            return json_decode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
        //判断邮箱是否已经注册
        $userBase=$this->userBaseService->findUserByEmail($email);
        if(!isset($userBase)){
            return json_decode(Code::statusDataReturn(Code::PARAMS_ERROR,Code::USER_EMAIL_EXIST));
        }

        $enPwd=$this->getEncryptPassword($password);
        $code=$this->getEmailCode($email,$enPwd);
        $url=\Yii::$app->params['base_dir'].'/index/active?e='.$email.'&p='.$enPwd.'&c='.$code;
        //最终发送的地址内容
        echo $url;
    }


    /**
     * 用户验证邮箱是否正确 并且注册
     * @return string
     */
    public function actionActive()
    {
        $email=\Yii::$app->request->get('e');//用户输入的邮箱
        $password=\Yii::$app->request->get('p');//用户输入的密码
        $code=\Yii::$app->request->get('c');//用户输入的密码

        $valCode=$this->getEmailCode($email,$password);
        $password=$this->getDecryptPassword($password);

        if($code!=$valCode){
            return $this->renderPartial('activeError.php',['error'=>'无效的链接地址']);
        }
        try{
            $userBase=new UserBase();
            $userBase->email=$email;
            $password->$password;

            $this->userBaseService->addUser($userBase);
        }catch (Exception $e){
            return $this->renderPartial('activeError.php',['error'=>$e->getMessage()]);
        }
        return $this->renderPartial('activeSuccess.php');

    }

    /**
     * 根据邮件获取邮件验证Code
     * @param $email
     * @param $password
     * @return string
     */
    private function getEmailCode($email,$password)
    {
        return md5(md5($email.\Yii::$app->params['emailEncryptPassword'].$password).\Yii::$app->params['emailEncryptPassword']);
    }

    /**
     * 获取临时加密的密码
     * @param $password
     * @return string
     */
    private function getEncryptPassword($password)
    {
        $enPassword = \Yii::$app->params['encryptPassword'];
        $enDigit = \Yii::$app->params['encryptDigit'];
        return Aes::encrypt($password,$enPassword,$enDigit);
    }

    /**
     * 根据临时加密密码 获取用户密码
     * @param $password
     * @return string
     */
    private function getDecryptPassword($password)
    {
        $enPassword = \Yii::$app->params['encryptPassword'];
        $enDigit = \Yii::$app->params['encryptDigit'];
        return Aes::decrypt($password,$enPassword,$enDigit);
    }


    /**
     * 生成手机六位验证码
     * @return string
     */
    private function randomPhoneCode(){
        $code="";
        for($i=0;$i<6;$i++){
            $code.=rand(0,9);
        }
        return $code;
    }




}