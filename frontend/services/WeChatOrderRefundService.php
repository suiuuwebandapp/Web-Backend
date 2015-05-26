<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/26
 * Time: 下午2:55
 */

namespace frontend\services;


use common\components\Code;
use common\entity\WeChatOrderRefund;
use common\models\BaseDb;
use common\models\WeChatOrderRefundDb;
use yii\base\Exception;

class WeChatOrderRefundService extends BaseDb{


    public $weChatOrderListDb;
    public $weChatOrderRefundDb;
    function __construct()
    {

    }
    public function insertWeChatInfo(WeChatOrderRefund $weChatOrderList)
    {
        try {
            $conn = $this->getConnection();
            $this->weChatOrderRefundDb=new WeChatOrderRefundDb($conn);
            return $this->weChatOrderRefundDb->addWeChatOrderRefund($weChatOrderList);
        } catch (Exception $e) {

            throw new Exception('添加退款信息异常', Code::FAIL, $e);
        } finally {
            $this->closeLink();
        }
    }
    public function findRefundInfoByOrderNumber($orderNumber)
    {
        try {
            $conn = $this->getConnection();
            $this->weChatOrderRefundDb=new WeChatOrderRefundDb($conn);
            return $this->weChatOrderRefundDb->findRefundInfoByOrderNumber($orderNumber);
        } catch (Exception $e) {

            throw new Exception('添加退款信息异常', Code::FAIL, $e);
        } finally {
            $this->closeLink();
        }
    }

}