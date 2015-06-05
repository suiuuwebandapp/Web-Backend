<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/6/4
 * Time: 上午10:09
 */

namespace backend\services;


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
}