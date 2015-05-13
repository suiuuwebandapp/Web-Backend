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
use common\entity\UserAccess;
use common\entity\UserBase;
use frontend\interfaces\TencentInterface;
use frontend\interfaces\WechatInterface;
use frontend\interfaces\WeiboInterface;
use frontend\services\UserBaseService;
use yii\base\Exception;
use yii\web\Controller;


class AccessController extends Controller
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
            return $this->redirect("/");
        }else{
            return $this->redirect("/error/access-error");
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
            return $this->redirect("/");
        }else{
            return $this->redirect("/error/access-error");
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
            return $this->redirect("/");
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
                \Yii::$app->session->set(Code::USER_LOGIN_SESSION,$userBase);
                return Code::statusDataReturn(Code::SUCCESS,$userBase);
            }
        }
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

        }catch (Exception $e){
            return Code::statusDataReturn(Code::FAIL,$e->getName());
        }
       return Code::statusDataReturn(Code::SUCCESS,$userBase);
    }

    public function actionConnectQq(){
        $this->qqInterface->toConnectQQ();
    }

    public function actionConnectWeibo()
    {
        $this->weiboInterface->toConnectWeibo();
    }

    public function actionConnectWechat()
    {
        $this->wechatInterface->toConnectWechat();
    }




}