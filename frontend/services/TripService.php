<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/29
 * Time : 下午2:55
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\services;


use common\entity\TravelTrip;
use common\entity\TravelTripApply;
use common\entity\TravelTripPublisher;
use common\entity\TravelTripService;
use common\entity\UserAttention;
use common\models\BaseDb;
use common\models\TravelTripDb;
use common\models\UserAttentionDb;
use yii\base\Exception;

class TripService extends BaseDb{

    private $tripTravelDb;

    public function __construct()
    {

    }

    public function getList($page,$title,$countryId,$cityId,$peopleCount,$startPrice,$endPrice,$tag)
    {
        try {
            $conn = $this->getConnection();
            $this->tripTravelDb = new TravelTripDb($conn);
            return $this->tripTravelDb->getList($page,$title,$countryId,$cityId,$peopleCount,$startPrice,$endPrice,$tag,TravelTrip::TRAVEL_TRIP_STATUS_NORMAL);
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
     * @return array|bool
     * @throws Exception
     * @throws \Exception
     */
    public function addTravelTrip(TravelTrip $travelTrip,$scenicList,$picList,$priceList,TravelTripPublisher $travelTripPublisher,$serviceList)
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
     * 更新随游
     * @param TravelTrip $travelTrip
     * @param $scenicList
     * @param $picList
     * @param $priceList
     * @param $serviceList
     * @return array|bool
     * @throws Exception
     * @throws \Exception
     */
    public function updateTravelTrip(TravelTrip $travelTrip,$scenicList,$picList,$priceList,$serviceList)
    {
        $conn = $this->getConnection();
        $tran=$conn->beginTransaction();
        try {

            $this->tripTravelDb = new TravelTripDb($conn);
            $this->tripTravelDb->updateTravelTrip($travelTrip);
            //删除 ，添加
            if($scenicList!=null){
                $this->tripTravelDb->deleteTravelTripScenicBytripId($travelTrip->tripId);
                foreach($scenicList as $scenic)
                {
                    $scenic->tripId=$travelTrip->tripId;
                    $this->tripTravelDb->addTravelTripScenic($scenic);
                }
            }
            if($picList!=null){
                $this->tripTravelDb->deleteTravelTripPicBytripId($travelTrip->tripId);
                foreach($picList as $pic)
                {
                    $pic->tripId=$travelTrip->tripId;
                    $this->tripTravelDb->addTravelTripPicture($pic);
                }
            }
            if($priceList!=null){
                $this->tripTravelDb->deleteTravelTripPriceBytripId($travelTrip->tripId);
                foreach($priceList as $price)
                {
                    $price->tripId=$travelTrip->tripId;
                    $this->tripTravelDb->addTravelTripPrice($price);
                }
            }
            if($serviceList!=null){
                $this->tripTravelDb->deleteTravelTripServiceBytripId($travelTrip->tripId);
                foreach($serviceList as $service)
                {
                    $service->tripId=$travelTrip->tripId;
                    $this->tripTravelDb->addTravelTripService($service);
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
            $tripInfo['priceList']=$this->tripTravelDb->getTravelTripPriceList($tripId);
            $tripInfo['publisherList']=$this->tripTravelDb->getTravelTripPublisherList($tripId);
            $tripInfo['scenicList']=$this->tripTravelDb->getTravelTripScenicList($tripId);
            $tripInfo['serviceList']=$this->tripTravelDb->getTravelTripServiceList($tripId);

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
        try{
            $conn = $this->getConnection();
            $this->tripTravelDb=new TravelTripDb($conn);
            $this->tripTravelDb->changeTravelStatus($tripId,$status);
        }catch (Exception $e){
            throw $e;
        }finally {
            $this->closeLink();
        }
    }


    /**
     * 根据随友Id获取详情
     * @param $publisherId
     * @return array|bool|null
     * @throws Exception
     * @throws \Exception
     */
    public function getTravelTripPublisherById($publisherId)
    {
        $userPublisher=null;
        try{
            $conn = $this->getConnection();
            $this->tripTravelDb=new TravelTripDb($conn);
            $userPublisher=$this->tripTravelDb->getTravelTripPublisherById($publisherId);
        }catch (Exception $e){
            throw $e;
        }finally {
            $this->closeLink();
        }
        return $userPublisher;
    }


    /**
     * 添加随游申请
     * @param TravelTripApply $travelTripApply
     * @throws Exception
     * @throws \Exception
     */
    public function addTravelTripApply(TravelTripApply $travelTripApply)
    {
        try{
            $conn = $this->getConnection();
            $this->tripTravelDb=new TravelTripDb($conn);
            $this->tripTravelDb->addTravelTripApply($travelTripApply);
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
     * @param TravelTripPublisher $travelTripPublisher
     * @throws Exception
     * @throws \Exception
     */
    public function deleteTravelTriPublisher(TravelTripPublisher $travelTripPublisher)
    {
        if($travelTripPublisher!=null)
        {
            throw new Exception("TravelTripPublisher Is Not Allow Null");
        }
        try{
            $conn = $this->getConnection();
            $this->tripTravelDb=new TravelTripDb($conn);
            $this->tripTravelDb->deleteTravelTripPublisher($travelTripPublisher);
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
     * @param $applyId
     * @param $publisherId
     * @param $currentPublisherId
     * @throws Exception
     * @throws \Exception
     */
    public function agreePublisherApply($applyId,$publisherId,$currentPublisherId)
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
            $applyInfo=$this->tripTravelDb->findTravelTripApplyById($applyId);
            $travelInfo=$this->tripTravelDb->findTravelTripById($applyInfo['tripId']);
            if($travelInfo['createPublisherId']!=$currentPublisherId){
                throw new Exception("No Power To Agree Apply");
            }
            $travelTripPublisher->tripId=$applyInfo['tripId'];
            $travelTripPublisher->tripPublisherId=$publisherId;

            $this->tripTravelDb->addTravelTripPublisher($travelTripPublisher);//添加随游与随友关联
            $this->tripTravelDb->changeTravelTripApplyStatus($applyId,TravelTripApply::TRAVEL_TRIP_APPLY_STATUS_AGREE);//同意申请
            $this->commit($tran);
        }catch (Exception $e){
            $this->rollback($tran);
            throw $e;
        }finally{
            $this->closeLink();
        }
    }


    /**
     * 拒绝随友加入随游的申请
     * @param $applyId
     * @param $currentPublisherId
     * @throws Exception
     * @throws \Exception
     */
    public function opposePublisherApply($applyId,$currentPublisherId)
    {
        if(empty($applyId))
        {
            throw new Exception("ApplyId Is Not Allow Empty");
        }
        try{
            $conn = $this->getConnection();
            $this->tripTravelDb=new TravelTripDb($conn);
            $applyInfo=$this->tripTravelDb->findTravelTripApplyById($applyId);
            $travelInfo=$this->tripTravelDb->findTravelTripById($applyInfo['tripId']);
            if($travelInfo['createPublisherId']!=$currentPublisherId){
                throw new Exception("No Power To Agree Apply");
            }
            $this->tripTravelDb->changeTravelTripApplyStatus($applyId,TravelTripApply::TRAVEL_TRIP_APPLY_STATUS_OPPOSE);//同意申请
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }
}