<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/12
 * Time: 上午11:42
 */
namespace frontend\controllers;


use common\components\Code;
use common\entity\UserBase;
use frontend\components\Page;
use frontend\services\CircleService;
use frontend\services\UserAttentionService;
use frontend\services\UserBaseService;
use Yii;
use yii\base\Exception;

class AppMainController extends AController
{
    private $AttentionService;
    private $CircleService;

    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        $this->CircleService = new CircleService();
        $this->AttentionService = new UserAttentionService();
    }


    public function actionUpdateUserInfo()
    {
        $this->loginValid();
        $userSign =$this->userObj->userSign;
        $userService=new UserBaseService();
        $sex = trim(\Yii::$app->request->post('sex',UserBase::USER_SEX_SECRET));
        $nickname = trim(\Yii::$app->request->post('nickname'));
        $headImg = trim(\Yii::$app->request->post('headImg'));
        $birthday = trim(\Yii::$app->request->post('birthday'));
        $intro = trim(\Yii::$app->request->post('intro'));
        $info = trim(\Yii::$app->request->post('info'));
        $countryId = \Yii::$app->request->post('countryId');
        $cityId = \Yii::$app->request->post('cityId');
        $lon = \Yii::$app->request->post('lon');
        $lat = \Yii::$app->request->post('lat');
        $profession = trim(\Yii::$app->request->post('profession'));

        if(empty($nickname)||strlen($nickname)>30){
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"昵称格式不正确"));
            return;
        }
        if(empty($countryId)){
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"请选择居住地国家"));
            return;
        }
        if(empty($cityId)){
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"请选择居住地城市"));
            return;
        }
        try{
            $userInfo=$userService->findUserByUserSign($userSign);
            $userInfo->sex=$sex;
            $userInfo->nickname=$nickname;
            $userInfo->headImg=$headImg;
            $userInfo->birthday=$birthday;
            $userInfo->intro=$intro;
            $userInfo->info=$info;
            $userInfo->countryId=$countryId;
            $userInfo->cityId=$cityId;
            $userInfo->lon=$lon;
            $userInfo->lat=$lat;
            $userInfo->profession=$profession;

            $userService->updateUserBase($userInfo);
            $this->appRefreshUserInfo();
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e) {
            echo json_encode(Code::statusDataReturn(Code::FAIL,$e->getMessage()));
        }
    }

    //得到搜索结果
    public function actionGetSeek()
    {
        $this->loginValid(false);
        try{
            $str=\Yii::$app->request->post('str');
            $page = new Page(\Yii::$app->request);
            if(empty($str))
            {
                echo json_encode(Code::statusDataReturn(Code::FAIL,'无法搜索未知标题'));
                exit;
            }
            $this->CircleService->getSeekResult($str,$page);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }
    //得到用户主页
    public function actionGetHomepageInfo()
    {
        //确实当前用户是否关注
        //需要验证 true
        $this->loginValid(false);
        try{
            $userSign=\Yii::$app->request->post('userSign');
            //$userSign='085963dc0af031709b032725e3ef18f5';
            $page = new Page(\Yii::$app->request);
            $mySign=$this->userObj->userSign;
            //$mySign='5787a571910e3352a76c753776e1b8f4';
            if(empty($userSign))
            {
                echo json_encode(Code::statusDataReturn(Code::FAIL,'无法得到未知用户主页'));
                exit;
            }
            $data=$this->CircleService->getHomepageInfo($userSign,$page,$mySign);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }

    //得到参与随游列表
    public function actionGetTravelListByUserSign()
    {
        $this->loginValid();
        try{
            $userSign = $this->userObj->userSign;
            $page = new Page(\Yii::$app->request);
            $data =$this->CircleService->getTravelListByUserSign($userSign,$page);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }
    //得到用户的帖子
    public function actionGetArticleListByUserSign()
    {
        $this->loginValid();
        try{
            $userSign = $this->userObj->userSign;
            $page = new Page(\Yii::$app->request);

            $data =$this->CircleService->getArticleListByUserSign($userSign,$page);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }

    //得到首页列表
    public function actionGetIndexList()
    {

        $this->loginValid(false);
        try{
            $page1 = new Page();//得到所有的  个数在里面定义
            $page1->pageSize=6;
            $page2 = new Page();//得到所有的  个数在里面定义
            $page2->pageSize=2;
            $page3 = new Page();//得到所有的  个数在里面定义
            $page3->pageSize=2;
            $page4 = new Page();//得到所有的  个数在里面定义
            $number = \Yii::$app->request->post('n');
            if(empty($number))
            {
                $number=2;
            }
            $page4->pageSize=$number;
            $userSign = $this->userObj->userSign;
            $data= array();
            $data['circleDynamic'] = $this->AttentionService->getAttentionCircleDynamic($userSign,$page1);
            $data['userDynamic'] = $this->AttentionService->getAttentionUserDynamic($userSign,$page2);
            $data['recommendUser'] =$this->AttentionService->getRecommendUser($page3);
            $data['recommendTravel'] =$this->AttentionService->getRecommendTravel($page4);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }
}