<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/6/4
 * Time: 上午10:08
 */

namespace backend\controllers;


use backend\components\Page;
use backend\components\TableResult;
use backend\services\TripService;
use backend\services\UserBaseService;
use common\components\Code;
use common\entity\TravelTrip;
use common\entity\TravelTripRecommend;
use Yii;
use yii\base\Exception;

class TripController extends CController {

    private $tripSer;

    public function __construct($id, $module = null)
    {
        $this->tripSer=new TripService();
        parent::__construct($id, $module);
    }

    public function actionList()
    {
        return $this->render('list');
    }

    public function actionGetList()
    {
        $page=new Page(Yii::$app->request);
        $page->sortName="tripId";
        $page->sortType="desc";
        $search=\Yii::$app->request->get("searchText","");
        $status=Yii::$app->request->get('status');
        $startPrice=Yii::$app->request->get('startPrice');
        $endPrice=Yii::$app->request->get('endPrice');
        $page = $this->tripSer->getTripDbList($page,$search,$startPrice,$endPrice,$status);
        $tableResult=new TableResult($page->draw,count($page->getList()),$page->totalCount,$page->getList());
        echo json_encode($tableResult);
    }

    public function actionGetCommentList()
    {
        $page=new Page(Yii::$app->request);
        $page->sortName="commentId";
        $page->sortType="desc";
        $search=Yii::$app->request->get("searchText","");
        $page = $this->tripSer->getCommentList($page,$search);
        $tableResult=new TableResult($page->draw,count($page->getList()),$page->totalCount,$page->getList());
        echo json_encode($tableResult);
    }

    public function actionCommentList()
    {
        return $this->render('commentList');
    }
    public function actionDeleteComment()
    {
        $id=\Yii::$app->request->post("id");
        if(empty($id)){return json_encode(Code::statusDataReturn(Code::FAIL,"编号不能为空"));}
        try{
            $this->tripSer->deleteComment($id);
        }catch (Exception $e){
            return json_encode(Code::statusDataReturn(Code::FAIL,$e->getName()));
        }
        return json_encode(Code::statusDataReturn(Code::SUCCESS));

    }

    public function actionToEditUserRecommend()
    {
        $tripId=Yii::$app->request->get("tripId");
        $userBaseService=new UserBaseService();
        $page=new Page();
        $page->showAll=true;
        $userRecommendList=$userBaseService->getUserRecommendListByPage($page,null);
        $tripService=new TripService();
        $recommendInfo=$tripService->findTravelTripRecommendByTripId($tripId);
        return $this->render('editUserRecommend',[
            'userRecommendList'=>$userRecommendList->getList(),
            'tripId'=>$tripId,
            'recommendInfo'=>$recommendInfo
        ]);
    }


    public function actionUpdateUserRecommend()
    {
        $tripId=Yii::$app->request->post("tripId");
        $userId=Yii::$app->request->post("userId");
        $content=Yii::$app->request->post("content");

        $tripRecommend=new TravelTripRecommend();
        $tripRecommend->userId=$userId;
        $tripRecommend->tripId=$tripId;
        $tripRecommend->content=$content;

        $this->tripSer->updateTravelTripRecommend($tripRecommend);

        return json_encode(Code::statusDataReturn(Code::SUCCESS));

    }


    public function actionToUpdateTripInfo()
    {
        $tripId=Yii::$app->request->get("tripId");
        $tripInfo=$this->tripSer->findObjectById(TravelTrip::class,$tripId);

        return $this->render("updateTripInfo",[
            'tripInfo'=>$tripInfo
        ]);
    }

    public function actionUpdateTripInfo()
    {
        $trip=Yii::$app->request->post("tripId");
        $tripCount=Yii::$app->request->post("tripCount");
        $score=Yii::$app->request->post("score");
        $isHot=Yii::$app->request->post("hot",0);
        $type=Yii::$app->request->post("type",0);

        $this->tripSer->updateTravelTripBase($trip,$score,$tripCount,$isHot,$type);

        return json_encode(Code::statusDataReturn(Code::SUCCESS));
    }


}