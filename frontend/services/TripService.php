<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/29
 * Time : 下午2:55
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\services;


use backend\components\Page;
use common\components\Code;
use common\components\SysMessageUtils;
use common\entity\TravelTrip;
use common\entity\TravelTripApply;
use common\entity\TravelTripDetail;
use common\entity\TravelTripPublisher;
use common\entity\TravelTripTraffic;
use common\entity\UserAttention;
use common\models\BaseDb;
use common\models\TravelTripDb;
use common\models\UserAttentionDb;
use common\models\UserPublisherDb;
use yii\base\Exception;

class TripService extends BaseDb{

    private $tripTravelDb;

    public function __construct()
    {

    }

    /**
     * 获取随游列表
     * @param $page
     * @param $title
     * @param $countryId
     * @param $cityId
     * @param $peopleCount
     * @param $startPrice
     * @param $endPrice
     * @param $tag
     * @param null $isHot
     * @param null $typeArray
     * @param null $activity
     * @return Page|null
     * @throws Exception
     * @throws \Exception
     */
    public function getList($page,$title,$countryId,$cityId,$peopleCount,$startPrice,$endPrice,$tag,$isHot=null,$typeArray=null,$activity=null)
    {
        try {
            if(!empty($typeArray)){
                foreach($typeArray as $t){
                    if(!is_numeric($t)){
                        throw new Exception("Invalid TripType");
                    }
                }
            }

            $conn = $this->getConnection();
            $this->tripTravelDb = new TravelTripDb($conn);
            $tagStr='';
            if(!empty($tag)&&$tag!='全部'){

                $intersection=array();
                $tagList=explode(',',$tag);
                foreach($tagList as $val){
                    $valArr=json_decode(\Yii::$app->redis->get(Code::TRAVEL_TRIP_TAG_PREFIX.md5($val)),true);
                    if(count($valArr)>0){
                    $intersection=array_merge($intersection,$valArr);
                    }
                }
                if(!empty($intersection)){
                    $arr = array_count_values($intersection);
                    arsort($arr);
                    $result = array_keys($arr);
                    $tagStr=implode(',',$result);
                }else{
                    $tagStr='-1';
                }
            }
            if(empty($countryId)&&empty($cityId)){
                $countryService=new CountryService();
                $cc=$countryService->getCC($title);
                $countryId=$cc[0];
                $cityId=$cc[1];
                if(is_array($countryId)){
                    $countryId=implode(",",$countryId);
                }
                if(is_array($cityId)){
                    $cityId=implode(",",$cityId);
                }
            }
            if($activity==1){
                $tagStr='154,170,142,148,192,113,133,178';
            }else if($activity==2){
                $tagStr='54,41,87,58,130,75,77,114,131,145';
            }
            return $this->tripTravelDb->getList($page,$title,$countryId,$cityId,$peopleCount,$startPrice,$endPrice,$tagStr,$isHot,$typeArray,TravelTrip::TRAVEL_TRIP_STATUS_NORMAL);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }

    /**
     * 全文检索获取列表
     *
     * @param $page
     * @param $tripIds
     * @return \backend\components\Page|null
     * @throws Exception
     * @throws \Exception
     */
    public function getListBySearch(Page $page,$tripIds)
    {
        try {
            $conn = $this->getConnection();
            $this->tripTravelDb = new TravelTripDb($conn);
            return $this->tripTravelDb->getListBySearch($page,$tripIds);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }

    /**
     * 添加随游（事务）
     * @param TravelTrip $travelTrip
     * @param $scenicList
     * @param $picList
     * @param $priceList
     * @param TravelTripPublisher $travelTripPublisher
     * @param $serviceList
     * @param $detailList
     * @param $highlightList
     * @param $specialList
     * @return array|bool
     * @throws Exception
     * @throws \Exception
     */
    public function addTravelTrip(TravelTrip $travelTrip,$scenicList,$picList,$priceList,TravelTripPublisher $travelTripPublisher,$serviceList,$detailList,$highlightList,$specialList)
    {
        $conn = $this->getConnection();
        $tran=$conn->beginTransaction();
        try {

            $this->tripTravelDb = new TravelTripDb($conn);
            $this->tripTravelDb->addTravelTrip($travelTrip);
            $tripId=$conn->getLastInsertID();
            $travelTripPublisher->tripId=$tripId;
            $this->tripTravelDb->addTravelTripPublisher($travelTripPublisher);
            if($scenicList!=null){
                foreach($scenicList as $scenic)
                {
                    $scenic->tripId=$tripId;
                    $this->tripTravelDb->addTravelTripScenic($scenic);
                }
            }
            if($picList!=null){
                foreach($picList as $pic)
                {
                    $pic->tripId=$tripId;
                    $this->tripTravelDb->addTravelTripPicture($pic);
                }
            }
            if($priceList!=null){
                foreach($priceList as $price)
                {
                    $price->tripId=$tripId;
                    $this->tripTravelDb->addTravelTripPrice($price);
                }
            }
            if($serviceList!=null){
                foreach($serviceList as $service)
                {
                    $service->tripId=$tripId;
                    $this->tripTravelDb->addTravelTripService($service);
                }
            }
            if($detailList!=null){
                foreach($detailList as $detail)
                {
                    $detail->tripId=$tripId;
                    $this->tripTravelDb->addTravelTripDetail($detail);
                }
            }
            if($highlightList!=null){
                foreach($highlightList as $highlight)
                {
                    $highlight->tripId=$tripId;
                    $this->tripTravelDb->addTravelTripHighLight($highlight);
                }
            }

            if($specialList!=null){
                foreach($specialList as $special)
                {
                    $special->tripId=$tripId;
                    $this->saveObject($special);
                }
            }


            $this->commit($tran);
            return $this->tripTravelDb->findTravelTripById($tripId);
        } catch (Exception $e) {
            $this->rollback($tran);
            throw $e;
        } finally {
            $this->closeLink();
        }
    }


    /**
     * 添加交通服务随游
     * @param TravelTrip $travelTrip
     * @param TravelTripTraffic $travelTripTraffic
     * @param $picList
     * @param TravelTripPublisher $travelTripPublisher
     * @param $detailList
     * @return array|bool
     * @throws Exception
     * @throws \Exception
     */
    public function addTravelTripTraffic(TravelTrip $travelTrip,TravelTripTraffic $travelTripTraffic,$picList,TravelTripPublisher $travelTripPublisher,$detailList)
    {
        $conn = $this->getConnection();
        $tran=$conn->beginTransaction();
        try {

            $this->tripTravelDb = new TravelTripDb($conn);
            $this->tripTravelDb->addTravelTrip($travelTrip);
            $tripId=$conn->getLastInsertID();

            $travelTripTraffic->tripId=$tripId;
            $travelTripPublisher->tripId=$tripId;

            $this->saveObject($travelTripTraffic);
            $this->tripTravelDb->addTravelTripPublisher($travelTripPublisher);
            if($picList!=null){
                foreach($picList as $pic)
                {
                    $pic->tripId=$tripId;
                    $this->tripTravelDb->addTravelTripPicture($pic);
                }
            }
            if($detailList!=null){
                foreach($detailList as $detail)
                {
                    $detail->tripId=$tripId;
                    $this->tripTravelDb->addTravelTripDetail($detail);
                }
            }

            $this->commit($tran);
            return $this->tripTravelDb->findTravelTripById($tripId);
        } catch (Exception $e) {
            $this->rollback($tran);
            throw $e;
        } finally {
            $this->closeLink();
        }
    }


    /**
     * 更新交通服务随游
     * @param TravelTrip $travelTrip
     * @param TravelTripTraffic $travelTripTraffic
     * @param $picList
     * @param $detailList
     * @return array|bool
     * @throws Exception
     * @throws \Exception
     */
    public function updateTravelTripTraffic(TravelTrip $travelTrip,TravelTripTraffic $travelTripTraffic,$picList,$detailList)
    {
        $conn = $this->getConnection();
        $tran=$conn->beginTransaction();
        try {

            $this->tripTravelDb = new TravelTripDb($conn);
            $this->tripTravelDb->updateTravelTrip($travelTrip);
            $this->updateObject($travelTripTraffic);
            //删除 ，添加
            $this->tripTravelDb->deleteTravelTripPicBytripId($travelTrip->tripId);
            if($picList!=null){
                foreach($picList as $pic)
                {
                    $pic->tripId=$travelTrip->tripId;
                    $this->tripTravelDb->addTravelTripPicture($pic);
                }
            }
            $this->tripTravelDb->deleteTravelTripDetailByTripId($travelTrip->tripId);
            if($detailList!=null){
                foreach($detailList as $detail)
                {
                    $detail->tripId=$travelTrip->tripId;
                    $this->tripTravelDb->addTravelTripDetail($detail);
                }
            }
            $this->tripTravelDb->deleteTravelTripHighLightByTripId($travelTrip->tripId);

            $this->commit($tran);
            return $this->tripTravelDb->findTravelTripById($travelTrip->tripId);
        } catch (Exception $e) {
            $this->rollback($tran);
            throw $e;
        } finally {
            $this->closeLink();
        }
    }

    /**
     * 更新随游
     * @param TravelTrip $travelTrip
     * @param $scenicList
     * @param $picList
     * @param $priceList
     * @param $serviceList
     * @param $detailList
     * @param $highlightList
     * @param $specialList
     * @return array|bool
     * @throws Exception
     * @throws \Exception
     */
    public function updateTravelTrip(TravelTrip $travelTrip,$scenicList,$picList,$priceList,$serviceList,$detailList,$highlightList,$specialList)
    {
        $conn = $this->getConnection();
        $tran=$conn->beginTransaction();
        try {

            $this->tripTravelDb = new TravelTripDb($conn);
            $this->tripTravelDb->updateTravelTrip($travelTrip);
            //删除 ，添加
            $this->tripTravelDb->deleteTravelTripScenicBytripId($travelTrip->tripId);
            if($scenicList!=null){
                foreach($scenicList as $scenic)
                {
                    $scenic->tripId=$travelTrip->tripId;
                    $this->tripTravelDb->addTravelTripScenic($scenic);
                }
            }
            $this->tripTravelDb->deleteTravelTripPicBytripId($travelTrip->tripId);
            if($picList!=null){
                foreach($picList as $pic)
                {
                    $pic->tripId=$travelTrip->tripId;
                    $this->tripTravelDb->addTravelTripPicture($pic);
                }
            }
            $this->tripTravelDb->deleteTravelTripPriceBytripId($travelTrip->tripId);
            if($priceList!=null){
                foreach($priceList as $price)
                {
                    $price->tripId=$travelTrip->tripId;
                    $this->tripTravelDb->addTravelTripPrice($price);
                }
            }
            $this->tripTravelDb->deleteTravelTripServiceBytripId($travelTrip->tripId);
            if($serviceList!=null){
                foreach($serviceList as $service)
                {

                    $service->tripId=$travelTrip->tripId;
                    $this->tripTravelDb->addTravelTripService($service);
                }
            }
            $this->tripTravelDb->deleteTravelTripDetailByTripId($travelTrip->tripId);
            if($detailList!=null){
                foreach($detailList as $detail)
                {
                    $detail->tripId=$travelTrip->tripId;
                    $this->tripTravelDb->addTravelTripDetail($detail);
                }
            }
            $this->tripTravelDb->deleteTravelTripHighLightByTripId($travelTrip->tripId);
            if($highlightList!=null){
                foreach($highlightList as $highlight)
                {
                    $highlight->tripId=$travelTrip->tripId;
                    $this->tripTravelDb->addTravelTripHighLight($highlight);
                }
            }

            $this->tripTravelDb->deleteTravelTripSpecialByTripId($travelTrip->tripId);
            if($specialList!=null){
                foreach($specialList as $special)
                {
                    $special->tripId=$travelTrip->tripId;
                    $this->saveObject($special);
                }
            }


            $this->commit($tran);
            return $this->tripTravelDb->findTravelTripById($travelTrip->tripId);
        } catch (Exception $e) {
            $this->rollback($tran);
            throw $e;
        } finally {
            $this->closeLink();
        }
    }



    /**
     * 获取随游详情
     * @param $tripId
     * @param
     * $userSign
     * @return array
     * @throws Exception
     * @throws \Exception
     */
    public function getTravelTripInfoById($tripId,$userSign=null)
    {
        if(empty($tripId)){
            throw new Exception ("TripId Is Not Empty");
        }
        $tripInfo=array();
        try{
            $conn = $this->getConnection();
            $this->tripTravelDb=new TravelTripDb($conn);
            $attention =new UserAttentionDb($conn);
            $userAttention = new UserAttention();
            $userAttention->relativeId=$tripId;
            $userAttention->relativeType=UserAttention::TYPE_COLLECT_FOR_TRAVEL;
            $userAttention->userSign = $userSign;
            $attentionEntity = new UserAttention();
            $attentionEntity->relativeId=$tripId;
            $attentionEntity->relativeType=UserAttention::TYPE_PRAISE_FOR_TRAVEL;
            $attentionEntity->userSign = $userSign;
            $praise=$attention->getAttentionResult($attentionEntity);
            $collect=$attention->getAttentionResult($userAttention);
            $tripInfo['praise']=$praise==false?array():array($praise);
            $tripInfo['attention']=$collect==false?array():array($collect);
            $tripInfo['info']=$this->tripTravelDb->findTravelTripById($tripId);
            if($tripInfo['info']['status']==TravelTrip::TRAVEL_TRIP_STATUS_DELETE){
                throw new Exception("随游不存在");
            }
            $tripInfo['picList']=$this->tripTravelDb->getTravelTripPicList($tripId);
            $tripInfo['publisherList']=$this->tripTravelDb->getTravelTripPublisherList($tripId);

            if($tripInfo['info']['type']==TravelTrip::TRAVEL_TRIP_TYPE_TRAFFIC){
                $traffic=$this->findObjectByType(TravelTripTraffic::class,"tripId",$tripId);
                $tripInfo['trafficInfo']=$traffic[0];
            }else{
                $tripInfo['priceList']=$this->tripTravelDb->getTravelTripPriceList($tripId);
                $tripInfo['scenicList']=$this->tripTravelDb->getTravelTripScenicList($tripId);
                $tripInfo['serviceList']=$this->tripTravelDb->getTravelTripServiceList($tripId);
                $tripInfo['highlightList']=$this->tripTravelDb->getTravelTripHighlightList($tripId);
                $tripInfo['specialList']=$this->tripTravelDb->getTravelTripSpecialList($tripId);
            }

            //所有明细
            $detailList=$this->tripTravelDb->getTravelTripDetailList($tripId);
            $includeDetailList=[];
            $unIncludeDetailList=[];
            if(!empty($detailList)&&count($detailList)>0){
                foreach($detailList as $detail){
                    if($detail['type']==TravelTripDetail::TRAVEL_TRIP_DETAIL_TYPE_INCLUDE){
                        $includeDetailList[]=$detail;
                    }else{
                        $unIncludeDetailList[]=$detail;
                    }
                }
            }
            $tripInfo['includeDetailList']=$includeDetailList;
            $tripInfo['unIncludeDetailList']=$unIncludeDetailList;

            foreach($tripInfo['publisherList'] as $publisherInfo){
                if($publisherInfo['publisherId']==$tripInfo['info']['createPublisherId']){
                    $tripInfo['createPublisherInfo']=$publisherInfo;
                }
            }

        }catch (Exception $e){
            throw $e;
        }finally {
            $this->closeLink();
        }
        return $tripInfo;
    }


    /**
     * 根据随游获取随友详情
     * @param $tripId
     * @return array|null
     * @throws Exception
     * @throws \Exception
     */
    public function getTravelTripPublisherList($tripId)
    {
        $travelTripList=null;
        if(empty($tripId))
        {
            throw new Exception("TripId Is Not Allow Empty");
        }
        try{
            $conn = $this->getConnection();
            $this->tripTravelDb=new TravelTripDb($conn);
            $travelTripList=$this->tripTravelDb->getTravelTripPublisherList($tripId);

        }catch (Exception $e){
            throw $e;
        }finally {
            $this->closeLink();
        }
        return $travelTripList;
    }

    /**
     * 获取随游详情
     * @param $tripId
     * @return mixed
     * @throws Exception
     * @throws \Exception
     */
    public function getTravelTripById($tripId)
    {
        if(empty($tripId)){
            throw new Exception ("TripId Is No Allow Empty");
        }
        $tripInfo=null;
        try{
            $conn = $this->getConnection();
            $this->tripTravelDb=new TravelTripDb($conn);
            $tripInfo=$this->tripTravelDb->findTravelTripById($tripId);
            if($tripInfo['status']==TravelTrip::TRAVEL_TRIP_STATUS_DELETE){
                throw new Exception("随游不存在");
            }
            $tripInfo=$this->arrayCastObject($tripInfo,TravelTrip::class);

        }catch (Exception $e){
            throw $e;
        }finally {
            $this->closeLink();
        }
        return $tripInfo;
    }


    /**
     * 获取随游交通服务详情
     * @param $tripId
     * @return mixed|null
     * @throws Exception
     * @throws \Exception
     */
    public function  getTravelTripTrafficByTripId($tripId)
    {
        if(empty($tripId)){
            throw new Exception ("TripId Is No Allow Empty");
        }
        $trafficInfo=null;
        try{
            $rst=$this->findObjectByType(TravelTripTraffic::class,"tripId",$tripId);
            if(empty($rst)){
                throw new Exception("随游不存在");
            }
            $trafficInfo=$this->arrayCastObject($rst[0],TravelTripTraffic::class);
        }catch (Exception $e){
            throw $e;
        }finally {
            $this->closeLink();
        }
        return $trafficInfo;
    }

    /**
     * 获取景区列表
     * @param $tripId
     * @return array|null
     * @throws Exception
     * @throws \Exception
     */
    public function getTravelTripScenicList($tripId)
    {
        $scenicList=null;
        if(empty($tripId))
        {
            throw new Exception("TripId Is Not Allow Empty");
        }
        try{
            $conn = $this->getConnection();
            $this->tripTravelDb=new TravelTripDb($conn);
            $scenicList=$this->tripTravelDb->getTravelTripScenicList($tripId);

        }catch (Exception $e){
            throw $e;
        }finally {
            $this->closeLink();
        }
        return $scenicList;
    }

    /**
     * 改变随游状态
     * @param $tripId
     * @param $status
     * @throws Exception
     * @throws \Exception
     */
    public function changeTripStatus($tripId,$status)
    {
        if(empty($tripId))
        {
            throw new Exception("TripId Is Not Allow Empty");
        }
        $conn = $this->getConnection();
        $tran = $conn->beginTransaction();
        try{
            $this->tripTravelDb=new TravelTripDb($conn);
            $this->tripTravelDb->changeTravelStatus($tripId,$status);
            //如果是发布随游，那么添加随游发布者的线路数
            if($status==TravelTrip::TRAVEL_TRIP_STATUS_NORMAL){
                $publisherDb=new UserPublisherDb($conn);
                $tripInfo=$this->tripTravelDb->findTravelTripById($tripId);
                $publisherDb->addPublisherTripCount($tripInfo['createPublisherId']);
            }
            $this->commit($tran);
        }catch (Exception $e){
            $this->rollback($tran);
            throw $e;
        }finally {
            $this->closeLink();
        }
    }

    /**
     * 添加随游申请
     * @param $userSign
     * @param TravelTripApply $travelTripApply
     * @throws Exception
     * @throws \Exception
     */
    public function addTravelTripApply($userSign,TravelTripApply $travelTripApply)
    {
        try{
            $conn = $this->getConnection();
            $this->tripTravelDb=new TravelTripDb($conn);
            $this->tripTravelDb->addTravelTripApply($travelTripApply);
            $tripInfo=$this->tripTravelDb->findTravelTripById($travelTripApply->tripId);
            $publisherDb=new UserPublisherDb($conn);
            $publisherUserInfo=$publisherDb->findUserBaseByPublisherId($tripInfo['createPublisherId']);
            //给创建人发送申请消息
            $sysMessageUtil=new SysMessageUtils();
            $sysMessageUtil->sendUserJoinTripMessage($userSign,$publisherUserInfo['userSign'],$tripInfo['tripId'],$tripInfo['title']);
        }catch (Exception $e){
            throw $e;
        }finally {
            $this->closeLink();
        }
    }

    /**
     * 更新随游申请状态
     * @param $applyId
     * @param $status
     * @throws Exception
     * @throws \Exception
     */
    public function updateTravelTripApplyStatus($applyId,$status)
    {
        if(empty($tripId))
        {
            throw new Exception("TripId Is Not Allow Empty");
        }
        try{
            $conn = $this->getConnection();
            $this->tripTravelDb=new TravelTripDb($conn);
            $this->tripTravelDb->changeTravelTripApplyStatus($applyId,$status);
        }catch (Exception $e){
            throw $e;
        }finally {
            $this->closeLink();
        }
    }

    /**
     * 获取随游申请列表
     * @param $tripId
     * @param $status
     * @throws Exception
     * @throws \Exception
     */
    public function getTelTripApplyList($tripId,$status)
    {
        if(empty($tripId))
        {
            throw new Exception("TripId Is Not Allow Empty");
        }
        try{
            $conn = $this->getConnection();
            $this->tripTravelDb=new TravelTripDb($conn);
            $this->tripTravelDb->getTelTripApplyList($tripId,$status);
        }catch (Exception $e){
            throw $e;
        }finally {
            $this->closeLink();
        }
    }

    /**
     * 删除随友
     * @param $userSign
     * @param TravelTripPublisher $travelTripPublisher
     * @throws Exception
     * @throws \Exception
     */
    public function deleteTravelTriPublisher($userSign,TravelTripPublisher $travelTripPublisher)
    {
        if($travelTripPublisher==null)
        {
            throw new Exception("TravelTripPublisher Is Not Allow Null");
        }
        try{
            $conn = $this->getConnection();
            $this->tripTravelDb=new TravelTripDb($conn);
            $this->tripTravelDb->deleteTravelTripPublisher($travelTripPublisher);
            $publisherDb=new UserPublisherDb($conn);
            $publisherUserInfo=$publisherDb->findUserBaseByPublisherId($travelTripPublisher->publisherId);
            $tripInfo=$this->tripTravelDb->findTravelTripById($travelTripPublisher->tripId);
            //给被移除这发送消息提醒
            $sysMessageUtil=new SysMessageUtils();
            $sysMessageUtil->sendRemoveUserForTripMessage($userSign,$publisherUserInfo['userSign'],$travelTripPublisher->tripId,$tripInfo['title']);
        }catch (Exception $e){
            throw $e;
        }finally {
            $this->closeLink();
        }
    }

    /**
     * 获取我的随游列表
     * @param $createPublisherId
     * @return array
     * @throws Exception
     * @throws \Exception
     */
    public function getMyTripList($createPublisherId)
    {
        if(empty($createPublisherId))
        {
            throw new Exception("CreatePublisherId Is Not Allow Null");
        }
        try{
            $conn = $this->getConnection();
            $this->tripTravelDb=new TravelTripDb($conn);
            return $this->tripTravelDb->getMyTripList($createPublisherId);
        }catch (Exception $e){
            throw $e;
        }finally {
            $this->closeLink();
        }
    }

    /**
     * 获取某个随友加入的随游
     * @param $publisherId
     * @return array
     * @throws Exception
     * @throws \Exception
     */
    public function getMyJoinTripList($publisherId)
    {
        if(empty($publisherId))
        {
            throw new Exception("PublisherId Is Not Allow Null");
        }
        try{
            $conn = $this->getConnection();
            $this->tripTravelDb=new TravelTripDb($conn);
            return $this->tripTravelDb->getMyJoinTripList($publisherId);
        }catch (Exception $e){
            throw $e;
        }finally {
            $this->closeLink();
        }
    }

    /**
     * 获取随友申请列表
     * @param $tripId
     * @return array|null
     * @throws Exception
     * @throws \Exception
     */
    public function getPublisherApplyList($tripId)
    {
        if(empty($tripId))
        {
            throw new Exception("TripId Is Not Allow Empty");
        }
        $list=null;
        try{
            $conn = $this->getConnection();
            $this->tripTravelDb=new TravelTripDb($conn);
            $list=$this->tripTravelDb->getPublisherApplyList($tripId);
        }catch (Exception $e){
            throw $e;
        }finally {
            $this->closeLink();
        }
        return $list;
    }

    /**
     * 通过随友申请加入随游
     * @param $userSign
     * @param $applyId
     * @param $publisherId
     * @param $currentPublisherId
     * @throws Exception
     * @throws \Exception
     */
    public function agreePublisherApply($userSign,$applyId,$publisherId,$currentPublisherId)
    {
        if(empty($applyId))
        {
            throw new Exception("ApplyId Is Not Allow Empty");
        }
        if(empty($publisherId))
        {
            throw new Exception("PublisherId Is Not Allow Empty");
        }
        $conn = $this->getConnection();
        $tran=$conn->beginTransaction();
        try{
            $this->tripTravelDb=new TravelTripDb($conn);
            $travelTripPublisher=new TravelTripPublisher();
            $publisherDb=new UserPublisherDb($conn);
            $applyInfo=$this->tripTravelDb->findTravelTripApplyById($applyId);
            $travelInfo=$this->tripTravelDb->findTravelTripById($applyInfo['tripId']);
            if($travelInfo['createPublisherId']!=$currentPublisherId){
                throw new Exception("No Power To Agree Apply");
            }
            $travelTripPublisher->tripId=$applyInfo['tripId'];
            $travelTripPublisher->publisherId=$publisherId;

            $this->tripTravelDb->addTravelTripPublisher($travelTripPublisher);//添加随游与随友关联
            $this->tripTravelDb->changeTravelTripApplyStatus($applyId,TravelTripApply::TRAVEL_TRIP_APPLY_STATUS_AGREE);//同意申请
            $publisherUserInfo=$publisherDb->findUserBaseByPublisherId($publisherId);
            $this->commit($tran);
            //发送消息提醒
            $sysMessageUtil=new SysMessageUtils();
            $sysMessageUtil->sendAgreePublisherApplyMessage($userSign,$publisherUserInfo['userSign'],$travelInfo['tripId'],$travelInfo['title']);
        }catch (Exception $e){
            $this->rollback($tran);
            throw $e;
        }finally{
            $this->closeLink();
        }
    }

    /**
     * 拒绝随友加入随游的申请
     * @param $userSign
     * @param $applyId
     * @param $currentPublisherId
     * @throws Exception
     * @throws \Exception
     */
    public function opposePublisherApply($userSign,$applyId,$currentPublisherId)
    {
        if(empty($applyId))
        {
            throw new Exception("ApplyId Is Not Allow Empty");
        }
        try{
            $conn = $this->getConnection();
            $this->tripTravelDb=new TravelTripDb($conn);
            $publisherDb=new UserPublisherDb($conn);

            $applyInfo=$this->tripTravelDb->findTravelTripApplyById($applyId);
            $travelInfo=$this->tripTravelDb->findTravelTripById($applyInfo['tripId']);
            if($travelInfo['createPublisherId']!=$currentPublisherId){
                throw new Exception("No Power To Agree Apply");
            }
            $this->tripTravelDb->changeTravelTripApplyStatus($applyId,TravelTripApply::TRAVEL_TRIP_APPLY_STATUS_OPPOSE);//拒绝申请
            $publisherUserInfo=$publisherDb->findUserBaseByPublisherId($applyInfo['publisherId']);
            //给申请人发送消息提醒
            $sysMessageUtil=new SysMessageUtils();
            $sysMessageUtil->sendOpposePublisherApplyMessage($userSign,$publisherUserInfo['userSign'],$travelInfo['tripId'],$travelInfo['title']);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }

    /**
     * 获取推荐随游
     * @param Page $page
     * @param $countryId
     * @param $cityId
     * @return Page|null
     * @throws Exception
     * @throws \Exception
     */
    public function getRelateRecommendTrip(Page $page,$countryId,$cityId)
    {
        try{
            $conn = $this->getConnection();
            $this->tripTravelDb=new TravelTripDb($conn);
            $page=$this->tripTravelDb->getRelateRecommendTrip($page,$countryId,$cityId);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
        return $page;
    }


    /**
     * 获取是否有等待审核的相关随游
     * @param $tripId
     * @param $publisherId
     * @return array|bool|null
     * @throws Exception
     * @throws \Exception
     */
    public function findTravelTripApplyByTripIdAndUser($tripId,$publisherId)
    {
        $travelTripApply=null;
        try{
            $conn = $this->getConnection();
            $this->tripTravelDb=new TravelTripDb($conn);
            $travelTripApply=$this->tripTravelDb->findTravelTripApplyByTripIdAndUser($tripId,$publisherId);
            $travelTripApply=$this->arrayCastObject($travelTripApply,TravelTripApply::class);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
        return $travelTripApply;
    }


    /**
     * 更新随游封面图
     * @param $tripId
     * @param $titleImg
     * @throws Exception
     * @throws \Exception
     */
    public function updateTravelTripTitleImg($tripId,$titleImg)
    {
        try{
            $conn = $this->getConnection();
            $this->tripTravelDb=new TravelTripDb($conn);
            $this->tripTravelDb->updateTravelTripTitleImg($tripId,$titleImg);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }

    /**
     * 获取用户推荐详情
     * @param $tripId
     * @return mixed|null
     * @throws Exception
     * @throws \Exception
     */
    public function findTravelTripRecommendByTripId($tripId)
    {
        $rst=null;
        try {
            $conn = $this->getConnection();
            $this->tripTravelDb=new TravelTripDb($conn);
            $rst=$this->tripTravelDb->findTravelTripRecommendByTripId($tripId);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $rst;
    }


    /**
     * 获取随游目的地 和 目的地数量
     * @param Page $page
     * @param $search
     * @return Page|null
     * @throws Exception
     * @throws \Exception
     */
    public function getTripDesSearchList(Page $page,$search)
    {
        try {
            $conn = $this->getConnection();
            $this->tripTravelDb=new TravelTripDb($conn);
            $page=$this->tripTravelDb->getTripDesSearchList($page,$search);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $page;
    }
}