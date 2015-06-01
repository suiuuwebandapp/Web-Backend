<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/6/1
 * Time: 下午3:00
 */

namespace backend\services;


use common\models\BaseDb;
use common\models\RecommendListDb;
use yii\base\Exception;

class RecommendListService extends BaseDb{

    private $recommendDb;

    public function __construct()
    {

    }

    public function getList($page,$type)
    {
        try {
            $conn = $this->getConnection();
            $this->recommendDb = new RecommendListDb($conn);
            $page=$this->recommendDb->getList($page,$type);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $page;
    }

    public  function addRecommend($recommend)
    {
        try {
            $conn = $this->getConnection();
            $this->recommendDb = new RecommendListDb($conn);
            $this->recommendDb->addRecommend($recommend);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }

}