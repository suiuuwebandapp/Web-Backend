<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/4/26
 * Time: 下午5:53
 */
namespace frontend\controllers;

use common\components\Code;
use common\components\Common;
use common\entity\UserBase;
use frontend\components\Page;
use frontend\services\CircleService;
use frontend\services\UserAttentionService;
use frontend\services\UserBaseService;
use yii\base\Controller;
use yii\base\Exception;

class AttentionController extends AController
{

    private $userBaseService;
    private $CircleService;
    private $AttentionService;

    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        $this->userBaseService = new UserBaseService();
        $this->CircleService = new CircleService();
        $this->AttentionService = new UserAttentionService();

    }

    //得到关注圈子
    public function  actionGetAttentionCircle()
    {
        $this->loginValid();
        try {
            $page = new Page(\Yii::$app->request);
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

        $this->loginValid();
        try {
            $page = new Page(\Yii::$app->request);
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
        $this->loginValid();
        try {
            $page = new Page(\Yii::$app->request);
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
        $this->loginValid();
        try {
            $page = new Page(\Yii::$app->request);
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
        $this->loginValid();
        try{
            $circleId= \Yii::$app->request->post('cId');
            if(empty($circleId))
            {
                echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'关注信息不能为空'));
                return;
            }
            $userSign = $this->userObj->userSign;
            $data =  $this->AttentionService->CreateAttentionToCircle($circleId,$userSign);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }
    //关注用户
    public function actionAddAttentionUser()
    {
        $this->loginValid();
        try{
            $userSign= \Yii::$app->request->post('userSign');
            if(empty($userSign))
            {
                echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'关注信息不能为空'));
                return;
            }
            $cUserSign = $this->userObj->userSign;
            $data =$this->AttentionService->CreateAttentionToUser($userSign,$cUserSign);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }
    //收藏文章
    public function actionAddCollectionArticle()
    {
        $this->loginValid();
        try{
            $articleId= \Yii::$app->request->post('articleId');
            if(empty($articleId))
            {
                echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'收藏信息不能为空'));
                return;
            }
            $userSign = $this->userObj->userSign;
            $data = $this->AttentionService->CreateCollectionToArticle($articleId,$userSign);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }
    //收藏随游
    public function actionAddCollectionTravel()
    {
        $this->loginValid();
        try{
            $travelId= \Yii::$app->request->post('travelId');
            if(empty($travelId))
            {
                echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'收藏信息不能为空'));
                return;
            }
            $userSign = $this->userObj->userSign;
            $data= $this->AttentionService->CreateCollectionToTravel($travelId,$userSign);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }

    //随游点赞
    public function actionAddPraiseTravel()
    {
        $this->loginValid();
        try{
            $travelId= \Yii::$app->request->post('travelId');
            if(empty($travelId))
            {
                echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'点赞信息不能为空'));
                return;
            }
            $userSign = $this->userObj->userSign;
            $data= $this->AttentionService->CreatePraiseToTravel($travelId,$userSign);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }
    //圈子文章点赞
    public function actionAddPraiseCircleArticle()
    {
        $this->loginValid();
        try{
            $articleId= \Yii::$app->request->post('articleId');
            if(empty($articleId))
            {
                echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'点赞信息不能为空'));
                return;
            }
            $userSign = $this->userObj->userSign;
            $data= $this->AttentionService->CreatePraiseToCircleArticle($articleId,$userSign);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }

    //删除
    public function actionDeleteAttention()
    {
        $this->loginValid();
        try{
            $attentionId= \Yii::$app->request->post('attentionId');
            if(empty($attentionId))
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

    //圈子动态  /热门动态
    public function actionGetCircleDynamic()
    {
        $this->loginValid();
        try{
            $page = new Page(\Yii::$app->request);
            $userSign = $this->userObj->userSign;
            $data = $this->AttentionService->getAttentionCircleDynamic($userSign,$page);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }

    public function actionGetRecommendCircle()
    {
        $this->loginValid(false);
        try{
            $page = new Page(\Yii::$app->request);
            $data = $this->AttentionService->getRecommendCircle($page);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }
    //用户动态 //关注动态
    public function actionGetUserDynamic()
    {
        $this->loginValid();
        try{
            $page = new Page(\Yii::$app->request);
            $count = \Yii::$app->request->post('count');
            $userSign = $this->userObj->userSign;
            $data = $this->AttentionService->getAttentionUserDynamic($userSign,$page,$count);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }
    //获得推荐用户
    public function actionGetRecommendUser()
    { $this->loginValid(false);
        try{
            $page =new Page(\Yii::$app->request);
            $data =$this->AttentionService->getRecommendUser($page);
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
        $this->loginValid();
        try{
            $page = new Page(\Yii::$app->request);
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
        $this->loginValid();
        try{
            $page = new Page(\Yii::$app->request);
            $userSign = $this->userObj->userSign;
            $type = \Yii::$app->request->post('type');
            $data =$this->AttentionService->getMessageRemind($userSign,$page,$type);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }

    //取消消息 即阅读消息
    public function actionDeleteUserMessageRemind()
    {
        $this->loginValid();
        try{
            $rid = \Yii::$app->request->post('rid');
            $userSign = $this->userObj->userSign;
            $this->AttentionService->deleteUserMessageRemind($rid,$userSign);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }

}
