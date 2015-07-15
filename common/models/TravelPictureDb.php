<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/7/14
 * Time: 下午6:20
 */

namespace common\models;


use common\entity\TravelPicture;
use yii\db\mssql\PDO;

class TravelPictureDb extends ProxyDb {

    public function addTravelPicture(TravelPicture $travelPicture)
    {
        $sql = sprintf("
            INSERT INTO tag_list
            (
             title,contents,picList,country,city,lon,lat,tags,userSign,createTime
            )
            VALUES
            (
            :title,:contents,:picList,:country,:city,:lon,:lat,:tags,:userSign,now()
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
        $command->execute();
        return $this->getConnection()->getLastInsertID();
    }




}