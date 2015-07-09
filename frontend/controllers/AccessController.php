<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/8
 * Time : 下午1:05
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\controllers;


use common\components\Code;
use common\components\LogUtils;
use common\components\Validate;
use common\entity\UserAccess;
use common\entity\UserBase;
use common\entity\WeChat;
use common\pay\alipay\auth\AlipayAuthApi;
use common\pay\alipay\auth\AlipayConfig;
use common\pay\alipay\lib\AlipayNotify;
use frontend\interfaces\TencentInterface;
use frontend\interfaces\WechatInterface;
use frontend\interfaces\WeiboInterface;
use frontend\services\UserBaseService;
use frontend\services\WeChatService;
use yii\base\Exception;
use yii\web\Controller;
use yii\web\YiiAsset;


class AccessController extends UnCController
{


    private $qqInterface;
    private $weiboInterface;
    private $wechatInterface;
    private $userBaseService;

    public function __construct($id, $module, $config = []){
        parent::__construct($id, $module, $config = []);
        $this->qqInterface=new TencentInterface();
        $this->weiboInterface=new WeiboInterface();
        $this->wechatInterface=new WechatInterface();
    }
    /**
     * 微博登录
     * @throws Exception
     */
    public function actionWeiboLogin()
    {
        $code = \Yii::$app->request->get("code");
        $type = \Yii::$app->session->get("accessType");
        $weiboInterface=new WeiboInterface();

        //获取微博用户UID
        $uidRst=$weiboInterface->getWeiboUid($code);
        if($uidRst['status']!=Code::SUCCESS){
            throw new Exception('微博认证失败');
        }
        $uid=$uidRst['data'];
        //查看是否存在 数据库 UID  不存在获取用户基本信息 注册新用户
        $userInfo=$weiboInterface->getUserById($uid);
        if($userInfo['status']!=Code::SUCCESS){
            throw new Exception('获取用户信息失败');
        }
        $userInfo=$userInfo['data'];
        $sex='';
        if($userInfo['gender']=='m'){ $sex=1; }else if($userInfo['gender']=='f'){ $sex=0; }else if($userInfo['gender']=='n'){ $sex=2;}

        $openId=$userInfo['id'];
        $nickname=$userInfo['screen_name'];
        $headImg=$userInfo['avatar_large'];

        $rst=$this->accessLogin($openId,UserAccess::ACCESS_TYPE_SINA_WEIBO,$nickname,$sex,$headImg);
        if($rst['status']==Code::SUCCESS){
            if($rst['data']!=null&&$rst['data']->phone!=null){
                \Yii::$app->session->set(Code::USER_LOGIN_SESSION,$rst['data']);
                if(empty($type)){
                    return $this->redirect("/");
                }else {
                    return $this->redirect("/wechat-trip");
                }
            }else{
                if($sex!=UserBase::USER_SEX_MALE&&$sex!=UserBase::USER_SEX_FEMALE&&$sex!=UserBase::USER_SEX_SECRET){
                    throw new Exception("Invalid Sex Value");
                }

                $userBase=new UserBase();
                $userBase->nickname=$nickname;
                $userBase->headImg=$headImg;
                $userBase->sex=$sex;

                $userAccess=new UserAccess();
                $userAccess->openId=$openId;
                $userAccess->type=UserAccess::ACCESS_TYPE_SINA_WEIBO;

                \Yii::$app->session->set("regUserBase",$userBase);
                \Yii::$app->session->set("regUserAccess",$userAccess);
                if(empty($type)){
                    return $this->redirect("/access/access-finish");
                }else
                {
                    return $this->redirect("/we-chat/binding");
                }

            }
        }else{
            if(empty($type)){
                return $this->redirect("/error/access-error");
            }else
            {
                return $this->redirect("/we-chat/error?str=微博登陆失败");
            }

        }

    }


    public function actionWeixinLogin()
    {
        $code = \Yii::$app->request->get("code");
        $state = \Yii::$app->request->get("state");
        $tokenRst=$this->wechatInterface->callBackGetTokenInfo($state,$code);
        if($tokenRst['status']!=Code::SUCCESS){
            throw new Exception('微信认证失败');
        }
        $tokenInfo=$tokenRst['data'];
        $openId=$tokenInfo['openid'];
        $accessToken=$tokenInfo['access_token'];

        $userInfoRst=$this->wechatInterface->getUserInfo($accessToken,$openId);
        if($userInfoRst['status']!=Code::SUCCESS){
            throw new Exception('获取微信用户信息失败');
        }
        $userInfo=$userInfoRst['data'];
        $nickname=$userInfo['nickname'];
        $headImg=$userInfo['headimgurl'];
        $unionid=$userInfo['unionid'];
        $sex=UserBase::USER_SEX_FEMALE;
        if($userInfo['sex']==1){
            $sex=UserBase::USER_SEX_MALE;
        }
        $rst=$this->accessLogin($unionid,UserAccess::ACCESS_TYPE_WECHAT,$nickname,$sex,$headImg);

        if($rst['status']==Code::SUCCESS){
            if($rst['data']!=null&&$rst['data']->phone!=null){
                \Yii::$app->session->set(Code::USER_LOGIN_SESSION,$rst['data']);
                return $this->redirect("/");
            }else{

                if($sex!=UserBase::USER_SEX_MALE&&$sex!=UserBase::USER_SEX_FEMALE&&$sex!=UserBase::USER_SEX_SECRET){
                    throw new Exception("Invalid Sex Value");
                }
                $userBase=new UserBase();
                $userBase->nickname=$nickname;
                $userBase->headImg=$headImg;
                $userBase->sex=$sex;

                $userAccess=new UserAccess();
                $userAccess->openId=$unionid;
                $userAccess->type=UserAccess::ACCESS_TYPE_WECHAT;

                \Yii::$app->session->set("regUserBase",$userBase);
                \Yii::$app->session->set("regUserAccess",$userAccess);
                return $this->redirect("/access/access-finish");
            }
        }else{
            return $this->redirect("/error/access-error");
        }
    }

    public function actionWeixinLoginJs()
    {
        $code = \Yii::$app->request->get("code");
        $state = \Yii::$app->request->get("state");
        $tokenRst=$this->wechatInterface->getWechatUserOpenId($code);
        if($tokenRst['status']!=Code::SUCCESS){
           $this->redirect(['we-chat/error?str="微信认证失败']);
        }
        $tokenInfo=$tokenRst['data'];
        $openId=$tokenInfo['openid'];

        $userInfoRst=$this->wechatInterface->getWeChatUserInfo($openId,true);
        if($userInfoRst['status']!=Code::SUCCESS){
            $this->redirect(['we-chat/error?str="获取微信用户信息失败']);
        }
        $userInfo=$userInfoRst['data'];
        $nickname=$userInfo['nickname'];
        $headImg=$userInfo['headimgurl'];
        $unionid=$userInfo['unionid'];
        $sex=UserBase::USER_SEX_FEMALE;
        if($userInfo['sex']==1){
            $sex=UserBase::USER_SEX_MALE;
        }
        $rst=$this->accessLogin($unionid,UserAccess::ACCESS_TYPE_WECHAT,$nickname,$sex,$headImg);

        if($rst['status']==Code::SUCCESS){
            if($rst['data']!=null&&$rst['data']->phone!=null){
                \Yii::$app->session->set(Code::USER_LOGIN_SESSION,$rst['data']);
                return $this->redirect("/wechat-trip");
            }else{

                if($sex!=UserBase::USER_SEX_MALE&&$sex!=UserBase::USER_SEX_FEMALE&&$sex!=UserBase::USER_SEX_SECRET){
                    throw new Exception("Invalid Sex Value");
                }
                $userBase=new UserBase();
                $userBase->nickname=$nickname;
                $userBase->headImg=$headImg;
                $userBase->sex=$sex;

                $userAccess=new UserAccess();
                $userAccess->openId=$unionid;
                $userAccess->type=UserAccess::ACCESS_TYPE_WECHAT;

                \Yii::$app->session->set("regUserBase",$userBase);
                \Yii::$app->session->set("regUserAccess",$userAccess);
                return $this->redirect("/we-chat/binding");
            }
        }else{
            return $this->redirect("/we-chat/error?str=微信登陆失败");
        }
    }

    public function actionQqLogin()
    {
        $code = \Yii::$app->request->get("code");
        $state = \Yii::$app->request->get("state");

        $tokenRst=$this->qqInterface->callBackGetToken($state,$code);
        if($tokenRst['status']!=Code::SUCCESS){
            throw new Exception('QQ用户认证失败');
        }
        $tokenId=$tokenRst['data'];
        $openIdRst=$this->qqInterface->getOpenId($tokenId);
        if($openIdRst['status']!=Code::SUCCESS){
            throw new Exception('获取QQ OpenId失败');
        }
        $openId=$openIdRst['data'];
        $userInfoRst=$this->qqInterface->getUserInfo($tokenId,$openId);
        if($userInfoRst['status']!=Code::SUCCESS){
            throw new Exception('获取QQ基本信息失败');
        }
        $userInfo=$userInfoRst['data'];
        $sex=2;
        if($userInfo['gender']=='男'){ $sex=1; }else if($userInfo['gender']=='v'){ $sex=0; }

        $nickname=$userInfo['nickname'];
        $headImg=$userInfo['figureurl_qq_2'];

        $rst=$this->accessLogin($openId,UserAccess::ACCESS_TYPE_QQ,$nickname,$sex,$headImg);
        if($rst['status']==Code::SUCCESS){
            if($rst['data']!=null&&$rst['data']->phone!=null){
                \Yii::$app->session->set(Code::USER_LOGIN_SESSION,$rst['data']);
                return $this->redirect("/");
            }else{
                if($sex!=UserBase::USER_SEX_MALE&&$sex!=UserBase::USER_SEX_FEMALE&&$sex!=UserBase::USER_SEX_SECRET){
                    throw new Exception("Invalid Sex Value");
                }
                $userBase=new UserBase();
                $userBase->nickname=$nickname;
                $userBase->headImg=$headImg;
                $userBase->sex=$sex;

                $userAccess=new UserAccess();
                $userAccess->openId=$openId;
                $userAccess->type=UserAccess::ACCESS_TYPE_QQ;

                \Yii::$app->session->set("regUserBase",$userBase);
                \Yii::$app->session->set("regUserAccess",$userAccess);
                return $this->redirect("/access/access-finish");
            }

        }else{
            return $this->redirect("/error/access-error");
        }


    }




    private function accessLogin($openId,$type,$nickname,$sex,$headImg)
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
        ///一下代码暂时忽略
        if($sex!=UserBase::USER_SEX_MALE&&$sex!=UserBase::USER_SEX_FEMALE&&$sex!=UserBase::USER_SEX_SECRET){
            return Code::statusDataReturn(Code::PARAMS_ERROR,"Invalid Sex Value");
        }
        if($type!=UserAccess::ACCESS_TYPE_QQ&&$type!=UserAccess::ACCESS_TYPE_WECHAT&&$type!=UserAccess::ACCESS_TYPE_SINA_WEIBO){
            return Code::statusDataReturn(Code::PARAMS_ERROR,"Invalid Type Value");
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
            \Yii::$app->session->set(Code::USER_LOGIN_SESSION,$userBase);
            if($type==UserAccess::ACCESS_TYPE_WECHAT)
            {
                $weChatSer=new WeChatService();
                $weChatSer->bindingWeChatByUnionID($userBase->userSign,$openId);
            }

        }catch (Exception $e){
            LogUtils::log($e);
            return Code::statusDataReturn(Code::FAIL,$e->getName());
        }
       return Code::statusDataReturn(Code::SUCCESS,$userBase);
    }

    public function actionConnectQq(){
        $this->qqInterface->toConnectQQ();
    }

    public function actionConnectWeibo()
    {
        $str=\Yii::$app->request->get("str");
        \Yii::$app->session->set("accessType",$str);
        $this->weiboInterface->toConnectWeibo();
    }

    public function actionConnectWechat()
    {
        $this->wechatInterface->toConnectWechat();
    }

    public function actionConnectWechatJs()
    {
        $this->wechatInterface->toConnectWechatJs();
    }
    public function actionAccessFinish()
    {
        return $this->render("accessRegister");
    }


    /**
     * 第三方登录注册
     * @return string
     */
    public function actionAccessRegister()
    {
        $sendCode=trim(\Yii::$app->request->post('code',""));
        $password=trim(\Yii::$app->request->post('password',""));
        $nickname=trim(\Yii::$app->request->post('nickname',""));

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
            $userBase=\Yii::$app->session->get("regUserBase");
            $userAccess=\Yii::$app->session->get("regUserAccess");
            $userBase->phone = $phone;
            $userBase->password = $password;
            $userBase->nickname=$nickname;
            $userBase->areaCode=$areaCode;
            $userAccess->userId=$userBase->userSign;



            $this->userBaseService=new UserBaseService();

            $tempUserBase=$this->userBaseService->findUserAccessByOpenIdAndType($userAccess->openId,$userAccess->type);
            if($tempUserBase==null){
                $userBase=$this->userBaseService->addUser($userBase,$userAccess);
            }else{
                $userBase=$tempUserBase;
                $userBase->phone=$phone;
                $userBase->phone = $phone;
                $userBase->password = $password;
                $userBase->nickname=$nickname;
                $this->userBaseService->updateUserBase($userBase);
            }
            //绑定用户状态
            \Yii::$app->session->set(Code::USER_LOGIN_SESSION,$userBase);
            \Yii::$app->session->remove("regUserBase");
            \Yii::$app->session->remove("regUserAccess");
            if($userAccess->type==UserAccess::ACCESS_TYPE_WECHAT)
            {
                $weChatSer=new WeChatService();
                $weChatSer->bindingWeChatByUnionID($userBase->userSign,$userAccess->openId);
            }

        } catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL, $e->getMessage()));
        }

        return json_encode(Code::statusDataReturn(Code::SUCCESS, 'success'));
    }

    public function actionBindUser()
    {
        $username=trim(\Yii::$app->request->post('username',""));
        $password=trim(\Yii::$app->request->post('password',""));
        $valNum=trim(\Yii::$app->request->post('valNum',""));
        $sysNum=\Yii::$app->session->get(Code::USER_LOGIN_VERIFY_CODE);
        if(empty($valNum)||$valNum!=$sysNum){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "无效的验证码"));
        }
        $valPassword=Validate::validatePassword($password);
        if(!empty($valPassword)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, $valPassword));
        }
        try{
            $this->userBaseService=new UserBaseService();
            if(strpos($username,"@")){
                $valUsername=Validate::validateEmail($username);
                if(!empty($valUsername)){
                    return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$valUsername));
                }
            }else{
                $valUsername=Validate::validatePhone($username);
                if(!empty($valUsername)){
                    return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$valUsername));
                }
            }

            $userBase=$this->userBaseService->findUserByUserNameAndPwd($username,$password);
            if($userBase!=null){
                $userAccess=\Yii::$app->session->get("regUserAccess");
                if($userAccess==null){
                    return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"第三方登录信息超时，请重新登录"));
                }
                $userAccess->userId=$userBase->userSign;

                $this->userBaseService->addUserAccess($userAccess);
                if($userAccess->type==UserAccess::ACCESS_TYPE_WECHAT)
                {
                    $weChatSer=new WeChatService();
                    $weChatSer->bindingWeChatByUnionID($userBase->userSign,$userAccess->openId);
                }
                //添加用户登录状态
                \Yii::$app->session->set(Code::USER_LOGIN_SESSION, $userBase);
                \Yii::$app->session->remove("regUserBase");
                \Yii::$app->session->remove("regUserAccess");
                return json_encode(Code::statusDataReturn(Code::SUCCESS));
            }else{
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的用户名或密码"));
            }
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"绑定用户异常，请稍后重试"));
        }

    }

    /**
     * 支付宝Auth认证回调
     */
    public function actionAlipayAuthReturn()
    {
        $alipayConfig=new AlipayConfig();
        $alipay_config=$alipayConfig->alipay_config;
        //计算得出通知验证结果
        $alipayNotify = new AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyReturn();
        if($verify_result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代码

            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

            //支付宝用户号

            $user_id = $_GET['user_id'];

            //授权令牌
            $token = $_GET['token'];


            $account=$_GET['email'];

            $username=$_GET['real_name'];


            //判断是否在商户网站中已经做过了这次通知返回的处理
            //如果没有做过处理，那么执行商户的业务程序
            //如果有做过处理，那么不执行商户的业务程序

            echo $account;echo $username;
            echo "验证成功<br />";

            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        }
        else {
            //验证失败
            //如要调试，请看alipay_notify.php页面的verifyReturn函数
            echo "验证失败";
        }
    }


    public function actionAlipayLogin()
    {
        $alipayAutoApi=new AlipayAuthApi();
        $alipayAutoApi->auth();
    }

}