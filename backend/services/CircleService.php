<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/6/2
 * Time: 上午10:43
 */

namespace backend\services;


use common\models\BaseDb;
use common\models\CircleDb;
use yii\base\Exception;

class CircleService extends BaseDb{

    private $circleDb;

    public function __construct()
    {

    }
    public function getCircleList($page,$search,$type)
    {
        try {
            $conn = $this->getConnection();
            $this->circleDb = new CircleDb($conn);
            $page=$this->circleDb->getCircleList($page,$search,$type);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $page;
    }

    public function addCircleSort($circleSort)
    {
        try {
            $conn = $this->getConnection();
            $this->circleDb = new CircleDb($conn);
            $this->circleDb->addCircleSort($circleSort);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }

    public function edit($circleSort)
    {
        try {
            $conn = $this->getConnection();
            $this->circleDb = new CircleDb($conn);
            $this->circleDb->edit($circleSort);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }

    public function delete($id)
    {
        try {
            $conn = $this->getConnection();
            $this->circleDb = new CircleDb($conn);
            $this->circleDb->delete($id);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }
    public function change($id,$status)
    {
        try {
            $conn = $this->getConnection();
            $this->circleDb = new CircleDb($conn);
            if($status==1)
            {
                $status=0;
            }else
            {
                $status=1;
            }
            $this->circleDb->change($id,$status);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }

    public function getInfo($id)
    {
        try {
            $conn = $this->getConnection();
            $this->circleDb = new CircleDb($conn);
            return $this->circleDb->getInfo($id);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }

}