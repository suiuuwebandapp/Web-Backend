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
use frontend\interfaces\TencentInterface;
use frontend\interfaces\WeiboInterface;
use yii\base\Exception;
use yii\web\Controller;


class AccessController extends Controller
{


    private $qqInterface;

    public function __construct($id, $module, $config = []){
        parent::__construct($id, $module, $config = []);
        $this->qqInterface=new TencentInterface();
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
        if($userInfo['gender']=='m'){
            $sex='男';
        }else if($userInfo['gender']=='f'){
            $sex='女';
        }else if($userInfo['gender']=='n'){
            $sex='未知';
        }
        echo 'ID:'.$userInfo['id'].'<br/>';
        echo '昵称:'.$userInfo['screen_name'].'<br/>';
        echo '友好显示名称:'.$userInfo['name'].'<br/>';
        echo '性别:'.$sex.'<br/>';
        echo '用户头像地址:'.$userInfo['avatar_large'].'<img src="'.$userInfo['avatar_large'].'" /><br/>';




    }


    public function actionWeixinLogin()
    {

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
        //将OpenId，token 等基本信息存入数据库
        echo 'openId:'.$openId.'<br/>';
        echo '昵称:'.$userInfo['nickname'].'<br/>';
        echo '性别:'.$userInfo['gender'].'<br/>';
        echo '用户头像地址(40x40):'.$userInfo['figureurl_qq_1'].'<img src="'.$userInfo['figureurl_qq_1'].'" /><br/>';
        echo '用户头像地址(100x100):'.$userInfo['figureurl_qq_2'].'<img src="'.$userInfo['figureurl_qq_2'].'" /><br/>';


    }

    public function actionConnectQq(){
        $this->qqInterface->toConnectQQ();
    }

}