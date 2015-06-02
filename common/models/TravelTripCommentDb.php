<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/6
 * Time: 下午4:17
 */
namespace common\models;

use common\entity\TravelTripComment;
use common\entity\UserAttention;
use common\entity\UserOrderInfo;
use yii\db\mssql\PDO;

class TravelTripCommentDb extends ProxyDb
{
    /**
     * 添加评论
     * @param TravelTripComment $TravelTripComment
     * @return int
     * @throws \yii\db\Exception
     */
    public function addTripComment(TravelTripComment $TravelTripComment)
    {
        $sql = sprintf("
            INSERT INTO travel_trip_comment
            (
             userSign,content,rTitle,replayCommentId,supportCount,opposeCount,cTime,tripId,isTravel,rUserSign
            )
            VALUES
            (
              :userSign,:content,:rTitle,:replayCommentId,:supportCount,:opposeCount,now(),:tripId,:isTravel,:rUserSign
            )
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":userSign", $TravelTripComment->userSign, PDO::PARAM_STR);
        $command->bindParam(":content", $TravelTripComment->content, PDO::PARAM_STR);
        $command->bindParam(":rTitle", $TravelTripComment->rTitle, PDO::PARAM_STR);
        $command->bindParam(":replayCommentId", $TravelTripComment->replayCommentId, PDO::PARAM_INT);
        $command->bindParam(":supportCount", $TravelTripComment->supportCount, PDO::PARAM_INT);
        $command->bindParam(":opposeCount", $TravelTripComment->opposeCount, PDO::PARAM_INT);
        $command->bindParam(":tripId",$TravelTripComment->tripId, PDO::PARAM_INT);
        $command->bindParam(":isTravel",$TravelTripComment->isTravel, PDO::PARAM_INT);
        $command->bindParam(":rUserSign", $TravelTripComment->rUserSign, PDO::PARAM_STR);

        $command->execute();

    }


    /**
     * 删除评论
     * @param $commentId
     * @param $userSign
     * @return array|bool
     */
    public function deleteCommentById($commentId,$userSign)
    {
        $sql = sprintf("
        DELETE FROM travel_trip_comment WHERE commentId=:commentId AND userSign =:userSign
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":commentId", $commentId, PDO::PARAM_INT);
        $command->bindParam(":userSign", $userSign, PDO::PARAM_STR);
        return $command->execute();
    }


    /**得到评论列表10代表相对类型为随游评论支持或反对 status 1 支持 2是反对
     * @param $tripId
     * @param $userSign
     * @param $page
     * @return array
     */
    public function getCommentListByTripId($tripId,$page,$userSign)
    {
        // LEFT JOIN user_base r ON r.userSign=a.rUserSign r.nickname as

        $sql=sprintf("
            FROM travel_trip_comment a
            LEFT JOIN user_base c ON c.userSign=a.userSign
            LEFT JOIN user_base r ON r.userSign=a.rUserSign
            LEFT JOIN (SELECT * FROM user_attention bd WHERE bd.userSign=:userSign AND bd.relativeType=:rType ) b ON a.commentId=b.relativeId
            LEFT JOIN
            (
                SELECT userId,count(orderId) as travelCount FROM user_order_info WHERE userId=:userSign AND tripId=:tripId
                AND
                (
                  status=" . UserOrderInfo::USER_ORDER_STATUS_PLAY_SUCCESS . "
                  OR status=" . UserOrderInfo::USER_ORDER_STATUS_PLAY_FINISH . "
                )
            ) AS o ON o.userId=a.userSign
            WHERE a.tripId=:tripId AND c.`status`=1 ORDER BY a.commentId DESC
        ");
        $this->setParam("tripId", $tripId);
        $this->setParam("userSign", $userSign);
        $this->setParam("rType", UserAttention::TYPE_COMMENT_FOR_TRAVEL);
        $this->setSelectInfo('a.commentId,r.nickname as rTitle,a.content,b.`status`,c.nickname,c.headImg,c.userSign,a.isTravel,o.travelCount');
        $this->setSql($sql);
        return $this->find($page);
    }

    public function getCommentInfoById($commentId)
    {
        $sql = sprintf("
       SELECT * FROM travel_trip_comment WHERE commentId=:commentId
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":commentId", $commentId, PDO::PARAM_INT);
        return $command->queryOne();
    }
    /**
     * 更新评论支持反对数
     * @param TravelTripComment $Comment
     * @return int
     * @throws \yii\db\Exception
     */
    public function updateCommentSupportNumb(TravelTripComment $Comment)
    {


        $sql = sprintf("
            UPDATE  travel_trip_comment SET
            supportCount=:supportCount,opposeCount=:opposeCount
            WHERE commentId=:commentId
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":supportCount", $Comment->supportCount, PDO::PARAM_INT);
        $command->bindParam(":opposeCount", $Comment->opposeCount, PDO::PARAM_INT);
        $command->bindParam(":commentId", $Comment->commentId, PDO::PARAM_INT);
        return $command->execute();
    }

    /**根据随游和用户 得到用户有没有游玩过
     * @param $userSign
     * @param $tripId
     * @return array|bool
     */
    public function getTravelOrderByUserSign($userSign,$tripId)
    {
        $sql = sprintf("
       SELECT * FROM user_order_info b WHERE b.userId=:userSign AND b.tripId=:tripId  AND (b.`status`= :successStatus OR b.`status`= :finishStatus)
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindValue(":successStatus", UserOrderInfo::USER_ORDER_STATUS_PLAY_SUCCESS, PDO::PARAM_INT);
        $command->bindValue(":finishStatus", UserOrderInfo::USER_ORDER_STATUS_PLAY_FINISH, PDO::PARAM_INT);
        $command->bindParam(":userSign", $userSign, PDO::PARAM_INT);
        $command->bindParam(":tripId", $tripId, PDO::PARAM_INT);
        return $command->queryOne();
    }

    /**得到在那些随游里发言
     * @param $page
     * @param $userSign
     * @return \backend\components\Page|null
     */
    public function getCommentTripId($page,$userSign)
    {
        $sql=sprintf("
            FROM travel_trip_comment a
            LEFT JOIN travel_trip b ON a.tripId=b.tripId
            WHERE a.userSign=:userSign  GROUP BY a.tripId
        ");
        $this->setParam("userSign", $userSign);
        $this->setSelectInfo('b.title,b.titleImg,b.tripId,b.score');
        $this->setSql($sql);
        return $this->find($page);
    }

    //根据用户和随游得到评论
    public function getCommentUserAndTrip($page,$userSign,$tripId)
    {
        $sql=sprintf("
            FROM travel_trip_comment a
            WHERE a.userSign=:userSign AND a.tripId=:tripId
        ");
        $this->setParam("userSign", $userSign);
        $this->setParam("tripId", $tripId);
        $this->setSql($sql);
        return $this->find($page);
    }

    //根据用户得到评论
    public function getCommentByUser($page, $userSign)
    {
        $sql = sprintf("
            FROM travel_trip_comment a
            LEFT JOIN user_base d ON d.userSign =a.userSign
            LEFT JOIN travel_trip b ON a.tripId=b.tripId
            LEFT JOIN user_base c ON c.userSign=a.rUserSign
            WHERE a.userSign = :userSign OR a.rUserSign=:rSign ORDER BY a.commentId DESC
        ");
        $this->setParam("userSign", $userSign);
        $this->setParam("rSign", $userSign);
        $this->setSelectInfo('a.*,b.title,c.nickname as rnickname,c.headImg as rheadImg,d.nickname,d.headImg');
        $this->setSql($sql);
        return $this->find($page);
    }
}