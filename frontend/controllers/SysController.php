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
        $returnUrl="sysEditTrip";

        $travelInfo=$this->tripService->getTravelTripInfoById($tripId);

        if($travelInfo['info']['type']==TravelTrip::TRAVEL_TRIP_TYPE_TRAFFIC){
            $returnUrl="sysTrafficTrip";
        }

        return $this->render($returnUrl,[
            'travelInfo'=>$travelInfo,
            'countryList' => $countryList,
            'tagList' => $tagList
        ]);
    }







    public function actionUpdateTrafficTrip()
    {
        $tripId = trim(\Yii::$app->request->post("tripId", ""));
        $title = trim(\Yii::$app->request->post("title", ""));
        $titleImg = trim(\Yii::$app->request->post("titleImg", ""));
        $countryId = trim(\Yii::$app->request->post("countryId", ""));
        $cityId = trim(\Yii::$app->request->post("cityId", ""));
        $carLicense = \Yii::$app->request->post("license", "");
        $maxUserCount = \Yii::$app->request->post("maxUserCount", "");
        $scheduledType = \Yii::$app->request->post("scheduledType", "");
        $scheduledTime = \Yii::$app->request->post("scheduledTime", "");

        $carType = \Yii::$app->request->post("carType", "");
        $seatCount = \Yii::$app->request->post("seatCount", "");
        $space = \Yii::$app->request->post("space", "");
        $allowSmoke = \Yii::$app->request->post("allowSmoke", 0);
        $allowPet = \Yii::$app->request->post("allowPet", 0);
        $childSeat = \Yii::$app->request->post("childSeat", 0);
        $picList = \Yii::$app->request->post("picList", "");


        $carServiceType = \Yii::$app->request->post("carServiceType", "");
        $airServiceType = \Yii::$app->request->post("airServiceType", "");
        $carBasePrice = \Yii::$app->request->post("carBasePrice", "");
        $serviceTime = \Yii::$app->request->post("serviceTime", "");
        $serviceMileage = \Yii::$app->request->post("serviceMileage", "");
        $overTime = \Yii::$app->request->post("overTime", "");
        $overMileage = \Yii::$app->request->post("overMileage", "");
        $airBasePrice = trim(\Yii::$app->request->post("airBasePrice", ""));
        $nightPriceType = trim(\Yii::$app->request->post("nightPriceType", ""));
        $nightTimeBegin = trim(\Yii::$app->request->post("nightTimeBegin", ""));
        $nightTimeEnd = trim(\Yii::$app->request->post("nightTimeEnd", ""));
        $nightTimePrice = trim(\Yii::$app->request->post("nightTimePrice", ""));


        $info = trim(\Yii::$app->request->post("info", ""));
        $serviceTimeType=trim(\Yii::$app->request->post("serviceTimeType",""));
        $serviceTimeBegin=trim(\Yii::$app->request->post("serviceTimeBegin",""));
        $serviceTimeEnd=trim(\Yii::$app->request->post("serviceTimeEnd",""));
        $includeDetailList = \Yii::$app->request->post("includeDetailList", "");
        $unIncludeDetailList = \Yii::$app->request->post("unIncludeDetailList", "");
        $status = \Yii::$app->request->post("status", TravelTrip::TRAVEL_TRIP_STATUS_DRAFT);


        if($carServiceType==='true'||$carServiceType==1){
            $carServiceType=1;
        }else{
            $carServiceType=0;
        }
        if($airServiceType==='true'||$airServiceType==1){
            $airServiceType=1;
        }else{
            $airServiceType=0;
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
        if (empty($countryId)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "国家不允许为空"));
        }
        if (empty($cityId)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "城市不允许为空"));
        }
        if (empty($carLicense)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "驾照时间不允许为空"));
        }
        if (empty($maxUserCount)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "最多接待人数不允许为空"));
        }
        if ($scheduledType==2&&empty($scheduledTime)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "提前预定时间不允许为空"));
        }
        if (empty($carType)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "车型不允许为空"));
        }
        if (empty($seatCount)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "座位数不允许为空"));
        }
        if (empty($space)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "行李控件不允许为空"));
        }
        if (empty($picList)||count($picList)<5) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "至少要有五个图片介绍"));
        }
        if ($carServiceType==1&&$airServiceType==1) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "包车接机服务不能同时为空"));
        }

        if ($carServiceType==0) {

            if(empty($serviceTime)){
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "服务时间不允许为空"));
            }
            if(empty($carBasePrice)||$carBasePrice<0){
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "汽车服务基础价格不允许为空"));
            }
        }

        if($airServiceType==0){

            if(empty($airBasePrice)||$airBasePrice<0){
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "接机服务基础价格不允许为空"));
            }
            if($nightPriceType==1){
                if(empty($nightTimeBegin)||empty($nightTimeEnd)){
                    return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "夜间服务时间不允许为空"));
                }
                if(empty($nightTimePrice)||$nightTimePrice<0){
                    return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "夜间服务价格不允许为空"));
                }

            }
        }

        if (empty($info)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "详情介绍不允许为空"));
        }

        if ($serviceTimeType==0) {
            if (empty($serviceTimeBegin)||empty($serviceTimeEnd)) {
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "服务时间不允许为空"));
            }
        }

        $basePrice=null;
        if($carServiceType==0){
            $basePrice=$carBasePrice;
        }else{
            $basePrice=$airBasePrice;
        }


        $tripPicList = array();
        $tripDetailList=array();


        //随游基本信息
        $travelTrip = $this->tripService->getTravelTripById($tripId);
        $travelTrip->title = $title;
        $travelTrip->titleImg = $titleImg;
        $travelTrip->intro = $title;
        $travelTrip->countryId = $countryId;
        $travelTrip->cityId = $cityId;
        $travelTrip->basePrice = $basePrice;
        $travelTrip->basePriceType=TravelTrip::TRAVEL_TRIP_BASE_PRICE_TYPE_COUNT;
        $travelTrip->maxUserCount = $maxUserCount;
        $travelTrip->info = $info;
        $travelTrip->travelTime = $serviceTime;
        $travelTrip->travelTimeType = TravelTrip::TRAVEL_TRIP_TIME_TYPE_HOUR;
        $travelTrip->status = $status;

        $travelTripTraffic=$this->tripService->getTravelTripTrafficByTripId($tripId);;
        $travelTripTraffic->driverLicenseDate=$carLicense;

        $travelTripTraffic->carType=$carType;
        $travelTripTraffic->seatCount=$seatCount;
        $travelTripTraffic->spaceInfo=$space;
        $travelTripTraffic->allowSmoke=$allowSmoke;
        $travelTripTraffic->allowPet=$allowPet;
        $travelTripTraffic->childSeat=$childSeat;


        if($scheduledType==2){
            $travelTrip->scheduledTime=$scheduledTime*(60*60*24);
        }else{
            $travelTrip->scheduledTime=null;
        }

        if($serviceTimeType==1){
            $travelTrip->startTime = null;
            $travelTrip->endTime = null;
        }else{
            $travelTrip->startTime = $serviceTimeBegin;
            $travelTrip->endTime = $serviceTimeEnd;
        }

        if($carServiceType==0){
            $travelTripTraffic->serviceTime=$serviceTime;
            $travelTripTraffic->serviceMileage=$serviceMileage;
            $travelTripTraffic->overTimePrice=$overTime;
            $travelTripTraffic->overMileagePrice=$overMileage;
            $travelTripTraffic->carPrice=$carBasePrice;
        }else{
            $travelTripTraffic->carPrice=null;
            $travelTripTraffic->serviceTime=null;
            $travelTripTraffic->serviceMileage=null;
            $travelTripTraffic->overTimePrice=null;
            $travelTripTraffic->overMileagePrice=null;
        }
        if($airServiceType==0){
            $travelTripTraffic->airplanePrice=$airBasePrice;
            if($nightPriceType==1){
                $travelTripTraffic->nightServicePrice=$nightTimePrice;
                $travelTripTraffic->nightTimeStart=$nightTimeBegin;
                $travelTripTraffic->nightTimeEnd=$nightTimeEnd;
            }
        }else{
            $travelTripTraffic->airplanePrice=null;
            $travelTripTraffic->nightServicePrice=null;
            $travelTripTraffic->nightTimeStart=null;
            $travelTripTraffic->nightTimeEnd=null;
        }



        //设置图片列表
        foreach ($picList as $pic) {
            $tempPic = new TravelTripPicture();
            $tempPic->tripId=$tripId;
            $tempPic->url = $pic;
            $tripPicList[] = $tempPic;
        }
        //包含内容
        if(!empty($includeDetailList)){
            foreach ($includeDetailList as $includeDetail) {
                if(empty($includeDetail)){
                    continue;
                }
                $tempDetail = new TravelTripDetail();
                $tempDetail->tripId=$tripId;
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
                $tempDetail->tripId=$tripId;
                $tempDetail->type = TravelTripDetail::TRAVEL_TRIP_DETAIL_TYPE_UN_INCLUDE;
                $tempDetail->name = $unIncludeDetail;
                $tripDetailList[] = $tempDetail;
            }
        }
        try {
            $travelTrip=$this->tripService->updateTravelTripTraffic($travelTrip, $travelTripTraffic, $tripPicList, $tripDetailList);

            return json_encode(Code::statusDataReturn(Code::SUCCESS,$travelTrip));
        } catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
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
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }


}