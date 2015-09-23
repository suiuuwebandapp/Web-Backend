<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/3
 * Time: 下午2:39
 */
namespace app\modules\v1\controllers;

use common\components\DateUtils;
use common\components\LogUtils;
use app\modules\v1\entity\TravelTrip;
use app\modules\v1\entity\TravelTripApply;
use app\modules\v1\entity\TravelTripComment;
use app\modules\v1\entity\TravelTripDetail;
use app\modules\v1\entity\TravelTripHighlight;
use app\modules\v1\entity\TravelTripPicture;
use app\modules\v1\entity\TravelTripPrice;
use app\modules\v1\entity\TravelTripPublisher;
use app\modules\v1\entity\TravelTripScenic;
use app\modules\v1\entity\TravelTripService;
use app\modules\v1\entity\UserBase;
use app\components\Page;
use common\components\Code;
use common\components\TagUtil;
use app\modules\v1\services\CountryService;
use app\modules\v1\services\PublisherService;
use app\modules\v1\services\TravelTripCommentService;
use app\modules\v1\services\TripService;
use app\modules\v1\services\UserBaseService;
use yii\base\Exception;
use Yii;
class AppTravelController extends AController
{
    private $travelSer;
    private $tripCommentSer;
    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        $this->travelSer=new TripService();
        $this->tripCommentSer=new TravelTripCommentService();
    }

    //得到随游列表 根据筛选条件
    public function actionGetTravelList()
    {
        $this->loginValid();
        try{
            $page=new Page(Yii::$app->request);
            //$page->showAll=true;
            $title=Yii::$app->request->get('title');
            $countryId=Yii::$app->request->get('countryId');
            $cityId=Yii::$app->request->get('cityId');
            $cc=Yii::$app->request->get('cc');
            if(!empty($cc))
            {
                if(empty($countryId)&&empty($cityId)){
                    $countrySer = new CountryService();
                    $arr=$countrySer->getCC($cc);
                    $countryId=$arr[0];
                    $cityId=$arr[1];
                    if(is_array($countryId)){
                        $countryId=implode(",",$countryId);
                    }
                    if(is_array($cityId)){
                        $cityId=implode(",",$cityId);
                    }
                }
            }
            $peopleCount=Yii::$app->request->get('peopleCount');
            $startPrice=Yii::$app->request->get('startPrice');
            $endPrice=Yii::$app->request->get('endPrice');
            $tag=Yii::$app->request->get('tag');
            $data=$this->travelSer->getList($page,$title,$countryId,$cityId,$peopleCount,$startPrice,$endPrice,$tag);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$data->getList(),$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }

    }

    //得到国家城市
    public function actionGetCountry()
    {
        $this->loginValid();
        try{
            $countryService = new CountryService();
            $countryList = $countryService->getCountryList();
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$countryList));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }

    }
    //得到国家城市
    public function actionGetCity()
    {
        $this->loginValid();
        try{
            $countryId=Yii::$app->request->get('countryId');
            $cityName=Yii::$app->request->get('cityName');
            $countryService = new CountryService();
            $cityList = $countryService->getCityList($countryId,$cityName);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$cityList));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }

    }
    //得到类型
    public function actionGetTagList()
    {
        $this->loginValid();
        try{
            $tagList = TagUtil::getInstance()->getTagList();
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$tagList));
        }catch (Exception $e){
            LogUtils::log($e);
            echo json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }
    public function actionTest()
    {
        return $this->renderPartial('test');
    }
    //得到随游详情
    public function actionGetTravelInfo()
    {

        try{
            $appSign = \Yii::$app->request->get(\Yii::$app->params['app_suiuu_sign']);
            $currentUser = json_decode(stripslashes(\Yii::$app->redis->get(Code::APP_USER_LOGIN_SESSION . $appSign)));

            if (!isset($currentUser) && empty($appSign)) {
                return $this->renderPartial('error',['str1'=>'appSign不能为空','str2'=>'返回','url'=>"#"]);
            } else if (isset($currentUser)) {
                if ($currentUser->status != UserBase::USER_STATUS_NORMAL) {
                    return $this->renderPartial('error',['str1'=>'用户已经被删除','str2'=>'返回','url'=>"#"]);
                } else {
                    $this->userObj = $currentUser;
                }
            } else {
                return $this->renderPartial('error',['str1'=>'登陆已过期请重新登陆','str2'=>'返回','url'=>"#"]);
            }
            $userSign=$this->userObj->userSign;
            $trId=Yii::$app->request->get('trId');
            if(empty($trId)){  return $this->renderPartial('error',['str1'=>'未知随游','str2'=>'返回','url'=>"#"]);}
            $data=$this->travelSer->getTravelTripInfoById($trId,$userSign);
            $tripInfo=$data['info'];
            $publisherService=new PublisherService();
            $tripPublisherId=$tripInfo['createPublisherId'];

            $createPublisherId=$publisherService->findById($tripPublisherId);
            if(empty($createPublisherId)){
                return $this->renderPartial('error',['str1'=>'无法得到未知的随友','str2'=>'返回','url'=>"#"]);
            }
            return $this->renderPartial('info',['info'=>$data,'sign'=>$appSign]);
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->renderPartial('error',['str1'=>'获取详情异常','url'=>"#"]);

        }
    }

    //得到随游详情
    public function actionGetTravelInfoJson()
    {
        $this->loginValid();
        try{
            $userSign=$this->userObj->userSign;
            $trId=Yii::$app->request->get('tripId');
            if(empty($trId)){ return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"未知随游id"));}
            $data=$this->travelSer->getTravelTripInfoById($trId,$userSign);
            $tripInfo=$data['info'];
            $publisherService=new PublisherService();
            $tripPublisherId=$tripInfo['createPublisherId'];
            $createPublisherId=$publisherService->findById($tripPublisherId);
            if(empty($createPublisherId)){
                return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,'无法得到未知的随友'));
            }
                $page=new Page(Yii::$app->request);
                if(empty($this->userObj))
                {
                    $userSign='';
                }else
                {
                    $userSign=$this->userObj->userSign;
                }
                $rst= $this->tripCommentSer->getTravelComment($trId,$page,$userSign);
            $data['comment'] =$rst;
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"获取详情异常"));
        }
    }
    public function actionGetCommentList()
    {
        $this->loginValid(false);
        try{
            $tripId=\Yii::$app->request->get('tripId');
            $userSign=\Yii::$app->request->get('userSign');
            $page=new Page(Yii::$app->request);
            if(empty($tripId)){
                $rst= $this->tripCommentSer->getCommentTripList($page,$userSign);
                return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$rst));
            }else{
                if(empty($this->userObj))
                {
                    $userSign='';
                }else
                {
                    $userSign=$this->userObj->userSign;
                }
                $rst= $this->tripCommentSer->getTravelComment($tripId,$page,$userSign);
                return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$rst));
            }
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,"获取评论列表失败"));
        }
    }

    public function actionAddComment()
    {
        $this->loginValid();
        try{
            $userSign=$this->userObj->userSign;
            $tripId = \Yii::$app->request->post('tripId');
            $content = \Yii::$app->request->post('content');
            $rId= \Yii::$app->request->post('rId');
            $rTitle= \Yii::$app->request->post('rTitle');
            $rSign= \Yii::$app->request->post('rSign');
            if(empty($tripId)){return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,'无法评论未知随游'));}
            if(empty($content)){return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,'无法发布空评论'));}
            $this->tripCommentSer->addComment($userSign,$content,$rId,$tripId,$rTitle,$rSign);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,"发布评论失败"));
        }
    }
    public function actionAddSupport()
    {
        $this->loginValid();
        try {
            $userSign =$this->userObj->userSign;
            $commentId= \Yii::$app->request->post('rId');//评论id
            $this->tripCommentSer->addCommentSupport($commentId,$userSign,TravelTripComment::TYPE_SUPPORT);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e){
            LogUtils::log($e);
            echo json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }


    private function ob2ar($obj) {
        $obj=$this->unifyReturn($obj);
        if(is_object($obj)) {
            $obj = (array)$obj;
            $obj = $this->ob2ar($obj);
        } elseif(is_array($obj)) {
            foreach($obj as $key => $value) {
                $obj[$key] = $this->ob2ar($value);
            }
        }
        return $obj;
    }



    /**
     * 编辑随游
     * @return string|\yii\web\Response
     * @throws Exception
     * @throws \Exception
     */
    public function actionEditTrip()
    {
        $this->loginValid();
        $tripId=\Yii::$app->request->post("trip");
        $travelInfo=$this->travelSer->getTravelTripInfoById($tripId);
        //验证当前用户是不是随游的所属者
        $userPublisherId = $this->userPublisherObj->userPublisherId;//当前用户
        if($travelInfo['info']['createPublisherId']!=$userPublisherId){
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,"您没有权限修改此随游"));
        }
        return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$travelInfo));
    }

    /**
     * 更新随游
     * @throws Exception
     * @throws \Exception
     */
    public function actionUpdateTrip()
    {
        $this->loginValid();
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
        $highlightList = \Yii::$app->request->post("highlightList", "");
        $status = \Yii::$app->request->post("status", TravelTrip::TRAVEL_TRIP_STATUS_DRAFT);


        if ($this->userPublisherObj == null) {
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR, "请您先注册为随友"));
        }
        if (empty($tripId)) {
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR, "随游不允许为空"));
        }
        if (empty($title)) {
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR, "标题不允许为空"));
        }
        if (empty($titleImg)) {
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR, "封面图不允许为空"));
        }
        if (empty($intro)) {
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR, "简介不允许为空"));
        }
        if (empty($countryId)) {
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR, "国家不允许为空"));
        }
        if (empty($cityId)) {
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR, "城市不允许为空"));
        }
        if (empty($basePrice)) {
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR, "基础价格不允许为空"));
        }
        if (empty($peopleCount)) {
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR, "服务人数不允许为空"));
        }
        if (empty($beginTime) || empty($endTime)) {
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR, "结束时间不允许为空"));
        }
        if (empty($tripLong)) {
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR, "随游时长不允许为空"));
        }
        if (empty($info)) {
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR, "详情介绍不允许为空"));
        }
        if (empty($scenicList)) {
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR, "至少要有一个景区"));
        }
        if (empty($picList)) {
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR, "至少要有一个图片介绍"));
        }
        if (count($scenicList) > 10) {
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR, "图片介绍不能大于10个"));
        }
        if (empty($tagList)) {
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR, "至少要选择一个标签"));
        }

        //验证当前用户是不是随游的所属者
        $userPublisherId = $this->userPublisherObj->userPublisherId;//当前用户
        $travelTrip = $this->travelSer->getTravelTripById($tripId);
        if($travelTrip->createPublisherId!=$userPublisherId){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR, "您没有权限修改此随游"));
        }

        $tripStartTime = DateUtils::convertTimePicker($beginTime, 1);
        $tripEndTime = DateUtils::convertTimePicker($endTime, 1);

        $tripScenicList = array();
        $tripPicList = array();
        $tripStepPriceList = array();
        $tripServiceList = array();
        $tripDetailList=array();
        $tripHighlightList=array();

        //随游基本信息
        $travelTrip = $this->travelSer->getTravelTripById($tripId);
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
        $travelTrip->tags = implode(",", $tagList);


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

        try {

            $travelTrip=$this->travelSer->updateTravelTrip($travelTrip, $tripScenicList, $tripPicList, $tripStepPriceList, $tripServiceList,$tripDetailList,$tripHighlightList,null);
            if($status==TravelTrip::TRAVEL_TRIP_STATUS_NORMAL&&$travelTrip['status']==TravelTrip::TRAVEL_TRIP_STATUS_DRAFT){
                $this->travelSer->changeTripStatus($travelTrip['tripId'],TravelTrip::TRAVEL_TRIP_STATUS_NORMAL);
            }
            $t=TagUtil::getInstance();
            $t->updateTagValList($tagList,$travelTrip['tripId']);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$travelTrip));
        } catch (Exception $e) {
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }

    }

    /**
     * 申请加入随游
     * @return \yii\web\Response
     * @throws Exception
     * @throws \Exception
     */
    public function actionApplyTrip()
    {
        $this->loginValid();
        $tripId=trim(\Yii::$app->request->post("trip", ""));
        $info=trim(\Yii::$app->request->post("info", ""));
        $publisherId=$this->userPublisherObj->userPublisherId;

        $tripInfo=$this->travelSer->getTravelTripById($tripId);
        if(!isset($tripInfo))
        {
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"找不到该随游"));
        }
        $apply=$this->travelSer->findTravelTripApplyByTripIdAndUser($tripId,$publisherId);
        if(isset($apply)){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"您已经有申请正在审核，请耐心等待回复"));
        }

        $travelTripApply=new TravelTripApply();
        $travelTripApply->tripId=$tripId;
        $travelTripApply->publisherId=$publisherId;
        $travelTripApply->info=$info;
        $travelTripApply->status=TravelTripApply::TRAVEL_TRIP_APPLY_STATUS_WAIT;

        $this->travelSer->addTravelTripApply($this->userObj->userSign,$travelTripApply);
        return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"您的申请已经提交，请耐心等待审核"));
    }

    /**
     * 移除用户随游关联
     * @throws Exception
     * @throws \Exception
     */
    public function actionRemovePublisher()
    {
        $this->loginValid();
        $tripId=trim(\Yii::$app->request->post("tripId", ""));
        $tripPublisherId=trim(\Yii::$app->request->post("tripPublisherId", ""));

        if(empty($tripId)){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"TripId Is Not Allow Empty"));
        }
        if(empty($tripPublisherId)){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"TripPublisherId Is Not Allow Empty"));
        }
        $tripInfo=$this->travelSer->getTravelTripById($tripId);
        if(!isset($tripInfo)){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR));
        }
        if($tripInfo->createPublisherId!=$this->userPublisherObj->userPublisherId){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"Invalid User Power"));
        }
        try{
            $travelTripPublisher=new TravelTripPublisher();
            $travelTripPublisher->tripId=$tripId;
            $travelTripPublisher->tripPublisherId=$tripPublisherId;

            $this->travelSer->deleteTravelTriPublisher($this->userObj->userSing,$travelTripPublisher);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 获取我的随游列表
     */
    public function actionMyTripList()
    {
        $this->loginValid();
        /*if(!$this->userObj->isPublisher){
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,"User Is Not Publisher"));
        }*/
        $userSign=trim(\Yii::$app->request->get("userSign", ""));
        if(empty($userSign)){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,'未知的用户'));
        }
        //$userSign='a4c1406ff4cc382389f19bf6ec3e55c1';
        try{
            $publisherService=new PublisherService();
            $createPublisherInfo = $publisherService->findUserPublisherByUserSign($userSign);
            if(empty($createPublisherInfo)){
                return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,'未知的随友'));
            }
            $userPublisherId=$createPublisherInfo->userPublisherId;
            $myList=$this->travelSer->getMyTripList($userPublisherId);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$myList));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 获取某个随友加入的随游
     */
    public function actionMyJoinTripList()
    {
        $this->loginValid();

        $userSign=trim(\Yii::$app->request->get("userSign", ""));
        if(empty($userSign)){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,'未知的用户'));
        }
        try{
            $publisherService=new PublisherService();
            $createPublisherInfo = $publisherService->findUserPublisherByUserSign($userSign);
            if(empty($createPublisherInfo)){
                return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,'未知的随友'));
            }
            $userPublisherId=$createPublisherInfo->userPublisherId;
            $list=$this->travelSer->getMyJoinTripList($userPublisherId);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$list));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 申请列表
     * @return string|\yii\web\Response
     */
    public function actionGetApplyList()
    {
        $tripId=trim(\Yii::$app->request->get("trip", ""));
        $tripId=105;
        if(empty($tripId)){
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,'找不到该随游'));
        }

        try{
            $applyList=$this->travelSer->getPublisherApplyList($tripId);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$applyList));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,'未知系统异常'));
        }
    }
    /**
     * 同意随游申请
     */
    public function actionAgreeApply()
    {
        $this->loginValid();
        $applyId=trim(\Yii::$app->request->post("applyId", ""));
        $publisherId=trim(\Yii::$app->request->post("publisherId", ""));

        if(empty($applyId)){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"ApplyId Is Not Allow Empty"));
        }
        if(empty($publisherId)){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"PublisherId Is Not Allow Empty"));
        }

        try{
            $this->travelSer->agreePublisherApply($this->userObj->userSing,$applyId,$publisherId,$this->userPublisherObj->userPublisherId);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 拒绝随游申请
     */
    public function actionOpposeApply()
    {
        $this->loginValid();
        $applyId=trim(\Yii::$app->request->post("applyId", ""));

        if(empty($applyId)){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"ApplyId Is Not Allow Empty"));
        }

        try{
            $this->travelSer->opposePublisherApply($this->userObj->userSing,$applyId,$this->userPublisherObj->userPublisherId);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 删除随游
     */
    public function actionDeleteTrip()
    {
        $this->loginValid();
        $tripId=trim(\Yii::$app->request->post("tripId", ""));
        if(empty($tripId)){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"TripId Is Not Allow Empty"));
        }

        try{
            $info=$this->travelSer->getTravelTripById($tripId);
            if($info->createPublisherId!=$this->userPublisherObj->userPublisherId){
                throw new Exception("您只能删除自己的随游哦~");
            }
            $this->travelSer->changeTripStatus($tripId,TravelTrip::TRAVEL_TRIP_STATUS_DELETE);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }

    }

    public function actionGetPublisherInfo()
    {
        $this->loginValid();
        $userSign=trim(\Yii::$app->request->get("userSign", ""));
        if(empty($userSign)){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,'未知的用户'));
        }

        try{
            $userService=new UserBaseService();
            $publisherService=new PublisherService();
            $createUserInfo=$userService->findBaseInfoBySignArray($userSign);
            if(empty($createUserInfo))
            {
                return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,'未知的用户'));
            }
            if(empty($createUserInfo['isPublisher']))
            {
                return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,'未知的随友'));
            }
            $createPublisherInfo = $publisherService->findUserPublisherByUserSign($userSign);
            $data=array();
            $data['user']=$createUserInfo;
            $data['publisher']=$createPublisherInfo;
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }
    }


    private function unifyReturn($data)
    {
        if($data==false)
        {
            $data=array();
        }
        return $data;
    }


}