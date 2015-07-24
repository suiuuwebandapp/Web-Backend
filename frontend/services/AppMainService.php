<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/7/23
 * Time: 上午11:48
 */

namespace frontend\services;


use backend\models\UserBaseDb;
use common\models\BaseDb;
use common\models\CircleDb;
use common\models\UserAttentionDb;
use yii\base\Exception;

class AppMainService extends BaseDb
{


    public function __construct()
    {

    }

    public function getUserTravel($userSign,$page)
    {
        try {
            $data = array();
            $conn = $this->getConnection();
            return $data;
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }
}