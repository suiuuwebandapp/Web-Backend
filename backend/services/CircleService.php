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
}