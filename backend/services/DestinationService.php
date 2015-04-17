<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/17
 * Time : 下午1:31
 * Email: zhangxinmailvip@foxmail.com
 */

namespace backend\services;


use backend\entity\DestinationInfo;
use backend\entity\DestinationScenic;
use common\models\BaseDb;
use common\models\DestinationDb;
use yii\base\Exception;

class DestinationService extends BaseDb {


    public $destinationDb;

    public function __construct()
    {

    }


    public function addDestinationInfo(DestinationInfo $destinationInfo)
    {
        try {
            $conn = $this->getConnection();
            $this->destinationDb = new DestinationDb($conn);
            $this->destinationDb->addDestinationInfo($destinationInfo);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }

    }

    public function addDestinationScenic(DestinationScenic $destinationScenic)
    {
        try {
            $conn = $this->getConnection();
            $this->destinationDb = new DestinationDb($conn);
            $this->destinationDb->addDestinationScenic($destinationScenic);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }


    public function updateDestinationInfo(DestinationScenic $destinationInfo)
    {
        try {
            $conn = $this->getConnection();
            $this->destinationDb = new DestinationDb($conn);
            $this->destinationDb->updateDestinationInfo($destinationInfo);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }

    public function updateDestinationScenic(DestinationScenic $destinationScenic)
    {
        try {
            $conn = $this->getConnection();
            $this->destinationDb = new DestinationDb($conn);
            $this->destinationDb->updateDestinationScenic($destinationScenic);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }


    public function deleteDestinationById($destinationId)
    {
        try {
            $conn = $this->getConnection();
            $this->destinationDb = new DestinationDb($conn);
            $this->destinationDb->deleteDestinationById($destinationId);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }


    public function deleteScenicById($scenicId)
    {
        try {
            $conn = $this->getConnection();
            $this->destinationDb = new DestinationDb($conn);
            $this->destinationDb->deleteScenicById($scenicId);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }


    public function deleteScenicByDesId($destinationId)
    {
        try {
            $conn = $this->getConnection();
            $this->destinationDb = new DestinationDb($conn);
            $this->destinationDb->deleteScenicByDesId($destinationId);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }


    public function findDestinationById($destinationId)
    {
        $destinationInfo=null;
        try{
            $conn = $this->getConnection();
            $this->destinationDb = new DestinationDb($conn);
            $rst=$this->destinationDb->findDestinationById($destinationId);
            $destinationInfo=$this->arrayCastObject($rst,DestinationInfo::class);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }

        return $destinationInfo;
    }


    public function findScenicById($scenicId)
    {
        $scenicInfo=null;
        try{
            $conn = $this->getConnection();
            $this->destinationDb = new DestinationDb($conn);
            $rst=$this->destinationDb->findScenicById($scenicId);
            $scenicInfo=$this->arrayCastObject($rst,DestinationScenic::class);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }

        return $scenicInfo;
    }




}