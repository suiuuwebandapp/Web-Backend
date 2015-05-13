<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/2
 * Time : 下午1:58
 * Email: zhangxinmailvip@foxmail.com
 */

namespace backend\controllers;

use common\components\Aes;
use common\components\Code;
use backend\entity\SysUser;
use backend\services\SysUserService;
use vendor\geetest\GeetestLib;
use Yii;
use yii\base\Exception;
use yii\web\Controller;
use yii\web\Cookie;

/**
 * 登录控制器
 * Class LoginController
 * @package backend\controllers
 */
class LoginController extends Controller
{

    private $sysUserService;

    public $layout="login";

    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        $this->sysUserService = new SysUserService();
    }

    /**
     * 测试清除redis 缓存
     */
    public function actionFull()
    {
        Yii::$app->redis->flushall();
    }

    /**
     * 登录首页
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', ['errors' => [], 'showVerifyCode' => false]);
    }

    public function actionInfo()
    {
        Yii::$app->redis->set('user','zhang');
        var_dump(Yii::$app->redis->expire('user','10'));
    }

    /**
     * 测试查询redis 集合
     */
    public function actionList()
    {
        var_dump(Yii::$app->redis->keys('*'));
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
        $cacheCount=Yii::$app->redis->get(Code::SYS_USER_LOGIN_ERROR_COUNT_PREFIX.$username);
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
            return $this->render('index', ['errors' => $errors, 'showVerifyCode' => $showVerifyCode]);
        }

        try{
            //验证用户名是否存在
            $result=$this->sysUserService->findUser($username,$password);
            if(isset($result)){
                //设置Session
                Yii::$app->session->set(Code::SYS_USER_LOGIN_SESSION,$result);
                //如果用户点击记住密码，设置Cookie
                if(!empty($remember)){
                    //记录加密Cookie
                    $enPassword = Yii::$app->params['encryptPassword'];
                    $enDigit = Yii::$app->params['encryptDigit'];

                    $aes=new Aes();
                    $sysSign=$aes->encrypt($result->userSign,$enPassword,$enDigit);
                    $cookies=Yii::$app->response->cookies;//cookie 注意，发送Cookie 是response 读取是 request
                    $signCookie= new Cookie([
                        'name' => Yii::$app->params['sys_suiuu_sign'],
                        'value' => $sysSign,
                    ]);
                    $signCookie->expire=time()+24*60*60*floor(Yii::$app->params['cookie_expire']);
                    $cookies->add($signCookie);
                }
                //清除错误登录次数
                Yii::$app->redis->del(Code::SYS_USER_LOGIN_ERROR_COUNT_PREFIX.$username);
                //跳转用户登录前的页面
                if(!empty($returnUrl)){
                    return $this->redirect($returnUrl);
                }else{
                    return $this->redirect('/');
                }
            }else{
                Yii::$app->redis->set(Code::SYS_USER_LOGIN_ERROR_COUNT_PREFIX.$username,++$errorCount);
                Yii::$app->redis->expire(Code::SYS_USER_LOGIN_ERROR_COUNT_PREFIX.$username,Code::SYS_USER_LOGIN_VERIFY_CODE_EXPIRE_TIME);

                $errors[]="用户名或密码错误";
            }
        }catch (Exception $e){
            $errors[]=$e->getMessage();
        }
        //判断是否需要输入验证码
        if($errorCount>=Code::SYS_LOGIN_ERROR_COUNT){
            $showVerifyCode=true;
        }
        return $this->render('index', ['errors' => $errors, 'showVerifyCode' => $showVerifyCode]);



    }

    /**
     * 安全退出方法
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        \Yii::$app->session->remove(Code::SYS_USER_LOGIN_SESSION);
        \Yii::$app->response->cookies->remove(\Yii::$app->params['sys_suiuu_sign']);

        return $this->redirect('/login');

    }

    /**
     * 测试添加用户方法
     * @throws Exception
     */
    public function actionAdd()
    {
        $sysUser = new SysUser();
        $sysUser->username = "admin";
        $sysUser->password = "password";
        $sysUser->phone = "17701085674";
        $sysUser->email = "xin.zhang@suiuu.com";
        $sysUser->nickname = "张鑫";
        $sysUser->lastLoginIp = $_SERVER['REMOTE_ADDR'];
        $sysUser->registerIp = $_SERVER['REMOTE_ADDR'];
        $sysUser->sex = 1;
        $sysUser->isEnabled = true;
        $sysUser->isAdmin = true;
        $sysUser->userSign=Code::getUUID();


        $this->sysUserService->addSysUser($sysUser);

    }


    public function actionSession()
    {
        Yii::$app->session->set("zhang","xin");

        echo Yii::$app->session->get("zhang");
    }

}
