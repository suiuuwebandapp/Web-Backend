<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/4/26
 * Time: 下午5:53
 */
namespace frontend\controllers;

use common\components\Code;
use common\entity\UserBase;
use frontend\services\CircleService;
use frontend\services\UserAttentionService;
use frontend\services\UserBaseService;
use yii\base\Controller;
use yii\base\Exception;

class AttentionController extends Controller
{

    private $userBaseService;
    private $CircleService;
    private $AttentionService;

    private $userObj;
    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        $this->userBaseService = new UserBaseService();
        $this->CircleService = new CircleService();
        $this->AttentionService = new UserAttentionService();
        $this->userObj =new UserBase();
        $this->userObj->userSign='7f11453d3ed03159808e5c8ee850f1db';
    }

    //得到关注圈子
    public function  actionGetAttentionCircle()
    {

        try {
            $page = \Yii::$app->request->post('page');
            $userSign = $this->userObj->userSign;
            $data = $this->AttentionService->getUserAttentionCircle($userSign, $page);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS, $data));
        } catch (Exception $e) {
            $error = $e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, $error));
        }

    }

    //得到关注用户
    public function  actionGetAttentionUser()
    {

        try {
            $page = \Yii::$app->request->post('page');
            $userSign = $this->userObj->userSign;
            $data = $this->AttentionService->getUserAttentionUser($userSign, $page);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS, $data));
        } catch (Exception $e) {
            $error = $e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, $error));
        }

    }

    //得到收藏文章
    public function  actionGetCollectionArticle()
    {

        try {
            $page = \Yii::$app->request->post('page');
            $userSign = $this->userObj->userSign;
            $data = $this->AttentionService->getUserCollectionArticle($userSign, $page);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS, $data));
        } catch (Exception $e) {
            $error = $e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, $error));
        }

    }

    //得到收藏随游
    public function  actionGetCollectionTravel()
    {

        try {
            $page = \Yii::$app->request->post('page');
            $userSign = $this->userObj->userSign;
            $data = $this->AttentionService->getUserCollectionTravel($userSign, $page);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS, $data));
        } catch (Exception $e) {
            $error = $e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, $error));
        }
    }

    //关注圈子
    public function actionAddAttentionCircle()
    {
        try{
            $circleId= \Yii::$app->request->post('cId');
            if(empty($circleId))
            {
                echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'关注信息不能为空'));
                return;
            }
            $userSign = $this->userObj->userSign;
            $this->AttentionService->CreateAttentionToCircle($circleId,$userSign);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }
    //关注用户
    public function actionAddAttentionUser()
    {
        try{
            $userSign= \Yii::$app->request->post('userSign');
            if(empty($userSign))
            {
                echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'关注信息不能为空'));
                return;
            }
            $cUserSign = $this->userObj->userSign;
            $this->AttentionService->CreateAttentionToUser($userSign,$cUserSign);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }
    //收藏文章
    public function actionAddCollectionArticle()
    {
        try{
            $articleId= \Yii::$app->request->post('articleId');
            if(empty($articleId))
            {
                echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'收藏信息不能为空'));
                return;
            }
            $userSign = $this->userObj->userSign;
            $this->AttentionService->CreateCollectionToArticle($articleId,$userSign);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }
    //收藏随游
    public function actionAddCollectionTravel()
    {
        try{
            $travelId= \Yii::$app->request->post('travelId');
            if(empty($travelId))
            {
                echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'收藏信息不能为空'));
                return;
            }
            $userSign = $this->userObj->userSign;
            $this->AttentionService->CreateCollectionToTravel($travelId,$userSign);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
}
    //删除
    public function actionDeleteAttention()
    {
        try{
            $attentionId= \Yii::$app->request->post('attentionId');
            if(empty($travelId))
            {
                echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'取消信息不能为空'));
                return;
            }
            $userSign = $this->userObj->userSign;
            $this->AttentionService->deleteAttention($attentionId,$userSign);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }

    //圈子动态
    public function actionGetCircleDynamic()
    {
        try{
            $page = \Yii::$app->request->post('page');
            $userSign = $this->userObj->userSign;
            $data = $this->AttentionService->getAttentionCircleDynamic($userSign,$page);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }
    //用户动态
    public function actionGetUserDynamic()
    {
        try{
            $page = \Yii::$app->request->post('page');
            $userSign = $this->userObj->userSign;
            $data = $this->AttentionService->getAttentionUserDynamic($userSign,$page);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }
    //获得推荐用户
    public function actionGetRecommendUser()
    {
        try{
            $page = \Yii::$app->request->post('page');
            $data =$this->AttentionService->getRecommendUser($page);
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
        try{
            $page = 0;//得到所有的  个数在里面定义
            $userSign = $this->userObj->userSign;
            $data= array();
            $data['circleDynamic'] = $this->AttentionService->getAttentionCircleDynamic($userSign,$page);
            $data['userDynamic'] = $this->AttentionService->getAttentionUserDynamic($userSign,$page);
            $data['recommendUser'] =$this->AttentionService->getRecommendUser($page);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }

    //得到粉丝
    public function actionGetFans()
    {
        try{
            $page = \Yii::$app->request->post('page');
            $userSign = $this->userObj->userSign;
            $data =$this->AttentionService->getUserFans($userSign,$page);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }


    //得到消息提醒
    public function actionGetMessagesRemind()
    {
        try{
            $page = \Yii::$app->request->post('page');
            $userSign = $this->userObj->userSign;
            $data =$this->AttentionService->getMessageRemind($userSign,$page);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }


    public function actionTest()
    {
        echo $this->AttentionService->addRemind();
    }
}
