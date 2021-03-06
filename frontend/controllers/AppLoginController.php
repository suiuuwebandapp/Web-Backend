<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/23
 * Time : 下午2:11
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\controllers;


use common\components\LogUtils;
use common\components\ValidateCode;
use frontend\services\CountryService;
use common\components\Aes;
use common\components\Code;
use common\components\SMSUtils;
use common\entity\UserAccess;
use common\entity\UserBase;
use frontend\services\UserBaseService;
use yii\base\Exception;
use yii\web\Controller;

class AppLoginController extends SController{

    private $userBaseService;
    public $enableCsrfValidation=false;
    public $layout=false;
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
        try{
        $openId=\Yii::$app->request->post("openId");
        $nickname=\Yii::$app->request->post("nickname");
        $sex=\Yii::$app->request->post("sex");
        $headImg=\Yii::$app->request->post("headImg");
        $type=\Yii::$app->request->post("type");
        $sign=\Yii::$app->request->post("sign");


       /* $openId=Code::getUUID();
        $nickname="测试";
        $sex=1;
        $headImg="http://www.baidu.com";
        $type=1;
        $sign=md5($openId.$type.\Yii::$app->params['apiPassword']);*/

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
                $enPassword = \Yii::$app->params['encryptPassword'];
                $enDigit = \Yii::$app->params['encryptDigit'];
                $aes=new Aes();
                $sysSign=$aes->encrypt($userBase->userSign,$enPassword,$enDigit);
                \Yii::$app->redis->set(Code::APP_USER_LOGIN_SESSION.$sysSign,json_encode($userBase));
                \Yii::$app->redis->expire(Code::APP_USER_LOGIN_SESSION.$sysSign,Code::APP_USER_LOGIN_VERIFY_CODE_EXPIRE_TIME);
                echo json_encode(Code::statusDataReturn(Code::SUCCESS,$userBase,$sysSign));
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

        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,$e->getName()));
        }
        return json_encode(Code::statusDataReturn(Code::SUCCESS,$userBase));

    }


    private function validateLoginParamSign($openId,$type,$sign)
    {
        $valSign=md5($openId.$type.\Yii::$app->params['apiPassword']);
        return $valSign==$sign?true:false;
    }

    public function actionGetCountryList()
    {
        $countryService = new CountryService();
        $countryList=$countryService->getCountryList();
        if(empty($countryList)){
            return json_encode(Code::statusDataReturn(Code::FAIL,'用户手机号不能为空'));
        }
        return  json_encode(Code::statusDataReturn(Code::SUCCESS,$countryList));

    }
    public function actionGetPhoneCode()
    {
        $areaCode=\Yii::$app->request->post('areaCode');
        $phone=\Yii::$app->request->post('phone');
        /*$phone='17701085674';
        $areaCode="";*/
        if(empty($phone))
        {
            return json_encode(Code::statusDataReturn(Code::FAIL,'用户手机号不能为空'));
        }
        if(empty($areaCode))
        {
            $areaCode='+86';
        }

        $count = \Yii::$app->redis->get(Code::USER_SEND_ERROR_COUNT_PREFIX . $phone);
        if ($count >= Code::MAX_SEND_COUNT) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "发送次数过多24小时后将继续发送"));
        }
        $code = $this->randomPhoneCode();
        //设置验证码 和 有效时长
        \Yii::$app->redis->set(Code::USER_PHONE_VALIDATE_CODE_AND_PHONE . $phone, $code);
        \Yii::$app->redis->expire(Code::USER_PHONE_VALIDATE_CODE_AND_PHONE . $phone, Code::USER_PHONE_VALIDATE_CODE_EXPIRE_TIME);

        //设置验证码次数时效
        \Yii::$app->redis->set(Code::USER_SEND_COUNT_PREFIX . $phone, ++$count);
        \Yii::$app->redis->expire(Code::USER_SEND_COUNT_PREFIX . $phone, Code::USER_LOGIN_VERIFY_CODE_EXPIRE_TIME);

        $rst = null;
        $smsUtils = new SmsUtils();
        $rst = $smsUtils->sendMessage($phone,$areaCode, $code,SmsUtils::SEND_MESSAGE_TYPE_REGISTER);
        return json_encode($rst);
    }

    public function actionAppRegister()
    {
        $phone=\Yii::$app->request->post('phone');
        $password=\Yii::$app->request->post('password');
        $cPassword=\Yii::$app->request->post('cPassword');
        $nick=\Yii::$app->request->post('nick');
        $code=\Yii::$app->request->post('validateCode');//验证码
        $areaCode=\Yii::$app->request->post('areaCode',"+86");//验证码
        if(empty($password))
        {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'密码不能为空'));
        }
        if($cPassword!=$password)
        {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'密码不一致'));
        }
        $rCode=\Yii::$app->redis->get(Code::USER_PHONE_VALIDATE_CODE_AND_PHONE . $phone);
        if(empty($rCode)||$rCode!=$code)
        {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'验证码不正确'));
        }
        try{
            $userBase=new UserBase();
            $userBase->nickname=$nick;
            $userBase->password=$password;
            $userBase->phone=$phone;
            $userBase->areaCode=$areaCode;
            $user=$this->userBaseService->addUser($userBase);
            $enPassword = \Yii::$app->params['encryptPassword'];
            $enDigit = \Yii::$app->params['encryptDigit'];
            $aes=new Aes();
            $sysSign=$aes->encrypt($user->userSign,$enPassword,$enDigit);
            \Yii::$app->redis->set(Code::APP_USER_LOGIN_SESSION.$sysSign,json_encode($user));
            \Yii::$app->redis->expire(Code::APP_USER_LOGIN_SESSION.$sysSign,Code::APP_USER_LOGIN_VERIFY_CODE_EXPIRE_TIME);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$user,$sysSign));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }

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
    public function actionAppLogin()
    {

        $username=\Yii::$app->request->post('username');
        $password=\Yii::$app->request->post('password');
        /*$username='519414839@qq.com';
        $password='qwe123';*/
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
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
        try{
            //验证用户名是否存在
            $result=$this->userBaseService->findUserByUserNameAndPwd($username,$password);
            if(isset($result)){

                //设置Session
                $enPassword = \Yii::$app->params['encryptPassword'];
                $enDigit = \Yii::$app->params['encryptDigit'];
                $aes=new Aes();
                $sysSign=$aes->encrypt($result->userSign,$enPassword,$enDigit);
                \Yii::$app->redis->set(Code::APP_USER_LOGIN_SESSION.$sysSign,json_encode($result));
                \Yii::$app->redis->expire(Code::APP_USER_LOGIN_SESSION.$sysSign,Code::APP_USER_LOGIN_VERIFY_CODE_EXPIRE_TIME);
                //如果用户点击记住密码，设置Cookie

                //清除错误登录次数
                \Yii::$app->redis->del(Code::APP_USER_LOGIN_ERROR_COUNT_PREFIX.$username);
                return json_encode(Code::statusDataReturn(Code::SUCCESS,$result,$sysSign));
            }else{

                \Yii::$app->redis->set(Code::APP_USER_LOGIN_ERROR_COUNT_PREFIX.$username,++$errorCount);
                \Yii::$app->redis->expire(Code::APP_USER_LOGIN_ERROR_COUNT_PREFIX.$username,Code::APP_USER_LOGIN_VERIFY_CODE_EXPIRE_TIME);
                $error="用户名或密码错误";
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
            }
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR));
        }



    }

    public function actionLogout()
    {

        $appSign=\Yii::$app->request->post(\Yii::$app->params['app_suiuu_sign']);
        $rst = \Yii::$app->redis->del(Code::APP_USER_LOGIN_SESSION.$appSign);
        if($rst==1)
        {
            return json_encode(Code::statusDataReturn(Code::SUCCESS,Code::APP_USER_LOGOUT_SUCCESS_STR));
        }else
        {
            return json_encode(Code::statusDataReturn(Code::FAIL,Code::APP_USER_LOGOUT_FAIL_STR));
        }
    }


    public function actionLoginVerify()
    {
        //验证用户是否登录
        $appSign=\Yii::$app->request->post(\Yii::$app->params['app_suiuu_sign']);
        $currentUser=json_decode(stripslashes(\Yii::$app->redis->get(Code::APP_USER_LOGIN_SESSION.$appSign)));
        if(empty($currentUser))
        {
            return json_encode(Code::statusDataReturn(Code::UN_LOGIN));
        }else
        {
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }
    }

    public function actionGetCode()
    {

        $ValidateCode=new ValidateCode();
        $ValidateCode->doimg();
        \Yii::$app->session->set(Code::USER_LOGIN_VERIFY_CODE,$ValidateCode->getCode());
    }

    public function actionTest()
    {
        echo  md5('1');
    }

    private function ob2ar($obj) {
        if(is_object($obj)) {
            $obj = (array)$obj;
            $obj = $this->ob2ar($obj);
        } elseif(is_array($obj)) {
            foreach($obj as $key => $value) {
                $obj[$key] = $this->ob2ar($value);
            }
        }
        return $obj;
    }




}