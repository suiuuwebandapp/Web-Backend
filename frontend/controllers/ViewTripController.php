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
use common\components\PageResult;
use common\components\TagUtil;
use common\entity\TravelTripComment;
use common\entity\UserAttention;
use frontend\services\PublisherService;
use frontend\services\TravelTripCommentService;
use frontend\services\TripService;
use frontend\services\UserAttentionService;
use frontend\services\UserBaseService;
use yii\base\Exception;

class ViewTripController extends UnCController{


    public $tripService;
    public $tripCommentSer;
    public $AttentionService;


    public function __construct($id, $module = null)
    {
        $this->tripService=new TripService();
        $this->AttentionService=new UserAttentionService();
        $this->tripCommentSer=new TravelTripCommentService();
        parent::__construct($id, $module);
    }

    public function actionList()
    {
        $tagList = TagUtil::getInstance()->getTagList();
        $cPage=\Yii::$app->request->post('cPage');
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
        return $this->render("list",[
            'tagList' => $tagList,
            'rTravel'=>$recommendTravel['data']
        ]);
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
        $travelInfo=$this->tripService->getTravelTripInfoById($tripId);
        $tripInfo=$travelInfo['info'];
        $userService=new UserBaseService();
        $publisherService=new PublisherService();
        $tripPublisherId=$tripInfo['createPublisherId'];
        $createPublisherId=$publisherService->findById($tripPublisherId);
        $createUserInfo=$userService->findUserByUserSign($createPublisherId->userId);
        $attention =new UserAttentionService();
        if(empty($this->userObj))
        {
            $userSign='';
        }else
        {
            $userSign=$this->userObj->userSign;
        }

        $rst=$attention->getAttentionResult(UserAttention::TYPE_COLLECT_FOR_TRAVEL,$tripId,$userSign);

        return $this->render("info",[
            'travelInfo'=>$travelInfo,
            'createUserInfo'=>$createUserInfo,
            'createPublisherInfo'=>$createPublisherId,
            'attention'=>$rst
        ]);
    }

    //收藏随游
    public function actionAddCollectionTravel()
    {
        try{
            if(empty($this->userObj))
            {
                echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'请登陆后再收藏'));
                return;
            }
            $travelId= \Yii::$app->request->post('travelId');
            if(empty($travelId))
            {
                echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'收藏信息不能为空'));
                return;
            }
            $userSign = $this->userObj->userSign;
            $data=$this->AttentionService->CreateCollectionToTravel($travelId,$userSign);
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

        try{
            if(empty($this->userObj))
            {
                echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'请登陆后再收藏'));
                return;
            }
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
    public function actionGetTripList()
    {
        $c=\Yii::$app->request->post("p");
        $title=\Yii::$app->request->post("title");
        $peopleCount=\Yii::$app->request->post("peopleCount");
        $amount=\Yii::$app->request->post("amount");
        $tag=\Yii::$app->request->post("tag");
        $startPrice="";
        $endPrice="";
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
            $this->tripService=new TripService();
            //查询基本
            $page= $this->tripService->getList($page,$title,null,null,$peopleCount,$startPrice,$endPrice,$tag);

            //查询热门推荐
            //
            $pageHtml=Common::pageHtml($page->currentPage,$page->pageSize,$page->totalCount);
            $pageResult=new PageResult($page);
            $pageResult->pageHtml=$pageHtml;

            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$pageResult));
        }catch (Exception $e){
            echo json_encode(Code::statusDataReturn(Code::FAIL,"获取随游列表失败"));
        }
        return;
    }

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
                $str=Common::pageHtml($cPage,$numb,$count);
            }
            //
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$rst['data'],$str));
        }catch (Exception $e){
            echo json_encode(Code::statusDataReturn(Code::FAIL,"获取评论列表失败"));
        }
    }

    public function actionAddComment()
    {
        try{
            if(empty($this->userObj))
            {
                echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'请登陆后再发布评论'));
                return;
            }
            $userSign=$this->userObj->userSign;
            $tripId = \Yii::$app->request->post('tripId');
            $content = \Yii::$app->request->post('content');
            $rId= \Yii::$app->request->post('rId');
            $rTitle= \Yii::$app->request->post('rTitle');
            $rUserSign= \Yii::$app->request->post('rSign');
            if(empty($tripId)){return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'无法评论未知随游'));}
            if(empty($content)){return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'无法发布空评论'));}
            $this->tripCommentSer->addComment($userSign,$content,$rId,$tripId,$rTitle,$rUserSign);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
            }catch (Exception $e){
        echo json_encode(Code::statusDataReturn(Code::FAIL,"发布评论失败"));
        }
    }
    public function actionAddSupport()
    {
        try {
            if(empty($this->userObj))
            {
                echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'请登陆后再发布评论'));
                return;
            }
            $userSign =$this->userObj->userSign;
            $commentId= \Yii::$app->request->post('rId');//评论id
            $this->tripCommentSer->addCommentSupport($commentId,$userSign,TravelTripComment::TYPE_SUPPORT);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }

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
            //
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$rst['data'],$str));
        }catch (Exception $e){
            echo json_encode(Code::statusDataReturn(Code::FAIL,"获取评论列表失败"));
        }
    }
}