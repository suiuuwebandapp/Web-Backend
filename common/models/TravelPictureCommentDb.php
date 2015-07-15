<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/7/15
 * Time: 上午10:01
 */

namespace common\models;


use common\entity\TravelPictureComment;
use yii\db\mssql\PDO;

class TravelPictureCommentDb extends ProxyDb {

    public function addTravelPictureComment(TravelPictureComment $travelPictureComment)
    {
        $sql = sprintf("
            INSERT INTO tag_list
            (
             tpId,comment,userSign,createTime,supportCount
            )
            VALUES
            (
             :tpId,:comment,:userSign,now(),:supportCount
            )
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":tpId", $travelPictureComment->tpId, PDO::PARAM_STR);
        $command->bindParam(":comment", $travelPictureComment->comment, PDO::PARAM_STR);
        $command->bindParam(":userSign", $travelPictureComment->userSign, PDO::PARAM_STR);
        $command->bindParam(":createTime", $travelPictureComment->createTime, PDO::PARAM_STR);
        $command->bindParam(":supportCount", $travelPictureComment->supportCount, PDO::PARAM_INT);
        $command->execute();
    }




}