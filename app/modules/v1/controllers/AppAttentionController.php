<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/4/26
 * Time: 下午5:53
 */
namespace app\modules\v1\controllers;

use common\components\Code;
use common\components\LogUtils;
use app\components\Page;
use app\modules\v1\services\UserAttentionService;
use yii\base\Exception;

class AppAttentionController extends AController
{
    private $AttentionService;
    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        $this->AttentionService = new UserAttentionService();

    }


    //得到关注用户
    public function  actionGetAttentionUser()
    {

        $this->loginValid();
        try {
            $page = new Page(\Yii::$app->request);
            $userSign = $this->userObj->userSign;
            $data = $this->AttentionService->getUserAttentionUser($userSign, $page);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS, $data));
        } catch (Exception $e) {
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
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
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS, $data));
        } catch (Exception $e) {
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }

    }

    //得到收藏随游
    public function  actionGetCollectionTravel()
    {
        $this->loginValid();
        try {
            $page = new Page(\Yii::$app->request);
            $userSign = \Yii::$app->request->get("userSign");
            $data = $this->AttentionService->getUserCollectionTravel($userSign, $page);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS, $data));
        } catch (Exception $e) {
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }
    }

    //得到关注旅图
    public function  actionGetAttentionTp()
    {
        $this->loginValid();
        try {
            $page = new Page(\Yii::$app->request);
            $userSign = \Yii::$app->request->get("userSign");
            //$this->userObj->userSign
            $data = $this->AttentionService->getUserAttentionTp($userSign, $page);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS, $data));
        } catch (Exception $e) {
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }
    }
    //得到关注问答社区
    public function  actionGetAttentionQa()
    {
        $this->loginValid();
        try {
            $page = new Page(\Yii::$app->request);
            $userSign = \Yii::$app->request->get("userSign");
            $data = $this->AttentionService->getUserAttentionQa($userSign, $page);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS, $data));
        } catch (Exception $e) {
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
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
                return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,'关注信息不能为空'));
            }
            $cUserSign = $this->userObj->userSign;
            $data =$this->AttentionService->CreateAttentionToUser($userSign,$cUserSign);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
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
                return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,'收藏信息不能为空'));
            }
            $userSign = $this->userObj->userSign;
            $data = $this->AttentionService->CreateCollectionToArticle($articleId,$userSign);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }
    }
    //收藏随游
    public function actionAddCollectionTravel()
    {
        header("Access-Control-Allow-Origin:*");
        $this->loginValid();
        try{
            $travelId= \Yii::$app->request->post('travelId');
            if(empty($travelId))
            {
                return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,'收藏信息不能为空'));
            }
            $userSign = $this->userObj->userSign;
            $data= $this->AttentionService->CreateCollectionToTravel($travelId,$userSign);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
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
                return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,'点赞信息不能为空'));
            }
            $userSign = $this->userObj->userSign;
            $data= $this->AttentionService->CreatePraiseToTravel($travelId,$userSign);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
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
                return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,'点赞信息不能为空'));
            }
            $userSign = $this->userObj->userSign;
            $data= $this->AttentionService->CreatePraiseToCircleArticle($articleId,$userSign);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }
    }

    //删除
    public function actionDeleteAttention()
    {
        header("Access-Control-Allow-Origin:http://www.suiuu.com");
        $this->loginValid();
        try{
            $attentionId= \Yii::$app->request->post('attentionId');
            if(empty($attentionId))
            {
                return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,'取消信息不能为空'));
            }
            $userSign = $this->userObj->userSign;
            $rst = $this->AttentionService->deleteAttention($attentionId,$userSign);
            if($rst==1){
                return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,'success'));
            }else{
                return $this->apiReturn(Code::statusDataReturn(Code::FAIL,'fail'));
            }
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
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
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }
    }
    //获得推荐用户
    public function actionGetRecommendUser()
    { $this->loginValid(false);
        try{
            $page =new Page(\Yii::$app->request);
            $data =$this->AttentionService->getRecommendUser($page);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
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
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
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
                return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR, "未知用户"));
            }
            $id = \Yii::$app->request->post('id');
            if (empty($id)) {
                return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR, "未知问题"));
            }
            $attentionSer = new UserAttentionService();
            $atId = $attentionSer->createAttentionToQa($id, $userSign);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS, $atId));
        }catch (Exception $e) {
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,"关注异常"));
        }
    }

    //关注旅图
    public function actionAttentionTp()
    {
        try {
            $this->loginValid();
            $userSign = $this->userObj->userSign;
            if (empty($userSign)) {
                return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR, "未知用户"));
            }
            $id = \Yii::$app->request->post('id');
            if (empty($id)) {
                return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR, "未知旅图"));
            }
            $attentionSer = new UserAttentionService();
            $atId = $attentionSer->createAttentionToTp($id, $userSign);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS, $atId));
        }catch (Exception $e) {
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,"关注异常"));
        }
    }
}
