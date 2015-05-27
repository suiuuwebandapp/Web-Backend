<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/27
 * Time: 下午3:30
 */

namespace backend\services;


use common\models\BaseDb;
use common\models\WeChatOrderListDb;
use yii\base\Exception;

class WechatOrderService extends BaseDb
{

    private $wechatOrderDb;

    public function __construct()
    {

    }

    /**
     *
     * @param $page
     * @param $search
     * @param $status
     * @param $isDel
     * @param $phone
     * @return array|Page
     * @throws Exception
     * @throws \Exception
     */
    public function getList($page,$searchName,$status,$isDel,$phone)
    {
        try {
            $conn = $this->getConnection();
            $this->wechatOrderDb = new WeChatOrderListDb($conn);
            $page=$this->wechatOrderDb->getWeChatOrderList($page,$searchName,$status,$isDel,$phone);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $page;
    }

}