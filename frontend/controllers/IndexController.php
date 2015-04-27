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
use common\components\Mail;
use common\components\Validate;
use common\entity\UserBase;
use frontend\services\CountryService;
use frontend\services\UserBaseService;
use common\components\Code;
use vendor\geetest\GeetestLib;
use yii\base\Exception;
use yii;
use yii\web\Cookie;

class IndexController extends UnCController{


    private $userBaseService;


    public $test;


    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        $this->userBaseService=new UserBaseService();
    }

    public function actionIndex()
    {
        $emailTime=\Yii::$app->session->get(Code::USER_REGISTER_EMAIL_TIMER);
        if(!empty($emailTime))
        {
            $now=date('Y-m-d H:i:s',time());
            $tempTime=strtotime($now)-strtotime($emailTime);
            $emailTime=$tempTime>Code::USER_REGISTER_TIMER?0:Code::USER_REGISTER_TIMER-$tempTime;
        }

        $view = \Yii::$app->view;
        $view->params['emailTime']=$emailTime;
        return $this->render('index');
    }

    public function actionTest()
    {
        return $this->renderPartial('test');
    }



    public function actionGetPasswordCode()
    {
        $username=Yii::$app->request->post('username');//用户名
        if(empty($username)||strlen($username)>50||strlen($username)<5){
            $errors="用户名格式不正确";
            return json_encode(Code::statusDataReturn(Code::FAIL,$errors));
        }
        $uscp=Yii::$app->redis->get(Code::USER_SEND_COUNT_PREFIX.$username);
        if($uscp>Code::MAX_SEND_COUNT)
        {
            return json_encode(Code::statusDataReturn(Code::FAIL,'发送次数过多24小时后将继续发送'));
        }else
        {
            if(Validate::validatePhone($username))
            {
                //手机
                Yii::$app->redis->set(Code::USER_SEND_COUNT_PREFIX.$username,++$uscp);
                Yii::$app->redis->expire(Code::USER_SEND_COUNT_PREFIX.$username,Code::USER_LOGIN_VERIFY_CODE_EXPIRE_TIME);
                $code=$this->randomPhoneCode();
                $str=$username.'_'.$code;
                yii::$app->session->set(Code::USER_PHONE_VALIDATE_CODE_AND_PHONE_FOR_PASSWORD,$str);

            }else if(Validate::validateEmail($username))
            {
                Yii::$app->redis->set(Code::USER_SEND_COUNT_PREFIX.$username,++$uscp);
                Yii::$app->redis->expire(Code::USER_SEND_COUNT_PREFIX.$username,Code::USER_LOGIN_VERIFY_CODE_EXPIRE_TIME);


            }else
            {
                $errors="用户名格式不正确";
                return json_encode(Code::statusDataReturn(Code::FAIL,$errors));
            }
        }
    }
    public function actionGetPassword()
    {


    }
    /**
     * 登录方法 POST
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {


        $username=Yii::$app->request->post('username');//用户名
        $password=Yii::$app->request->post('password');//密码
        $captcha=Yii::$app->request->post('captcha');//验证码

        $geetestChallenge=Yii::$app->request->post('geetest_challenge');//极验验证
        $geetestValidate=Yii::$app->request->post('geetest_validate');
        $geetestSecCode=Yii::$app->request->post('geetest_seccode');

        $returnUrl=Yii::$app->request->post('returnUrl');//登录前的URL
        $remember=Yii::$app->request->post('remember');//记住密码


        $errors=[];
        $errorCount=0;
        $showVerifyCode=false;
        //从Redis 获取用户名登录错误次数
        $cacheCount=Yii::$app->redis->get(Code::USER_LOGIN_ERROR_COUNT_PREFIX.$username);
        if(!empty($cacheCount)){
            $errorCount=$cacheCount;
        }
        //判断登录错误次数 是否验证 验证码
        if($errorCount>=Code::SYS_LOGIN_ERROR_COUNT){
            $showVerifyCode=true;
            if(empty($geetestChallenge)||empty($geetestValidate)||empty($geetestSecCode)){
                $errors[]="请完成验证码验证";
            }else{
                $geetestLib= new GeetestLib();
                $validateResponse = $geetestLib->validate($geetestChallenge, $geetestValidate, $geetestSecCode);
                if($validateResponse!=TRUE){
                    $errors[]="验证码不正确";
                }
            }

        }else if(empty($username)||strlen($username)>20||strlen($username)<5){
            $errors[]="用户名格式不正确";
        }else if(empty($password)||strlen($password)>20||strlen($password)<5){
            $errors[]="密码格式不正确";
        }
        //用户输入信息验证
        if(count($errors)>0){
            return json_encode(Code::statusDataReturn(Code::FAIL,$errors[0]));

        }

        try{
            //验证用户名是否存在
            $result=$this->userBaseService->findUserByUserNameAndPwd($username,$password);
            if(isset($result)){
                //设置Session
                Yii::$app->session->set(Code::USER_LOGIN_SESSION,$result);
                //如果用户点击记住密码，设置Cookie
                if($remember==true){

                    //记录加密Cookie
                    $enPassword = Yii::$app->params['encryptPassword'];
                    $enDigit = Yii::$app->params['encryptDigit'];

                    $aes=new Aes();
                    $Sign=$aes->encrypt($result->userSign,$enPassword,$enDigit);
                    $cookies=Yii::$app->response->cookies;//cookie 注意，发送Cookie 是response 读取是 request
                    $signCookie= new Cookie([
                        'name' => Yii::$app->params['suiuu_sign'],
                        'value' => $Sign,
                    ]);
                    $signCookie->expire=time()+24*60*60*floor(Yii::$app->params['cookie_expire']);
                    $cookies->add($signCookie);
                }
                //清除错误登录次数
                Yii::$app->redis->del(Code::USER_LOGIN_ERROR_COUNT_PREFIX.$username);
                //跳转用户登录前的页面
                return json_encode(Code::statusDataReturn(Code::SUCCESS));

            }else{
                Yii::$app->redis->set(Code::USER_LOGIN_ERROR_COUNT_PREFIX.$username,++$errorCount);
                Yii::$app->redis->expire(Code::USER_LOGIN_ERROR_COUNT_PREFIX.$username,Code::USER_LOGIN_VERIFY_CODE_EXPIRE_TIME);

                $errors[]="用户名或密码错误";
                return json_encode(Code::statusDataReturn(Code::FAIL,$errors[0],$errorCount));
            }

        }catch (Exception $e){
            $errors[]=$e->getMessage();
        }
        //判断是否需要输入验证码
        /*if($errorCount>=Code::SYS_LOGIN_ERROR_COUNT){
            $showVerifyCode=true;
        }
        return $this->render('index', ['errors' => $errors, 'showVerifyCode' => $showVerifyCode]);*/
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
        $error="";//错误信息
        $valMsg=Validate::validateEmail($email);
        if(!empty($valMsg))
        {
            $error=$valMsg;
        }else if(empty($password)||strlen($password)>30)
        {
            $error='密码格式不正确';
        }
        if(!empty($error)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }


        $emailTime=\Yii::$app->session->get(Code::USER_REGISTER_EMAIL_TIMER);

        if(!empty($emailTime))
        {
            $now=date('Y-m-d H:i:s',time());
            $tempTime=strtotime($now)-strtotime($emailTime);
            $emailTime=$tempTime>Code::USER_REGISTER_TIMER?0:$tempTime;
        }
        if($emailTime==0){
            //判断邮箱是否已经注册
            $userBase=$this->userBaseService->findUserByEmail($email);
            if(!empty($userBase)){
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,Code::USER_EMAIL_EXIST));
            }
            $enPwd=$this->getEncryptPassword($password);
            $code=$this->getEmailCode($email,$enPwd);
            $url=\Yii::$app->params['base_dir'].'/index/active?e='.$email.'&p='.$enPwd.'&c='.$code;
            //最终发送的地址内容
            $rst=Mail::sendRegisterMail($email,$url);
            //
            if($rst['status']==Code::SUCCESS){
                //设置邮件定时器，控制发送频率
                \Yii::$app->session->set(Code::USER_REGISTER_EMAIL_TIMER,date('Y-m-d H:i:s',time()));
                echo json_encode(Code::statusDataReturn(Code::SUCCESS,Code::USER_REGISTER_TIMER));
            }else{
                echo json_encode(Code::statusDataReturn(Code::FAIL,"发送邮件失败，请稍后重试"));
            }
        }else{
            echo json_encode(Code::statusDataReturn(Code::FAIL,"发送邮件过于频繁，请稍后再试"));
        }
        return null;
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
            return $this->redirect(['/result','result'=>'无效的链接地址！！']);
        }
        try{
            $userBase=new UserBase();
            $userBase->email=$email;
            $userBase->password=$password;

            $this->userBaseService->addUser($userBase);
        }catch (Exception $e){
            return $this->redirect(['/result','result'=>'验证邮箱失败！']);
        }
        return $this->redirect(['/result','result'=>'注册成功！']);

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


    public function actionCreateTravel()
    {
        //判断用户是否是随友，不是的话，跳转到随游注册页面
        if(isset($this->userObj)&&$this->userObj->isPublisher){
            return $this->redirect("/");
        }else{
            $email="";
            $phone="";
            $areaCode="";
            $countryService=new CountryService();
            $phoneCodeList=$countryService->getCountryPhoneCodeList();
            if(isset($this->userObj)){
                $email=$this->userObj->email;
                $phone=$this->userObj->phone;
            }
            if($areaCode==""){
                $areaCode="+86";
            }
            return $this->render("registerPublisher",[
                'email'=>$email,
                'phone'=>$phone,
                'areaCode'=>$areaCode,
                'phoneCodeList'=>$phoneCodeList
            ]);
        }

    }



}