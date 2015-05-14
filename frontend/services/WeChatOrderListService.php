<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/14
 * Time: 下午1:24
 */
namespace frontend\services;

use common\components\Code;
use common\entity\WeChatOrderList;
use common\models\WeChatOrderListDb;
use yii;
use common\models\BaseDb;
use yii\base\Exception;

class WeChatOrderListService extends BaseDb{


    public $weChatOrderListSer;
    function __construct()
    {

    }

    public function insertWeChatInfo(WeChatOrderList $weChatOrderList)
    {
        try {
            $conn = $this->getConnection();
            $this->weChatOrderListSer=new WeChatOrderListDb($conn);
            return $this->weChatOrderListSer->addWeChatOrderList($weChatOrderList);
        } catch (Exception $e) {
            throw new Exception('添加订购信息异常', Code::FAIL, $e);
        } finally {
            $this->closeLink();
        }
    }

    public function getOrderListByUserSign($userSign,$page)
    {
        try {
            $conn = $this->getConnection();
            $this->weChatOrderListSer=new WeChatOrderListDb($conn);
            return $this->weChatOrderListSer->getWeChatOrderListByUserSign($userSign,$page);
        } catch (Exception $e) {
            throw new Exception('查询用户订购信息异常', Code::FAIL, $e);
        } finally {
            $this->closeLink();
        }
    }
    public function getOrderInfoById($orderId,$userSign)
    {
        try {
            $conn = $this->getConnection();
            $this->weChatOrderListSer=new WeChatOrderListDb($conn);
            return $this->weChatOrderListSer->findWeChatOrderInfoById($orderId,$userSign);
        } catch (Exception $e) {
            throw new Exception('查询订购信息详情异常', Code::FAIL, $e);
        } finally {
            $this->closeLink();
        }
    }
}