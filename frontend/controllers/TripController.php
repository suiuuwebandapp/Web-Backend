<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/27
 * Time : 下午6:29
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\controllers;


use backend\components\Page;
use common\components\Code;
use common\components\DateUtils;
use common\components\TagUtil;
use common\entity\TravelTrip;
use common\entity\TravelTripPicture;
use common\entity\TravelTripPrice;
use common\entity\TravelTripPublisher;
use common\entity\TravelTripScenic;
use common\entity\TravelTripService;
use frontend\services\CountryService;
use frontend\services\PublisherService;
use frontend\services\TripService;
use frontend\services\UserBaseService;
use frontend\services\UserPublisherService;
use yii\base\Exception;

class TripController extends CController
{


    private $tripService;

    public function __construct($id, $module = null)
    {
        $this->tripService = new TripService();
        parent::__construct($id, $module);
    }


    public function actionNewTrip()
    {
        $countryService = new CountryService();
        $countryList = $countryService->getCountryList();
        $tagList = TagUtil::getInstance()->getTagList();

        return $this->render("newTrip", [
            'countryList' => $countryList,
            'tagList' => $tagList
        ]);
    }


    public function actionPreview()
    {
        $tripId=\Yii::$app->request->get("trip");
        $travelInfo=$this->tripService->getTravelTripInfoById($tripId);
        $tripInfo=$travelInfo['info'];
        $userService=new UserBaseService();
        $publisherService=new PublisherService();
        $tripPublisherId=$tripInfo['createPublisherId'];
        $createPublisherId=$publisherService->findById($tripPublisherId);
        $createUserInfo=$userService->findUserByUserSign($createPublisherId->userId);

        return $this->render("preview",[
            'travelInfo'=>$travelInfo,
            'createUserInfo'=>$createUserInfo,
            'createPublisherInfo'=>$createPublisherId
        ]);
    }

    public function actionEditTrip()
    {
        $tripId=\Yii::$app->request->get("trip");
        $countryService = new CountryService();
        $countryList = $countryService->getCountryList();
        $tagList = TagUtil::getInstance()->getTagList();

        $travelInfo=$this->tripService->getTravelTripInfoById($tripId);

        return $this->render("editTrip",[
            'travelInfo'=>$travelInfo,
            'countryList' => $countryList,
            'tagList' => $tagList
        ]);
    }


    public function actionSaveTrip()
    {

        $title = trim(\Yii::$app->request->post("title", ""));
        $titleImg = trim(\Yii::$app->request->post("titleImg", ""));
        $intro = trim(\Yii::$app->request->post("intro", ""));
        $countryId = trim(\Yii::$app->request->post("countryId", ""));
        $cityId = trim(\Yii::$app->request->post("cityId", ""));
        $scenicList = \Yii::$app->request->post("scenicList", "");
        $picList = \Yii::$app->request->post("picList", "");
        $basePrice = trim(\Yii::$app->request->post("basePrice", ""));
        $peopleCount = trim(\Yii::$app->request->post("peopleCount", ""));
        $stepPriceList = \Yii::$app->request->post("stepPriceList", "");
        $serviceList = \Yii::$app->request->post("serviceList", "");
        $beginTime = trim(\Yii::$app->request->post("beginTime", ""));
        $endTime = trim(\Yii::$app->request->post("endTime", ""));
        $tripLong = trim(\Yii::$app->request->post("tripLong", ""));
        $tripKind = trim(\Yii::$app->request->post("tripKind", ""));
        $info = trim(\Yii::$app->request->post("info", ""));
        $tagList = \Yii::$app->request->post("tagList", "");
        $status = \Yii::$app->request->post("status", TravelTrip::TRAVEL_TRIP_STATUS_DRAFT);


        if ($this->userPublisherObj == null) {
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "请您先注册为随友"));
            return;
        }

        if (empty($title)) {
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "标题不允许为空"));
            return;
        }
        if (empty($titleImg)) {
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "封面图不允许为空"));
            return;
        }
        if (empty($intro)) {
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "简介不允许为空"));
            return;
        }
        if (empty($countryId)) {
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "国家不允许为空"));
            return;
        }
        if (empty($cityId)) {
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "城市不允许为空"));
            return;
        }
        if (empty($basePrice)) {
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "基础价格不允许为空"));
            return;
        }
        if (empty($peopleCount)) {
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "服务人数不允许为空"));
            return;
        }
        if (empty($beginTime) || empty($endTime)) {
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "结束时间不允许为空"));
            return;
        }
        if (empty($tripLong)) {
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "随游时长不允许为空"));
            return;
        }
        if (empty($info)) {
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "详情介绍不允许为空"));
            return;
        }
        if (empty($scenicList)) {
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "至少要有一个景区"));
            return;
        }
        if (empty($picList)) {
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "至少要有一个图片介绍"));
            return;
        }
        if (count($scenicList) > 10) {
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "图片介绍不能大于10个"));
            return;
        }
        if (empty($tagList)) {
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "至少要选择一个标签"));
            return;
        }


        $tripStartTime = DateUtils::convertTimePicker($beginTime, 1);
        $tripEndTime = DateUtils::convertTimePicker($endTime, 1);
        $userPublisherId = $this->userPublisherObj->userPublisherId;

        $tripScenicList = array();
        $tripPicList = array();
        $tripStepPriceList = array();
        $tripServiceList = array();

        //随游基本信息
        $travelTrip = new TravelTrip();
        $travelTrip->title = $title;
        $travelTrip->titleImg = $titleImg;
        $travelTrip->intro = $intro;
        $travelTrip->countryId = $countryId;
        $travelTrip->cityId = $cityId;
        $travelTrip->basePrice = $basePrice;
        $travelTrip->maxUserCount = $peopleCount;
        $travelTrip->isAirplane = false;
        $travelTrip->isHotel = false;
        $travelTrip->info = $info;
        $travelTrip->travelTime = $tripLong;
        $travelTrip->travelTimeType = $tripKind;
        $travelTrip->startTime = $tripStartTime;
        $travelTrip->endTime = $tripEndTime;
        $travelTrip->status = $status;
        $travelTrip->createPublisherId = $userPublisherId;
        $travelTrip->tags = implode(",", $tagList);
        $travelTrip->score=0;

        //默认把发布人加入随游关联
        $travelTripPublisher = new TravelTripPublisher();
        $travelTripPublisher->tripPublisherId = $userPublisherId;

        //设置景区列表
        foreach ($scenicList as $scenic) {
            $tempScenic = new TravelTripScenic();
            $tempScenic->name = $scenic[0];
            $tempScenic->lon = $scenic[1];
            $tempScenic->lat = $scenic[2];
            $tripScenicList[] = $tempScenic;
        }
        //设置图片列表
        foreach ($picList as $pic) {
            $tempPic = new TravelTripPicture();
            $tempPic->url = $pic;
            $tripPicList[] = $tempPic;
        }
        //阶梯价格
        if(!empty($stepPriceList)){
            foreach ($stepPriceList as $step) {
                $tempPrice = new TravelTripPrice();
                $tempPrice->minCount = $step[0];
                $tempPrice->maxCount = $step[1];
                $tempPrice->price = $step[2];
                $tripStepPriceList[] = $tempPrice;
            }
        }
        if(!empty($serviceList)){
            //专项服务
            foreach ($serviceList as $service) {
                $tempService = new TravelTripService();
                $tempService->title = $service[0];
                $tempService->money = $service[1];
                $tempService->type = $service[2];
                $tripServiceList[] = $tempService;
            }
        }

        try {
            $travelTrip=$this->tripService->addTravelTrip($travelTrip, $tripScenicList, $tripPicList, $tripStepPriceList, $travelTripPublisher, $tripServiceList);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$travelTrip));
        } catch (Exception $e) {
            echo json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }



    public function actionUpdateTrip()
    {

        $tripId = trim(\Yii::$app->request->post("tripId", ""));
        $title = trim(\Yii::$app->request->post("title", ""));
        $titleImg = trim(\Yii::$app->request->post("titleImg", ""));
        $intro = trim(\Yii::$app->request->post("intro", ""));
        $countryId = trim(\Yii::$app->request->post("countryId", ""));
        $cityId = trim(\Yii::$app->request->post("cityId", ""));
        $scenicList = \Yii::$app->request->post("scenicList", "");
        $picList = \Yii::$app->request->post("picList", "");
        $basePrice = trim(\Yii::$app->request->post("basePrice", ""));
        $peopleCount = trim(\Yii::$app->request->post("peopleCount", ""));
        $stepPriceList = \Yii::$app->request->post("stepPriceList", "");
        $serviceList = \Yii::$app->request->post("serviceList", "");
        $beginTime = trim(\Yii::$app->request->post("beginTime", ""));
        $endTime = trim(\Yii::$app->request->post("endTime", ""));
        $tripLong = trim(\Yii::$app->request->post("tripLong", ""));
        $tripKind = trim(\Yii::$app->request->post("tripKind", ""));
        $info = trim(\Yii::$app->request->post("info", ""));
        $tagList = \Yii::$app->request->post("tagList", "");


        if ($this->userPublisherObj == null) {
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "请您先注册为随友"));
            return;
        }
        if (empty($tripId)) {
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "随游不允许为空"));
            return;
        }
        if (empty($title)) {
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "标题不允许为空"));
            return;
        }
        if (empty($titleImg)) {
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "封面图不允许为空"));
            return;
        }
        if (empty($intro)) {
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "简介不允许为空"));
            return;
        }
        if (empty($countryId)) {
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "国家不允许为空"));
            return;
        }
        if (empty($cityId)) {
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "城市不允许为空"));
            return;
        }
        if (empty($basePrice)) {
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "基础价格不允许为空"));
            return;
        }
        if (empty($peopleCount)) {
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "服务人数不允许为空"));
            return;
        }
        if (empty($beginTime) || empty($endTime)) {
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "结束时间不允许为空"));
            return;
        }
        if (empty($tripLong)) {
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "随游时长不允许为空"));
            return;
        }
        if (empty($info)) {
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "详情介绍不允许为空"));
            return;
        }
        if (empty($scenicList)) {
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "至少要有一个景区"));
            return;
        }
        if (empty($picList)) {
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "至少要有一个图片介绍"));
            return;
        }
        if (count($scenicList) > 10) {
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "图片介绍不能大于10个"));
            return;
        }
        if (empty($tagList)) {
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "至少要选择一个标签"));
            return;
        }


        $tripStartTime = DateUtils::convertTimePicker($beginTime, 1);
        $tripEndTime = DateUtils::convertTimePicker($endTime, 1);
        $userPublisherId = $this->userPublisherObj->userPublisherId;

        $tripScenicList = array();
        $tripPicList = array();
        $tripStepPriceList = array();
        $tripServiceList = array();

        //随游基本信息
        $travelTrip = $this->tripService->getTravelTripById($tripId);
        $travelTrip->title = $title;
        $travelTrip->titleImg = $titleImg;
        $travelTrip->intro = $intro;
        $travelTrip->countryId = $countryId;
        $travelTrip->cityId = $cityId;
        $travelTrip->basePrice = $basePrice;
        $travelTrip->maxUserCount = $peopleCount;
        $travelTrip->isAirplane = false;
        $travelTrip->isHotel = false;
        $travelTrip->info = $info;
        $travelTrip->travelTime = $tripLong;
        $travelTrip->travelTimeType = $tripKind;
        $travelTrip->startTime = $tripStartTime;
        $travelTrip->endTime = $tripEndTime;
        $travelTrip->createPublisherId = $userPublisherId;
        $travelTrip->tags = implode(",", $tagList);

        //默认把发布人加入随游关联
        $travelTripPublisher = new TravelTripPublisher();
        $travelTripPublisher->tripPublisherId = $userPublisherId;

        //设置景区列表
        foreach ($scenicList as $scenic) {
            $tempScenic = new TravelTripScenic();
            $tempScenic->name = $scenic[0];
            $tempScenic->lon = $scenic[1];
            $tempScenic->lat = $scenic[2];
            $tripScenicList[] = $tempScenic;
        }
        //设置图片列表
        foreach ($picList as $pic) {
            $tempPic = new TravelTripPicture();
            $tempPic->url = $pic;
            $tripPicList[] = $tempPic;
        }
        //阶梯价格
        foreach ($stepPriceList as $step) {
            $tempPrice = new TravelTripPrice();
            $tempPrice->minCount = $step[0];
            $tempPrice->maxCount = $step[1];
            $tempPrice->price = $step[2];
            $tripStepPriceList[] = $tempPrice;
        }

        //专项服务
        foreach ($serviceList as $service) {
            $tempService = new TravelTripService();
            $tempService->title = $service[0];
            $tempService->money = $service[1];
            $tempService->type = $service[2];
            $tripServiceList[] = $tempService;
        }

        try {
            $travelTrip=$this->tripService->addTravelTrip($travelTrip, $tripScenicList, $tripPicList, $tripStepPriceList, $travelTripPublisher, $tripServiceList);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$travelTrip));
        } catch (Exception $e) {
            echo json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }


    /**
     * 发布随游
     */
    public function  actionFinishTrip()
    {
        $tripId = trim(\Yii::$app->request->post("tripId", ""));
        if (empty($tripId)) {
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "随游不允许为空"));
            return;
        }
        try{
            $this->tripService->changeTripStatus($tripId,TravelTrip::TRAVEL_TRIP_STATUS_NORMAL);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            echo json_encode(Code::statusDataReturn(Code::FAIL, "发布随游失败"));
        }
    }

}