<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/8/27
 * Time: 上午11:14
 */

namespace frontend\controllers;

use common\components\Aes;
use common\components\Code;
use common\components\LogUtils;
use common\components\Mail;
use common\components\OssUpload;
use common\components\SmsUtils;
use common\components\Validate;
use common\entity\UserAccess;
use common\entity\UserBase;
use frontend\components\Page;
use frontend\interfaces\WechatInterface;
use frontend\interfaces\WeiboInterface;
use frontend\services\CountryService;
use frontend\services\PublisherService;
use frontend\services\TripService;
use frontend\services\UserAttentionService;
use frontend\services\UserBaseService;
use Yii;
use yii\base\Exception;

class WechatUserInfoController extends WController {
    public $layout=false;
    public $enableCsrfValidation=false;
    public $userOrderService =null ;
    public $userBaseSer=null;
    public $tripSer=null;
    public function __construct($id, $module = null)
    {
        $this->userBaseSer=new UserBaseService();
        $this->tripSer=new TripService();
        parent::__construct($id, $module);
    }

    public function actionSetting()
    {
        $login = $this->loginValid();
        if(!$login){
            return $this->redirect(['/we-chat/login']);
        }
        $userSign=$this->userObj->userSign;
        $userInfo = $this->userBaseSer->findUserByUserSignArray($userSign);
        return $this->renderPartial('setting',["userInfo"=>$userInfo,'userObj'=>$this->userObj,'active'=>8,'newMsg'=>0]);
    }

    public function actionSupply()
    {
        $this->loginValid();
        return $this->renderPartial('supply',['userObj'=>$this->userObj,'active'=>8,'newMsg'=>0]);
    }
    public function actionContact()
    {
        $this->loginValid();
        return $this->renderPartial('contact',['userObj'=>$this->userObj,'active'=>8,'newMsg'=>0]);
    }
    public function actionNotice()
    {
        $this->loginValid();
        return $this->renderPartial('notice',['userObj'=>$this->userObj,'active'=>8,'newMsg'=>0]);
    }
    public function actionInfo()
    {
        $login = $this->loginValid();
        if(!$login){
            return $this->redirect(['/we-chat/login']);
        }
        $userSign=$this->userObj->userSign;
        $userInfo = $this->userBaseSer->findUserByUserSignArray($userSign);
        $access =$this->userBaseSer->findUserAccessByUserSign($userSign);
        return $this->renderPartial('info',["userInfo"=>$userInfo,"access"=>$access,'userObj'=>$this->userObj,'active'=>8,'newMsg'=>0]);
    }
    public function actionHeadImg()
    {
        $login = $this->loginValid();
        if(!$login){
            return $this->redirect(['/we-chat/login']);
        }
        $userSign=$this->userObj->userSign;
        $userInfo = $this->userBaseSer->findUserByUserSignArray($userSign);
        return $this->renderPartial('headImg',["userInfo"=>$userInfo,'userObj'=>$this->userObj,'active'=>8,'newMsg'=>0]);
    }

    public function actionAccess()
    {
        $login = $this->loginValid();
        if(!$login){
            return $this->redirect(['/we-chat/login']);
        }
        $userSign=$this->userObj->userSign;
        $userInfo = $this->userBaseSer->findUserByUserSignArray($userSign);
        $access =$this->userBaseSer->findUserAccessByUserSign($userSign);
        return $this->renderPartial('modifyAccess',["userInfo"=>$userInfo,"access"=>$access,'userObj'=>$this->userObj,'active'=>8,'newMsg'=>0]);
    }
    public function actionUpView()
    {
        $login = $this->loginValid();
        if(!$login){
            return $this->redirect(['/we-chat/login']);
        }
        $view=Yii::$app->request->get("v");
        if(empty($view))
        {
            return $this->redirect(['/we-chat/error?str=未知页面']);
        }
        $userSign=$this->userObj->userSign;
        $userInfo = $this->userBaseSer->findUserByUserSignArray($userSign);
        $re_view = "modify".$view;
        $countrySer=new CountryService();
        $countryList = $countrySer->getCountryList();
        $cityList=$countrySer->getCityList($userInfo["countryId"],null);
        return $this->renderPartial($re_view,["userInfo"=>$userInfo,'countryList'=>$countryList,'cityList'=>$cityList,'userObj'=>$this->userObj,'active'=>8,'newMsg'=>0]);
    }

    public function actionGetCityById()
    {
        $id=Yii::$app->request->get("id");
        $countrySer=new CountryService();
        $cityList=$countrySer->findCityById($id);
        return json_encode(Code::statusDataReturn(Code::SUCCESS,$cityList));
    }
    public function actionGetCityListById()
    {
        $id=Yii::$app->request->get("id");
        $countrySer=new CountryService();
        $cityList=$countrySer->getCityList($id,null);
        return json_encode(Code::statusDataReturn(Code::SUCCESS,$cityList));
    }
    public function actionUpdateUserInfo()
    {
        $login = $this->loginValid();
        if(!$login){
            return $this->redirect(['/we-chat/login']);
        }
        $userId = $this->userObj->userId;
        $userInfo = $this->userBaseService->findUserById($userId);

        $surname = trim(\Yii::$app->request->post('surname'));
        $name = trim(\Yii::$app->request->post('name'));
        $qq = trim(\Yii::$app->request->post('qq'));
        $wechat = trim(\Yii::$app->request->post('wechat'));
        $sex = trim(\Yii::$app->request->post('sex', UserBase::USER_SEX_SECRET));
        $nickname = trim(\Yii::$app->request->post('nickname'));
        $birthday = trim(\Yii::$app->request->post('birthday'));
        $intro = trim(\Yii::$app->request->post('intro'));
        $info = trim(\Yii::$app->request->post('info'));
        $countryId = \Yii::$app->request->post('countryId');
        $cityId = \Yii::$app->request->post('cityId');
        $lon = \Yii::$app->request->post('lon');
        $lat = \Yii::$app->request->post('lat');
        $profession = trim(\Yii::$app->request->post('profession'));

        try {
            if (!empty($surname)) {
                $userInfo->surname = $surname;
            }
            if (!empty($name)) {
                $userInfo->name = $name;
            }
            if (!empty($qq)) {
                $userInfo->qq = $qq;
            }
            if (!empty($wechat)) {
                $userInfo->wechat = $wechat;
            }
                $userInfo->sex = $sex;
            if (!empty($nickname)) {
                if(strlen($nickname) > 30)
                {
                    return $this->redirect('/we-chat/error?str="昵称格式不正确"');
                }
                $userInfo->nickname = $nickname;
            }
            if (!empty($birthday)) {
                $userInfo->birthday = $birthday;

            }
            if (!empty($intro)) {
                if(strlen($intro) > 50)
                {
                    return $this->redirect('/we-chat/error?str=签名过长');
                }
                $userInfo->intro = $intro;

            }
            if (!empty($info)) {
                $userInfo->info = $info;

            }
            if (!empty($countryId)) {
                $userInfo->countryId = $countryId;

            }
            if (!empty($cityId)) {
                $userInfo->cityId = $cityId;

            }
            if (!empty($lon)) {
                $userInfo->lon = $lon;

            }
            if (!empty($lat)) {
                $userInfo->lat = $lat;

            }
            if (!empty($profession)) {
                $userInfo->profession = $profession;

            }

            $this->userBaseService->updateUserBase($userInfo);
            $this->refreshUserInfo();

            return $this->redirect('/wechat-user-info/info');

        } catch (Exception $e) {
            LogUtils::log($e);
            return $this->redirect('/we-chat/error?str=系统异常');
        }
    }

    public function actionUserInfo()
    {
        $login = $this->loginValid();
        /*if(!$login){
            return $this->redirect(['/we-chat/login']);
        }*/
        try{
            $userSign = Yii::$app->request->get("userSign");
            $page=new Page();
            $page->sortType="desc";
            $page->sortName="attentionId";
            $AttentionService = new UserAttentionService();
            $data = $AttentionService->getUserCollectionTravel($userSign, $page);
            $userInfo = $this->userBaseSer->findUserByUserSignArray($userSign);
            if(empty($userInfo))
            {
                return $this->redirect('/we-chat/error?str=未知用户');
            }
            $publisherService=new PublisherService();
            $createPublisherInfo = $publisherService->findUserPublisherByUserSign($userSign);
            $myList=array();
            if(!empty($createPublisherInfo)){
                $userPublisherId=$createPublisherInfo->userPublisherId;
                $myList=$this->tripSer->getMyTripList($userPublisherId);
            }
            return $this->renderPartial("userInfo",['attention'=>$data,'userInfo'=>$userInfo,'tripList'=>$myList,'userObj'=>$this->userObj,'active'=>8,'newMsg'=>0]);
         }catch (Exception $e){
            LogUtils::log($e);
            return $this->redirect('/we-chat/error?str=系统异常');
            }

    }

    public function actionTripList()
    {
        $this->loginValid();
        $userSign = Yii::$app->request->get("userSign");
        $publisherService=new PublisherService();
        $createPublisherInfo = $publisherService->findUserPublisherByUserSign($userSign);
        $myList=array();
        if(!empty($createPublisherInfo)){
            $userPublisherId=$createPublisherInfo->userPublisherId;
            $myList=$this->tripSer->getMyTripList($userPublisherId);
        }
        return $this->renderPartial("tripList",['tripList'=>$myList,'userObj'=>$this->userObj,'active'=>1,'newMsg'=>0]);
    }

    public function actionAttentionList()
    {
        $this->loginValid();
        $userSign = Yii::$app->request->get("userSign");
        $page=new Page();
        $page->sortType="desc";
        $page->sortName="attentionId";
        $page->showAll=true;
        $AttentionService = new UserAttentionService();
        $data = $AttentionService->getUserCollectionTravel($userSign, $page);
        return $this->renderPartial("attentionList",['list'=>$data,'userObj'=>$this->userObj,'active'=>1,'newMsg'=>0]);
    }

    /**
     * wechat 头像上传
     * @return string
     */
    public function actionWechatUploadHeadImg()
    {
        $this->loginValidJson();
        try {
            $userId=$this->userObj->userId;
            $crop = new CropAvatar(
                isset($_POST['avatar_src']) ? $_POST['avatar_src'] : null,
                isset($_POST['avatar_data']) ? $_POST['avatar_data'] : null,
                isset($_FILES['file_head']) ? $_FILES['file_head'] : null
            );
            if($crop -> getMsg())
            {
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, $crop -> getMsg()));
            }else{

                $ossUpload=new OssUpload();
                $rst=$ossUpload->putObject($crop -> getResult(),OssUpload::OSS_SUIUU_HEAD_DIR,$crop->getNewFileName());

                if($rst['status']==Code::SUCCESS){
                    unlink($crop -> getResult());
                    if(!empty($rst['data']))
                    {
                        $this->userBaseService->updateUserHeadImg($userId, $rst['data']);
                        $this->refreshUserInfo();
                    }
                    return json_encode(Code::statusDataReturn(Code::SUCCESS, $rst['data']));
                }else{
                    return json_encode(Code::statusDataReturn(Code::FAIL));
                }
            }
        } catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL, $e));
        }
    }

    public function actionSendCode()
    {
        $this->loginValidJson();
        $phone = \Yii::$app->request->post('phone');//发送给用户的手机
        $areaCode = \Yii::$app->request->post('areaCode');//区号
        $valNum = \Yii::$app->request->post('valNum');//图形验证码
        if(empty($valNum)||strtolower($valNum)!=strtolower(Yii::$app->session->get(Code::USER_LOGIN_VERIFY_CODE)))
        {
            //return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "图文验证码不正确"));
        }
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
        $userBaseService = new UserBaseService();
        if ($count < Code::MAX_SEND_COUNT) {
            //判断手机是否已经注册
            $userBase = $userBaseService->findUserByPhone($phone);
            if (!empty($userBase)) {
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, Code::USER_PHONE_EXIST));
            }
            $code = $this->randomPhoneCode();//验证码
            //设置验证码 和 有效时长
            \Yii::$app->redis->set(Code::USER_PHONE_VALIDATE_CODE_AND_PHONE . $phone, $code);
            Yii::$app->redis->expire(Code::USER_PHONE_VALIDATE_CODE_AND_PHONE . $phone, Code::USER_PHONE_VALIDATE_CODE_EXPIRE_TIME);
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
     * 验证手机
     * @return string
     * @throws Exception
     * @throws \Exception
     */
    public function actionValidatePhone()
    {
        $this->loginValidJson();
        $phone=trim(\Yii::$app->request->post("phone", ""));
        $code=trim(\Yii::$app->request->post("code", ""));
        $areaCode = \Yii::$app->request->post('areaCode',"+86");//区号
        if(empty($phone)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "未知的手机号"));
        }
        if(empty($code)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "未知的验证码"));
        }
        $rCode =\Yii::$app->redis->get(Code::USER_PHONE_VALIDATE_CODE_AND_PHONE . $phone);
        if(empty($rCode)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "手机号未获取验证码"));
        }
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
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "未知的用户"));
            }
            $rst->areaCode = $areaCode;
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
     * @return string
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
            $this->userBaseService=new UserBaseService();
            $currentUser=$this->userBaseService->findUserByUserSign($userSign);
            $this->userObj=$currentUser;
            \Yii::$app->session->set(Yii::$app->params['weChatSign'],$currentUser);
            \Yii::$app->session->set(Code::USER_LOGIN_SESSION,$currentUser);
            $sessionId=\Yii::$app->getSession()->id;
            \Yii::$app->redis->set(Code::USER_LOGIN_SESSION_CHAT.$sessionId,json_encode($currentUser));
        } catch (Exception $e) {
            LogUtils::log($e);
            return $this->redirect(['/result', 'result' => '验证邮箱失败！']);
        }
        return $this->redirect(['/wechat-user-info/info']);

    }

    /**
     * 发送验证邮件
     * @return string
     * @throws Exception
     * @throws \Exception
     */
    public function actionSendValidateMail()
    {
        $this->loginValidJson();
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
            $url = \Yii::$app->params['base_dir'] . '/wechat-user-info/validate-mail?e=' . urlencode($mail) . '&p=' . urlencode($enPwd) . '&c=' . urlencode($code);
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
    public function actionConnectWechat()
    {
        $wechatInterface = new WechatInterface();
        $wechatInterface->toConnectWechatAccess();
    }
    public function actionWeixinAccess()
    {
        $login = $this->loginValid();
        if(!$login){
            return $this->redirect(['/we-chat/login']);
        }
        $code = \Yii::$app->request->get("code");
        $state = \Yii::$app->request->get("state");
        $wechatInterface = new WechatInterface();
        $tokenRst=$wechatInterface->getWechatUserOpenId($code);
        if($tokenRst['status']!=Code::SUCCESS){
            $this->redirect(['we-chat/error?str="微信认证失败']);
        }
        $tokenInfo=$tokenRst['data'];
        $openId=$tokenInfo['openid'];

        $userInfoRst=$wechatInterface->getWeChatUserInfo($openId,true);
        if($userInfoRst['status']!=Code::SUCCESS){
            $this->redirect(['we-chat/error?str=获取微信用户信息失败']);
        }
        $userInfo=$userInfoRst['data'];
        $unionid=$userInfo['unionid'];
        $sex=UserBase::USER_SEX_FEMALE;
        if($userInfo['sex']==1){
            $sex=UserBase::USER_SEX_MALE;
        }
        $rst=$this->accessLogin($unionid,UserAccess::ACCESS_TYPE_WECHAT);

        if($rst['status']==Code::SUCCESS){
            if($rst['data']!=null&&($rst['data']->phone!=null||$rst['data']->email!=null)){
                return $this->redirect("/we-chat/error?str=该微信已绑定");
            }else{
                if($sex!=UserBase::USER_SEX_MALE&&$sex!=UserBase::USER_SEX_FEMALE&&$sex!=UserBase::USER_SEX_SECRET){
                    throw new Exception("Invalid Sex Value");
                }
                $userAccess=new UserAccess();
                $userAccess->openId=$unionid;
                $userAccess->type=UserAccess::ACCESS_TYPE_WECHAT;
                $userAccess->userId=$this->userObj->userSign;

                $this->userBaseSer->addUserAccess($userAccess);
                return $this->redirect("/wechat-user-info/info");
            }
        }else{
            return $this->redirect("/we-chat/error?str=微信登陆失败");
        }
    }


    public function actionConnectWeibo()
    {
        $weiboInterface = new WeiboInterface();
        $weiboInterface->setCallBackUrl("http://www.suiuu.com/wechat-user-info/weibo-login");
        $weiboInterface->toConnectWeibo();
    }

    public function actionWeiboLogin()
    {
        $code = \Yii::$app->request->get("code");
        $type = \Yii::$app->session->get("accessType");
        $weiboInterface = new WeiboInterface();

        //获取微博用户UID
        $uidRst = $weiboInterface->getWeiboUid($code);
        if ($uidRst['status'] != Code::SUCCESS) {
            throw new Exception('微博认证失败');
        }
        $uid = $uidRst['data'];
        //查看是否存在 数据库 UID  不存在获取用户基本信息 注册新用户
        $userInfo = $weiboInterface->getUserById($uid);
        if ($userInfo['status'] != Code::SUCCESS) {
            throw new Exception('获取用户信息失败');
        }
        $userInfo = $userInfo['data'];
        $sex = '';
        if ($userInfo['gender'] == 'm') {
            $sex = 1;
        } else if ($userInfo['gender'] == 'f') {
            $sex = 0;
        } else if ($userInfo['gender'] == 'n') {
            $sex = 2;
        }

        $openId = $userInfo['id'];
        $nickname = $userInfo['screen_name'];
        $headImg = $userInfo['avatar_large'];

        $rst = $this->accessLogin($openId, UserAccess::ACCESS_TYPE_SINA_WEIBO, $nickname, $sex, $headImg);
        if ($rst['status'] == Code::SUCCESS) {
            if ($rst['data'] != null && ($rst['data']->phone != null || $rst['data']->email != null)) {
                return $this->redirect("/we-chat/error?str=该微信已绑定");
            }else{
                if($sex!=UserBase::USER_SEX_MALE&&$sex!=UserBase::USER_SEX_FEMALE&&$sex!=UserBase::USER_SEX_SECRET){
                    throw new Exception("Invalid Sex Value");
                }
                $userAccess=new UserAccess();
                $userAccess->openId=$openId;
                $userAccess->type=UserAccess::ACCESS_TYPE_SINA_WEIBO;
                $userAccess->userId=$this->userObj->userSign;
                $this->userBaseSer->addUserAccess($userAccess);
                return $this->redirect("/wechat-user-info/info");
            }
        } else {

                return $this->redirect("/we-chat/error?str=微博登陆失败");
        }
    }
    private function accessLogin($openId,$type)
    {
        $this->userBaseService=new UserBaseService();
        $userBase=$this->userBaseService->findUserAccessByOpenIdAndType($openId,$type);

        if($userBase!=null){
            if($userBase->status!=UserBase::USER_STATUS_NORMAL){
                return Code::statusDataReturn(Code::FAIL,"User Status Is Disabled");
            }else{
                return Code::statusDataReturn(Code::SUCCESS,$userBase);
            }
        }else{
            return Code::statusDataReturn(Code::SUCCESS,null);
        }
    }
}
class CropAvatar {
    private $src;
    private $data;
    private $dst;
    private $type;
    private $extension;
    private $msg;

    function __construct($src, $data, $file) {
        $this -> setSrc($src);
        $this -> setData($data);
        $this -> setFile($file);
        $this -> crop($this -> src, $this -> dst, $this -> data);
    }

    private function setSrc($src) {
        if (!empty($src)) {
            $type = exif_imagetype($src);

            if ($type) {
                $this -> src = $src;
                $this -> type = $type;
                $this -> extension = image_type_to_extension($type);
                $this -> setDst();
            }
        }
    }

    private function setData($data) {
        if (!empty($data)) {
            $this -> data = json_decode(stripslashes($data));
        }
    }

    private function setFile($file) {
        $errorCode = $file['error'];

        if ($errorCode === UPLOAD_ERR_OK) {
            $type = exif_imagetype($file['tmp_name']);

            if ($type) {
                $extension = image_type_to_extension($type);
                $src = dirname(__DIR__).'/web/uploads/image/' . date('YmdHis') . '.original' . $extension;

                if ($type == IMAGETYPE_GIF || $type == IMAGETYPE_JPEG || $type == IMAGETYPE_PNG) {

                    if (file_exists($src)) {
                        unlink($src);
                    }
                    $result = move_uploaded_file($file['tmp_name'], $src);

                    if ($result) {
                        $this -> src = $src;
                        $this -> type = $type;
                        $this -> extension = $extension;
                        $this -> setDst();
                    } else {
                        $this -> msg = 'Failed to save file';
                    }
                } else {
                    $this -> msg = 'Please upload image with the following types: JPG, PNG, GIF';
                }
            } else {
                $this -> msg = 'Please upload image file';
            }
        } else {
            $this -> msg = $this -> codeToMessage($errorCode);
        }
    }

    private function setDst() {
        $this -> dst =  dirname(__DIR__).'/web/uploads/image/' . date('YmdHis') . '.png' ;
    }

    private function crop($src, $dst, $data) {
        if (!empty($src) && !empty($dst) && !empty($data)) {
            switch ($this -> type) {
                case IMAGETYPE_GIF:
                    $src_img = imagecreatefromgif($src);
                    break;

                case IMAGETYPE_JPEG:
                    $src_img = imagecreatefromjpeg($src);
                    break;

                case IMAGETYPE_PNG:
                    $src_img = imagecreatefrompng($src);
                    break;
            }

            if (!$src_img) {
                $this -> msg = "Failed to read the image file";
                return;
            }

            $size = getimagesize($src);
            $size_w = $size[0]; // natural width
            $size_h = $size[1]; // natural height

            $src_img_w = $size_w;
            $src_img_h = $size_h;

            $degrees = $data -> rotate;

            // Rotate the source image
            if (is_numeric($degrees) && $degrees != 0) {
                // PHP's degrees is opposite to CSS's degrees
                $new_img = imagerotate( $src_img, -$degrees, imagecolorallocatealpha($src_img, 0, 0, 0, 127) );

                imagedestroy($src_img);
                $src_img = $new_img;

                $deg = abs($degrees) % 180;
                $arc = ($deg > 90 ? (180 - $deg) : $deg) * M_PI / 180;

                $src_img_w = $size_w * cos($arc) + $size_h * sin($arc);
                $src_img_h = $size_w * sin($arc) + $size_h * cos($arc);

                // Fix rotated image miss 1px issue when degrees < 0
                $src_img_w -= 1;
                $src_img_h -= 1;
            }

            $tmp_img_w = $data -> width;
            $tmp_img_h = $data -> height;
            $dst_img_w = $data -> width;
            $dst_img_h = $data -> height;

            $src_x = $data -> x;
            $src_y = $data -> y;

            if ($src_x <= -$tmp_img_w || $src_x > $src_img_w) {
                $src_x = $src_w = $dst_x = $dst_w = 0;
            } else if ($src_x <= 0) {
                $dst_x = -$src_x;
                $src_x = 0;
                $src_w = $dst_w = min($src_img_w, $tmp_img_w + $src_x);
            } else if ($src_x <= $src_img_w) {
                $dst_x = 0;
                $src_w = $dst_w = min($tmp_img_w, $src_img_w - $src_x);
            }

            if ($src_w <= 0 || $src_y <= -$tmp_img_h || $src_y > $src_img_h) {
                $src_y = $src_h = $dst_y = $dst_h = 0;
            } else if ($src_y <= 0) {
                $dst_y = -$src_y;
                $src_y = 0;
                $src_h = $dst_h = min($src_img_h, $tmp_img_h + $src_y);
            } else if ($src_y <= $src_img_h) {
                $dst_y = 0;
                $src_h = $dst_h = min($tmp_img_h, $src_img_h - $src_y);
            }

            // Scale to destination position and size
            $ratio = $tmp_img_w / $dst_img_w;
            $dst_x /= $ratio;
            $dst_y /= $ratio;
            $dst_w /= $ratio;
            $dst_h /= $ratio;
            $dst_img = imagecreatetruecolor($dst_img_w, $dst_img_h);

            // Add transparent background to destination image
            imagefill($dst_img, 0, 0, imagecolorallocatealpha($dst_img, 0, 0, 0, 127));
            imagesavealpha($dst_img, true);
            $result = imagecopyresampled($dst_img, $src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
            if ($result) {
                switch ($this -> type) {
                    case IMAGETYPE_GIF:
                        if (!imagegif($dst_img, $this->dst)) {
                            $this -> msg = "Failed to save the cropped image file";
                        }
                        break;

                    case IMAGETYPE_JPEG:
                        if (!imagejpeg($dst_img, $this->dst)) {
                            $this -> msg = "Failed to save the cropped image file";
                        }
                        break;

                    case IMAGETYPE_PNG:
                        if (!imagepng($dst_img, $this->dst)) {
                            $this -> msg = "Failed to save the cropped image file";
                        }
                        break;
                }
            } else {
                $this -> msg = "Failed to crop the image file";
            }

            imagedestroy($src_img);
            imagedestroy($dst_img);
            if (file_exists($src)) {
                unlink($src);
            }
        }
    }

    private function codeToMessage($code) {
        $errors = array(
            UPLOAD_ERR_INI_SIZE =>'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            UPLOAD_ERR_FORM_SIZE =>'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
            UPLOAD_ERR_PARTIAL =>'The uploaded file was only partially uploaded',
            UPLOAD_ERR_NO_FILE =>'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR =>'Missing a temporary folder',
            UPLOAD_ERR_CANT_WRITE =>'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION =>'File upload stopped by extension',
        );

        if (array_key_exists($code, $errors)) {
            return $errors[$code];
        }

        return 'Unknown upload error';
    }

    public function getResult() {
        return !empty($this -> data) ? $this -> dst : $this -> src;
    }

    public function getNewFileName()
    {
        return !empty($this ->type) ? date("YmdHis") . '_' . rand(10000, 99999)  .image_type_to_extension($this ->type) : date("YmdHis") . '_' . rand(10000, 99999) . '.png' ;
    }
    public function getMsg() {
        return $this -> msg;
    }
}