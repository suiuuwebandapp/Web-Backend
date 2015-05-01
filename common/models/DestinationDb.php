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
use yii\db\mssql\PDO;

class DestinationDb extends ProxyDb{


    /**
     * 获取目的地列表
     * @param Page $page
     * @param $search
     * @param $status
     * @param null $countryId
     * @param null $cityId
     * @return Page
     */
    public function getDesList(Page $page,$search,$status,$countryId=null,$cityId=null)
    {
        $sql=sprintf("
            FROM destination_info i
            LEFT JOIN country co ON co.id=i.countryId
            LEFT JOIN city ci ON ci.id=i.cityId
            WHERE 1=1
        ");

        $selectInfo=sprintf("
            i.*,co.cname as countryCname,co.ename as countryEname,
            ci.cname as cityCname,ci.ename as cityEname
        ");

        $this->setSelectInfo($selectInfo);
        if(!empty($search)){
            $sql.=" AND title like :search ";
           $this->setParam("search",$search."%");
        }
        if(!empty($status)){
            $sql.=" AND status=:status ";
            $this->setParam("status",$status);
        }
        if(!empty($countryId)){
            $sql.=" AND co.id=:countryId ";
            $this->setParam("countryId",$countryId);
        }
        if(!empty($cityId)){
            $sql.=" AND ci.id=:cityId ";
            $this->setParam("cityId",$cityId);
        }
        $this->setSql($sql);
        return $this->find($page);
    }



    /**
     * 获取景区列表
     * @param Page $page
     * @param $desId
     * @param $search
     * @return Page
     */
    public function getScenicList(Page $page,$desId,$search)
    {
        $sql=sprintf("
            FROM destination_scenic
            WHERE destinationId=:desId
        ");
        $this->setParam("desId",$desId);
        if(!empty($search)){
            $sql.=" AND title like :search ";
            $this->setParam("search",$search."%");
        }
        $this->setSql($sql);
        return $this->find($page);
    }

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
              countryId,cityId,title,titleImg,intro,createUserId,createTime,lastUpdateTime,status
            )
            VALUES
            (
              :countryId,:cityId,:title,:titleImg,:intro,:createUserId,now(),now(),:status
            )
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":countryId", $destinationInfo->countryId, PDO::PARAM_INT);
        $command->bindParam(":cityId", $destinationInfo->cityId, PDO::PARAM_INT);
        $command->bindParam(":title", $destinationInfo->title, PDO::PARAM_STR);
        $command->bindParam(":titleImg", $destinationInfo->titleImg, PDO::PARAM_STR);
        $command->bindParam(":intro", $destinationInfo->intro, PDO::PARAM_STR);
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
              destinationId,title,titleImg,intro,beginTime,endTime,lon,lat,address
            )
            VALUES
            (
              :destinationId,:title,:titleImg,:intro,:beginTime,:endTime,:lon,:lat,:address
            )
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":destinationId", $destinationScenic->destinationId, PDO::PARAM_INT);
        $command->bindParam(":title", $destinationScenic->title, PDO::PARAM_STR);
        $command->bindParam(":titleImg", $destinationScenic->titleImg, PDO::PARAM_STR);
        $command->bindParam(":intro", $destinationScenic->intro, PDO::PARAM_STR);
        $command->bindParam(":beginTime", $destinationScenic->beginTime, PDO::PARAM_STR);
        $command->bindParam(":endTime", $destinationScenic->endTime, PDO::PARAM_STR);
        $command->bindParam(":lon", $destinationScenic->lon, PDO::PARAM_STR);
        $command->bindParam(":lat", $destinationScenic->lat, PDO::PARAM_STR);
        $command->bindParam(":address", $destinationScenic->address, PDO::PARAM_STR);


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
            countryId=:countryId,cityId=:cityId,title=:title,intro=:intro,titleImg=:titleImg,lastUpdateTime=now()
            WHERE destinationId=:destinationId

        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":countryId", $destinationInfo->countryId, PDO::PARAM_INT);
        $command->bindParam(":cityId", $destinationInfo->cityId, PDO::PARAM_STR);
        $command->bindParam(":title", $destinationInfo->title, PDO::PARAM_STR);
        $command->bindParam(":titleImg", $destinationInfo->titleImg, PDO::PARAM_STR);
        $command->bindParam(":intro", $destinationInfo->intro, PDO::PARAM_STR);
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
            destinationId=:destinationId,title=:title,titleImg=:titleImg,intro=:intro,beginTime=:beginTime,endTime=:endTime,
            lon=:lon,lat=:lat,address=:address
            WHERE scenicId=:scenicId;
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":destinationId", $destinationScenic->destinationId, PDO::PARAM_INT);
        $command->bindParam(":title", $destinationScenic->title, PDO::PARAM_STR);
        $command->bindParam(":titleImg", $destinationScenic->titleImg, PDO::PARAM_STR);
        $command->bindParam(":intro", $destinationScenic->intro, PDO::PARAM_STR);
        $command->bindParam(":beginTime", $destinationScenic->beginTime, PDO::PARAM_STR);
        $command->bindParam(":endTime", $destinationScenic->endTime, PDO::PARAM_STR);
        $command->bindParam(":lon", $destinationScenic->lon, PDO::PARAM_STR);
        $command->bindParam(":lat", $destinationScenic->lat, PDO::PARAM_STR);
        $command->bindParam(":address", $destinationScenic->address, PDO::PARAM_STR);

        $command->bindParam(":scenicId",$destinationScenic->scenicId,PDO::PARAM_INT);

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
     * @param $scenicId
     * @return array|bool
     */
    public function findScenicById($scenicId)
    {
        $sql=sprintf("
            SELECT * FROM destination_scenic
            WHERE scenicId=:scenicId
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":scenicId", $scenicId, PDO::PARAM_INT);
        return $command->queryOne();
    }


    /**
     * 改变目的地状态
     * @param $desId
     * @param $status
     * @throws \yii\db\Exception
     */
    public function changeStatus($desId,$status)
    {
        $sql = sprintf("
            UPDATE  destination_info SET
            status=:status
            WHERE destinationId=:desId

        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":desId", $desId, PDO::PARAM_INT);
        $command->bindParam(":status", $status, PDO::PARAM_INT);

        $command->execute();
    }

}