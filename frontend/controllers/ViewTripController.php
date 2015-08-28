<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/30
 * Time : 下午7:06
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\controllers;


use backend\components\Page;
use backend\components\TableResult;
use common\components\Code;
use common\components\Common;
use common\components\LogUtils;
use common\components\PageResult;
use common\components\TagUtil;
use common\entity\TravelTrip;
use common\entity\TravelTripComment;
use common\entity\UserAttention;
use frontend\services\PublisherService;
use frontend\services\TravelTripCommentService;
use frontend\services\TripService;
use frontend\services\UserAttentionService;
use frontend\services\UserBaseService;
use yii\base\Exception;
use yii\debug\models\search\Log;

class ViewTripController extends UnCController{


    public $tripService;
    public $tripCommentSer;
    public $AttentionService;

    public $isTripList;
    public $search;


    public function __construct($id, $module = null)
    {
        $this->tripService=new TripService();
        $this->AttentionService=new UserAttentionService();
        $this->tripCommentSer=new TravelTripCommentService();
        parent::__construct($id, $module);
    }

    /**
     * 跳转到随游列表页面
     * @return string
     * @throws Exception
     * @throws \Exception
     */
    public function actionList()
    {
        $search=\Yii::$app->request->get("s",null);
        $type=\Yii::$app->request->get("t",null);
        $activity=\Yii::$app->request->get("a",null);

        $typeArray=null;
        //查询热门推荐
        $tagList = TagUtil::getInstance()->getTagList();
        $page=new Page();
        $page->setCurrentPage(1);
        $page->pageSize=4;

        //$recommendTravel =$this->AttentionService->getRecommendTravel($page);
        //查询基本
        $tripPage=new Page();
        $tripPage->pageSize=16;
        $tripPage->setCurrentPage(1);
        $this->tripService=new TripService();
        if(!empty($type)){
            $typeArray=[$type];
        }
        $tripPage= $this->tripService->getList($tripPage,$search,null,null,null,null,null,null,null,$typeArray,$activity);

        $pageHtml=Common::pageHtml($tripPage->currentPage,$tripPage->pageSize,$tripPage->totalCount);
        $pageResult=new PageResult($tripPage);
        $pageResult->pageHtml=$pageHtml;

        $this->isTripList=true;
        $this->search=$search;
        return $this->render("list",[
            'tagList' => $tagList,
            //'rTravel'=>$recommendTravel['data'],
            'pageResult'=>$pageResult,
            'search'=>$search,
            'type'=>$type
        ]);
    }

    public function actionGetRecommendTrip()
    {
        try{
            $cPage=\Yii::$app->request->post('p');
            if(empty($cPage)||$cPage<1)
            {
                $cPage=1;
            }
            $numb=4;
            $page=new Page();
            $page->currentPage=$cPage;
            $page->pageSize=$numb;
            $page->startRow = (($page->currentPage - 1) * $page->pageSize);
            $recommendTravel =$this->AttentionService->getRecommendTravel($page);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$recommendTravel));
         }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 跳转到详情页面
     * @return string
     * @throws Exception
     * @throws \Exception
     */
    public function actionInfo()
    {
        $tripId=\Yii::$app->request->get("trip");
        $returnUrl="info";$travelInfo=null;
        $travelInfo=$this->tripService->getTravelTripInfoById($tripId);
        if($travelInfo['info']['type']==TravelTrip::TRAVEL_TRIP_TYPE_TRAFFIC){
            $returnUrl="trafficInfo";
        }
        $applyList=$this->tripService->getPublisherApplyList($tripId);
        $recommendPage=new Page();
        $recommendPage->pageSize=3;
        $recommendPage->sortName="score";
        $recommendPage->sortType="DESC";
        $recommendPage=$this->tripService->getRelateRecommendTrip($recommendPage,$travelInfo['info']['countryId'],$travelInfo['info']['cityId']);

        $tripInfo=$travelInfo['info'];
        $userService=new UserBaseService();
        $publisherService=new PublisherService();
        $tripPublisherId=$tripInfo['createPublisherId'];
        $createPublisherId=$publisherService->findById($tripPublisherId);
        $createUserInfo=$userService->findUserByUserSign($createPublisherId->userId);

        $userRecommend=$this->tripService->findTravelTripRecommendByTripId($tripId);

        $attention =new UserAttentionService();
        if(empty($this->userObj))
        {
            $userSign='';
        }else
        {
            $userSign=$this->userObj->userSign;
        }

        $rst=$attention->getAttentionResult(UserAttention::TYPE_COLLECT_FOR_TRAVEL,$tripId,$userSign);

        return $this->render($returnUrl,[
            'travelInfo'=>$travelInfo,
            'createUserInfo'=>$createUserInfo,
            'createPublisherInfo'=>$createPublisherId,
            'attention'=>$rst,
            'relateRecommend'=>$recommendPage->getList(),
            'applyList'=>$applyList,
            'userRecommend'=>$userRecommend
        ]);
    }

    /**
     * 收藏随游
     * @return string
     */
    public function actionAddCollectionTravel()
    {
        try{
            if(empty($this->userObj))
            {
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'请登陆后再收藏'));
            }
            $travelId= \Yii::$app->request->post('travelId');
            if(empty($travelId))
            {
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'收藏信息不能为空'));
            }
            $userSign = $this->userObj->userSign;
            $data=$this->AttentionService->CreateCollectionToTravel($travelId,$userSign);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }


    /**
     * 删除收藏
     * @return string
     */
    public function actionDeleteAttention()
    {

        try{
            if(empty($this->userObj))
            {
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'请登陆后再收藏'));
            }
            $attentionId= \Yii::$app->request->post('attentionId');
            if(empty($attentionId))
            {
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'取消信息不能为空'));
            }
            $userSign = $this->userObj->userSign;
            $this->AttentionService->deleteAttention($attentionId,$userSign);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }


    /**
     * 查询目的地的随游
     * @return string
     */
    public function actionGetTripSearch()
    {
        $search=\Yii::$app->request->post("s");
        if(empty($search)){
            return json_encode(Code::statusDataReturn(Code::SUCCESS,""));
        }
        try{
            $page=new Page();
            $page->pageSize=6;
            $page=$this->tripService->getTripDesSearchList($page,$search);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$page->getList()));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }


    /**
     * 获取随游列表
     * @return string
     */
    public function actionGetTripList()
    {
        $c=\Yii::$app->request->post("p");
        $title=\Yii::$app->request->post("title");
        $peopleCount=\Yii::$app->request->post("peopleCount");
        $amount=\Yii::$app->request->post("amount");
        $tag=\Yii::$app->request->post("tag");
        $orderType=\Yii::$app->request->post("orderType","1");
        $isHot=\Yii::$app->request->post("hot");
        $type=\Yii::$app->request->post("type");


        $startPrice="";
        $endPrice="";
        if($peopleCount==0){
            $peopleCount=null;
        }
        if(!empty($amount)){
            $amount=str_replace("￥","",$amount);
            $amount=str_replace(" ","",$amount);
            $priceArr=explode("-",$amount);
            $startPrice=$priceArr[0];
            $endPrice=$priceArr[1];
        }
        if(empty($c)){
            $c=1;
        }
        try{
            $page=new Page();
            $page->pageSize=16;
            $page->setCurrentPage($c);

            if($orderType==2){
                $page->sortName="tripCount";
            }else if($orderType==3){
                $page->sortName="commentCount";
            }else{
                $page->sortName="score";
            }
            $page->sortType="DESC";
            $this->tripService=new TripService();
            //查询基本
            $page= $this->tripService->getList($page,$title,null,null,$peopleCount,$startPrice,$endPrice,$tag,$isHot,$type);

            //查询热门推荐
            $pageHtml=Common::pageHtml($page->currentPage,$page->pageSize,$page->totalCount);
            $pageResult=new PageResult($page);
            $pageResult->pageHtml=$pageHtml;

            return json_encode(Code::statusDataReturn(Code::SUCCESS,$pageResult));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"获取随游列表失败"));
        }
    }

    /**初始化redis Tag
     * @throws Exception
     * @throws \Exception
     */
    public function actionInitRedisTag()
    {
        $c=\Yii::$app->request->post("p");
        $title=\Yii::$app->request->post("title");
        $peopleCount=\Yii::$app->request->post("peopleCount");
        $amount=\Yii::$app->request->post("amount");
        $tag=\Yii::$app->request->post("tag");
        $page=new Page();
        $page->showAll=true;
        $page= $this->tripService->getList($page,$title,null,null,$peopleCount,'','',$tag);
        $arr=$page->getList();
        foreach($arr as $val)
        {
            $i=intval($val['tripId']);
            $tag=$val['tags'];
            $t=TagUtil::getInstance();
            $t->updateTagValList(explode(',',stripslashes($tag)),$i);
        }

    }

    /**
     * 获取评论列表
     * @return string
     */
    public function actionGetCommentList()
    {
        try{
            $cPage=\Yii::$app->request->post('cPage');
            $tripId=\Yii::$app->request->post('tripId');
            if(empty($cPage)||$cPage<1)
            {
                $cPage=1;
            }
            $numb=5;
            $page=new Page();
            $page->currentPage=$cPage;
            $page->pageSize=$numb;
            $page->startRow = (($page->currentPage - 1) * $page->pageSize);
            if(empty($this->userObj))
            {
                $userSign='';
            }else
            {
                $userSign=$this->userObj->userSign;
            }

            $rst= $this->tripCommentSer->getTravelComment($tripId,$page,$userSign);
            $str='';
            $totalCount=$rst['msg']->totalCount;
            if(intval($totalCount)!=0)
            {

                $count=intval($totalCount);
                //$str=$count;//Common::pageHtml($cPage,$numb,$count);
            }
            //
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$rst['data'],$rst['msg']));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"获取评论列表失败"));
        }
    }


    /**
     * 添加评论
     * @return string
     */
    public function actionAddComment()
    {
        if(empty($this->userObj))
        {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'请登陆后再发布评论'));
        }
        try{
            $userSign=$this->userObj->userSign;
            $tripId = \Yii::$app->request->post('tripId');
            $content = \Yii::$app->request->post('content');
            $rId= \Yii::$app->request->post('rId');
            $rTitle= \Yii::$app->request->post('rTitle');
            $rUserSign= \Yii::$app->request->post('rSign');
            if(empty($tripId)){return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'无法评论未知随游'));}
            if(empty($content)){return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'无法发布空评论'));}
            $this->tripCommentSer->addComment($userSign,$content,$rId,$tripId,$rTitle,$rUserSign);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"发布评论失败"));
        }
    }


    /**
     * 支持评论
     * @return string
     */
    public function actionAddSupport()
    {
        try {
            if(empty($this->userObj))
            {
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'请登陆后再点赞'));
            }
            $userSign =$this->userObj->userSign;
            $commentId= \Yii::$app->request->post('rId');//评论id
            $this->tripCommentSer->addCommentSupport($commentId,$userSign,TravelTripComment::TYPE_SUPPORT);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }


    /**
     * 获取用户评论
     * @return string
     */
    public function actionGetUserComment()
    {
        try{
            $cPage=\Yii::$app->request->post('cPage');
            if(empty($cPage)||$cPage<1)
            {
                $cPage=1;
            }
            $numb=5;
            $page=new Page();
            $page->currentPage=$cPage;
            $page->pageSize=$numb;
            $page->startRow = (($page->currentPage - 1) * $page->pageSize);
            if(empty($this->userObj))
            {
                $userSign='';
            }else
            {
                $userSign=$this->userObj->userSign;
            }

            $rst= $this->tripCommentSer->getCommentTripList($page,$userSign);
            $str='';
            $totalCount=$rst['msg']->totalCount;
            if(intval($totalCount)!=0)
            {

                $count=intval($totalCount);
                $str=Common::pageHtml($cPage,$numb,$count);
            }

            return json_encode(Code::statusDataReturn(Code::SUCCESS,$rst['data'],$str));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"获取评论列表失败"));
        }
    }
}