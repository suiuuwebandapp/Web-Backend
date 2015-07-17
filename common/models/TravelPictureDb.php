<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/7/14
 * Time: 下午6:20
 */

namespace common\models;


use common\entity\TravelPicture;
use common\entity\TravelPictureComment;
use yii\db\mssql\PDO;

class TravelPictureDb extends ProxyDb {

    public function addTravelPicture(TravelPicture $travelPicture)
    {
        $sql = sprintf("
            INSERT INTO travel_picture
            (
             title,contents,picList,country,city,lon,lat,tags,userSign,createTime,commentCount,attentionCount
            )
            VALUES
            (
            :title,:contents,:picList,:country,:city,:lon,:lat,:tags,:userSign,now(),:commentCount,:attentionCount
            )
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":title", $travelPicture->title, PDO::PARAM_STR);
        $command->bindParam(":contents", $travelPicture->contents, PDO::PARAM_STR);
        $command->bindParam(":picList", $travelPicture->picList, PDO::PARAM_STR);
        $command->bindParam(":country", $travelPicture->country, PDO::PARAM_STR);
        $command->bindParam(":city", $travelPicture->city, PDO::PARAM_STR);
        $command->bindParam(":lon", $travelPicture->lon, PDO::PARAM_STR);
        $command->bindParam(":lat", $travelPicture->lat, PDO::PARAM_STR);
        $command->bindParam(":tags", $travelPicture->tags, PDO::PARAM_STR);
        $command->bindParam(":userSign", $travelPicture->userSign, PDO::PARAM_STR);
        $command->bindParam(":commentCount", $travelPicture->commentCount, PDO::PARAM_INT);
        $command->bindParam(":attentionCount", $travelPicture->attentionCount, PDO::PARAM_INT);
        $command->execute();
        return $this->getConnection()->getLastInsertID();
    }

    public function addTravelPictureComment(TravelPictureComment $travelPictureComment)
    {
        $sql = sprintf("
            INSERT INTO travel_picture_comment
            (
             tpId,comment,userSign,createTime,supportCount
            )
            VALUES
            (
             :tpId,:comment,:userSign,now(),:supportCount
            )
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":tpId", $travelPictureComment->tpId, PDO::PARAM_INT);
        $command->bindParam(":comment", $travelPictureComment->comment, PDO::PARAM_STR);
        $command->bindParam(":userSign", $travelPictureComment->userSign, PDO::PARAM_STR);
        $command->bindParam(":supportCount", $travelPictureComment->supportCount, PDO::PARAM_INT);
        $command->execute();
    }

    public function getTravelPictureInfoById($id)
    {
        $sql = sprintf("
            SELECT a.*,b.headImg FROM travel_picture a
            LEFT JOIN user_base b ON a.userSign = b.userSign
            WHERE id=:id;
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":id", $id, PDO::PARAM_INT);
        return $command->queryOne();
    }

    public function getCommentListByTpId($page,$tpId=null,$userSign=null)
    {
        $sql=sprintf("
        FROM travel_picture_comment a LEFT JOIN user_base b ON a.userSign = b.userSign WHERE 1=1
        ");
        if(!empty($tpId))
        {
            $sql.=" AND tpId = :tpId ";
            $this->setParam("tpId",$tpId);
        }
        if(!empty($userSign))
        {
            $sql.=" AND userSign = :userSign ";
            $this->setParam("userSign",$userSign);
        }
        $this->setSelectInfo('a.*,b.headImg');
        $this->setSql($sql);
        return $this->find($page);
    }

    public function getTravelPictureList($page,$tags,$search)
    {
        $sql=sprintf("
        FROM travel_picture a
        LEFT JOIN user_base b ON a.userSign = b.userSign
        WHERE 1=1
        ");
        if(!empty($search))
        {
            $sql.=" AND (country like :search OR city like :search )";
            $this->setParam('search','%'.$search.'%');
        }
        if(!empty($tags)){
            $sql.=" AND id IN (";
            $sql.=$tags;
            $sql.=")";
        }
        $this->setSelectInfo('a.*,b.headImg');
        $this->setSql($sql);
        return $this->find($page);
    }

    public function updateCommentCount($id,$commentCount)
    {
        $sql = sprintf("
            UPDATE travel_picture SET commentCount=:commentCount WHERE id=:id;
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":commentCount", $commentCount, PDO::PARAM_INT);
        $command->bindParam(":id", $id, PDO::PARAM_INT);
        $command->execute();
    }
    public function updateAttentionCount($id,$attentionCount)
    {
        $sql = sprintf("
            UPDATE travel_picture SET attentionCount=:attentionCount WHERE id=:id;
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":attentionCount", $attentionCount, PDO::PARAM_INT);
        $command->bindParam(":id", $id, PDO::PARAM_INT);
        $command->execute();
    }
}