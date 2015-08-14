<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/6/4
 * Time: 上午10:09
 */

namespace backend\services;


use common\entity\TravelTrip;
use common\entity\TravelTripRecommend;
use common\models\BaseDb;
use common\models\TravelTripDb;
use yii\base\Exception;

class TripService extends BaseDb{

    private $tripDb;

    public function __construct()
    {

    }
    public function getTripDbList($page,$search,$startPrice,$endPrice,$status)
    {
        try {
            $conn = $this->getConnection();
            $this->tripDb = new TravelTripDb($conn);
            $peopleCount=0;
            if(!empty($search))
            {
                $peopleCount=intval($search);
            }
            $page=$this->tripDb->sysGetList($page,$search,$peopleCount,$startPrice,$endPrice,$status);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $page;
    }
    public function getCommentList($page,$search)
    {
        try {
            $conn = $this->getConnection();
            $this->tripDb = new TravelTripDb($conn);
            $page=$this->tripDb->getComment($page,$search);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $page;
    }
    public function deleteComment($id)
    {
        try {
            $conn = $this->getConnection();
            $this->tripDb = new TravelTripDb($conn);
            $this->tripDb->deleteComment($id);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }


    public function updateTravelTripRecommend(TravelTripRecommend $travelTripRecommend)
    {
        $tripId=$travelTripRecommend->tripId;
        try {
            $rst=$this->findObjectByType(TravelTripRecommend::class,"tripId",$tripId);
            if(!empty($rst)&&count($rst)>0){
                $travelTripRecommend->recommendId=$rst[0]['recommendId'];
                $this->updateObject($travelTripRecommend);
            }else{
                $this->saveObject($travelTripRecommend);
            }
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }

    public function findTravelTripRecommendByTripId($tripId)
    {
        $userRecommend=null;
        try {
            $rst=$this->findObjectByType(TravelTripRecommend::class,"tripId",$tripId);
            if(!empty($rst)&&count($rst)>0){
                $userRecommend=$this->arrayCastObject($rst[0],TravelTripRecommend::class);
            }
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $userRecommend;
    }


    public function updateTravelTripBase($tripId,$score,$tripCount,$isHot,$type)
    {
        $conn = $this->getConnection();
        try {
            $this->tripDb = new TravelTripDb($conn);
            $this->tripDb->updateTravelTripBase($tripId,$score,$tripCount,$isHot,$type);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }


}