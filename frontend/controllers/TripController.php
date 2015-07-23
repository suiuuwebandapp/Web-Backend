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

        $travelInfo=$this->tripService->getTravelTripInfoById($tripId);
        //验证当前用户是不是随游的所属者
        $userPublisherId = $this->userPublisherObj->userPublisherId;//当前用户
        if($travelInfo['info']['createPublisherId']!=$userPublisherId){
            return $this->redirect(['/result', 'result' => '您没有权限修改此随游']);
        }
        return $this->render("editTrip",[
            'travelInfo'=>$travelInfo,
            'countryList' => $countryList,
            'tagList' => $tagList
        ]);
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
        if (empty($basePrice)) {
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
        if (empty($basePrice)) {
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

        $this->tripService->addTravelTripApply($travelTripApply);

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

            $this->tripService->deleteTravelTriPublisher($travelTripPublisher);
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
            $this->tripService->agreePublisherApply($applyId,$publisherId,$this->userPublisherObj->userPublisherId);
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
            $this->tripService->opposePublisherApply($applyId,$this->userPublisherObj->userPublisherId);
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

            $this->tripService->deleteTravelTriPublisher($travelTripPublisher);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }


}