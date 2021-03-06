<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/23
 * Time : 下午2:11
 * Email: zhangxinmailvip@foxmail.com
 */

namespace app\modules\v1\controllers;


use common\components\LogUtils;
use common\components\Validate;
use common\components\ValidateCode;
use app\modules\v1\services\CountryService;
use common\components\Aes;
use common\components\Code;
use common\components\SMSUtils;
use app\modules\v1\entity\UserAccess;
use app\modules\v1\entity\UserBase;
use app\modules\v1\services\UserBaseService;
use yii\base\Exception;
use yii;

class AppLoginController extends AController{

    private $userBaseService;
    public $enableCsrfValidation=false;
    public $layout=false;
    public function __construct($id, $module)
    {
        parent::__construct($id, $module);
        $this->userBaseService=new UserBaseService();
    }


    public function actionAccessBind()
    {
        $username=trim(\Yii::$app->request->post('username',""));
        $password=trim(\Yii::$app->request->post('password',""));
        $type=trim(\Yii::$app->request->post('type',""));
        $openId=trim(\Yii::$app->request->post('unionID',""));
        if($openId=="")
        {
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR, "openId不能为空"));
        }
        $valPassword=Validate::validatePassword($password);
        if(!empty($valPassword)) {
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR, $valPassword));
        }
        try{
            $userBaseService=new UserBaseService();
            if(strpos($username,"@")){
                $valUsername=Validate::validateEmail($username);
                if(!empty($valUsername)){
                    return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,$valUsername));
                }
            }else{
                $valUsername=Validate::validatePhone($username);
                if(!empty($valUsername)){
                    return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,$valUsername));
                }
            }
            $userBase=$userBaseService->findUserByUserNameAndPwd($username,$password);
            if($userBase!=null){
                $userAccess=new UserAccess();
                $userAccess->userId=$userBase->userSign;
                $userAccess->type= $type;
                $userAccess->openId= $openId;
                $userBaseService->addUserAccess($userAccess);
                $userBase=$this->userBaseService->findUserAccessByOpenIdAndType($openId,$type);
                if($userBase!=null){
                    if($userBase->status!=UserBase::USER_STATUS_NORMAL){
                        return $this->apiReturn(Code::statusDataReturn(Code::FAIL,"User Status Is Disabled"));
                    }else{
                        $enPassword = \Yii::$app->params['encryptPassword'];
                        $enDigit = \Yii::$app->params['encryptDigit'];
                        $aes=new Aes();
                        $sysSign=$aes->encrypt($userBase->userSign,$enPassword,$enDigit);
                        //设置聊天SESSION
                        $chatUser=\Yii::$app->redis->get(Code::USER_LOGIN_SESSION_CHAT.$sysSign);
                        if(empty($chatUser)){
                            \Yii::$app->redis->set(Code::USER_LOGIN_SESSION_CHAT.$sysSign,json_encode($userBase));
                            \Yii::$app->redis->expire(Code::USER_LOGIN_SESSION_CHAT.$sysSign,Code::APP_USER_LOGIN_VERIFY_CODE_EXPIRE_TIME);
                        }
                        \Yii::$app->redis->set(Code::APP_USER_LOGIN_SESSION.$sysSign,json_encode($userBase));
                        \Yii::$app->redis->expire(Code::APP_USER_LOGIN_SESSION.$sysSign,Code::APP_USER_LOGIN_VERIFY_CODE_EXPIRE_TIME);
                        return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$userBase,$sysSign));
                    }
                }
            }else{
                return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的用户名或密码"));
            }
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,"绑定用户异常，请稍后重试"));
        }
    }

    public function actionAccessReg()
    {
        $sendCode=trim(\Yii::$app->request->post('code',""));
        $phone=trim(\Yii::$app->request->post('phone',""));
        $password=trim(\Yii::$app->request->post('password',""));
        $nickname=trim(\Yii::$app->request->post('nickname',""));
        $areaCode=\Yii::$app->request->post('areaCode',"+86");
        $type=trim(\Yii::$app->request->post('type',""));
        $openId=trim(\Yii::$app->request->post('unionID',""));
        $sex=\Yii::$app->request->post("sex");
        $headImg=\Yii::$app->request->post("headImg");
        if(empty($sex))
        {
            $sex=UserBase::USER_SEX_SECRET;
        }
        if(empty($headImg))
        {
            $headImg = \Yii::$app->params["base_dir"] . '/assets/images/user_default.png';
        }
        if(empty($sendCode))
        {
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,'验证码错误'));
        }
        $valPassword=Validate::validatePassword($password);
        if(!empty($valPassword))
        {
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,$valPassword));
        }
        $valNickname=Validate::validateNickname($nickname);
        if(!empty($valNickname))
        {
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,$valNickname));
        }

        $rCode=\Yii::$app->redis->get(Code::USER_PHONE_VALIDATE_CODE_AND_PHONE . $phone);
        if(empty($rCode)||$rCode!=$sendCode)
        {
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,'验证码不正确'));
        }

        $error = "";//错误信息
        $valMsg = Validate::validatePhone($phone);
        if (!empty($valMsg)) {
            $error = $valMsg;
        } else if (empty($password) || strlen($password) > 30) {
            $error = '密码格式不正确';
        } else if (empty($areaCode)) {
            $error = '手机区号格式不正确';
        }
        if (!empty($error)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, $error));
        }

        try {
            $userAccess=new UserAccess();
            $userAccess->type= $type;
            $userAccess->openId= $openId;
            $userBaseService=new UserBaseService();
            $tempUserBase=$userBaseService->findUserAccessByOpenIdAndType($userAccess->openId,$userAccess->type);
            if($tempUserBase==null){
                $userBase=new UserBase();
                $userBase->phone = $phone;
                $userBase->password = $password;
                $userBase->nickname=$nickname;
                $userBase->areaCode=$areaCode;
                $userBase->sex=$sex;
                $userBase->headImg=$headImg;
                $userBase=$userBaseService->addUser($userBase,$userAccess);
            }else{
                $userBase=$tempUserBase;
                $userBase->phone=$phone;
                $userBase->phone = $phone;
                $userBase->password = $password;
                $userBase->nickname=$nickname;
                $userBase->sex=$sex;
                $userBase->headImg=$headImg;
                $userBaseService->updateUserBase($userBase);
            }
            $enPassword = \Yii::$app->params['encryptPassword'];
            $enDigit = \Yii::$app->params['encryptDigit'];
            $aes=new Aes();
            $sysSign=$aes->encrypt($userBase->userSign,$enPassword,$enDigit);
            //设置聊天SESSION
            $chatUser=\Yii::$app->redis->get(Code::USER_LOGIN_SESSION_CHAT.$sysSign);
            if(empty($chatUser)){
                \Yii::$app->redis->set(Code::USER_LOGIN_SESSION_CHAT.$sysSign,json_encode($userBase));
                \Yii::$app->redis->expire(Code::USER_LOGIN_SESSION_CHAT.$sysSign,Code::APP_USER_LOGIN_VERIFY_CODE_EXPIRE_TIME);
            }
            \Yii::$app->redis->set(Code::APP_USER_LOGIN_SESSION.$sysSign,json_encode($userBase));
            \Yii::$app->redis->expire(Code::APP_USER_LOGIN_SESSION.$sysSign,Code::APP_USER_LOGIN_VERIFY_CODE_EXPIRE_TIME);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$userBase,$sysSign));
        } catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL, $e->getMessage()));
        }

    }

    /**
     * App 第三方登录接口
     * @return null
     */
    public function actionAccessLogin()
    {
        try{
        $openId=\Yii::$app->request->post("unionID");
        $nickname=\Yii::$app->request->post("nickname");
        $sex=\Yii::$app->request->post("sex");
        $headImg=\Yii::$app->request->post("headImg");
        $type=\Yii::$app->request->post("type");
        $sign=\Yii::$app->request->post("sign");
        if(empty($openId)){
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"OpenId Is Not Allow Empty"));
            exit;
        }
        if(empty($sign)){
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"Sign Is Not Allow Empty"));
            exit;
        }
        if(empty($type)){
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"Type Is Not Allow Empty"));
            exit;
        }
        if(!$this->validateLoginParamSign($openId,$type,$sign)){
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"Invalid Sign Value"));
            exit;
        }

        /**
         * 判断用户是否之前接入过
         */
        $userBase=$this->userBaseService->findUserAccessByOpenIdAndType($openId,$type);
        if($userBase!=null){
            if($userBase->phone!=null||$userBase->email!=null){
            if($userBase->status!=UserBase::USER_STATUS_NORMAL){
                echo json_encode(Code::statusDataReturn(Code::FAIL,"User Status Is Disabled"));
            }else{
                $enPassword = \Yii::$app->params['encryptPassword'];
                $enDigit = \Yii::$app->params['encryptDigit'];
                $aes=new Aes();
                $sysSign=$aes->encrypt($userBase->userSign,$enPassword,$enDigit);
                //设置聊天SESSION
                $chatUser=\Yii::$app->redis->get(Code::USER_LOGIN_SESSION_CHAT.$sysSign);
                if(empty($chatUser)){
                    \Yii::$app->redis->set(Code::USER_LOGIN_SESSION_CHAT.$sysSign,json_encode($userBase));
                    \Yii::$app->redis->expire(Code::USER_LOGIN_SESSION_CHAT.$sysSign,Code::APP_USER_LOGIN_VERIFY_CODE_EXPIRE_TIME);
                }
                \Yii::$app->redis->set(Code::APP_USER_LOGIN_SESSION.$sysSign,json_encode($userBase));
                \Yii::$app->redis->expire(Code::APP_USER_LOGIN_SESSION.$sysSign,Code::APP_USER_LOGIN_VERIFY_CODE_EXPIRE_TIME);
                echo json_encode(Code::statusDataReturn(Code::SUCCESS,$userBase,$sysSign));
            }
            exit;
            }
        }
        if($sex!=UserBase::USER_SEX_MALE&&$sex!=UserBase::USER_SEX_FEMALE&&$sex!=UserBase::USER_SEX_SECRET){
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"Invalid Sex Value"));
            exit;
        }
        if($type!=UserAccess::ACCESS_TYPE_QQ&&$type!=UserAccess::ACCESS_TYPE_WECHAT&&$type!=UserAccess::ACCESS_TYPE_SINA_WEIBO){
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"Invalid Type Value"));
            exit;
        }

        $userBase=null;

        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,$e->getName()));
        }
        return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$userBase));

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
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,'用户手机号不能为空'));
        }
        return  $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$countryList));

    }
    public function actionGetPhoneCode()
    {
        $areaCode=\Yii::$app->request->post('areaCode');
        $phone=\Yii::$app->request->post('phone');
        if(empty($phone))
        {
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,'用户手机号不能为空'));
        }
        if(empty($areaCode))
        {
            $areaCode='+86';
        }

        $count = \Yii::$app->redis->get(Code::USER_SEND_ERROR_COUNT_PREFIX . $phone);
        if ($count >= Code::MAX_SEND_COUNT) {
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR, "发送次数过多24小时后将继续发送"));
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
        return $this->apiReturn($rst);
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
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,'密码不能为空'));
        }
        if($cPassword!=$password)
        {
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,'密码不一致'));
        }
        $rCode=\Yii::$app->redis->get(Code::USER_PHONE_VALIDATE_CODE_AND_PHONE . $phone);
        if(empty($rCode)||$rCode!=$code)
        {
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,'验证码不正确'));
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
            //设置聊天SESSION
            $chatUser=\Yii::$app->redis->get(Code::USER_LOGIN_SESSION_CHAT.$sysSign);
            if(empty($chatUser)){
                \Yii::$app->redis->set(Code::USER_LOGIN_SESSION_CHAT.$sysSign,json_encode($userBase));
                \Yii::$app->redis->expire(Code::USER_LOGIN_SESSION_CHAT.$sysSign,Code::APP_USER_LOGIN_VERIFY_CODE_EXPIRE_TIME);
            }
            \Yii::$app->redis->set(Code::APP_USER_LOGIN_SESSION.$sysSign,json_encode($user));
            \Yii::$app->redis->expire(Code::APP_USER_LOGIN_SESSION.$sysSign,Code::APP_USER_LOGIN_VERIFY_CODE_EXPIRE_TIME);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$user,$sysSign));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
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
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
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
                //设置聊天SESSION
                $chatUser=\Yii::$app->redis->get(Code::USER_LOGIN_SESSION_CHAT.$sysSign);
                if(empty($chatUser)){
                    \Yii::$app->redis->set(Code::USER_LOGIN_SESSION_CHAT.$sysSign,json_encode($result));
                    \Yii::$app->redis->expire(Code::USER_LOGIN_SESSION_CHAT.$sysSign,Code::APP_USER_LOGIN_VERIFY_CODE_EXPIRE_TIME);
                }
                \Yii::$app->redis->set(Code::APP_USER_LOGIN_SESSION.$sysSign,json_encode($result));
                \Yii::$app->redis->expire(Code::APP_USER_LOGIN_SESSION.$sysSign,Code::APP_USER_LOGIN_VERIFY_CODE_EXPIRE_TIME);
                //如果用户点击记住密码，设置Cookie

                //清除错误登录次数
                \Yii::$app->redis->del(Code::APP_USER_LOGIN_ERROR_COUNT_PREFIX.$username);
                return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$result,$sysSign));
            }else{

                \Yii::$app->redis->set(Code::APP_USER_LOGIN_ERROR_COUNT_PREFIX.$username,++$errorCount);
                \Yii::$app->redis->expire(Code::APP_USER_LOGIN_ERROR_COUNT_PREFIX.$username,Code::APP_USER_LOGIN_VERIFY_CODE_EXPIRE_TIME);
                $error="用户名或密码错误";
                return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
            }
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR));
        }



    }

    public function actionPasswordCode()
    {
        $phone = Yii::$app->request->post('phone');//用户名
        if (empty($phone) || strlen($phone) > 50 || strlen($phone) < 5) {
            $errors = "用户名格式不正确";
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL, $errors));
        }
        $count = Yii::$app->redis->get(Code::USER_SEND_COUNT_PREFIX . $phone);
        if ($count > Code::MAX_SEND_COUNT) {
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL, '发送次数过多24小时后将继续发送'));
        } else {
            if(empty(Validate::validatePhone($phone))){
                //手机验证
                $areaCode = Yii::$app->request->post('areaCode');
                $userBase = $this->userBaseService->findUserByPhone($phone);
                if (empty($userBase)) {
                    return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR, '用户不存在，请先注册'));
                }
                $code = $this->randomPhoneCode();
                //设置验证码 和 有效时长
                \Yii::$app->redis->set(Code::USER_PHONE_VALIDATE_CODE_AND_PHONE_FOR_PASSWORD . $phone, $code);
                Yii::$app->redis->expire(Code::USER_PHONE_VALIDATE_CODE_AND_PHONE_FOR_PASSWORD . $phone, Code::USER_PHONE_VALIDATE_CODE_EXPIRE_TIME);
                //调用发送短信接口 测试默认为成功
                $smsUtils = new SmsUtils();
                $rst = $smsUtils->sendMessage($phone, $areaCode,$code,SmsUtils::SEND_MESSAGE_TYPE_PASSWORD);
                if(!empty($rst))
                {
                    Yii::$app->redis->set(Code::USER_SEND_COUNT_PREFIX . $phone, ++$count);
                    Yii::$app->redis->expire(Code::USER_SEND_COUNT_PREFIX . $phone, Code::USER_LOGIN_VERIFY_CODE_EXPIRE_TIME);
                    return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS));
                }else {
                    return $this->apiReturn(Code::statusDataReturn(Code::FAIL, "发送信息失败，请稍后重试"));
                }
            } else {
                $errors = "用户名格式不正确";
                return $this->apiReturn(Code::statusDataReturn(Code::FAIL, $errors));
            }
        }
    }

    //找回密码
    public function actionUpdatePassword()
    {

        $phone = Yii::$app->request->post('phone');//用户名
        if (empty($phone) || strlen($phone) > 50 || strlen($phone) < 5) {
            $errors = "用户名格式不正确";
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR, $errors));
        }
        $code = Yii::$app->request->post('code');//验证码
        $password= Yii::$app->request->post('password');
        if(empty($password))
        {
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,'密码不能为空'));
        }
        $count = Yii::$app->redis->get(Code::USER_SEND_COUNT_PREFIX . $phone);
        if ($count > Code::MAX_SEND_COUNT) {
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR, '验证码错误次数过多'));
        }
        $phoneCode=\Yii::$app->redis->get(Code::USER_PHONE_VALIDATE_CODE_AND_PHONE_FOR_PASSWORD . $phone);
        if(empty($phoneCode)||$code!=$phoneCode)
        {
            Yii::$app->redis->set(Code::USER_SEND_COUNT_PREFIX . $phone, ++$count);
            Yii::$app->redis->expire(Code::USER_SEND_COUNT_PREFIX . $phone, 1800);
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR, '验证码错误'));
        }
        try{
            $rst = $this->userBaseService->findUserByPhone($phone);
            if(empty($rst))
            {
                $error='未发现该用户';
                return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
            }
            $r=$this->userBaseService->updatePassword($rst->userSign,$password);
            if($r==1)
            {
                Yii::$app->session->set(Code::USER_NAME_SESSION,'');
                Yii::$app->redis->del(Code::USER_PHONE_VALIDATE_CODE_AND_PHONE_FOR_PASSWORD . $phone);
                return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,'修改成功'));
            }else
            {
                //密码重复
                return $this->apiReturn(Code::statusDataReturn(Code::FAIL,'密码重复无需修改'));
            }
        } catch (Exception $e) {
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }
    }

    public function actionLogout()
    {

        $appSign=\Yii::$app->request->post(\Yii::$app->params['app_suiuu_sign']);
        $rst = \Yii::$app->redis->del(Code::APP_USER_LOGIN_SESSION.$appSign);
        if($rst==1)
        {
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,Code::APP_USER_LOGOUT_SUCCESS_STR));
        }else
        {
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,Code::APP_USER_LOGOUT_FAIL_STR));
        }
    }


    public function actionLoginVerify()
    {
        //验证用户是否登录
        $appSign=\Yii::$app->request->post(\Yii::$app->params['app_suiuu_sign']);
        $currentUser=json_decode(stripslashes(\Yii::$app->redis->get(Code::APP_USER_LOGIN_SESSION.$appSign)));
        if(empty($currentUser))
        {
            return $this->apiReturn(Code::statusDataReturn(Code::UN_LOGIN));
        }else
        {
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS));
        }
    }

    public function actionGetCode()
    {

        $ValidateCode=new ValidateCode();
        $ValidateCode->doimg();
        \Yii::$app->session->set(Code::USER_LOGIN_VERIFY_CODE,$ValidateCode->getCode());
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