<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/9/24
 * Time: 下午2:14
 */

namespace backend\services;


use backend\models\TravelPictureDb;
use common\models\BaseDb;
use yii\base\Exception;

class TravelPictureService extends BaseDb{

    private $tpDb;

    public function __construct()
    {

    }
    public function getTpList($page,$tag,$search,$id)
    {
        try {
            $conn = $this->getConnection();
            $this->tpDb = new TravelPictureDb($conn);
            $page=$this->tpDb->getTravelPictureList($page,$tag,$search,$id);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $page;
    }
    public function deleteTp($id)
    {
        try {
            $conn = $this->getConnection();
            $this->tpDb = new TravelPictureDb($conn);
            return $this->tpDb->deleteById($id);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }

    public function getTpInfo($id)
    {
        try {
            $conn = $this->getConnection();
            $this->tpDb = new TravelPictureDb($conn);
            return $this->tpDb->getTravelPictureInfoById($id);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }

    public function getCommentList($page,$search)
    {
        try {
            $conn = $this->getConnection();
            $this->tpDb = new TravelPictureDb($conn);
            $page=$this->tpDb->getCommentListByTpId($page,$search);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $page;
    }

    public function deleteComment($id,$tpId)
    {
        try {
            $conn = $this->getConnection();
            $this->tpDb = new TravelPictureDb($conn);
            $this->updateTravelPictureCommentCount($tpId,false);
            return $this->tpDb->deleteCommentById($id);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }

    public function updateTravelPictureCommentCount($id,$add)
    {
        try{
            $conn=$this->getConnection();
            $this->tpDb=new TravelPictureDb($conn);
            $info = $this->tpDb->getTravelPictureInfoById($id);
            $count = $info['commentCount']?$info['commentCount']:0;
            if($add)
            {
                $count++;
            }else
            {
                $count--;
                if($count<1)
                {
                    $count=0;
                }
            }
            $this->tpDb->updateCommentCount($id,$count);
            return $info;
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }
}