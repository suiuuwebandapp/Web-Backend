<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/6/1
 * Time: 上午10:40
 */

namespace backend\services;


use common\models\BaseDb;
use common\models\WeChatOrderListDb;
use yii\base\Exception;

class WechatOrderPayService extends BaseDb{

    private $payDb;

    public function __construct()
    {

    }

    public function getList($page,$searchText,$type)
    {
        try {
            $conn = $this->getConnection();
            $this->payDb = new WeChatOrderListDb($conn);
            $page=$this->payDb->getWeChatOrderPayList($page,$searchText,$type);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $page;
    }

}