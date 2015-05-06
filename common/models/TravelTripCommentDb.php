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
             userSign,content,replayCommentId,supportCount,opposeCount,cTime,tripId,isTravel
            )
            VALUES
            (
              :userSign,:content,:replayCommentId,:supportCount,:opposeCount,now(),:tripId,:isTravel
            )
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":userSign", $TravelTripComment->userSign, PDO::PARAM_STR);
        $command->bindParam(":content", $TravelTripComment->content, PDO::PARAM_STR);
        $command->bindParam(":replayCommentId", $TravelTripComment->replayCommentId, PDO::PARAM_INT);
        $command->bindParam(":supportCount", $TravelTripComment->supportCount, PDO::PARAM_INT);
        $command->bindParam(":opposeCount", $TravelTripComment->opposeCount, PDO::PARAM_INT);
        $command->bindParam(":tripId",$TravelTripComment->tripId, PDO::PARAM_INT);
        $command->bindParam(":isTravel",$TravelTripComment->isTravel, PDO::PARAM_INT);

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


    /**得到评论列表8代表相对类型为目的地评论支持或反对 status 1 支持 2是反对
     * @param $tripId
     * @param $userSign
     * @param $page
     * @return array
     */
    public function getCommentListByTripId($tripId,$page,$userSign)
    {

        $sql=sprintf("
        FROM travel_trip_comment a
LEFT JOIN user_base c ON c.userSign=a.userSign
LEFT JOIN (SELECT * FROM user_attention bd WHERE bd.userSign=:userSign AND bd.relativeType=:rType ) b ON a.commentId=b.relativeId
WHERE a.tripId=:tripId AND c.`status`=1 ORDER BY a.commentId DESC
        ");
        $this->setParam("tripId", $tripId);
        $this->setParam("userSign", $userSign);
        $this->setParam("rType", UserAttention::TYPE_COMMENT_FOR_TRAVEL);
        $this->setSelectInfo('a.commentId,a.rTitle,a.content,b.`status`,c.nickname,c.headImg,c.userSign,a.isTravel');
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
}