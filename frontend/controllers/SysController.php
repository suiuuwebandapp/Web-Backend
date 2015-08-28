<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/7/1
 * Time : 下午4:02
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\controllers;


use common\components\Code;
use common\components\DateUtils;
use common\components\LogUtils;
use common\components\TagUtil;
use common\entity\TravelTrip;
use common\entity\TravelTripDetail;
use common\entity\TravelTripHighlight;
use common\entity\TravelTripPicture;
use common\entity\TravelTripPrice;
use common\entity\TravelTripScenic;
use common\entity\TravelTripService;
use common\entity\TravelTripSpecial;
use frontend\services\CountryService;
use frontend\services\TripService;
use yii\web\Controller;
use yii\base\Exception;

class SysController extends Controller{

    public $tripService;

    public $countryList;
    public $areaCode='+86';
    public $enableCsrfValidation=false;

    public function __construct($id, $module = null)
    {
        //验证用户是否登录
        $sysUser=\Yii::$app->session->get(Code::SYS_USER_LOGIN_SESSION);
        if(isset($sysUser)){
        }else{
            return $this->redirect('/login');
        }
        $this->tripService=new TripService();
        $countrySer=new CountryService();
        $this->countryList=$countrySer->getCountryList();
        $this->areaCode='0086';

        parent::__construct($id, $module);
    }




    public function actionEditTrip()
    {
        $tripId=\Yii::$app->request->get("trip");
        $countryService = new CountryService();
        $countryList = $countryService->getCountryList();
        $tagList = TagUtil::getInstance()->getTagList();

        $travelInfo=$this->tripService->getTravelTripInfoById($tripId);
        return $this->render("sysEditTrip",[
            'travelInfo'=>$travelInfo,
            'countryList' => $countryList,
            'tagList' => $tagList
        ]);
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
        $basePriceType=trim(\Yii::$app->request->post("basePriceType", TravelTrip::TRAVEL_TRIP_BASE_PRICE_TYPE_PERSON));
        $peopleCount = trim(\Yii::$app->request->post("peopleCount", ""));
        $stepPriceList = \Yii::$app->request->post("stepPriceList", "");
        $serviceList = \Yii::$app->request->post("serviceList", "");
        $includeDetailList = \Yii::$app->request->post("includeDetailList", "");
        $unIncludeDetailList = \Yii::$app->request->post("unIncludeDetailList", "");
        $beginTime = trim(\Yii::$app->request->post("beginTime", ""));
        $endTime = trim(\Yii::$app->request->post("endTime", ""));
        $tripLong = trim(\Yii::$app->request->post("tripLong", ""));
        $tripKind = trim(\Yii::$app->request->post("tripKind", TravelTrip::TRAVEL_TRIP_TIME_TYPE_HOUR));
        $info = trim(\Yii::$app->request->post("info", ""));
        $tagList = \Yii::$app->request->post("tagList", "");
        $cusTagList = \Yii::$app->request->post("cusTagList", []);
        $specialList = \Yii::$app->request->post("specialList", "");

        $highlightList = \Yii::$app->request->post("highlightList", "");
        $status = \Yii::$app->request->post("status", TravelTrip::TRAVEL_TRIP_STATUS_DRAFT);


        if ($this->userPublisherObj == null) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "请您先注册为随友"));
        }
        if (empty($tripId)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "随游不允许为空"));
        }
        if (empty($title)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "标题不允许为空"));
        }
        if (empty($titleImg)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "封面图不允许为空"));
        }
        if (empty($intro)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "简介不允许为空"));
        }
        if (empty($countryId)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "国家不允许为空"));
        }
        if (empty($cityId)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "城市不允许为空"));
        }
        if (empty($basePrice)||$basePrice<0) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "基础价格不允许为空"));
        }
        if (empty($peopleCount)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "服务人数不允许为空"));
        }
        if (empty($beginTime) || empty($endTime)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "结束时间不允许为空"));
        }
        if (empty($tripLong)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "随游时长不允许为空"));
        }
        if (empty($info)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "详情介绍不允许为空"));
        }
        if (empty($scenicList)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "至少要有一个景区"));
        }
        if (empty($picList)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "至少要有一个图片介绍"));
        }
        if (count($scenicList) > 10) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "图片介绍不能大于10个"));
        }
        if (empty($tagList)&&empty($cusTagList)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "至少要选择一个标签"));
        }

        //验证当前用户是不是随游的所属者
        $userPublisherId = $this->userPublisherObj->userPublisherId;//当前用户
        $travelTrip = $this->tripService->getTravelTripById($tripId);
        if($travelTrip->createPublisherId!=$userPublisherId){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "您没有权限修改此随游"));
        }

        $tripStartTime = DateUtils::convertTimePicker($beginTime, 1);
        $tripEndTime = DateUtils::convertTimePicker($endTime, 1);

        $tripScenicList = array();
        $tripPicList = array();
        $tripStepPriceList = array();
        $tripServiceList = array();
        $tripDetailList=array();
        $tripHighlightList=array();
        $tripSpecialList=array();

        //随游基本信息
        $travelTrip = $this->tripService->getTravelTripById($tripId);
        $travelTrip->title = $title;
        $travelTrip->titleImg = $titleImg;
        $travelTrip->intro = $intro;
        $travelTrip->countryId = $countryId;
        $travelTrip->cityId = $cityId;
        $travelTrip->basePrice = $basePrice;
        $travelTrip->basePriceType=$basePriceType;
        $travelTrip->maxUserCount = $peopleCount;
        $travelTrip->isAirplane = false;
        $travelTrip->isHotel = false;
        $travelTrip->info = $info;
        $travelTrip->travelTime = $tripLong;
        $travelTrip->travelTimeType = $tripKind;
        $travelTrip->startTime = $tripStartTime;
        $travelTrip->endTime = $tripEndTime;
        $travelTrip->createPublisherId = $userPublisherId;
        $travelTrip->tags = implode(",", array_merge($tagList,$cusTagList));


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
        if($basePriceType==TravelTrip::TRAVEL_TRIP_BASE_PRICE_TYPE_PERSON&&!empty($stepPriceList)){
            foreach ($stepPriceList as $step) {
                if($step[1]==0){
                    continue;
                }
                $tempPrice = new TravelTripPrice();
                $tempPrice->minCount = $step[0];
                $tempPrice->maxCount = $step[1];
                $tempPrice->price = $step[2];
                if($tempPrice->price<0){
                    throw new Exception("无效的阶梯价格");
                }
                $tripStepPriceList[] = $tempPrice;
            }
        }
        //专项服务
        if(!empty($serviceList)){
            foreach ($serviceList as $service) {
                if(empty($service[0])||empty($service[1])){
                    continue;
                }
                $tempService = new TravelTripService();
                $tempService->title = $service[0];
                $tempService->money = $service[1];
                $tempService->type = $service[2];
                if($tempService->money<0){
                    throw new Exception("无效的专项服务价格");
                }
                $tripServiceList[] = $tempService;
            }
        }
        //包含内容
        if(!empty($includeDetailList)){
            foreach ($includeDetailList as $includeDetail) {
                if(empty($includeDetail)){
                    continue;
                }
                $tempDetail = new TravelTripDetail();
                $tempDetail->type = TravelTripDetail::TRAVEL_TRIP_DETAIL_TYPE_INCLUDE;
                $tempDetail->name = $includeDetail;
                $tripDetailList[] = $tempDetail;
            }
        }
        //不包含内容
        if(!empty($unIncludeDetailList)){
            foreach ($unIncludeDetailList as $unIncludeDetail) {
                if(empty($unIncludeDetail)){
                    continue;
                }
                $tempDetail = new TravelTripDetail();
                $tempDetail->type = TravelTripDetail::TRAVEL_TRIP_DETAIL_TYPE_UN_INCLUDE;
                $tempDetail->name = $unIncludeDetail;
                $tripDetailList[] = $tempDetail;
            }
        }
        //随游亮点
        if(!empty($highlightList)){
            foreach ($highlightList as $highlight) {
                if(empty($highlight)){
                    continue;
                }
                $tempHighlight = new TravelTripHighlight();
                $tempHighlight->value = $highlight;
                $tripHighlightList[] = $tempHighlight;
            }
        }
        //特色
        if(!empty($specialList)){
            foreach ($specialList as $special) {
                if(empty($special)){
                    continue;
                }
                $tempSpecial = new TravelTripSpecial();
                $tempSpecial->title=$special[0];
                $tempSpecial->info=$special[1];
                $tempSpecial->picUrl=$special[2];

                $tripSpecialList[] = $tempSpecial;
            }
        }


        try {
            $travelTrip=$this->tripService->updateTravelTrip($travelTrip, $tripScenicList, $tripPicList, $tripStepPriceList, $tripServiceList,$tripDetailList,$tripHighlightList,$tripSpecialList);
            if($status==TravelTrip::TRAVEL_TRIP_STATUS_NORMAL&&$travelTrip['status']==TravelTrip::TRAVEL_TRIP_STATUS_DRAFT){
                $this->tripService->changeTripStatus($travelTrip['tripId'],TravelTrip::TRAVEL_TRIP_STATUS_NORMAL);
            }
            $t=TagUtil::getInstance();
            $t->updateTagValList($tagList,$travelTrip['tripId']);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$travelTrip));
        } catch (Exception $e) {
            //LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }


}