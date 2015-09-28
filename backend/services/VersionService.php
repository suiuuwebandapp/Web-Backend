<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/9/28
 * Time: 上午10:23
 */

namespace backend\services;


use backend\models\VersionDb;
use common\models\BaseDb;
use yii\base\Exception;

class VersionService extends BaseDb{

    private $vDb;

    public function __construct()
    {

    }
    public function getList($page,$search,$type)
    {
        try {
            $conn = $this->getConnection();
            $this->vDb = new VersionDb($conn);
            $page=$this->vDb->getList($page,$search,$type);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $page;
    }
}