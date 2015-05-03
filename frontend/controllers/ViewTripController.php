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
use common\components\Code;
use common\components\TagUtil;
use frontend\services\PublisherService;
use frontend\services\TripService;
use frontend\services\UserBaseService;
use yii\base\Exception;

class ViewTripController extends UnCController{


    public $tripService;


    public function __construct($id, $module = null)
    {
        $this->tripService=new TripService();
        parent::__construct($id, $module);
    }

    public function actionList()
    {
        $tagList = TagUtil::getInstance()->getTagList();

        return $this->render("list",[
            'tagList' => $tagList
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

        return $this->render("info",[
            'travelInfo'=>$travelInfo,
            'createUserInfo'=>$createUserInfo,
            'createPublisherInfo'=>$createPublisherId
        ]);
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

        try{
            $page=new Page();
            $page->currentPage=$c;
            $page->showAll=true;

            $this->tripService=new TripService();
            //查询基本
            $rst= $this->tripService->getList($page,$title,null,null,$peopleCount,$startPrice,$endPrice,$tag);
            //查询类似推荐

            //查询热门推荐

            //
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$rst->getList()));
        }catch (Exception $e){
            echo json_encode(Code::statusDataReturn(Code::FAIL,"获取随游列表失败"));
        }
        return;
    }

}