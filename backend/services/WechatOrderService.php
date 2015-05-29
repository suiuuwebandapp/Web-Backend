<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/27
 * Time: 下午3:30
 */

namespace backend\services;


use common\entity\WeChatOrderList;
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
     * @param $searchText
     * @param $status
     * @param $isDel
     * @return array|Page
     * @throws Exception
     * @throws \Exception
     */
    public function getList($page,$searchText,$status,$isDel)
    {
        try {
            $conn = $this->getConnection();
            $this->wechatOrderDb = new WeChatOrderListDb($conn);
            $page=$this->wechatOrderDb->getWeChatOrderList($page,$searchText,$status,$isDel);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $page;
    }

    public function deleteOrder($orderNumber)
    {
        try {
            $conn = $this->getConnection();
            $this->wechatOrderDb=new WeChatOrderListDb($conn);
            return $this->wechatOrderDb->deleteOrder($orderNumber,null);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }
    public function overOrder($orderNumber)
    {
        try {
            $conn = $this->getConnection();
            $this->wechatOrderDb=new WeChatOrderListDb($conn);
            $orderInfo = new WeChatOrderList();
            $orderInfo->wOrderNumber=$orderNumber;
            $orderInfo->wStatus=WeChatOrderList::STATUS_END;
            return $this->wechatOrderDb->updateOrderRefund($orderInfo);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }
    public function getOrderInfo($orderNumber)
    {
        try {
            $conn = $this->getConnection();
            $this->wechatOrderDb=new WeChatOrderListDb($conn);
            return $this->wechatOrderDb->sysFindWeChatOrderInfoByNumber($orderNumber,null);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }

    public function updateOrderInfo($wDetails,$wRelativeSign,$wMoney,$wOrderNumber)
    {
        try {
            $conn = $this->getConnection();
            $this->wechatOrderDb=new WeChatOrderListDb($conn);
            $orderInfo = new WeChatOrderList();
            $orderInfo->wDetails=$wDetails;
            $orderInfo->wRelativeSign=$wRelativeSign;
            $orderInfo->wMoney=$wMoney;
            $orderInfo->wOrderNumber=$wOrderNumber;
            $orderInfo->wStatus=WeChatOrderList::STATUS_PROCESSED;
            return $this->wechatOrderDb->updateOrderInfo($orderInfo);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }

    public function updateOrderRefund($orderNumber,$status)
    {
        try {
            $conn = $this->getConnection();
            $this->wechatOrderDb=new WeChatOrderListDb($conn);
            $orderInfo = new WeChatOrderList();
            $orderInfo->wOrderNumber=$orderNumber;
            $orderInfo->wStatus=$status;
            return $this->wechatOrderDb->updateOrderRefund($orderInfo);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }

}