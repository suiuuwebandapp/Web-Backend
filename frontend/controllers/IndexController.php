<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/3
 * Time : 上午10:02
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\controllers;

use backend\components\Page;
use common\components\Aes;
use common\components\LogUtils;
use common\components\Mail;
use common\components\RequestValidate;
use common\components\SMSUtils;
use common\components\Validate;
use common\entity\UserBase;
use common\entity\UserPublisher;
use common\components\ValidateCode;
use frontend\services\CountryService;
use frontend\services\TripService;
use frontend\services\UserAttentionService;
use frontend\services\UserBaseService;
use common\components\Code;
use vendor\geetest\GeetestLib;
use yii\base\Exception;
use yii;
use yii\web\Cookie;

class IndexController extends UnCController
{


    private $userBaseService;
    public $isIndex;

    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        $this->userBaseService = new UserBaseService();
    }

    public function actionIndex()
    {


        if (RequestValidate::is_mobile_request()) {
           return $this->redirect('/wechat-trip');
        }


        //获取用户邮件定时器
        $emailTime = $this->getEmailTime();

        $view = \Yii::$app->view;
        $view->params['emailTime'] = $emailTime;

        $page=new Page();
        $page->setCurrentPage(1);
        $page->pageSize=4;
        $attentionService=new UserAttentionService();
        $recommendTravel =$attentionService->getRecommendTravel($page);

        $this->isIndex=true;
        return $this->render('index',[
            'recommendTravel'=>$recommendTravel['data']
        ]);
    }

    public function actionTripHelp()
    {
        return $this->render("tripHelp");
    }

    /**
     * 找回密码
     * @return string
     * @throws Exception
     * @throws \Exception
     */
    public function actionPasswordSendCode()
    {
        if(!isset($_POST['username']))
        {
            $areaCode = "";
            $countryService = new CountryService();
            $countryList=$countryService->getCountryList();
            if ($areaCode == "") {
                $areaCode = "+86";
            }
            return $this->render('forgetPassword',['areaCode'=>$areaCode,'countryList'=>$countryList]);
        }
        $username = Yii::$app->request->post('username');//用户名
        if (empty($username) || strlen($username) > 50 || strlen($username) < 5) {
            $errors = "用户名格式不正确";
            return json_encode(Code::statusDataReturn(Code::FAIL, $errors));
        }

        $count = Yii::$app->redis->get(Code::USER_SEND_COUNT_PREFIX . $username);
        if ($count > Code::MAX_SEND_COUNT) {
            return json_encode(Code::statusDataReturn(Code::FAIL, '发送次数过多24小时后将继续发送'));
        } else {
           if (empty(Validate::validateEmail($username))) {

                $code = Yii::$app->request->post('code');//用户名
                $codeSession = yii::$app->session->get(Code::USER_LOGIN_VERIFY_CODE);
                if(strtolower($code)!=strtolower($codeSession)||empty($codeSession)){
                    return json_encode(Code::statusDataReturn(Code::FAIL, '验证码错误'));
                }

                $userBase = $this->userBaseService->findUserByEmail($username);
                if (empty($userBase)) {
                    return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, '用户不存在，请先注册'));
                }
               $now = date('Y-m-d H:i:s', time());
               $time = strtotime($now);
                $r = $this->getEncryptPassword($time);
                $c = $this->getEmailCode($username,$r);
                $url = \Yii::$app->params['base_dir'] . '/index/reset-password?r=' . urlencode($r).'&u='. urlencode($username).'&c='. urlencode($c);
                //最终发送的地址内容
                $rst = Mail::sendPasswordMail($username, $url);
                //
                if ($rst['status'] == Code::SUCCESS) {
                    \Yii::$app->redis->set(Code::USER_PHONE_VALIDATE_CODE_AND_PHONE_FOR_PASSWORD . $username, $c);
                    Yii::$app->redis->expire(Code::USER_PHONE_VALIDATE_CODE_AND_PHONE_FOR_PASSWORD . $username, Code::USER_PHONE_VALIDATE_CODE_EXPIRE_TIME);
                    //设置邮件定时器，控制发送频率
                    Yii::$app->redis->set(Code::USER_SEND_COUNT_PREFIX . $username, ++$count);
                    Yii::$app->redis->expire(Code::USER_SEND_COUNT_PREFIX . $username, Code::USER_LOGIN_VERIFY_CODE_EXPIRE_TIME);
                    return json_encode(Code::statusDataReturn(Code::SUCCESS));
                } else {
                    return json_encode(Code::statusDataReturn(Code::FAIL, "发送邮件失败，请稍后重试"));
                }
            }elseif(empty(Validate::validatePhone($username))){
               //手机验证
               $areaCode = Yii::$app->request->post('areaCode');
               $userBase = $this->userBaseService->findUserByPhone($username);
               if (empty($userBase)) {
                   return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, '用户不存在，请先注册'));
               }

               $code = $this->randomPhoneCode();
                //设置验证码 和 有效时长
               \Yii::$app->redis->set(Code::USER_PHONE_VALIDATE_CODE_AND_PHONE_FOR_PASSWORD . $username, $code);
               Yii::$app->redis->expire(Code::USER_PHONE_VALIDATE_CODE_AND_PHONE_FOR_PASSWORD . $username, Code::USER_PHONE_VALIDATE_CODE_EXPIRE_TIME);
               //调用发送短信接口 测试默认为成功
               $smsUtils = new SmsUtils();
               $rst = $smsUtils->sendMessage($username, $areaCode,$code,SmsUtils::SEND_MESSAGE_TYPE_PASSWORD);
               if(!empty($rst))
               {
                   Yii::$app->redis->set(Code::USER_SEND_COUNT_PREFIX . $username, ++$count);
                   Yii::$app->redis->expire(Code::USER_SEND_COUNT_PREFIX . $username, Code::USER_LOGIN_VERIFY_CODE_EXPIRE_TIME);
                   return json_encode(Code::statusDataReturn(Code::SUCCESS));
               }else {
                   return json_encode(Code::statusDataReturn(Code::FAIL, "发送信息失败，请稍后重试"));
               }
           } else {
                $errors = "用户名格式不正确";
                return json_encode(Code::statusDataReturn(Code::FAIL, $errors));
            }
        }
    }

    /**
     * 跳转到重置密码页面
     * @return string
     */
    public function actionResetPasswordView()
    {
        return $this->render('resetPassword');
    }

    /**
     * 发送邮箱链接跳转或者手机正确验证后跳转
     * @return string|yii\web\Response
     */
    public function actionResetPassword()
    {
        if(isset($_POST['u']))
        {
            $u = Yii::$app->request->post('u');//用户名
            $code = Yii::$app->request->post('code');//验证码
            $phoneCode=\Yii::$app->redis->get(Code::USER_PHONE_VALIDATE_CODE_AND_PHONE_FOR_PASSWORD . $u);
             if(empty($phoneCode)||$code!=$phoneCode)
             {
                 return json_encode(Code::statusDataReturn(Code::FAIL, '验证码错误'));
             }
            Yii::$app->session->set(Code::USER_NAME_SESSION,$u);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }


        $r = Yii::$app->request->get('r');//时间加密
        $u = Yii::$app->request->get('u');//用户名
        $c = Yii::$app->request->get('c');//验证
        $time =$this->getDecryptPassword($r);
        $cc = $this->getEmailCode($u,$r);
        $redisCode=\Yii::$app->redis->get(Code::USER_PHONE_VALIDATE_CODE_AND_PHONE_FOR_PASSWORD . $u);
        if($cc==$c&&!empty($redisCode)&&$cc==$redisCode)
        {
            $now = date('Y-m-d H:i:s', time());
            $nowTime = strtotime($now);
            $t=$nowTime-$time;
            if($t>intval(Code::USER_PHONE_VALIDATE_CODE_EXPIRE_TIME))
            {
                return $this->redirect(['/result', 'result' => '链接已过期！！']);
            }
            Yii::$app->session->set(Code::USER_NAME_SESSION,$u);
            return $this->render('resetPassword');
        }else{
            return $this->redirect(['/result', 'result' => '无效的链接地址！！']);
        }

    }

    /**
     * 更新密码
     * @return string
     */
    public function actionUpdatePassword()
    {
        $username=  Yii::$app->session->get(Code::USER_NAME_SESSION);
        $redisCode=\Yii::$app->redis->get(Code::USER_PHONE_VALIDATE_CODE_AND_PHONE_FOR_PASSWORD .$username);
        $password = Yii::$app->request->post('password');
        $confirmPassword = Yii::$app->request->post('confirmPassword');
        $code = Yii::$app->request->post('code');//用户名
        $codeSession = yii::$app->session->get(Code::USER_LOGIN_VERIFY_CODE);
        if(strtolower($code)!=strtolower($codeSession)||empty($codeSession)){
            return json_encode(Code::statusDataReturn(Code::FAIL, '验证码错误'));
        }
        $error='';
        if(empty($username)||empty($redisCode))
        {
            $error = "请重新验证手机或邮箱";
            return json_encode(Code::statusDataReturn(Code::FAIL,$error));
        }
        if($password!=$confirmPassword)
        {
            $error = "密码不统一";
            return json_encode(Code::statusDataReturn(Code::FAIL,$error));
        }
        try{
            if (empty(Validate::validateEmail($username))) {
                $rst = $this->userBaseService->findUserByEmail($username);
            }else
            {
                $rst = $this->userBaseService->findUserByPhone($username);
            }
            if(empty($rst))
            {
                $error='未发现该用户';
                return json_encode(Code::statusDataReturn(Code::FAIL,$error));
            }
            $r=$this->userBaseService->updatePassword($rst->userSign,$password);
            if($r==1)
            {
                Yii::$app->session->set(Code::USER_NAME_SESSION,'');
                Yii::$app->redis->del(Code::USER_PHONE_VALIDATE_CODE_AND_PHONE_FOR_PASSWORD . $username);
                return json_encode(Code::statusDataReturn(Code::SUCCESS,'修改成功'));
            }else
            {
                //密码重复
                return json_encode(Code::statusDataReturn(Code::FAIL,'密码重复无需修改'));
            }
        } catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 获取图像验证码
     */
    public function actionGetCode()
    {
        $ValidateCode=new ValidateCode();
        $ValidateCode->doimg();
        \Yii::$app->session->set(Code::USER_LOGIN_VERIFY_CODE,$ValidateCode->getCode());
    }

    /**
     * 登录方法 POST
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {


        $username = Yii::$app->request->post('username');//用户名
        $password = Yii::$app->request->post('password');//密码
        $captcha = Yii::$app->request->post('captcha');//验证码

        $geetestChallenge = Yii::$app->request->post('geetest_challenge');//极验验证
        $geetestValidate = Yii::$app->request->post('geetest_validate');
        $geetestSecCode = Yii::$app->request->post('geetest_seccode');

        $returnUrl = Yii::$app->request->post('returnUrl');//登录前的URL
        $remember = Yii::$app->request->post('remember');//记住密码


        $errors = [];
        $errorCount = 0;
        $showVerifyCode = false;
        //从Redis 获取用户名登录错误次数
        $cacheCount = Yii::$app->redis->get(Code::USER_LOGIN_ERROR_COUNT_PREFIX . $username);
        if (!empty($cacheCount)) {
            $errorCount = $cacheCount;
        }
        //判断登录错误次数 是否验证 验证码
        if ($errorCount >= Code::SYS_LOGIN_ERROR_COUNT) {
            $showVerifyCode = true;
            if (empty($geetestChallenge) || empty($geetestValidate) || empty($geetestSecCode)) {
                $errors[] = "请完成验证码验证";
            } else {
                $geetestLib = new GeetestLib();
                $validateResponse = $geetestLib->validate($geetestChallenge, $geetestValidate, $geetestSecCode);
                if ($validateResponse != TRUE) {
                    $errors[] = "验证码不正确";
                }
            }

        } else if (empty($username) || strlen($username) > 50 || strlen($username) < 5) {
            $errors[] = "用户名格式不正确";
        } else if (empty($password) || strlen($password) > 20 || strlen($password) < 5) {
            $errors[] = "密码格式不正确";
        }
        //用户输入信息验证
        if (count($errors) > 0) {
            return json_encode(Code::statusDataReturn(Code::FAIL, $errors[0],$errorCount));

        }
        try {
            //验证用户名是否存在
            $result = $this->userBaseService->findUserByUserNameAndPwd($username, $password);
            if (isset($result)) {
                //设置Session
                Yii::$app->session->set(Code::USER_LOGIN_SESSION, $result);
                //如果用户点击记住密码，设置Cookie
                if ($remember == true) {

                    //记录加密Cookie
                    $enPassword = Yii::$app->params['encryptPassword'];
                    $enDigit = Yii::$app->params['encryptDigit'];

                    $aes = new Aes();
                    $Sign = $aes->encrypt($result->userSign, $enPassword, $enDigit);
                    $cookies = Yii::$app->response->cookies;//cookie 注意，发送Cookie 是response 读取是 request
                    $signCookie = new Cookie([
                        'name' => Yii::$app->params['suiuu_sign'],
                        'value' => $Sign,
                    ]);
                    $signCookie->expire = time() + 24 * 60 * 60 * floor(Yii::$app->params['cookie_expire']);
                    $cookies->add($signCookie);
                }
                //清除错误登录次数
                Yii::$app->redis->del(Code::USER_LOGIN_ERROR_COUNT_PREFIX . $username);
                //跳转用户登录前的页面
                return json_encode(Code::statusDataReturn(Code::SUCCESS));

            } else {
                Yii::$app->redis->set(Code::USER_LOGIN_ERROR_COUNT_PREFIX . $username, ++$errorCount);
                Yii::$app->redis->expire(Code::USER_LOGIN_ERROR_COUNT_PREFIX . $username, Code::USER_LOGIN_VERIFY_CODE_EXPIRE_TIME);

                $errors[] = "用户名或密码错误";
                return json_encode(Code::statusDataReturn(Code::FAIL, $errors[0], $errorCount));
            }

        } catch (Exception $e) {
            LogUtils::log($e);
            $errors[] = "登录失败";
            return json_encode(Code::statusDataReturn(Code::FAIL, $errors[0], $errorCount));

        }
    }

    /**
     * 用户手机注册
     * @return mixed
     */
    public function actionPhoneRegister()
    {

        $sendCode=Yii::$app->request->post('code');
        $password=Yii::$app->request->post('password');
        $nickname=Yii::$app->request->post('nickname');

        if(empty($sendCode))
        {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'验证码错误'));
        }
        $valPassword=Validate::validatePassword($password);
        if(!empty($valPassword))
        {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$valPassword));
        }
        $valNickname=Validate::validateNickname($nickname);
        if(!empty($valNickname))
        {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$valNickname));
        }

        $session = \Yii::$app->session->get(Code::USER_PHONE_VALIDATE_CODE_AND_PHONE);
        if (empty($session)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, Code::USER_PHONE_CODE_ERROR));
        }
        $array = explode('-', $session);
        $phone = $array[0];
        $areaCode = $array[1];
        $validateCode = $array[2];

        $error = "";//错误信息
        $valMsg = Validate::validatePhone($phone);
        if (!empty($valMsg)) {
            $error = $valMsg;
        } else if (empty($password) || strlen($password) > 30) {
            $error = '密码格式不正确';
        } else if (empty($areaCode)) {
            $error = '手机区号格式不正确';
        } else if ($sendCode != $validateCode) {
            $error = '验证码输入有误，请查证后再试';
        }

        if (!empty($error)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, $error));
        }

        try {
            $userBase = new UserBase();
            $userBase->phone = $phone;
            $userBase->password = $password;
            $userBase->nickname=$nickname;
            $userBase = $this->userBaseService->addUser($userBase);
            //添加用户登录状态
            \Yii::$app->session->set(Code::USER_LOGIN_SESSION, $userBase);
            return json_encode(Code::statusDataReturn(Code::SUCCESS, 'success'));
        } catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }



    /**
     * 给用户发送短信
     */
    public function actionSendMessage()
    {
        $phone = \Yii::$app->request->post('phone');//发送给用户的手机
        $areaCode = \Yii::$app->request->post('areaCode');//区号
        $password = \Yii::$app->request->post('password');
        $nickname = \Yii::$app->request->post('nickname');//用户输入的昵称
        $valNum=\Yii::$app->request->post("valNum");
        $sysNum= \Yii::$app->session->get(Code::USER_LOGIN_VERIFY_CODE);
        //$passwordConfirm = \Yii::$app->request->post('passwordConfirm');
        /*else if ($password != $passwordConfirm) {
            $error = '两次密码输入不一致';
        }*/
        $error = "";//错误信息
        $valMsg = Validate::validatePhone($phone);
        $valNicknameMsg=Validate::validateNickname($nickname);
        if (!empty($valMsg)) {
            $error = $valMsg;
        } else if(!empty($valNicknameMsg)){
            $error = $valNicknameMsg;
        }else if (empty($password) || strlen($password) > 30) {
            $error = '密码格式不正确';
        } else if (empty($areaCode)) {
            $error = '手机区号格式不正确';
        }else if(empty($valNum)||strtolower($valNum)!=strtolower($sysNum)){
            $error = '图形验证码输入有误';
        }
        if (!empty($error)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, $error));
        }

        $count = \Yii::$app->redis->get(Code::USER_SEND_COUNT_PREFIX . $phone);

        if ($count < Code::MAX_SEND_COUNT) {
            //判断手机是否已经注册
            $userBase = $this->userBaseService->findUserByPhone($phone);
            if (!empty($userBase)) {
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, Code::USER_PHONE_EXIST));
            }
            $code = $this->randomPhoneCode();//验证码
            //分割可能会有问题，测试阶段
            \Yii::$app->session->set(Code::USER_PHONE_VALIDATE_CODE_AND_PHONE, $phone . "-" . $areaCode . "-" . $code);
            //调用发送短信接口 测试默认为成功
            //调用发送短信接口 测试默认为成功
            $smsUtils = new SmsUtils();
            $rst = $smsUtils->sendMessage($phone, $areaCode,$code,SmsUtils::SEND_MESSAGE_TYPE_REGISTER);
            if ($rst['status'] == Code::SUCCESS) {
                //设置手机定时器，控制发送频率
                Yii::$app->redis->set(Code::USER_SEND_COUNT_PREFIX . $phone, ++$count);
                Yii::$app->redis->expire(Code::USER_SEND_COUNT_PREFIX . $phone, Code::USER_LOGIN_VERIFY_CODE_EXPIRE_TIME);
                return json_encode(Code::statusDataReturn(Code::SUCCESS, Code::USER_REGISTER_TIMER));
            } else {
                return json_encode(Code::statusDataReturn(Code::FAIL, '短信发送异常'));
            }
        } else {
            return json_encode(Code::statusDataReturn(Code::FAIL, "发送验证码过于频繁，请稍后再试"));
        }


    }


    /**
     * 第三方登录发送短信验证
     * @return string
     * @throws Exception
     */
    public function actionAccSendMessage()
    {
        $phone = \Yii::$app->request->post('phone');//发送给用户的手机
        $areaCode = \Yii::$app->request->post('areaCode');//区号
        $error = "";//错误信息
        $valMsg = Validate::validatePhone($phone);
        if (!empty($valMsg)) {
            $error = $valMsg;
        } else if (empty($areaCode)) {
            $error = '手机区号格式不正确';
        }

        if (!empty($error)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, $error));
        }

        $count = \Yii::$app->redis->get(Code::USER_SEND_COUNT_PREFIX . $phone);

        if ($count < Code::MAX_SEND_COUNT) {
            //判断手机是否已经注册
            $userBase = $this->userBaseService->findUserByPhone($phone);
            if (!empty($userBase)) {
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, Code::USER_PHONE_EXIST));
            }
            $code = $this->randomPhoneCode();//验证码
            //分割可能会有问题，测试阶段
            \Yii::$app->session->set(Code::USER_PHONE_VALIDATE_CODE_AND_PHONE, $phone . "-" . $areaCode . "-" . $code);
            //调用发送短信接口 测试默认为成功
            //调用发送短信接口 测试默认为成功
            $smsUtils = new SmsUtils();
            $rst = $smsUtils->sendMessage($phone, $areaCode,$code,SmsUtils::SEND_MESSAGE_TYPE_REGISTER);
            if ($rst['status'] == Code::SUCCESS) {
                //设置手机定时器，控制发送频率
                Yii::$app->redis->set(Code::USER_SEND_COUNT_PREFIX . $phone, ++$count);
                Yii::$app->redis->expire(Code::USER_SEND_COUNT_PREFIX . $phone, Code::USER_LOGIN_VERIFY_CODE_EXPIRE_TIME);
                return json_encode(Code::statusDataReturn(Code::SUCCESS, Code::USER_REGISTER_TIMER));
            } else {
                return json_encode(Code::statusDataReturn(Code::FAIL, '短信发送异常'));
            }
        } else {
            return json_encode(Code::statusDataReturn(Code::FAIL, "发送验证码过于频繁，请稍后再试"));
        }

    }

    /**
     * 给用户发送邮件
     * @return mixed
     */
    public function actionSendEmail()
    {
        $nickname = \Yii::$app->request->post('nickname');//用户输入的昵称
        $email = \Yii::$app->request->post('email');//用户输入的邮箱
        $password = \Yii::$app->request->post('password');//用户输入的密码
        $error = "";//错误信息
        $valMsg = Validate::validateEmail($email);
        $valNicknameMsg=Validate::validateNickname($nickname);
        if (!empty($valMsg)) {
            $error = $valMsg;
        } else if (empty($password) || strlen($password) > 30) {
            $error = '密码格式不正确';
        }
        if(!empty($valNicknameMsg)){
            $error = $valNicknameMsg;
        }

        if (!empty($error)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, $error));
        }


        $emailTime = $this->getEmailTime();
        if ($emailTime == 0) {
            //判断邮箱是否已经注册
            $userBase = $this->userBaseService->findUserByEmail($email);
            if (!empty($userBase)) {
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, Code::USER_EMAIL_EXIST));
            }
            $enPwd = $this->getEncryptPassword($password);
            $code = $this->getEmailCode($email, $enPwd,$nickname);
            $url = \Yii::$app->params['base_dir'] . '/index/active?e=' . urlencode($email) . '&p=' . urlencode($enPwd) . '&c=' . urlencode($code). '&n=' . urlencode($nickname);
            //最终发送的地址内容
            $rst = Mail::sendRegisterMail($email, $url);
            //
            if ($rst['status'] == Code::SUCCESS) {
                //设置邮件定时器，控制发送频率
                \Yii::$app->session->set(Code::USER_REGISTER_EMAIL_TIMER, date('Y-m-d H:i:s', time()));
                return json_encode(Code::statusDataReturn(Code::SUCCESS, Code::USER_REGISTER_TIMER));
            } else {
                return json_encode(Code::statusDataReturn(Code::FAIL, "发送邮件失败，请稍后重试"));
            }
        } else {
            return json_encode(Code::statusDataReturn(Code::FAIL, "发送邮件过于频繁，请稍后再试"));
        }
    }


    /**
     * 用户验证邮箱是否正确 并且注册
     * @return string
     */
    public function actionActive()
    {
        $email = \Yii::$app->request->get('e');//用户输入的邮箱
        $password = \Yii::$app->request->get('p');//用户输入的密码
        $nickname = \Yii::$app->request->get('n');//用户输入的昵称
        $code = \Yii::$app->request->get('c');//用户输入的密码

        $valCode = $this->getEmailCode($email, $password,$nickname);
        $password = $this->getDecryptPassword($password);

        if ($code != $valCode) {
            return $this->redirect(['/result', 'result' => '无效的链接地址！！']);
        }
        try {
            $userBase = new UserBase();
            $userBase->email = $email;
            $userBase->password = $password;
            $userBase->nickname=$nickname;
            $userBase=$this->userBaseService->addUser($userBase);
            //设置SESSION 登录状态
            Yii::$app->session->set(Code::USER_LOGIN_SESSION, $userBase);
            return $this->redirect(['/result', 'result' => '注册成功！']);
        } catch (Exception $e) {
            LogUtils::log($e);
            return $this->redirect(['/result', 'result' => '验证邮箱失败！']);
        }

    }

    /**
     * 根据邮件获取邮件验证Code
     * @param $email
     * @param $password
     * @param $nickname
     * @return string
     */
    private function getEmailCode($email, $password,$nickname="")
    {
        return md5(md5($email . \Yii::$app->params['emailEncryptPassword'] . $password) . \Yii::$app->params['emailEncryptPassword'].$nickname);
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
        return Aes::encrypt($password, $enPassword, $enDigit);
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
        return Aes::decrypt($password, $enPassword, $enDigit);
    }


    /**
     * 生成手机六位验证码
     * @return string
     */
    private function randomPhoneCode()
    {
        $code = "";
        for ($i = 0; $i < 6; $i++) {
            $code .= rand(0, 9);
        }
        return $code;
    }



    /**
     * 发送随友注册验证码
     */
    public function actionSendTravelCode()
    {
        $areaCode = \Yii::$app->request->post("areaCode", "");
        $phone = \Yii::$app->request->post("phone", "");
        if (empty($areaCode)) {
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "区号不能为空"));
            return;
        }
        if (empty($phone)) {
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "接受号码不能为空"));
            return;
        }

        //验证验证码是否正确
        if($this->userBaseService->validatePhoneExist($phone,null)){
            echo json_encode(Code::statusDataReturn(Code::FAIL,"手机号码已经注册"));
            return;
        }

        $count = Yii::$app->redis->get(Code::USER_SEND_ERROR_COUNT_PREFIX . $phone);
        if ($count >= Code::MAX_SEND_COUNT) {
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "发送次数过多24小时后将继续发送"));
            return;
        }
        $code = $this->randomPhoneCode();
        //设置验证码 和 有效时长
        \Yii::$app->redis->set(Code::USER_PHONE_VALIDATE_CODE_AND_PHONE . $phone, $code);
        Yii::$app->redis->expire(Code::USER_PHONE_VALIDATE_CODE_AND_PHONE . $phone, Code::USER_PHONE_VALIDATE_CODE_EXPIRE_TIME);

        //设置验证码次数时效
        Yii::$app->redis->set(Code::USER_SEND_COUNT_PREFIX . $phone, ++$count);
        Yii::$app->redis->expire(Code::USER_SEND_COUNT_PREFIX . $phone, Code::USER_LOGIN_VERIFY_CODE_EXPIRE_TIME);

        //调用发送短信接口 测试默认为成功
        $smsUtils = new SmsUtils();
        $rst = $smsUtils->sendMessage($phone, $areaCode,$code,SmsUtils::SEND_MESSAGE_TYPE_REGISTER);
        echo json_encode($rst);
    }

    /**
     * 验证手机
     * @return string
     * @throws Exception
     * @throws \Exception
     */
    public function actionValidatePhone()
    {

        $phone=trim(\Yii::$app->request->post("phone", ""));
        $code=trim(\Yii::$app->request->post("code", ""));
        if(empty($phone)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "未知的手机号"));
        }
        if(empty($code)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "未知的验证码"));
        }
        $rCode =\Yii::$app->redis->get(Code::USER_PHONE_VALIDATE_CODE_AND_PHONE . $phone);
        if($code===$rCode)
        {
            $userBase=null;
            if($this->userObj==null){
                $userBase=new UserBase();
            }else{
                $userBase= clone $this->userObj;
            }

            $rst=$this->userBaseService->findBaseAllBySign($userBase->userSign);
            if(empty($rst)||$rst==false)
            {
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERRORi, "未知的用户"));
            }
            $rst->phone=$phone;
            $this->userBaseService->updateUserBase($rst);
            $this->refreshUserInfo();
            return json_encode(Code::statusDataReturn(Code::SUCCESS, "验证成功"));
        }else{
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "验证码错误"));
        }
    }


    /**
     * 验证邮箱
     * @return string|yii\web\Response
     */
    public function actionValidateMail()
    {
        $email = \Yii::$app->request->get('e');//用户输入的邮箱
        $sign = \Yii::$app->request->get('p');//用户sign
        $code = \Yii::$app->request->get('c');//加密

        $valCode = $this->getEmailCode($email, $sign);
        $userSign = $this->getDecryptPassword($sign);

        if ($code != $valCode) {
            return $this->redirect(['/result', 'result' => '无效的链接地址！！']);
        }
        try {
            $rstUser=$this->userBaseService->findBaseAllBySign($userSign);
            if(empty($rstUser)||$rstUser==false)
            {
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "未知的用户"));
            }
            $rstUser->email=$email;
            $this->userBaseService->updateUserBase($rstUser);
            $this->refreshUserInfo();
        } catch (Exception $e) {
            LogUtils::log($e);
            return $this->redirect(['/result', 'result' => '验证邮箱失败！']);
        }
        return $this->redirect(['/result', 'result' => '验证成功']);

    }

    /**
     * 发送验证邮件
     * @return string
     * @throws Exception
     * @throws \Exception
     */
    public function actionSendValidateMail()
    {
        $mail=trim(\Yii::$app->request->post("mail", ""));
        $val=Validate::validateEmail($mail);
        if(!empty($val)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, $val));
        }
        if(empty($mail)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "未知的邮箱"));
        }
        if($this->userObj!=null&&$this->userObj->email==$mail){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, '修改邮箱不能和旧邮箱相同'));
        }
        $count = Yii::$app->redis->get(Code::USER_SEND_COUNT_PREFIX .$mail );
        if ($count > Code::MAX_SEND_COUNT) {
            return json_encode(Code::statusDataReturn(Code::FAIL, '发送次数过多24小时后将继续发送'));
        } else {
            //判断邮箱是否已经注册
            $userBase = $this->userBaseService->findUserByEmail($mail);
            if (!empty($userBase)) {
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, Code::USER_EMAIL_EXIST));
            }
            $userBase=null;
            if($this->userObj==null){
                $userBase=new UserBase();
            }else{
                $userBase= clone $this->userObj;
            }
            $rstUser=$this->userBaseService->findBaseInfoBySign($userBase->userSign);
            if(empty($rstUser)||$rstUser==false)
            {
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "未知的用户"));
            }

            $enPwd = $this->getEncryptPassword($userBase->userSign);
            $code = $this->getEmailCode($mail, $enPwd);
            $url = \Yii::$app->params['base_dir'] . '/index/validate-mail?e=' . urlencode($mail) . '&p=' . urlencode($enPwd) . '&c=' . urlencode($code);
            //最终发送的地址内容
            $rst = Mail::sendValidateMail($mail, $url);
            //
            if ($rst['status'] == Code::SUCCESS) {
                return json_encode(Code::statusDataReturn(Code::SUCCESS, '发送成功'));
            } else {
                return json_encode(Code::statusDataReturn(Code::FAIL, "发送邮件失败，请稍后重试"));
            }
        }
    }



    /**
     * 激活随友注册
     * @return yii\web\Response
     * @throws Exception
     */
    public function actionActivePub()
    {
        $key = \Yii::$app->request->get('c');//用户输入的密码

        $jsonInfo=Yii::$app->redis->get($key);

        if (empty($jsonInfo)) {
            return $this->redirect(['/result', 'result' => '无效的链接地址！！']);
        }

        $jsonInfo=json_decode($jsonInfo,true);
        $userBase=$jsonInfo['userBase'];
        $userPublisher=$jsonInfo['userPublisher'];
        $userBase=$this->userBaseService->arrayCastObject($userBase,UserBase::class);
        $userPublisher=$this->userBaseService->arrayCastObject($userPublisher,UserPublisher::class);
        try{
            if(empty($userBase->userId)){
                $userBase=$this->userBaseService->addUser($userBase,null,$userPublisher);
            }else{
                $userBase=$this->userBaseService->updateUserBaseAndAddUserPublisher($userBase,$userPublisher);
            }
            //设置SESSION 登录状态
            Yii::$app->session->set(Code::USER_LOGIN_SESSION, $userBase);

            return $this->redirect('/index/reg-pub-success');
        }catch (Exception $e){
            return $this->redirect(['/result', 'result' => '邮箱认证失败:'.$e->getMessage()]);

        }
    }

    /**
     * 跳转到等待验证页面
     * @return string
     */
    public function actionWaitEmailValidate()
    {
        return $this->render("waitEmailValidate");
    }

    /**
     * 跳转到注册成功页面
     * @return string
     */
    public function actionRegPubSuccess()
    {
        return $this->render("regPubSuccess");
    }



    /**
     * 发送邮箱验证
     * @param $userBase
     * @param $userPublisher
     * @return array
     */
    private function sendPublisherEmailValidate($userBase,$userPublisher)
    {
        try{
            $userInfo=[
                'userBase'=>$userBase,
                'userPublisher'=>$userPublisher
            ];
            $key=Code::getUUID();
            Yii::$app->redis->set($key,json_encode($userInfo));
            Yii::$app->redis->expire($key, Code::USER_EMAIL_VALIDATE_CODE_EXPIRE_TIME);
            $link = \Yii::$app->params['base_dir'] . '/index/active-pub?c='.urlencode($key);

            return Mail::sendRegisterMail($userBase->email,$link);
        }catch (Exception $e){
            return Code::statusDataReturn(Code::FAIL,$e->getName());
        }
    }

    /**
     * 获取用户邮箱定时器
     * @return int|mixed
     */
    public function getEmailTime()
    {
        $emailTime = \Yii::$app->session->get(Code::USER_REGISTER_EMAIL_TIMER);
        if (!empty($emailTime)) {
            $now = date('Y-m-d H:i:s', time());
            $tempTime = strtotime($now) - strtotime($emailTime);
            $emailTime = $tempTime > Code::USER_REGISTER_TIMER ? 0 : Code::USER_REGISTER_TIMER - $tempTime;
        }
        return $emailTime;
    }


    public function actionError()
    {
        return $this->render("error",[
           'message'=>'系统异常'
        ]);
    }


    /**
     * 验证手机是否存在
     * @return mixed|string
     */
    public function actionValPhoneExist()
    {
        $phone=Yii::$app->request->post("phone","");
        if(Validate::validatePhone($phone)!=''){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的手机号码"));

        }

        try{
            $userBase=$this->userBaseService->findUserByPhone($phone);
            if($userBase==null){
                return json_encode(Code::statusDataReturn(Code::SUCCESS,0));
            }else{
                return json_encode(Code::statusDataReturn(Code::SUCCESS,1));
            }
        }catch (Exception $e){
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }


}