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
use common\components\LogUtils;
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
            return json_encode(Code::statusDataReturn(Code::SUCCESS, $data));
        } catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
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
            return json_encode(Code::statusDataReturn(Code::SUCCESS, $data));
        } catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
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
            return json_encode(Code::statusDataReturn(Code::SUCCESS, $data));
        } catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
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
            return json_encode(Code::statusDataReturn(Code::SUCCESS, $data));
        } catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    //得到关注旅图
    public function  actionGetAttentionTp()
    {
        $this->loginValid();
        try {
            $page = new Page(\Yii::$app->request);
            $userSign = $this->userObj->userSign;
            $data = $this->AttentionService->getUserAttentionTp($userSign, $page);
            return json_encode(Code::statusDataReturn(Code::SUCCESS, $data));
        } catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }
    //得到关注问答社区
    public function  actionGetAttentionQa()
    {
        $this->loginValid();
        try {
            $page = new Page(\Yii::$app->request);
            $userSign = $this->userObj->userSign;
            $data = $this->AttentionService->getUserAttentionQa($userSign, $page);
            return json_encode(Code::statusDataReturn(Code::SUCCESS, $data));
        } catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
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
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'关注信息不能为空'));
            }
            $userSign = $this->userObj->userSign;
            $data =  $this->AttentionService->CreateAttentionToCircle($circleId,$userSign);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
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
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'关注信息不能为空'));
            }
            $cUserSign = $this->userObj->userSign;
            $data =$this->AttentionService->CreateAttentionToUser($userSign,$cUserSign);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
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
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'收藏信息不能为空'));
            }
            $userSign = $this->userObj->userSign;
            $data = $this->AttentionService->CreateCollectionToArticle($articleId,$userSign);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
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
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'收藏信息不能为空'));
            }
            $userSign = $this->userObj->userSign;
            $data= $this->AttentionService->CreateCollectionToTravel($travelId,$userSign);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
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
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'点赞信息不能为空'));
            }
            $userSign = $this->userObj->userSign;
            $data= $this->AttentionService->CreatePraiseToTravel($travelId,$userSign);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
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
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'点赞信息不能为空'));
            }
            $userSign = $this->userObj->userSign;
            $data= $this->AttentionService->CreatePraiseToCircleArticle($articleId,$userSign);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
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
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'取消信息不能为空'));
            }
            $userSign = $this->userObj->userSign;
            $rst = $this->AttentionService->deleteAttention($attentionId,$userSign);
            if($rst==1){
                return json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
            }else{
                return json_encode(Code::statusDataReturn(Code::FAIL,'fail'));
            }
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
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
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    public function actionGetRecommendCircle()
    {
        $this->loginValid(false);
        try{
            $page = new Page(\Yii::$app->request);
            $data = $this->AttentionService->getRecommendCircle($page);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
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
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }
    //获得推荐用户
    public function actionGetRecommendUser()
    { $this->loginValid(false);
        try{
            $page =new Page(\Yii::$app->request);
            $data =$this->AttentionService->getRecommendUser($page);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
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
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
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
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
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
            return json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }


    /**关注问答社区
     * @return string
     */
    public function actionAttentionQa()
    {
        try {
            $this->loginValid();
            $userSign = $this->userObj->userSign;
            if (empty($userSign)) {
                return json_encode(Code::statusDataReturn(Code::FAIL, "未知用户"));
            }
            $id = \Yii::$app->request->post('id');
            $attentionSer = new UserAttentionService();
            $atId = $attentionSer->createAttentionToQa($id, $userSign);
            return json_encode(Code::statusDataReturn(Code::SUCCESS, $atId));
        }catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"关注异常"));
        }
    }

    //关注旅图
    public function actionAttentionTp()
    {
        try {
            $this->loginValid();
            $userSign = $this->userObj->userSign;
            if (empty($userSign)) {
                return json_encode(Code::statusDataReturn(Code::FAIL, "未知用户"));
            }
            $id = \Yii::$app->request->post('id');
            $attentionSer = new UserAttentionService();
            $atId = $attentionSer->createAttentionToTp($id, $userSign);
            return json_encode(Code::statusDataReturn(Code::SUCCESS, $atId));
        }catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"关注异常"));
        }
    }
}
