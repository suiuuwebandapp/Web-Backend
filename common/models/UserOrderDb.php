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
use common\entity\UserAccount;
use common\entity\UserAccountRecord;
use common\entity\UserOrderComment;
use common\entity\UserOrderInfo;
use common\entity\UserOrderPublisher;
use common\entity\UserOrderPublisherCancel;
use common\entity\UserOrderPublisherIgnore;
use yii\db\mssql\PDO;

class UserOrderDb extends ProxyDb
{

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
        $command = $this->getConnection()->createCommand($sql);
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
              publisherId,orderId,createTime,isFinished
            )
            VALUES
            (
              :publisherId,:orderId,now(),FALSE
            )

        ");

        $command = $this->getConnection()->createCommand($sql);
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
    public function changeOrderStatus($orderId, $status)
    {
        $sql = sprintf("
            UPDATE user_order_info SET
            status=:status
            WHERE orderId=:orderId
        ");
        $command = $this->getConnection()->createCommand($sql);
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
        $sql = sprintf("
            SELECT * FROM user_order_info
            WHERE orderNumber=:orderNumber AND isDel=FALSE

        ");
        $command = $this->getConnection()->createCommand($sql);
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
        $sql = sprintf("
            SELECT * FROM user_order_info

            WHERE orderId=:orderId
        ");
        $command = $this->getConnection()->createCommand($sql);
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
        $sql = sprintf("
            SELECT uoi.*, ub.nickname,ub.phone,ub.areaCode,ub.email,ub.sex,ub.birthday,ub.headImg,ub.hobby,
            ub.profession,ub.school,ub.intro,ub.info,ub.travelCount,ub.userSign
            FROM user_order_info uoi
            LEFT JOIN user_order_publisher uop ON uop.orderId=uoi.orderId
            LEFT JOIN user_publisher up  ON up.userPublisherId=uop.publisherId
            LEFT JOIN user_base ub ON ub.userSign=up.userId
            WHERE isDel=FALSE AND uoi.userId=:userId
            AND uoi.status!=" . UserOrderInfo::USER_ORDER_STATUS_PLAY_SUCCESS . "
            AND uoi.status!=" . UserOrderInfo::USER_ORDER_STATUS_PLAY_FINISH . "
            AND uoi.status!=" . UserOrderInfo::USER_ORDER_STATUS_CANCELED . "
            AND uoi.status!=" . UserOrderInfo::USER_ORDER_STATUS_PUBLISHER_CANCEL . "


        ");
        $command = $this->getConnection()->createCommand($sql);
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
        $sql = sprintf("
            SELECT uoi.*, ub.nickname,ub.phone,ub.areaCode,ub.email,ub.sex,ub.birthday,ub.headImg,ub.hobby,
            ub.profession,ub.school,ub.intro,ub.info,ub.travelCount,ub.userSign,uoc.orderCommentId AS isComment
            FROM user_order_info uoi
            LEFT JOIN user_order_publisher uop ON uop.orderId=uoi.orderId
            LEFT JOIN user_publisher up  ON up.userPublisherId=uop.publisherId
            LEFT JOIN user_base ub ON ub.userSign=up.userId
            LEFT JOIN user_order_comment uoc ON uoc.orderId=uoi.orderId
            WHERE isDel=FALSE AND uoi.userId=:userId
            AND
            (
              uoi.status=" . UserOrderInfo::USER_ORDER_STATUS_PLAY_SUCCESS . "
              OR uoi.status=" . UserOrderInfo::USER_ORDER_STATUS_PLAY_FINISH . "
              OR uoi.status=" . UserOrderInfo::USER_ORDER_STATUS_CANCELED . "
              OR uoi.status=" . UserOrderInfo::USER_ORDER_STATUS_PUBLISHER_CANCEL . "

            )
        ");
        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":userId", $userSign, PDO::PARAM_STR);
        $rst=$command->queryAll();
        return $rst;
    }


    /**
     * 添加忽略
     * @param UserOrderPublisherIgnore $userOrderPublisherIgnore
     * @throws \yii\db\Exception
     */
    public function addUserOrderPublisherIgnore(UserOrderPublisherIgnore $userOrderPublisherIgnore)
    {
        $sql = sprintf("
            INSERT INTO user_order_publisher_ignore
            (
              orderId,publisherId
            )
            VALUES
            (
               :orderId,:publisherId
            )
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":orderId", $userOrderPublisherIgnore->orderId, PDO::PARAM_INT);
        $command->bindParam(":publisherId", $userOrderPublisherIgnore->publisherId, PDO::PARAM_INT);

        $command->execute();
    }


    /**
     * 获取等待加入的随游(未确认)
     * @param $publisherId
     * @return array
     */
    public function getUnConfirmOrderByPublisher($publisherId)
    {

        $sql = sprintf("
            SELECT uoi.*,ttp.publisherId,ub.nickname,ub.phone,ub.areaCode,ub.email,ub.sex,ub.birthday,ub.headImg,ub.hobby,
            ub.profession,ub.school,ub.intro,ub.info,ub.travelCount
            FROM user_order_info uoi
            LEFT JOIN travel_trip_publisher ttp ON ttp.tripId=uoi.tripId
            LEFT JOIN user_base ub ON ub.userSign=uoi.userId

            WHERE uoi.isDel=FALSE AND uoi.status=:status AND ttp.publisherId=:publisherId AND orderId NOT IN
            (
              SELECT orderId FROM user_order_publisher_ignore WHERE publisherId=:publisherId
            )
            AND CONCAT(uoi.beginDate,' ',uoi.startTime)>now()
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindValue(":status", UserOrderInfo::USER_ORDER_STATUS_PAY_SUCCESS, PDO::PARAM_INT);
        $command->bindParam(":publisherId", $publisherId, PDO::PARAM_INT);

        return $command->queryAll();
    }


    /**
     * 伪删除订单
     * @param $orderId
     * @throws \yii\db\Exception
     */
    public function deleteOrderInfo($orderId)
    {
        $sql = sprintf("
            UPDATE user_order_info SET
            isDel=TRUE
            WHERE orderId=:orderId
        ");
        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":orderId", $orderId, PDO::PARAM_INT);

        $command->execute();
    }

    /**
     * 获取随友的订单
     * @param $publisherId
     * @return array
     */
    public function getPublisherOrderList($publisherId)
    {
        $sql=sprintf("
            SELECT uoi.*,ttp.publisherId,ub.nickname,ub.phone,ub.areaCode,ub.email,ub.sex,ub.birthday,ub.headImg,ub.hobby,
            ub.profession,ub.school,ub.intro,ub.info,ub.travelCount
            FROM user_order_info uoi
            LEFT JOIN travel_trip_publisher ttp ON ttp.tripId=uoi.tripId
            LEFT JOIN user_base ub ON ub.userSign=uoi.userId
            WHERE uoi.isDel=FALSE AND uoi.orderId IN
            (
              SELECT orderId FROM user_order_publisher
              WHERE publisherId=:publisherId
            )
            AND ttp.publisherId IS NOT NULL
            AND uoi.status IN
            (".
            UserOrderInfo::USER_ORDER_STATUS_CONFIRM.",".
            UserOrderInfo::USER_ORDER_STATUS_PLAY_SUCCESS.",".
            UserOrderInfo::USER_ORDER_STATUS_PLAY_FINISH

            .")
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":publisherId",$publisherId);

        return $command->queryAll();
    }


    /**
     * 根据订单号，获取随友信息
     * @param $orderId
     * @return array|bool
     */
    public function findPublisherByOrderId($orderId)
    {
        $sql=sprintf("
            SELECT * FROM user_publisher
            WHERE userPublisherId=
            (
              SELECT publisherId FROM user_order_publisher WHERE orderId=:orderId
            )
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":orderId",$orderId);
        return $command->queryOne();
    }


    /**
     * 添加随友取消订单
     * @param UserOrderPublisherCancel $userOrderPublisherCancel
     * @throws \yii\db\Exception
     */
    public function addUserOrderPublisherCancel(UserOrderPublisherCancel $userOrderPublisherCancel)
    {
        $sql = sprintf("
            INSERT INTO user_order_publisher_cancel
            (
              orderId,publisherId,cancelTime,content,status
            )
            VALUES
            (
               :orderId,:publisherId,now(),:content,:status
            )
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":orderId", $userOrderPublisherCancel->orderId, PDO::PARAM_INT);
        $command->bindParam(":publisherId", $userOrderPublisherCancel->publisherId, PDO::PARAM_INT);
        $command->bindParam(":content", $userOrderPublisherCancel->content, PDO::PARAM_STR);
        $command->bindParam(":status", $userOrderPublisherCancel->status, PDO::PARAM_INT);

        $command->execute();
    }


    /**
     * 添加评价
     * @param UserOrderComment $userOrderComment
     * @throws \yii\db\Exception
     */
    public function addUserOrderComment(UserOrderComment $userOrderComment)
    {

        $sql = sprintf("
            INSERT INTO user_order_comment
            (
              tripId,userId,orderId,publisherId,content,commentTime,tripScore,publisherScore
            )
            VALUES
            (
              :tripId,:userId,:orderId,:publisherId,:content,now(),:tripScore,:publisherScore
            )
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":tripId", $userOrderComment->tripId, PDO::PARAM_INT);
        $command->bindParam(":userId", $userOrderComment->userId, PDO::PARAM_STR);
        $command->bindParam(":orderId", $userOrderComment->orderId, PDO::PARAM_INT);
        $command->bindParam(":publisherId", $userOrderComment->publisherId, PDO::PARAM_INT);
        $command->bindParam(":content", $userOrderComment->content, PDO::PARAM_STR);
        $command->bindParam(":tripScore", $userOrderComment->tripScore, PDO::PARAM_INT);
        $command->bindParam(":publisherScore", $userOrderComment->tripScore, PDO::PARAM_INT);

        $command->execute();
    }


    /**
     * 根据订单用户 获取评价
     * @param $orderId
     * @return array|bool
     */
    public function findUserOrderCommentByOrderId($orderId)
    {
        $sql = sprintf("
            SELECT * FROM  user_order_comment
            WHERE orderId=:orderId
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":orderId", $orderId, PDO::PARAM_INT);

        return $command->queryOne();
    }


    /**
     * 添加用户账号记录
     * @param UserAccountRecord $userAccountRecord
     * @throws \yii\db\Exception
     */
    public function addUserAccountRecord(UserAccountRecord $userAccountRecord)
    {
        $sql=sprintf("
            INSERT INTO user_account_record
            (
              userId,type,relateId,money,info,recordTime
            )
            VALUES
            (
              :userId,:type,:relateId,:money,:info,now()
            )
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":userId", $userAccountRecord->userId, PDO::PARAM_STR);
        $command->bindParam(":type", $userAccountRecord->type, PDO::PARAM_STR);
        $command->bindParam(":relateId", $userAccountRecord->relateId, PDO::PARAM_INT);
        $command->bindParam(":money", $userAccountRecord->money, PDO::PARAM_STR);
        $command->bindParam(":info", $userAccountRecord->info, PDO::PARAM_STR);

        $command->execute();
    }

    /**
     * 添加用户账户
     * @param UserAccount $userAccount
     */
    public function addUserAccount(UserAccount $userAccount)
    {
        $sql=sprintf("
            INSERT INTO user_account_record
            (
              userId,account,username,type,createTime,updateTime,isDel
            )
            VALUES
            (
              :userId,:account,:username,:type,now(),now(),FALSE
            )
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":userId", $userAccount->userId, PDO::PARAM_STR);
        $command->bindParam(":account", $userAccount->account, PDO::PARAM_STR);
        $command->bindParam(":username", $userAccount->username, PDO::PARAM_INT);
        $command->bindParam(":type", $userAccount->type, PDO::PARAM_STR);

        $command->execute();
    }


    /**
     * 更新用户y
     * @param $userSign
     * @param $balance
     * @param $version
     * @return int
     * @throws \yii\db\Exception
     */
    public function updateUserBaseMoney($userSign,$balance,$version)
    {
        $sql=sprintf("
            UPDATE user_base SET version=version+1,balance=:balance
            WHERE userSign=:userSign AND version=:version
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":userSign", $userSign, PDO::PARAM_STR);
        $command->bindParam(":balance", $balance, PDO::PARAM_STR);
        $command->bindParam(":version", $version, PDO::PARAM_INT);

        return $command->execute();
    }

    /**
     * 获取用户账户余额
     * @param $userSign
     * @return array|bool
     */
    public function getUserMoney($userSign)
    {
        $sql=sprintf("
            SELECT balance,version FROM user_base
            WHERE userSign=:userSign
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":userSign", $userSign, PDO::PARAM_STR);

        return $command->queryOne();
    }


    /**
     * 后台获取订单列表
     * @param Page $page
     * @param $search
     * @param $beginTime
     * @param $endTime
     * @param $status
     * @return Page
     */
    public function getOrderList(Page $page,$search,$beginTime,$endTime,$status)
    {
        $sql=sprintf("
            FROM user_order_info uoi
            LEFT JOIN user_base ub ON uoi.userId=ub.userSign
            WHERE 1=1
        ");

        if(!empty($search)){
            $sql.=' AND  ( uoi.orderNumber=:search OR userPhone=:search OR userNickname like :search  )';
            $this->setParam('search',$search.'%');
        }

        if(!empty($beginTime)&&!empty($endTime)){
            $sql.=' AND  uoi.createTime >=:beginTime AND uoi.endTime <=:endTime';
            $this->setParam('beginTime',$beginTime);
            $this->setParam('endTime',$endTime);
        }else if(empty($beginTime)&&!empty($endTime)){
            $sql.=' AND uoi.endTime <=:endTime';
            $this->setParam('endTime',$endTime);
        }
        else if(!empty($beginTime)&&empty($endTime)){
            $sql.=' AND uoi.beginTime <=:beginTime';
            $this->setParam('beginTime',$beginTime);
        }

        if($status!==""){
            $sql.=' AND uoi.status=:status ';
            $this->setParam('status',$status);
        }
        $this->setSelectInfo(' uoi.*,ub.nickname AS userNickname,ub.phone AS userPhone ');
        $this->setSql($sql);
        return $this->find($page);

    }




}