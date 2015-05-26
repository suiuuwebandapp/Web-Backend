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


    public $weChatOrderListDb;
    function __construct()
    {

    }

    public function insertWeChatInfo(WeChatOrderList $weChatOrderList)
    {
        try {
            $conn = $this->getConnection();
            $this->weChatOrderListDb=new WeChatOrderListDb($conn);
            return $this->weChatOrderListDb->addWeChatOrderList($weChatOrderList);
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
            $this->weChatOrderListDb=new WeChatOrderListDb($conn);
            return $this->weChatOrderListDb->getWeChatOrderListByUserSign($userSign,$page);
        } catch (Exception $e) {
            throw new Exception('查询用户订购信息异常', Code::FAIL, $e);
        } finally {
            $this->closeLink();
        }
    }
    public function getWeChatOrderListByOrderNumber($orderNumber)
    {
        try {
            $conn = $this->getConnection();
            $this->weChatOrderListDb=new WeChatOrderListDb($conn);
            return $this->weChatOrderListDb->getWeChatOrderListByOrderNumber($orderNumber);
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
            $this->weChatOrderListDb=new WeChatOrderListDb($conn);
            return $this->weChatOrderListDb->findWeChatOrderInfoById($orderId,$userSign);
        } catch (Exception $e) {
            throw new Exception('查询订购信息详情异常', Code::FAIL, $e);
        } finally {
            $this->closeLink();
        }
    }
    public function getOrderInfoByOrderNumber($orderNumber,$userSign)
    {
        try {
            $conn = $this->getConnection();
            $this->weChatOrderListDb=new WeChatOrderListDb($conn);
            return $this->weChatOrderListDb->findWeChatOrderInfoByNumber($orderNumber,$userSign);
        } catch (Exception $e) {
            throw new Exception('查询订购信息详情异常', Code::FAIL, $e);
        } finally {
            $this->closeLink();
        }
    }

    public function updateOrderUserSign($openId,$userSign)
    {
        try {
            $conn = $this->getConnection();
            $this->weChatOrderListDb=new WeChatOrderListDb($conn);
            return $this->weChatOrderListDb->updateOrderUserSign($openId,$userSign);
        } catch (Exception $e) {
            throw new Exception('更新订购信息详情异常', Code::FAIL, $e);
        } finally {
            $this->closeLink();
        }
    }

    public function deleteOrder($orderNumber,$userSign)
    {
        try {
            $conn = $this->getConnection();
            $this->weChatOrderListDb=new WeChatOrderListDb($conn);
            return $this->weChatOrderListDb->deleteOrder($orderNumber,$userSign);
        } catch (Exception $e) {
            throw new Exception('删除订购信息异常', Code::FAIL, $e);
        } finally {
            $this->closeLink();
        }
    }

    public function orderPayEnd($wOrderNumber,$payNumber,$type,$money)
    {
        try {
            $conn = $this->getConnection();
            $this->weChatOrderListDb=new WeChatOrderListDb($conn);
            $i=$this->weChatOrderListDb->orderPayEnd($wOrderNumber);
            if($i==1)
            {
                $this->weChatOrderListDb->addWxOrderPay($wOrderNumber,$payNumber,$type,$money);
            }else{
                throw new Exception('变更订单状态异常');
            }
        } catch (Exception $e) {
            throw new Exception('变更订单状态异常', Code::FAIL, $e);
        } finally {
            $this->closeLink();
        }
    }
    public function updateOrderStatus($orderNumber,$status,$userSign)
    {
        try {
            $conn = $this->getConnection();
            $this->weChatOrderListDb=new WeChatOrderListDb($conn);
            return $this->weChatOrderListDb->updateOrderStatus($orderNumber,$status,$userSign);
        } catch (Exception $e) {
            throw new Exception('变更订单状态异常', Code::FAIL, $e);
        } finally {
            $this->closeLink();
        }
    }



}