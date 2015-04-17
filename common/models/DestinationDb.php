<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/17
 * Time : 上午11:20
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\models;


use backend\entity\DestinationInfo;
use backend\entity\DestinationScenic;
use yii\db\mssql\PDO;

class DestinationDb extends ProxyDb{


    /**
     * 添加目的地详情
     * @param DestinationInfo $destinationInfo
     * @throws \yii\db\Exception
     */
    public function addDestinationInfo(DestinationInfo $destinationInfo)
    {
        $sql = sprintf("
            INSERT INTO destination_info
            (
              countryId,cityId,title,titleImg,createUserId,createTime,lastUpdateTime,status
            )
            VALUES
            (
              :countryId,:cityId,:title,:titleImg,:createUserId,now(),now(),:status
            )
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":countryId", $destinationInfo->countryId, PDO::PARAM_INT);
        $command->bindParam(":cityId", $destinationInfo->cityId, PDO::PARAM_STR);
        $command->bindParam(":title", $destinationInfo->title, PDO::PARAM_STR);
        $command->bindParam(":titleImg", $destinationInfo->titleImg, PDO::PARAM_STR);
        $command->bindParam(":createUserId", $destinationInfo->createUserId, PDO::PARAM_INT);
        $command->bindParam(":status", $destinationInfo->status, PDO::PARAM_INT);

        $command->execute();

    }


    /**
     * 添加目的地景区
     * @param DestinationScenic $destinationScenic
     * @throws \yii\db\Exception
     */
    public function addDestinationScenic(DestinationScenic $destinationScenic)
    {
        $sql = sprintf("
            INSERT INTO destination_scenic
            (
              destinationId,title,titleImg,beginTime,endTime
            )
            VALUES
            (
              :destinationId,:title,:titleImg,:beginTime,:endTime
            )
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":destinationId", $destinationScenic->destinationId, PDO::PARAM_INT);
        $command->bindParam(":title", $destinationScenic->title, PDO::PARAM_STR);
        $command->bindParam(":titleImg", $destinationScenic->titleImg, PDO::PARAM_STR);
        $command->bindParam(":beginTime", $destinationScenic->beginTime, PDO::PARAM_STR);
        $command->bindParam(":endTime", $destinationScenic->endTime, PDO::PARAM_STR);

        $command->execute();
    }


    /**
     * 更新目的地详情
     * @param DestinationInfo $destinationInfo
     * @throws \yii\db\Exception
     */
    public function updateDestinationInfo(DestinationInfo $destinationInfo)
    {
        $sql = sprintf("
            UPDATE  destination_info SET
            (
              countryId=:countryId,cityId=:cityId,title=:title,titleImg=:titleImg,lastUpdateTime=now(),status=:status
            )
            WHERE destinationId=:destinationId

        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":countryId", $destinationInfo->countryId, PDO::PARAM_INT);
        $command->bindParam(":cityId", $destinationInfo->cityId, PDO::PARAM_STR);
        $command->bindParam(":title", $destinationInfo->title, PDO::PARAM_STR);
        $command->bindParam(":titleImg", $destinationInfo->titleImg, PDO::PARAM_STR);
        $command->bindParam(":status", $destinationInfo->status, PDO::PARAM_INT);
        $command->bindParam(":destinationId", $destinationInfo->destinationId, PDO::PARAM_INT);


        $command->execute();

    }


    /**
     * 更新目的地景区
     * @param DestinationScenic $destinationScenic
     * @throws \yii\db\Exception
     */
    public function updateDestinationScenic(DestinationScenic $destinationScenic)
    {
        $sql = sprintf("
            UPDATE destination_scenic SET
            (
              destinationId=:destinationId,title=:title,titleImg=:titleImg,beginTime=:beginTime,endTime=:endTime
            )
            WHERE scenicId=:scenicId;
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":destinationId", $destinationScenic->destinationId, PDO::PARAM_INT);
        $command->bindParam(":title", $destinationScenic->title, PDO::PARAM_STR);
        $command->bindParam(":titleImg", $destinationScenic->titleImg, PDO::PARAM_STR);
        $command->bindParam(":beginTime", $destinationScenic->beginTime, PDO::PARAM_STR);
        $command->bindParam(":endTime", $destinationScenic->endTime, PDO::PARAM_STR);

        $command->bindParam(":scenicId",$scenicId,PDO::PARAM_INT);

        $command->execute();
    }


    /**
     * 根据Id 删除目的地的详情
     * @param $destinationId
     * @throws \yii\db\Exception
     */
    public function deleteDestinationById($destinationId)
    {
        $sql=sprintf("
            DELETE FROM destination_info
            WHERE destinationId=:destinationId
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":destinationId", $destinationId, PDO::PARAM_INT);

        $command->execute();
    }


    /**
     * 根据目的地景点ID 删除景点
     * @param $scenicId
     * @throws \yii\db\Exception
     */
    public function deleteScenicById($scenicId)
    {
        $sql=sprintf("
            DELETE FROM destination_scenic
            WHERE scenicId=:scenicId
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":scenicId", $scenicId, PDO::PARAM_INT);
        $command->execute();

    }


    /**
     * 删除目的地景点，更具目的地Id
     * @param $destinationId
     * @throws \yii\db\Exception
     */
    public function deleteScenicByDesId($destinationId)
    {
        $sql=sprintf("
            DELETE FROM destination_scenic
            WHERE destinationId=:destinationId
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":destinationId", $destinationId, PDO::PARAM_INT);
        $command->execute();

    }


    /**
     * 根据目的地Id获取目的地详情
     * @param $destinationId
     * @return array|bool
     */
    public function findDestinationById($destinationId)
    {
        $sql=sprintf("
            SELECT * FROM destination_info
            WHERE destinationId=:destinationId
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":destinationId", $destinationId, PDO::PARAM_INT);
        return $command->queryOne();
    }

    /**
     * 根据景点Id获取景点内容
     * @param $destinationId
     * @return array|bool
     */
    public function findScenicById($destinationId)
    {
        $sql=sprintf("
            SELECT * FROM destination_scenic
            WHERE scenicId=:scenicId
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":destinationId", $destinationId, PDO::PARAM_INT);
        return $command->queryOne();
    }

}