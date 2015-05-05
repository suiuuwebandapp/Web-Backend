<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/17
 * Time : 上午11:20
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\models;


use backend\components\Page;
use common\entity\DestinationInfo;
use common\entity\DestinationScenic;
use common\entity\UserOrderInfo;
use common\entity\UserOrderPublisher;
use yii\db\mssql\PDO;

class UserOrderDb extends ProxyDb{

    public function addUserOrderInfo(UserOrderInfo $userOrderInfo)
    {
        $sql = sprintf("
            INSERT  INTO user_order_info
            (
              orderNumber,userId,tripId,personCount,beginDate,startTime,basePrice,servicePrice,totalPrice,serviceInfo,
              tripJsonInfo,createTime,status,isDel
            )
            VALUES
            (
              :orderNumber,:userId,:tripId,:personCount,:beginDate,:startTime,:basePrice,:servicePrice,:totalPrice,:serviceInfo,
              :tripJsonInfo,now(),:status,FALSE
            )

        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":orderNumber", $userOrderInfo->orderNumber, PDO::PARAM_STR);
        $command->bindParam(":userId", $userOrderInfo->userId, PDO::PARAM_STR);
        $command->bindParam(":tripId", $userOrderInfo->tripId, PDO::PARAM_INT);
        $command->bindParam(":personCount", $userOrderInfo->personCount, PDO::PARAM_INT);
        $command->bindParam(":beginDate", $userOrderInfo->beginDate, PDO::PARAM_INT);
        $command->bindParam(":startTime", $userOrderInfo->startTime, PDO::PARAM_STR);
        $command->bindParam(":basePrice", $userOrderInfo->basePrice, PDO::PARAM_STR);
        $command->bindParam(":servicePrice", $userOrderInfo->servicePrice, PDO::PARAM_STR);
        $command->bindParam(":totalPrice", $userOrderInfo->totalPrice, PDO::PARAM_STR);
        $command->bindParam(":serviceInfo", $userOrderInfo->serviceInfo, PDO::PARAM_STR);
        $command->bindParam(":tripJsonInfo", $userOrderInfo->tripJsonInfo, PDO::PARAM_STR);
        $command->bindParam(":status", $userOrderInfo->status, PDO::PARAM_INT);

        $command->execute();
    }


    /**
     * 添加
     * @param UserOrderPublisher $userOrderPublisher
     * @throws \yii\db\Exception
     */
    public function addUserOrderPublisher(UserOrderPublisher $userOrderPublisher)
    {
        $sql = sprintf("
            INSERT  INTO user_order_publisher
            (
              publisherId,orderId,createTime,finshTime,isFinished
            )
            VALUES
            (
              publisherId,orderId,now(),NULL,FALSE
            )

        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":publisherId", $userOrderPublisher->publisherId, PDO::PARAM_STR);
        $command->bindParam(":orderId", $userOrderPublisher->orderId, PDO::PARAM_STR);

        $command->execute();
    }


    /**
     * 改变订单状态
     * @param $orderId
     * @param $status
     * @throws \yii\db\Exception
     */
    public function changeOrderStatus($orderId,$status)
    {
        $sql=sprintf("
            UPDATE user_order_info SET
            status=:status
            WHERE orderId=:orderId
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":orderId", $orderId, PDO::PARAM_STR);
        $command->bindParam(":status", $status, PDO::PARAM_INT);

        $command->execute();
    }


    /***
     * 根据订单号获取订单详情
     * @param $orderNumber
     * @return array|bool
     */
    public function findOrderByOrderNumber($orderNumber)
    {
        $sql=sprintf("
            SELECT * FROM user_order_info
            WHERE orderNumber=:orderNumber

        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":orderNumber", $orderNumber, PDO::PARAM_STR);

        return $command->queryOne();
    }

    /**
     * 根据订单Id获取订单详情
     * @param $orderId
     * @return array|bool
     */
    public function findOrderById($orderId)
    {
        $sql=sprintf("
            SELECT * FROM user_order_info

            WHERE orderId=:orderId
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":orderId", $orderId, PDO::PARAM_STR);

        return $command->queryOne();
    }


    /**
     * 获取未完成订单列表
     * @param $userSign
     * @return array
     */
    public function getUnFinishOrderList($userSign)
    {
        $sql=sprintf("
            SELECT uoi.*, ub.nickname,ub.phone,ub.areaCode,ub.email,ub.sex,ub.birthday,ub.headImg,ub.hobby,
            ub.profession,ub.school,ub.intro,ub.info,ub.travelCount
            FROM user_order_info uoi
            LEFT JOIN user_order_publisher uop ON uop.orderId=uoi.orderId
            LEFT JOIN user_publisher up  ON up.userPublisherId=uop.publisherId
            LEFT JOIN user_base ub ON ub.userSign=up.userId
            WHERE isDel=FALSE AND uoi.userId=:userId
            AND uoi.status!=".UserOrderInfo::USER_ORDER_STATUS_PLAY_SUCCESS."
            AND uoi.status!=".UserOrderInfo::USER_ORDER_STATUS_PLAY_FINISH."
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":userId", $userSign, PDO::PARAM_STR);

        return $command->queryAll();
    }

    /**
     * 获取已完成订单列表
     * @param $userSign
     * @return array
     */
    public function getFinishOrderList($userSign)
    {
        $sql=sprintf("
            SELECT uoi.*, ub.nickname,ub.phone,ub.areaCode,ub.email,ub.sex,ub.birthday,ub.headImg,ub.hobby,
            ub.profession,ub.school,ub.intro,ub.info,ub.travelCount
            FROM user_order_info uoi
            LEFT JOIN user_order_publisher uop ON uop.orderId=uoi.orderId
            LEFT JOIN user_publisher up  ON up.userPublisherId=uop.publisherId
            LEFT JOIN user_base ub ON ub.userSign=up.userId
            WHERE isDel=FALSE AND uoi.userId=:userId
            AND uoi.status==".UserOrderInfo::USER_ORDER_STATUS_PLAY_SUCCESS."
            AND uoi.status==".UserOrderInfo::USER_ORDER_STATUS_PLAY_FINISH."
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":userId", $userSign, PDO::PARAM_STR);

        return $command->queryAll();
    }

}