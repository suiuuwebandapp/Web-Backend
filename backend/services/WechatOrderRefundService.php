<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/28
 * Time: 下午5:34
 */

namespace backend\services;


use common\entity\WeChatOrderList;
use common\entity\WeChatOrderRefund;
use common\models\BaseDb;
use common\models\WeChatOrderRefundDb;
use yii\base\Exception;

class WechatOrderRefundService extends BaseDb{

    private $refundDb;

    public function __construct()
    {

    }
    /**
     *
     * @param $page
     * @param $searchText
     * @param $status
     * @return array|Page
     * @throws Exception
     * @throws \Exception
     */
    public function getList($page,$searchText,$status)
    {
        try {
            $conn = $this->getConnection();
            $this->refundDb = new WeChatOrderRefundDb($conn);
            $page=$this->refundDb->sysGetList($page,$searchText,$status);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $page;
    }

    public function getOrderInfo($orderNumber)
    {
        try {
            $conn = $this->getConnection();
            $this->refundDb=new WeChatOrderRefundDb($conn);
            return $this->refundDb->findRefundInfoByOrderNumber($orderNumber);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }

    public function sysUpdateInfo($orderNumber,$money,$updateUserSign,$updateReason,$status)
    {
        try {
            $conn = $this->getConnection();
            $this->refundDb=new WeChatOrderRefundDb($conn);
            $orderInfoSer = new WechatOrderService();
            if($status==WeChatOrderRefund::STATUS_REFUND_FAIL)
            {
                $orderInfoSer->updateOrderRefund($orderNumber,WeChatOrderList::STATUS_REFUND_FAL);
            }elseif($status==WeChatOrderRefund::STATUS_REFUND_SUCCESS)
            {
                $orderInfoSer->updateOrderRefund($orderNumber,WeChatOrderList::STATUS_REFUND_SUCCESS);
            }
            return $this->refundDb->sysUpdateInfo($orderNumber,$money,$updateUserSign,$updateReason,$status);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }
    public function deleteRefund($orderNumber)
    {
        try {
            $conn = $this->getConnection();
            $this->refundDb=new WeChatOrderRefundDb($conn);
            return $this->refundDb->deleteRefund($orderNumber);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }
}