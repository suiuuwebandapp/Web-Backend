<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/17
 * Time : 下午1:31
 * Email: zhangxinmailvip@foxmail.com
 */

namespace backend\services;


use backend\components\Page;
use common\components\Code;
use common\entity\DestinationInfo;
use common\entity\DestinationScenic;
use common\models\BaseDb;
use common\models\DestinationDb;
use yii\base\Exception;

class DestinationService extends BaseDb {


    public $destinationDb;

    public function __construct()
    {

    }


    /**
     * 获取目的地列表
     * @param Page $page
     * @param $search
     * @param $status
     * @return Page
     * @throws Exception
     * @throws \Exception
     */
    public function getDesList(Page $page,$search,$status)
    {
        try {
            $conn = $this->getConnection();
            $this->destinationDb = new DestinationDb($conn);
            $page=$this->destinationDb->getDesList($page,$search,$status);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $page;
    }


    /**
     * 获取景区列表
     * @param Page $page
     * @param $search
     * @return Page
     * @throws Exception
     * @throws \Exception
     */
    public function getScenicList(Page $page,$search)
    {
        try {
            $conn = $this->getConnection();
            $this->destinationDb = new DestinationDb($conn);
            $page=$this->destinationDb->getDesList($page,$search);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $page;
    }



    /**
     * 添加目的地详情
     * @param DestinationInfo $destinationInfo
     * @throws Exception
     * @throws \Exception
     */
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

    /**
     * 添加目的地景区
     * @param DestinationScenic $destinationScenic
     * @throws Exception
     * @throws \Exception
     */
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


    /**
     * 更新目的地详情
     * @param DestinationInfo $destinationInfo
     * @throws Exception
     * @throws \Exception
     */
    public function updateDestinationInfo(DestinationInfo $destinationInfo)
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

    /**
     * 更新目的地景区
     * @param DestinationScenic $destinationScenic
     * @throws Exception
     * @throws \Exception
     */
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


    /**
     *
     * 删除目的地根据Id
     * @param $destinationId
     * @throws Exception
     * @throws \Exception
     */
    public function deleteDestinationById($destinationId)
    {
        $conn = $this->getConnection();
        $tran=$conn->beginTransaction();
        try {
            $this->destinationDb = new DestinationDb($conn);
            $this->destinationDb->deleteDestinationById($destinationId);
            $this->destinationDb->deleteScenicByDesId($destinationId);
            $this->commit($tran);
        } catch (Exception $e) {
            $this->rollback($tran);
            throw $e;
        } finally {
            $this->closeLink();
        }
    }


    /**
     * 删除景区根据ID
     *
     * @param $scenicId
     * @throws Exception
     * @throws \Exception
     */
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


    /**
     * 删除景区根据目的地Id
     * @param $destinationId
     * @throws Exception
     * @throws \Exception
     */
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


    /**
     * 根据Id获取目的地
     * @param $destinationId
     * @return mixed|null
     * @throws Exception
     * @throws \Exception
     */
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


    /**
     * 根据景区获取ID
     *
     * @param $scenicId
     * @return mixed|null
     * @throws Exception
     * @throws \Exception
     */
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


    /**
     * 改变目的地状态
     * @param $desId
     * @param $status
     * @throws Exception
     * @throws \Exception
     */
    public function changeStatus($desId,$status)
    {
        if(empty($desId)||empty($desId)){
            throw new Exception(Code::INVALID_PARAM);
        }
        try {
            $conn = $this->getConnection();
            $this->destinationDb = new DestinationDb($conn);
            $this->destinationDb->changeStatus($desId,$status);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }



}