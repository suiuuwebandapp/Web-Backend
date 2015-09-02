<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/3
 * Time: 下午2:39
 */
namespace frontend\controllers;

use common\components\DateUtils;
use common\components\LogUtils;
use common\components\SysMessageUtils;
use common\entity\TravelTrip;
use common\entity\TravelTripApply;
use common\entity\TravelTripComment;
use common\entity\TravelTripDetail;
use common\entity\TravelTripHighlight;
use common\entity\TravelTripPicture;
use common\entity\TravelTripPrice;
use common\entity\TravelTripPublisher;
use common\entity\TravelTripScenic;
use common\entity\TravelTripService;
use common\entity\UserBase;
use common\entity\UserOrderInfo;
use frontend\components\Page;
use common\components\Code;
use common\components\TagUtil;
use frontend\services\CountryService;
use frontend\services\PublisherService;
use frontend\services\TravelTripCommentService;
use frontend\services\TripService;
use frontend\services\UserAttentionService;
use frontend\services\UserBaseService;
use frontend\services\UserOrderService;
use yii\base\Exception;
use Yii;
class AppTravelController extends AController
{
    private $travelSer;
    private $tripCommentSer;
    private $userOrderService;
    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        $this->travelSer=new TripService();
        $this->tripCommentSer=new TravelTripCommentService();
        $this->userOrderService = new UserOrderService();
    }

    //得到随游列表 根据筛选条件
    public function actionGetTravelList()
    {
        $this->loginValid();
        try{
            $page=new Page(Yii::$app->request);
            //$page->showAll=true;
            $title=Yii::$app->request->post('title');
            $countryId=Yii::$app->request->post('countryId');
            $cityId=Yii::$app->request->post('cityId');
            $cc=Yii::$app->request->post('cc');
            if(!empty($cc))
            {
                $countrySer = new CountryService();
                $arr=$countrySer->getCC($cc);
                $countryId=$arr[0];
                $cityId=$arr[1];
            }
            $peopleCount=Yii::$app->request->post('peopleCount');
            $startPrice=Yii::$app->request->post('startPrice');
            $endPrice=Yii::$app->request->post('endPrice');
            $tag=Yii::$app->request->post('tag');
            $data=$this->travelSer->getList($page,$title,$countryId,$cityId,$peopleCount,$startPrice,$endPrice,$tag);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$data->getList(),$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }

    //得到国家城市
    public function actionGetCountry()
    {
        $this->loginValid();
        try{
            $countryService = new CountryService();
            $countryList = $countryService->getCountryList();
            //$tagList = TagUtil::getInstance()->getTagList();
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$countryList));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }
    //得到国家城市
    public function actionGetCity()
    {
        $this->loginValid();
        try{
            $countryId=Yii::$app->request->post('countryId');
            $cityName=Yii::$app->request->post('cityName');
            $countryService = new CountryService();
            $cityList = $countryService->getCityList($countryId,$cityName);
            //$tagList = TagUtil::getInstance()->getTagList();
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$cityList));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
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
            $token = \Yii::$app->request->get("token");
            $appSign = \Yii::$app->redis->get(Code::APP_TOKEN . $token);
            if(empty($appSign))
            {
                return $this->renderPartial('error',['str1'=>'token已过期','str2'=>'返回','url'=>"#"]);
                exit;
            }
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
            $page =new Page();
            $userRecommend=$this->travelSer->findTravelTripRecommendByTripId($trId);
            $rst= $this->tripCommentSer->getTravelComment($trId,$page,$userSign);
            return $this->renderPartial('info',['info'=>$data,'token'=>$token,'comment'=>$rst,'userRecommend'=>$userRecommend]);
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
            $trId=Yii::$app->request->post('tripId');
            if(empty($trId)){ return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"未知随游id"));}
            $data=$this->travelSer->getTravelTripInfoById($trId,$userSign);
            $tripInfo=$data['info'];
            $publisherService=new PublisherService();
            $tripPublisherId=$tripInfo['createPublisherId'];
            $createPublisherId=$publisherService->findById($tripPublisherId);
            if(empty($createPublisherId)){
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'无法得到未知的随友'));
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
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"获取详情异常"));
        }
    }
    public function actionGetCommentList()
    {
        $this->loginValid(false);
        try{
            $cPage=\Yii::$app->request->post('cPage');
            $tripId=\Yii::$app->request->post('tripId');
            $numb=\Yii::$app->request->post('numb');
            if(empty($cPage)||$cPage<1)
            {
                $cPage=1;
            }
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

            return json_encode(Code::statusDataReturn(Code::SUCCESS,$rst));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"获取评论列表失败"));
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
            if(empty($tripId)){return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'无法评论未知随游'));}
            if(empty($content)){return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'无法发布空评论'));}
            $this->tripCommentSer->addComment($userSign,$content,$rId,$tripId,$rTitle,$rSign);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"发布评论失败"));
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
     * 获取用户未完成的订单
     * @throws Exception
     * @throws \Exception
     */
    public function actionGetUnFinishOrder()
    {
        try{
            $this->loginValid();
            $userSign=$this->userObj->userSign;
            $list=$this->userOrderService->getUnFinishOrderList($userSign);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$list));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 获取用户已完成的订单
     */
    public function actionGetFinishOrder()
    {
        try{
            $this->loginValid();
            $userSign=$this->userObj->userSign;
            $list=$this->userOrderService->getFinishOrderList($userSign);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$list));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 获取未确认的订单（根据随友Id）
     */
    public function actionGetUnConfirmOrder()
    {
        try{
            $this->loginValid();
            $publisherId=$this->userPublisherObj->userPublisherId;
            if(empty($publisherId)){
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的随友信息"));
            }
            $list=$this->userOrderService->getUnConfirmOrderByPublisher($publisherId);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$list));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 确认订单
     */
    public function actionPublisherConfirmOrder()
    {
        $this->loginValid();
        $orderId=trim(\Yii::$app->request->post("orderId", ""));
        $publisherId=$this->userPublisherObj->userPublisherId;
        if(empty($orderId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
        }
        if(empty($publisherId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的随友信息"));
        }
        try{
            $this->userOrderService->publisherConfirmOrder($orderId,$publisherId);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 忽略订单
     */
    public function actionPublisherIgnoreOrder()
    {
        $this->loginValid();
        $orderId=trim(\Yii::$app->request->post("orderId", ""));
        $publisherId=$this->userPublisherObj->userPublisherId;

        if(empty($orderId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
        }
        if(empty($publisherId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的随友信息"));
        }
        try{
            $this->userOrderService->publisherIgnoreOrder($orderId,$publisherId);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }

    /**
     * 尚未接单情况，直接取消订单
     * @return string
     */
    public function actionCancelOrder()
    {
        $orderId=trim(\Yii::$app->request->post("orderId", ""));
        if(empty($orderId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
        }

        try{
            $orderInfo=$this->userOrderService->findOrderByOrderId($orderId);
            if(empty($orderInfo)){
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
            }
            if($orderInfo->status!=UserOrderInfo::USER_ORDER_STATUS_PAY_WAIT){
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"该订单目前无法直接取消"));
            }
            if($orderInfo->userId!=$this->userObj->userSign){
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"您没有权限取消此订单"));
            }
            $this->userOrderService->changeOrderStatus($orderInfo->orderNumber,UserOrderInfo::USER_ORDER_STATUS_CANCELED);

            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"取消订单失败"));
        }

    }

    /**
     * 订单申请退款 未接单状态
     * @return string
     */
    public function actionRefundOrder()
    {
        $orderId=trim(\Yii::$app->request->post("orderId", ""));

        if(empty($orderId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
        }

        try{
            $orderInfo=$this->userOrderService->findOrderByOrderId($orderId);
            if(empty($orderInfo)){
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
            }
            if($orderInfo->userId!=$this->userObj->userSign){
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"您没有权限申请退款"));
            }
            if($orderInfo->status!=UserOrderInfo::USER_ORDER_STATUS_PAY_SUCCESS){
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"订单暂时无法申请退款"));
            }
            $this->userOrderService->changeOrderStatus($orderInfo->orderNumber,UserOrderInfo::USER_ORDER_STATUS_REFUND_WAIT);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"申请退款失败"));
        }
    }

    /**
     * 订单在已接单情况下申请退款
     * @return string
     */
    public function actionRefundOrderByMessage()
    {
        $orderId=trim(\Yii::$app->request->post("orderId", ""));
        $message=trim(\Yii::$app->request->post("message", ""));

        if(empty($orderId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
        }
        if(empty($message)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"请填写退款申请"));
        }

        try{
            $this->userOrderService->userRefundOrder($this->userObj->userSign,$orderId,$message);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"申请退款失败"));
        }
    }

    /**
     * 用户删除订单
     * @return string
     */
    public function actionDeleteOrder()
    {
        $orderId=trim(\Yii::$app->request->post("orderId", ""));

        if(empty($orderId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
        }
        try{
            $this->userOrderService->deleteOrderInfo($this->userObj->userSign,$orderId);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"删除订单失败"));
        }
    }

    /**
     * 获取随友订单
     * @return string
     */
    public function actionGetPublisherOrderList()
    {
        $this->loginValid();
        $publisherId=$this->userPublisherObj->userPublisherId;
        if(empty($publisherId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的随友信息"));
        }
        try{
            $list=$this->userOrderService->getPublisherOrderList($publisherId);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$list));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"获取随友订单失败"));
        }
    }

    /**
     * 随友取消订单
     * @return string
     */
    public function actionPublisherCancelOrder()
    {
        $this->loginValid();
        $orderId=trim(\Yii::$app->request->post("orderId", ""));
        $message=trim(\Yii::$app->request->post("message", ""));
        $publisherId=$this->userPublisherObj->userPublisherId;
        if(empty($orderId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
        }
        if(empty($message)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "请输入取消原因"));
        }
        if(empty($orderId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的随友信息"));
        }

        try{
            $this->userOrderService->publisherCancelOrder($publisherId,$orderId,$message);

            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"取消订单失败"));
        }
    }

    /**
     * 用户确认游玩
     * @return string
     */
    public function actionUserConfirmPlay()
    {
        $this->loginValid();
        $orderId=trim(\Yii::$app->request->post("orderId", ""));
        if(empty($orderId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
        }
        try{
            $orderInfo=$this->userOrderService->findOrderByOrderId($orderId);
            if($orderInfo==null){
                throw new Exception("Invalid Order Id");
            }
            $this->userOrderService->userConfirmPlay($orderId,$this->userObj->userSign);
            $userPublisher=$this->userOrderService->findPublisherByOrderId($orderId);
            if($userPublisher==null){
                throw new Exception("Invalid Publisher");
            }
            //给随友发送消息
            $sysMessageUtils=new SysMessageUtils();
            $sysMessageUtils->sendUserConfirmPlayMessage($this->userObj->userSign,$userPublisher->userId,$orderInfo->orderNumber);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"确认游玩失败"));
        }
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
        /*$countryService = new CountryService();
        $countryList = $countryService->getCountryList();
        $tagList = TagUtil::getInstance()->getTagList();*/
        $travelInfo=$this->travelSer->getTravelTripInfoById($tripId);
        //验证当前用户是不是随游的所属者
        $userPublisherId = $this->userPublisherObj->userPublisherId;//当前用户
        if($travelInfo['info']['createPublisherId']!=$userPublisherId){
            return json_encode(Code::statusDataReturn(Code::FAIL,"您没有权限修改此随游"));
        }
        return json_encode(Code::statusDataReturn(Code::SUCCESS,$travelInfo));
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
        if (empty($tagList)) {
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
            $travelTrip=$this->tripService->updateTravelTrip($travelTrip, $tripScenicList, $tripPicList, $tripStepPriceList, $tripServiceList,$tripDetailList,$tripHighlightList);
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
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"找不到该随游"));
        }
        $apply=$this->travelSer->findTravelTripApplyByTripIdAndUser($tripId,$publisherId);
        if(isset($apply)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"您已经有申请正在审核，请耐心等待回复"));
        }

        $travelTripApply=new TravelTripApply();
        $travelTripApply->tripId=$tripId;
        $travelTripApply->publisherId=$publisherId;
        $travelTripApply->info=$info;
        $travelTripApply->status=TravelTripApply::TRAVEL_TRIP_APPLY_STATUS_WAIT;

        $this->travelSer->addTravelTripApply($this->userObj->userSign,$travelTripApply);
        return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"您的申请已经提交，请耐心等待审核"));
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
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"TripId Is Not Allow Empty"));
        }
        if(empty($tripPublisherId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"TripPublisherId Is Not Allow Empty"));
        }
        $tripInfo=$this->travelSer->getTravelTripById($tripId);
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

            $this->travelSer->deleteTravelTriPublisher($this->userObj->userSing,$travelTripPublisher);
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
        $this->loginValid();
        /*if(!$this->userObj->isPublisher){
            return json_encode(Code::statusDataReturn(Code::FAIL,"User Is Not Publisher"));
        }*/
        $userSign=trim(\Yii::$app->request->post("userSign", ""));
        if(empty($userSign)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'未知的用户'));
        }
        //$userSign='a4c1406ff4cc382389f19bf6ec3e55c1';
        try{
            $publisherService=new PublisherService();
            $createPublisherInfo = $publisherService->findUserPublisherByUserSign($userSign);
            if(empty($createPublisherInfo)){
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'未知的随友'));
            }
            $userPublisherId=$createPublisherInfo->userPublisherId;
            $myList=$this->travelSer->getMyTripList($userPublisherId);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$myList));
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
        $this->loginValid();

        $userSign=trim(\Yii::$app->request->post("userSign", ""));
        if(empty($userSign)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'未知的用户'));
        }
        try{
            $publisherService=new PublisherService();
            $createPublisherInfo = $publisherService->findUserPublisherByUserSign($userSign);
            if(empty($createPublisherInfo)){
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'未知的随友'));
            }
            $userPublisherId=$createPublisherInfo->userPublisherId;
            $list=$this->travelSer->getMyJoinTripList($userPublisherId);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$list));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 申请列表
     * @return string|\yii\web\Response
     */
    public function actionGetApplyList()
    {
        $tripId=trim(\Yii::$app->request->post("trip", ""));
        $tripId=105;
        if(empty($tripId)){
            return json_encode(Code::statusDataReturn(Code::FAIL,'找不到该随游'));
        }

        try{
            $applyList=$this->travelSer->getPublisherApplyList($tripId);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$applyList));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,'未知系统异常'));
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
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"ApplyId Is Not Allow Empty"));
        }
        if(empty($publisherId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"PublisherId Is Not Allow Empty"));
        }

        try{
            $this->travelSer->agreePublisherApply($this->userObj->userSing,$applyId,$publisherId,$this->userPublisherObj->userPublisherId);
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
        $this->loginValid();
        $applyId=trim(\Yii::$app->request->post("applyId", ""));

        if(empty($applyId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"ApplyId Is Not Allow Empty"));
        }

        try{
            $this->travelSer->opposePublisherApply($this->userObj->userSing,$applyId,$this->userPublisherObj->userPublisherId);
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
        $this->loginValid();
        $tripId=trim(\Yii::$app->request->post("tripId", ""));
        if(empty($tripId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"TripId Is Not Allow Empty"));
        }

        try{
            $info=$this->travelSer->getTravelTripById($tripId);
            if($info->createPublisherId!=$this->userPublisherObj->userPublisherId){
                throw new Exception("您只能删除自己的随游哦~");
            }
            $this->travelSer->changeTripStatus($tripId,TravelTrip::TRAVEL_TRIP_STATUS_DELETE);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }

    public function actionGetPublisherInfo()
    {
        $this->loginValid();
        $userSign=trim(\Yii::$app->request->post("userSign", ""));
        //$userSign='a4c1406ff4cc382389f19bf6ec3e55c1';
        if(empty($userSign)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'未知的用户'));
        }

        try{
            $userService=new UserBaseService();
            $publisherService=new PublisherService();
            $createUserInfo=$userService->findBaseInfoBySignArray($userSign);
            if(empty($createUserInfo))
            {
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'未知的用户'));
            }
            if(empty($createUserInfo['isPublisher']))
            {
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'未知的随友'));
            }
            $createPublisherInfo = $publisherService->findUserPublisherByUserSign($userSign);
            $data=array();
            $data['user']=$createUserInfo;
            $data['publisher']=$createPublisherInfo;
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }


    /**
     * 添加用户订单
     * @return void|\yii\web\Response
     * @throws Exception
     * @throws \Exception
     */
    public function actionAddOrder()
    {
        $this->loginValid();
        $tripId=trim(\Yii::$app->request->post("tripId", ""));
        $peopleCount=trim(\Yii::$app->request->post("peopleCount", ""));
        $beginDate=trim(\Yii::$app->request->post("beginDate", ""));
        $startTime=trim(\Yii::$app->request->post("startTime", ""));
        $serviceIds=trim(\Yii::$app->request->post("serviceIds", ""));
        $userSign = $this->userObj->userSign;
        if(empty($userSign)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'无效用户'));
        }
        if(empty($tripId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"随游编号不正确"));
        }
        if(empty($peopleCount)||$peopleCount==0){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"出行人数不正确"));
        }
        if(empty($beginDate)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"行程日期不正确"));
        }
        if(empty($startTime)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"起始时间不正确"));
        }

        if(strtotime($beginDate)<time()){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的出行日期"));
        }

        if(strtotime($beginDate)==strtotime(date('y-M-d'),time())){
            //TODO 判断如果是当天，需要判断当前时间是否正确  并且判断服务时间
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"时间不正确"));
        }
        //判断开始时间是否小于当前时间


        $tripService=new TripService();
        try{
            $travelInfo=$tripService->getTravelTripInfoById($tripId);
            $tripInfo=$travelInfo['info'];
            $tripPriceList=$travelInfo['priceList'];
            $tripPublisherList=$travelInfo['publisherList'];
            $tripServiceList=$travelInfo['serviceList'];

            $orderTripInfoJson=json_encode($travelInfo);//订单详情
            $selectServiceList=[];//附加服务list
            $servicePrice=0;//附加服务价格
            $basePrice=0;

            if($peopleCount>$tripInfo['maxUserCount']){
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"PeopleCount Over Max User Count"));
            }
            if($tripInfo['basePriceType']==TravelTrip::TRAVEL_TRIP_BASE_PRICE_TYPE_COUNT){
                $basePrice=$tripInfo['basePrice'];
            }else{
                //计算阶梯价格 和 基础价格
                if(!empty($tripPriceList)&&count($tripPriceList)>0){
                    foreach($tripPriceList as $stepPrice)
                    {
                        if($peopleCount>=$stepPrice['minCount']&&$peopleCount<=$stepPrice['maxCount']){
                            $basePrice=$stepPrice['price']*$peopleCount;
                            break;
                        }
                    }
                }else{
                    $basePrice=$tripInfo['basePrice']*$peopleCount;
                }
            }


            if(!empty($serviceIds)){
                $serviceIdArr=explode(",",$serviceIds);
                foreach($serviceIdArr as $serviceId)
                {
                    foreach($tripServiceList as $tripService)
                    {
                        if($serviceId==$tripService['serviceId'])
                        {
                            $selectServiceList[]=$tripService;
                            if($tripService['type']==TravelTripService::TRAVEL_TRIP_SERVICE_TYPE_COUNT){
                                $servicePrice+=($tripService['money']);
                            }else if($tripService['type']==TravelTripService::TRAVEL_TRIP_SERVICE_TYPE_PEOPLE){
                                $servicePrice+=($tripService['money']*$peopleCount);
                            }
                            break;
                        }
                    }
                }
            }
            $serviceInfo=json_encode($selectServiceList);//附加服务详情

            $userOrderInfo=new UserOrderInfo();
            $userOrderInfo->tripId=$tripInfo['tripId'];
            $userOrderInfo->userId=$userSign;
            $userOrderInfo->beginDate=$beginDate;
            $userOrderInfo->startTime=$startTime;
            $userOrderInfo->personCount=$peopleCount;
            $userOrderInfo->serviceInfo=$serviceInfo;
            $userOrderInfo->basePrice=$tripInfo['basePrice'];
            $userOrderInfo->servicePrice=$servicePrice;
            $userOrderInfo->tripJsonInfo=$orderTripInfoJson;
            $userOrderInfo->totalPrice=$basePrice+$servicePrice;
            $userOrderInfo->status=UserOrderInfo::USER_ORDER_STATUS_PAY_WAIT;//默认订单状态，待支付
            $userOrderInfo->orderNumber=Code::createOrderNumber();
            $this->userOrderService->addUserOrder($userOrderInfo);
            //给随友发送消息
            $sysMessageUtils=new SysMessageUtils();
            $sysMessageUtils->sendNewOrderMessage($userSign,$tripPublisherList,$userOrderInfo->orderNumber);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$userOrderInfo->orderNumber));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"系统未知异常"));
        }
    }
//随友订单详情
    public function actionTripOrderInfo()
    {
        try{

            $this->loginValid();
            $userSign=$this->userObj->userSign;
            $userBaseService = new UserBaseService();
            $userPublisherObj=$userBaseService->findUserPublisherByUserSign($userSign);
            if(empty($userPublisherObj)){
                return json_encode(Code::statusDataReturn(Code::FAIL,'无效的随友信息'));
            }
            $publisherId=$userPublisherObj->userPublisherId;
            $orderNumber=\Yii::$app->request->post('orderNumber');
            if(empty($orderNumber)){
                return json_encode(Code::statusDataReturn(Code::FAIL,'未知的订单'));
            }
            $info = $this->userOrderService->findOrderByOrderNumber($orderNumber);
            if(empty($info))
            {
                return json_encode(Code::statusDataReturn(Code::FAIL,'未知的订单'));
            }
            $tripId = $info->tripId;
            $lstPublisher =$this->travelSer->getTravelTripPublisherList($tripId);
            $bo=true;
            foreach($lstPublisher as $publisherInfo)
            {
                if($publisherInfo['publisherId']==$publisherId)
                {
                    $bo=false;
                }
            }
            $orderList = $this->userOrderService->getPublisherOrderList($publisherId);
            foreach($orderList as $orderInfo)
            {
                if($orderInfo['orderNumber']==$orderNumber)
                {
                    $bo=false;
                }
            }
            if($bo)
            {
                return json_encode(Code::statusDataReturn(Code::FAIL,'无关订单详情'));
            }
            $userSer =new UserBaseService();
            $userInfo = $userSer->findUserByUserSign($info->userId);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,array('info'=>$info,'userInfo'=>$userInfo)));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,'系统异常'));
        }
    }

    //用户订单详情
    public function actionUserOrderInfo()
    {
        try{
            $this->loginValid();
            $userSign=$this->userObj->userSign;
            $orderNumber=\Yii::$app->request->post('orderNumber');
            if(empty($orderNumber)){
                return json_encode(Code::statusDataReturn(Code::FAIL,'未知的订单'));
            }
            $info = $this->userOrderService->findOrderByOrderNumber($orderNumber);
            if(empty($info)){
                return json_encode(Code::statusDataReturn(Code::FAIL,'未知的订单'));
            }
            $orderId=$info->orderId;
            $publisherInfo =$this->userOrderService->findPublisherByOrderId($orderId);
            if($userSign!=$info->userId)
            {
                return json_encode(Code::statusDataReturn(Code::FAIL,'订单用户不匹配'));
            }
            $publisherBase=null;
            if(!empty($publisherInfo))
            {
                $sign=$publisherInfo->userId;
                $userBaseService = new UserBaseService();
                $publisherBase=$userBaseService->findUserByUserSign($sign);
            }
            return json_encode(Code::statusDataReturn(Code::SUCCESS,array('info'=>$info,'publisherBase'=>$publisherBase)));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,'系统异常'));
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