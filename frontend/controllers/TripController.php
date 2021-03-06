<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/27
 * Time : 下午6:29
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\controllers;


use common\components\Code;
use common\components\DateUtils;
use common\components\LogUtils;
use common\components\TagUtil;
use common\entity\TravelTrip;
use common\entity\TravelTripApply;
use common\entity\TravelTripDetail;
use common\entity\TravelTripHighlight;
use common\entity\TravelTripPicture;
use common\entity\TravelTripPrice;
use common\entity\TravelTripPublisher;
use common\entity\TravelTripScenic;
use common\entity\TravelTripService;
use common\entity\TravelTripSpecial;
use common\entity\TravelTripTraffic;
use frontend\services\CountryService;
use frontend\services\PublisherService;
use frontend\services\TripService;
use frontend\services\UserBaseService;
use yii\base\Exception;

class TripController extends CController
{


    private $tripService;

    public function __construct($id, $module = null)
    {
        $this->tripService = new TripService();
        parent::__construct($id, $module);
    }


    /**
     * 创建随游
     * @return string
     * @throws Exception
     * @throws \Exception
     */
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

    public function actionNewTrafficTrip()
    {
        $countryService = new CountryService();
        $countryList = $countryService->getCountryList();
        $tagList = TagUtil::getInstance()->getTagList();

        return $this->render("newTrafficTrip", [
            'countryList' => $countryList,
            'tagList' => $tagList
        ]);
    }



    /**
     * 预览页面
     * @return string|\yii\web\Response
     * @throws Exception
     * @throws \Exception
     */
    public function actionPreview()
    {
        $tripId=\Yii::$app->request->get("trip");
        $travelInfo=$this->tripService->getTravelTripInfoById($tripId);
        $tripInfo=$travelInfo['info'];

        //验证当前用户是不是随游的所属者
        $userPublisherId = $this->userPublisherObj->userPublisherId;//当前用户
        if($tripInfo['createPublisherId']!=$userPublisherId){
            return $this->redirect(['/result', 'result' => '您没有权限修改此随游']);
        }

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

    public function actionTest()
    {
        /*$t=TagUtil::getInstance();
        $tagVd = json_decode(\Yii::$app->redis->get(Code::TRAVEL_TRIP_TAG_PREFIX . md5('购物')), true);
        if (!empty($tagVd)) {
            $rst = array_search(1, $tagVd);
            var_dump($rst!==false);
            if ($rst!==false) {
                $nArr = array_splice($tagVd, 0, 2);
                var_dump( $tagVd);
            }
        }*/

    }

    /**
     * 编辑随游
     * @return string|\yii\web\Response
     * @throws Exception
     * @throws \Exception
     */
    public function actionEditTrip()
    {
        $tripId=\Yii::$app->request->get("trip");
        $countryService = new CountryService();
        $countryList = $countryService->getCountryList();
        $tagList = TagUtil::getInstance()->getTagList();
        $returnUrl="editTrip";
        $travelInfo=$this->tripService->getTravelTripInfoById($tripId);
        //验证当前用户是不是随游的所属者
        $userPublisherId = $this->userPublisherObj->userPublisherId;//当前用户
        if($travelInfo['info']['createPublisherId']!=$userPublisherId){
            return $this->redirect(['/result', 'result' => '您没有权限修改此随游']);
        }

        if($travelInfo['info']['type']==TravelTrip::TRAVEL_TRIP_TYPE_TRAFFIC){
            $returnUrl="editTrafficTrip";
        }

        return $this->render($returnUrl,[
            'travelInfo'=>$travelInfo,
            'countryList' => $countryList,
            'tagList' => $tagList
        ]);
    }


    /**
     * 添加交通服务
     * @return string
     * @throws Exception
     * @throws \Exception
     */
    public function actionSaveTrafficTrip()
    {
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

        if ($this->userPublisherObj == null) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "请您先注册为随友"));
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

        $userPublisherId = $this->userPublisherObj->userPublisherId;

        $tripPicList = array();
        $tripDetailList=array();


        //随游基本信息
        $travelTrip = new TravelTrip();
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
        $travelTrip->createPublisherId = $userPublisherId;
        $travelTrip->tags = "";
        $travelTrip->type=TravelTrip::TRAVEL_TRIP_TYPE_TRAFFIC;

        $travelTripTraffic=new TravelTripTraffic();
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



        //默认把发布人加入随游关联
        $travelTripPublisher = new TravelTripPublisher();
        $travelTripPublisher->publisherId = $userPublisherId;


        //设置图片列表
        foreach ($picList as $pic) {
            $tempPic = new TravelTripPicture();
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
        try {
            $travelTrip=$this->tripService->addTravelTripTraffic($travelTrip, $travelTripTraffic, $tripPicList, $travelTripPublisher, $tripDetailList);

            return json_encode(Code::statusDataReturn(Code::SUCCESS,$travelTrip));
        } catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }


    /**
     * 添加交通服务
     * @return string
     * @throws Exception
     * @throws \Exception
     */
    public function actionAutoSaveTrafficTrip()
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

        if ($this->userPublisherObj == null) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "请您先注册为随友"));
        }
        if (empty($title)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "标题不允许为空"));
        }
        if (empty($titleImg)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "封面图不允许为空"));
        }

        if ($carServiceType==0) {
            if(!empty($carBasePrice)&&$carBasePrice<0){
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "汽车服务基础价格不允许为空"));
            }
        }

        if($airServiceType==0){
            if(!empty($airBasePrice)&&$airBasePrice<0){
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "接机服务基础价格不允许为空"));
            }
        }

        if($carServiceType==0){
            $basePrice=$carBasePrice;
        }else{
            $basePrice=$airBasePrice;
        }

        $countryId=empty($countryId)?null:$countryId;
        $cityId=empty($cityId)?null:$cityId;
        $basePrice=empty($basePrice)?null:$basePrice;
        $maxUserCount=empty($maxUserCount)?null:$maxUserCount;
        $serviceTime=empty($serviceTime)?null:$serviceTime;
        $seatCount=empty($seatCount)?null:$seatCount;
        $serviceMileage=empty($serviceMileage)?null:$serviceMileage;
        $overMileage=empty($overMileage)?null:$overMileage;
        $overTime=empty($overTime)?null:$overTime;
        $carBasePrice=empty($carBasePrice)?null:$carBasePrice;
        $airBasePrice=empty($airBasePrice)?null:$airBasePrice;

        $userPublisherId = $this->userPublisherObj->userPublisherId;

        $tripPicList = array();
        $tripDetailList=array();


        //随游基本信息
        $travelTrip = new TravelTrip();
        $travelTrip->tripId=$tripId;
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
        $travelTrip->status = TravelTrip::TRAVEL_TRIP_STATUS_AUTO_SAVE;//自动保存标示
        $travelTrip->createPublisherId = $userPublisherId;
        $travelTrip->tags = "";
        $travelTrip->type=TravelTrip::TRAVEL_TRIP_TYPE_TRAFFIC;
        $travelTrip->score=0;
        $travelTrip->tripCount=0;

        $travelTripTraffic=new TravelTripTraffic();
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



        //默认把发布人加入随游关联
        $travelTripPublisher = new TravelTripPublisher();
        $travelTripPublisher->publisherId = $userPublisherId;


        //设置图片列表
        if(!empty($picList)){
            foreach ($picList as $pic) {
                $tempPic = new TravelTripPicture();
                $tempPic->url = $pic;
                $tripPicList[] = $tempPic;
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
        try {
            if(empty($tripId)){
                $travelTrip=$this->tripService->addTravelTripTraffic($travelTrip, $travelTripTraffic, $tripPicList, $travelTripPublisher, $tripDetailList);
            }else{
                $travelTrip=$this->tripService->updateTravelTripTraffic($travelTrip, $travelTripTraffic, $tripPicList, $tripDetailList);
            }

            return json_encode(Code::statusDataReturn(Code::SUCCESS,$travelTrip));
        } catch (Exception $e) {
            throw $e;
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }

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

        if($carServiceType=='true'||$carServiceType==1){
            $carServiceType=1;
        }else{
            $carServiceType=0;
        }
        if($airServiceType=='true'||$airServiceType==1){
            $airServiceType=1;
        }else{
            $airServiceType=0;
        }

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

        $userPublisherId = $this->userPublisherObj->userPublisherId;

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
        $travelTrip->createPublisherId = $userPublisherId;

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
        }else {
            $travelTripTraffic->airplanePrice = null;
            $travelTripTraffic->nightServicePrice = null;
            $travelTripTraffic->nightTimeStart = null;
            $travelTripTraffic->nightTimeEnd = null;
        }



        //默认把发布人加入随游关联
        $travelTripPublisher = new TravelTripPublisher();
        $travelTripPublisher->publisherId = $userPublisherId;


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

    /**
     * 添加随游
     */
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
        $userPublisherId = $this->userPublisherObj->userPublisherId;

        $tripScenicList = array();
        $tripPicList = array();
        $tripStepPriceList = array();
        $tripServiceList = array();
        $tripDetailList=array();
        $tripHighlightList=array();
        $tripSpecialList=array();

        //随游基本信息
        $travelTrip = new TravelTrip();
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
        $travelTrip->status = $status;
        $travelTrip->createPublisherId = $userPublisherId;
        $travelTrip->tags = implode(",", array_merge($tagList,$cusTagList));
        $travelTrip->score=0;

        //默认把发布人加入随游关联
        $travelTripPublisher = new TravelTripPublisher();
        $travelTripPublisher->publisherId = $userPublisherId;

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
                if($tempPrice->price<0){
                    throw new Exception("无效的阶梯价格");
                }
                $tempPrice->price = $step[2];
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
            $travelTrip=$this->tripService->addTravelTrip($travelTrip, $tripScenicList, $tripPicList, $tripStepPriceList, $travelTripPublisher, $tripServiceList,$tripDetailList,$tripHighlightList,$tripSpecialList);
            $t=TagUtil::getInstance();
            $t->updateTagValList($tagList,$travelTrip['tripId']);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$travelTrip));
        } catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }


    /**
     * 自动保存随游
     */
    public function actionAutoSaveTrip()
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
        $tagList = \Yii::$app->request->post("tagList", []);
        $cusTagList = \Yii::$app->request->post("cusTagList", []);
        $specialList = \Yii::$app->request->post("specialList", "");

        $highlightList = \Yii::$app->request->post("highlightList", "");
        $status = \Yii::$app->request->post("status", TravelTrip::TRAVEL_TRIP_STATUS_AUTO_SAVE);


        if ($this->userPublisherObj == null) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "请您先注册为随友"));
        }
        if (empty($title)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "标题不允许为空"));
        }
        if (empty($titleImg)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "封面图不允许为空"));
        }

        if(empty($countryId)){
            $countryId=null;
        }
        if(empty($cityId)){
            $cityId=null;
        }
        if(empty($basePrice)){
            $basePrice=0;
        }
        if(empty($peopleCount)){
            $peopleCount=0;
        }
        if(empty($tripLong)){
            $tripLong=0;
        }
        $tripStartTime = DateUtils::convertTimePicker($beginTime, 1);
        $tripEndTime = DateUtils::convertTimePicker($endTime, 1);
        $userPublisherId = $this->userPublisherObj->userPublisherId;

        $tripScenicList = array();
        $tripPicList = array();
        $tripStepPriceList = array();
        $tripServiceList = array();
        $tripDetailList=array();
        $tripHighlightList=array();
        $tripSpecialList=array();

        //随游基本信息
        $travelTrip = new TravelTrip();
        $travelTrip->tripId=$tripId;
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
        $travelTrip->status = $status;
        $travelTrip->createPublisherId = $userPublisherId;
        $travelTrip->tags = implode(",", array_merge($tagList,$cusTagList));
        $travelTrip->score=0;
        $travelTrip->tripCount=0;

        //默认把发布人加入随游关联
        $travelTripPublisher = new TravelTripPublisher();
        $travelTripPublisher->publisherId = $userPublisherId;

        //设置景区列表
        if(!empty($scenicList)){
            foreach ($scenicList as $scenic) {
                $tempScenic = new TravelTripScenic();
                $tempScenic->name = $scenic[0];
                $tempScenic->lon = $scenic[1];
                $tempScenic->lat = $scenic[2];
                $tripScenicList[] = $tempScenic;
            }
        }
        //设置图片列表
        if(!empty($picList)){
            foreach ($picList as $pic) {
                $tempPic = new TravelTripPicture();
                $tempPic->url = $pic;
                $tripPicList[] = $tempPic;
            }
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
                if($tempPrice->price<0){
                    throw new Exception("无效的阶梯价格");
                }
                $tempPrice->price = $step[2];
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
            if(empty($travelTrip->tripId)){
                $travelTrip=$this->tripService->addTravelTrip($travelTrip, $tripScenicList, $tripPicList, $tripStepPriceList, $travelTripPublisher, $tripServiceList,$tripDetailList,$tripHighlightList,$tripSpecialList);
            }else{
                $travelTrip=$this->tripService->updateTravelTrip($travelTrip, $tripScenicList, $tripPicList, $tripStepPriceList, $tripServiceList,$tripDetailList,$tripHighlightList,$tripSpecialList);
            }

            $t=TagUtil::getInstance();
            $t->updateTagValList($tagList,$travelTrip['tripId']);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$travelTrip));
        } catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }

    /**
     * 更新随游
     * @throws Exception
     * @throws \Exception
     */
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
        $travelTrip->status=$status;

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

            $t=TagUtil::getInstance();
            $t->updateTagValList($tagList,$travelTrip['tripId']);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$travelTrip));
        } catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }

    /**
     * 发布随游
     */
    public function  actionFinishTrip()
    {
        $tripId = trim(\Yii::$app->request->post("tripId", ""));
        if (empty($tripId)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "随游不允许为空"));
        }
        try{
            $travelTrip=$this->tripService->getTravelTripById($tripId);
            if($travelTrip->status!=TravelTrip::TRAVEL_TRIP_STATUS_DRAFT){
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "您没有权限修改该随游"));
            }
            $this->tripService->changeTripStatus($tripId,TravelTrip::TRAVEL_TRIP_STATUS_NORMAL);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL, "发布随游失败"));
        }
    }

    /**
     * 跳转随游申请页面
     */
    public function actionToApplyTrip()
    {
        $tripId=trim(\Yii::$app->request->get("trip", ""));
        if (empty($tripId)) {
            return $this->redirect(['/result', 'result' => '随游不允许为空']);
        }
        $travelTrip=$this->tripService->getTravelTripById($tripId);
        if($travelTrip->createPublisherId==$this->userPublisherObj->userPublisherId){
            return $this->redirect(['/result', 'result' => '您不能加入自己的随游']);
        }

        $userService=new UserBaseService();
        $publisherService=new PublisherService();
        $tripPublisherId=$travelTrip->createPublisherId;
        $createPublisherId=$publisherService->findById($tripPublisherId);
        $createUserInfo=$userService->findUserByUserSign($createPublisherId->userId);

        return $this->render("apply",[
            'tripInfo'=>$travelTrip,
            'createUserInfo'=>$createUserInfo,
            'createPublisherInfo'=>$createPublisherId
        ]);
    }

    /**
     * 申请加入随游
     * @return \yii\web\Response
     * @throws Exception
     * @throws \Exception
     */
    public function actionApplyTrip()
    {
        $tripId=trim(\Yii::$app->request->post("trip", ""));
        $info=trim(\Yii::$app->request->post("info", ""));
        $publisherId=$this->userPublisherObj->userPublisherId;

        $tripInfo=$this->tripService->getTravelTripById($tripId);
        if(!isset($tripInfo))
        {
            return $this->redirect(['/result', 'result' => '找不到该随游']);
        }
        $apply=$this->tripService->findTravelTripApplyByTripIdAndUser($tripId,$publisherId);
        if(isset($apply)){
            return $this->redirect(['/result', 'result' => '您已经有申请正在审核，请耐心等待回复']);
        }
        $travelPublisherList=$this->tripService->getTravelTripPublisherList($tripId);
        if(isset($travelPublisherList)){
            foreach($travelPublisherList as $publisher){
                if($publisherId==$publisher['publisherId']){
                    return $this->redirect(['/result', 'result' => '您已经在此随游之中，无须申请']);
                }
            }
        }

        $travelTripApply=new TravelTripApply();
        $travelTripApply->tripId=$tripId;
        $travelTripApply->publisherId=$publisherId;
        $travelTripApply->info=$info;
        $travelTripApply->status=TravelTripApply::TRAVEL_TRIP_APPLY_STATUS_WAIT;

        $this->tripService->addTravelTripApply($this->userObj->userSign,$travelTripApply);

        return $this->redirect(['/result', 'result' => '您的申请已经提交，请耐心等待审核']);
    }

    /**
     * 移除用户随游关联
     * @throws Exception
     * @throws \Exception
     */
    public function actionRemovePublisher()
    {
        $tripId=trim(\Yii::$app->request->post("tripId", ""));
        $tripPublisherId=trim(\Yii::$app->request->post("tripPublisherId", ""));

        if(empty($tripId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"TripId Is Not Allow Empty"));
        }
        if(empty($tripPublisherId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"TripPublisherId Is Not Allow Empty"));
        }
        $tripInfo=$this->tripService->getTravelTripById($tripId);
        if(!isset($tripInfo)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR));
        }
        if($tripInfo->createPublisherId!=$this->userPublisherObj->userPublisherId){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"Invalid User Power"));
        }
        try{
            $travelTripPublisher=new TravelTripPublisher();
            $travelTripPublisher->tripId=$tripId;
            $travelTripPublisher->tripPublisherId=$tripPublisherId;

            $this->tripService->deleteTravelTriPublisher($this->userObj->userSign,$travelTripPublisher);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 获取我的随游列表
     */
    public function actionMyTripList()
    {
        if(!$this->userObj->isPublisher){
            return json_encode(Code::statusDataReturn(Code::FAIL,"User Is Not Publisher"));
        }
        try{
            $list=$this->tripService->getMyTripList($this->userPublisherObj->userPublisherId);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$list));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 获取某个随友加入的随游
     */
    public function actionMyJoinTripList()
    {
        if(!$this->userObj->isPublisher){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"User Is Not Publisher"));
        }
        try{
            $list=$this->tripService->getMyJoinTripList($this->userPublisherObj->userPublisherId);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$list));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 跳转到申请列表页面
     * @return string|\yii\web\Response
     */
    public function actionToApplyList()
    {
        $tripId=trim(\Yii::$app->request->get("trip", ""));
        if(empty($tripId)){
            return $this->redirect(['/result', 'result' => '找不到该随游']);
        }

        try{
            $applyList=$this->tripService->getPublisherApplyList($tripId);
            $tripInfo=$this->tripService->getTravelTripInfoById($tripId);
            return $this->render("applyList",[
                'applyList'=>$applyList,
                'travelInfo'=>$tripInfo
            ]);
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->redirect(['/result', 'result' => '未知系统异常']);

        }
    }

    /**
     * 同意随游申请
     */
    public function actionAgreeApply()
    {
        $applyId=trim(\Yii::$app->request->post("applyId", ""));
        $publisherId=trim(\Yii::$app->request->post("publisherId", ""));

        if(empty($applyId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"ApplyId Is Not Allow Empty"));
        }
        if(empty($publisherId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"PublisherId Is Not Allow Empty"));
        }

        try{
            $this->tripService->agreePublisherApply($this->userObj->userSign,$applyId,$publisherId,$this->userPublisherObj->userPublisherId);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 拒绝随游申请
     */
    public function actionOpposeApply()
    {
        $applyId=trim(\Yii::$app->request->post("applyId", ""));

        if(empty($applyId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"ApplyId Is Not Allow Empty"));
        }

        try{
            $this->tripService->opposePublisherApply($this->userObj->userSign,$applyId,$this->userPublisherObj->userPublisherId);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 删除随游
     */
    public function actionDeleteTrip()
    {
        $tripId=trim(\Yii::$app->request->post("tripId", ""));

        if(empty($tripId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"TripId Is Not Allow Empty"));
        }

        try{
            $info=$this->tripService->getTravelTripById($tripId);
            if($info->createPublisherId!=$this->userPublisherObj->userPublisherId){
                throw new Exception("您只能删除自己的随游哦~");
            }
            $this->tripService->changeTripStatus($tripId,TravelTrip::TRAVEL_TRIP_STATUS_DELETE);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }


    /**
     * 退出随游
     * @return string
     */
    public function actionQuitTrip()
    {
        $tripId=trim(\Yii::$app->request->post("tripId", ""));

        if(empty($tripId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"TripId Is Not Allow Empty"));
        }

        try{
            $travelTripPublisher=new TravelTripPublisher();
            $travelTripPublisher->tripId=$tripId;
            $travelTripPublisher->tripPublisherId=$this->userPublisherObj->userPublisherId;

            $this->tripService->deleteTravelTriPublisher($this->userObj->userSign,$travelTripPublisher);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }


}