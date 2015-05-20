<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/29
 * Time : 下午2:05
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\models;


use backend\components\Page;
use common\entity\TravelTrip;
use common\entity\TravelTripApply;
use common\entity\TravelTripPicture;
use common\entity\TravelTripPrice;
use common\entity\TravelTripPublisher;
use common\entity\TravelTripScenic;
use common\entity\TravelTripService;
use yii\db\mssql\PDO;

class TravelTripDb extends ProxyDb{


    /**
     * 获取推荐随游
     * @param Page $page
     * @param $countryId
     * @param $cityId
     * @return Page|null
     */
    public function getRelateRecommendTrip(Page $page,$countryId,$cityId)
    {
        $sql=sprintf("
            FROM travel_trip
            WHERE 1=1
        ");
        $sql.=" AND status=:status";
        $this->setParam("status",TravelTrip::TRAVEL_TRIP_STATUS_NORMAL);
        if(!empty($countryId)&&!empty($cityId)){
            $sql.=" AND (countryId =:countryId or cityId=:cityId) ";
            $this->setParam("cityId",$cityId);
            $this->setParam("countryId",$countryId);
        }else if(!empty($countryId)){

            $sql.=" AND countryId =:countryId";
            $this->setParam("countryId",$countryId);
        }else if(!empty($cityId)){
            $sql.=" AND cityId =:cityId";
            $this->setParam("cityId",$cityId);
        }

        $this->setSql($sql);
        return $this->find($page);

    }

    /**
     * 获取随游列表
     * @param $page
     * @param $title
     * @param $countryId
     * @param $cityId
     * @param $peopleCount
     * @param $startPrice
     * @param $endPrice
     * @param $tag
     * @param $status
     * @return Page|null
     */
    public function getList($page,$title,$countryId,$cityId,$peopleCount,$startPrice,$endPrice,$tag,$status)
    {

        $sql=sprintf("
            FROM travel_trip AS t
            LEFT JOIN user_publisher AS uo ON uo.userPublisherId=t.createPublisherId
            LEFT JOIN user_base AS u ON uo.userId=u.userSign
            LEFT JOIN country AS c ON c.id=t.countryId
            LEFT JOIN city AS ci ON ci.id=t.cityId
            WHERE 1=1
        ");
        $sql.=" AND t.status=:status";
        $this->setParam("status",$status);
        if(!empty($title)){
            $sql.=" AND t.title like :title ";
            $this->setParam("title",$title."%");
        }
         if(!empty($countryId)){
             $sql.=" AND t.countryId =:countryId";
             $this->setParam("countryId",$countryId);
         }
        if(!empty($cityId)){
            $sql.=" AND t.cityId =:cityId";
            $this->setParam("cityId",$cityId);
        }
        if(!empty($peopleCount)&&$peopleCount!=0){
            $sql.=" AND t.maxUserCount<=:peopleCount ";
            $this->setParam("peopleCount",$peopleCount);
        }
        if(!empty($startPrice)){
            $sql.=" AND t.basePrice>=:startPrice ";
            $this->setParam("startPrice",$startPrice);
        }
        if(!empty($endPrice)){
            $sql.=" AND t.basePrice<=:endPrice ";
            $this->setParam("endPrice",$endPrice);
        }
        if(!empty($tag)&&$tag!="全部"){
            $sql.=" AND t.tags like :tag ";
            $this->setParam("tag","%".$tag."%");
        }
        $this->setSql($sql);
        $this->setSelectInfo(" t.*,u.nickname,u.headImg,c.cname,c.ename,ci.cname,ci.ename ");

        return $this->find($page);
    }

    /**
     * 全文检索根据列表
     * @param $page
     * @param $tripIds
     * @return \backend\components\Page|null
     */
    public function getListBySearch($page,$tripIds)
    {

        $sql=sprintf("
            FROM travel_trip AS t
            LEFT JOIN user_publisher AS uo ON uo.userPublisherId=t.createPublisherId
            LEFT JOIN user_base AS u ON uo.userId=u.userSign
            LEFT JOIN country AS c ON c.id=t.countryId
            LEFT JOIN city AS ci ON ci.id=t.cityId
            WHERE 1=1
        ");

        $sql.=" AND t.tripId in (".$tripIds.")";

        $this->setSql($sql);
        $this->setSelectInfo(" t.*,u.nickname,u.headImg,c.cname,c.ename,ci.cname,ci.ename ");


        return $this->find($page);
    }

    /**
     * 添加随游
     * @param TravelTrip $travelTrip
     * @throws \yii\db\Exception
     */
    public function addTravelTrip(TravelTrip $travelTrip)
    {
        $sql = sprintf("
            INSERT INTO travel_trip
            (
              createPublisherId,createTime,title,titleImg,countryId,cityId,lon,lat,basePrice,maxUserCount,isAirplane,
              isHotel,score,startTime,endTime,travelTime,travelTimeType,intro,info,tags,status
            )
            VALUES
            (
              :createPublisherId,now(),:title,:titleImg,:countryId,:cityId,:lon,:lat,:basePrice,:maxUserCount,:isAirplane,
              :isHotel,:score,:startTime,:endTime,:travelTime,:travelTimeType,:intro,:info,:tags,:status
            )
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":createPublisherId", $travelTrip->createPublisherId, PDO::PARAM_INT);
        $command->bindParam(":title", $travelTrip->title, PDO::PARAM_STR);
        $command->bindParam(":titleImg", $travelTrip->titleImg, PDO::PARAM_STR);
        $command->bindParam(":countryId", $travelTrip->countryId, PDO::PARAM_INT);
        $command->bindParam(":cityId", $travelTrip->cityId, PDO::PARAM_INT);
        $command->bindParam(":lon", $travelTrip->lon, PDO::PARAM_STR);
        $command->bindParam(":lat", $travelTrip->lat, PDO::PARAM_STR);
        $command->bindParam(":basePrice", $travelTrip->basePrice, PDO::PARAM_STR);
        $command->bindParam(":maxUserCount", $travelTrip->maxUserCount, PDO::PARAM_INT);
        $command->bindParam(":isAirplane", $travelTrip->isAirplane, PDO::PARAM_INT);
        $command->bindParam(":isHotel", $travelTrip->isHotel, PDO::PARAM_INT);
        $command->bindParam(":score", $travelTrip->score, PDO::PARAM_STR);
        $command->bindParam(":startTime", $travelTrip->startTime, PDO::PARAM_STR);
        $command->bindParam(":endTime", $travelTrip->endTime, PDO::PARAM_STR);
        $command->bindParam(":travelTime", $travelTrip->travelTime, PDO::PARAM_STR);
        $command->bindParam(":travelTimeType", $travelTrip->travelTimeType, PDO::PARAM_INT);
        $command->bindParam(":intro", $travelTrip->intro, PDO::PARAM_STR);
        $command->bindParam(":info", $travelTrip->info, PDO::PARAM_STR);
        $command->bindParam(":tags", $travelTrip->tags, PDO::PARAM_STR);
        $command->bindParam(":status", $travelTrip->status, PDO::PARAM_INT);


        $command->execute();
    }

    /**
     * 修改随游
     * @param TravelTrip $travelTrip
     * @throws \yii\db\Exception
     */
    public function updateTravelTrip(TravelTrip $travelTrip)
    {
        $sql = sprintf("
            UPDATE travel_trip SET
            title=:title,titleImg=:titleImg,countryId=:countryId,cityId=:cityId,lon=:lon,lat=:lat,basePrice=:basePrice,
            maxUserCount=:maxUserCount,isAirplane=:isAirplane,isHotel=:isHotel,score=:score,startTime=:startTime,
            endTime=:endTime,travelTime=:travelTime,travelTimeType=:travelTimeType,intro=:intro,info=:info,tags=:tags,
            status=:status
            WHERE tripId=:tripId
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":title", $travelTrip->title, PDO::PARAM_STR);
        $command->bindParam(":titleImg", $travelTrip->titleImg, PDO::PARAM_STR);
        $command->bindParam(":countryId", $travelTrip->countryId, PDO::PARAM_INT);
        $command->bindParam(":cityId", $travelTrip->cityId, PDO::PARAM_INT);
        $command->bindParam(":lon", $travelTrip->lon, PDO::PARAM_STR);
        $command->bindParam(":lat", $travelTrip->lat, PDO::PARAM_STR);
        $command->bindParam(":basePrice", $travelTrip->basePrice, PDO::PARAM_STR);
        $command->bindParam(":maxUserCount", $travelTrip->maxUserCount, PDO::PARAM_INT);
        $command->bindParam(":isAirplane", $travelTrip->isAirplane, PDO::PARAM_INT);
        $command->bindParam(":isHotel", $travelTrip->isHotel, PDO::PARAM_INT);
        $command->bindParam(":score", $travelTrip->score, PDO::PARAM_STR);
        $command->bindParam(":startTime", $travelTrip->startTime, PDO::PARAM_STR);
        $command->bindParam(":endTime", $travelTrip->endTime, PDO::PARAM_STR);
        $command->bindParam(":travelTime", $travelTrip->travelTime, PDO::PARAM_STR);
        $command->bindParam(":travelTimeType", $travelTrip->travelTimeType, PDO::PARAM_INT);
        $command->bindParam(":intro", $travelTrip->intro, PDO::PARAM_STR);
        $command->bindParam(":info", $travelTrip->info, PDO::PARAM_STR);
        $command->bindParam(":tags", $travelTrip->tags, PDO::PARAM_STR);
        $command->bindParam(":status", $travelTrip->status, PDO::PARAM_INT);

        $command->bindParam(":tripId", $travelTrip->tripId, PDO::PARAM_INT);

        $command->execute();
    }

    /**
     * 改变随游状态
     * @param $tripId
     * @param $status
     * @throws \yii\db\Exception
     */
    public function changeTravelStatus($tripId,$status)
    {
        $sql=sprintf("
            UPDATE  travel_trip SET
            status=:status
            WHERE tripId=:tripId
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":status", $status, PDO::PARAM_INT);
        $command->bindParam(":tripId", $tripId, PDO::PARAM_INT);
        $command->execute();
    }

    /**
     * 查看随游详情
     * @param $tripId
     * @return array|bool
     */
    public function findTravelTripById($tripId)
    {
        $sql=sprintf("
            SELECT t.*,c.cname AS countryCname,c.ename AS countryEname,ci.cname AS cityCname,ci.ename AS cityEname FROM travel_trip AS t
            LEFT JOIN country AS c ON c.id=t.countryId
            LEFT JOIN city AS ci ON ci.id=t.cityId
            WHERE tripId=:tripId
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":tripId", $tripId, PDO::PARAM_INT);

        return $command->queryOne();
    }

    /**
     * 添加随游照片
     * @param TravelTripPicture $travelTripPicture
     * @throws \yii\db\Exception
     */
    public function addTravelTripPicture(TravelTripPicture $travelTripPicture)
    {
        $sql = sprintf("
            INSERT INTO travel_trip_picture
            (
              tripId,title,url
            )
            VALUES
            (
              :tripId,:title,:url
            )
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":tripId", $travelTripPicture->tripId, PDO::PARAM_INT);
        $command->bindParam(":title", $travelTripPicture->title, PDO::PARAM_STR);
        $command->bindParam(":url", $travelTripPicture->url, PDO::PARAM_STR);

        $command->execute();
    }

    /**
     * 根据随游批量删除照片
     * @param $tripId
     * @throws \yii\db\Exception
     */
    public function deleteTravelTripPicByTripId($tripId)
    {
        $sql=sprintf("
            DELETE FROM travel_trip_picture
            WHERE tripId=:tripId
        ");
        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":tripId", $tripId, PDO::PARAM_INT);
        $command->execute();
    }

    /**
     * 查找随游照片
     * @param $tripId
     * @return array
     */
    public function getTravelTripPicList($tripId)
    {
        $sql=sprintf("
            SELECT * FROM travel_trip_picture
            WHERE tripId=:tripId
        ");
        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":tripId", $tripId);
        return $command->queryAll();
    }

    /**
     * 添加随游阶梯价格
     * @param TravelTripPrice $travelTripPrice
     * @throws \yii\db\Exception
     */
    public function addTravelTripPrice(TravelTripPrice $travelTripPrice)
    {
        $sql = sprintf("
            INSERT INTO travel_trip_price
            (
              tripId,minCount,maxCount,price
            )
            VALUES
            (
              :tripId,:minCount,:maxCount,:price
            )
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":tripId", $travelTripPrice->tripId, PDO::PARAM_INT);
        $command->bindParam(":minCount", $travelTripPrice->minCount, PDO::PARAM_INT);
        $command->bindParam(":maxCount", $travelTripPrice->maxCount, PDO::PARAM_INT);
        $command->bindParam(":price", $travelTripPrice->price, PDO::PARAM_STR);

        $command->execute();
    }

    /**
     * 根据随游批量删除阶梯价格
     * @param $tripId
     * @throws \yii\db\Exception
     */
    public function deleteTravelTripPriceByTripId($tripId)
    {
        $sql=sprintf("
            DELETE FROM travel_trip_price
            WHERE tripId=:tripId
        ");
        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":tripId", $tripId, PDO::PARAM_INT);
        $command->execute();
    }

    /**
     * 查找随游阶梯价格
     * @param $tripId
     * @return array
     */
    public function getTravelTripPriceList($tripId)
    {
        $sql=sprintf("
            SELECT * FROM travel_trip_price
            WHERE tripId=:tripId
        ");
        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":tripId", $tripId, PDO::PARAM_INT);
        return $command->queryAll();
    }

    /**
     * 添加随游中的随友关联
     * @param TravelTripPublisher $travelTripPublisher
     * @throws \yii\db\Exception
     */
    public function addTravelTripPublisher(TravelTripPublisher $travelTripPublisher)
    {
        $sql = sprintf("
            INSERT INTO travel_trip_publisher
            (
              tripId,publisherId
            )
            VALUES
            (
              :tripId,:publisherId
            )
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":tripId", $travelTripPublisher->tripId, PDO::PARAM_INT);
        $command->bindParam(":publisherId", $travelTripPublisher->publisherId, PDO::PARAM_INT);

        $command->execute();
    }

    /**
     * 根据随游批量删除随友关联
     * @param $tripId
     * @throws \yii\db\Exception
     */
    public function deleteTravelTripPublisherByTripId($tripId)
    {
        $sql=sprintf("
            DELETE FROM travel_trip_publisher
            WHERE tripId=:tripId
        ");
        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":tripId", $tripId, PDO::PARAM_INT);
        $command->execute();
    }

    /**
     * 查找随游随友列表
     * @param $tripId
     * @return array
     */
    public function getTravelTripPublisherList($tripId)
    {
        $sql=sprintf("
            SELECT  ub.nickname,ub.phone,ub.areaCode,ub.email,ub.sex,ub.birthday,ub.headImg,ub.hobby,
            ub.profession,ub.school,ub.intro,ub.info,ub.travelCount,ub.userSign,t.* FROM travel_trip_publisher t
            LEFT JOIN user_publisher up ON up.userPublisherId=t.publisherId
            LEFT JOIN user_base ub ON ub.userSign=up.userId
            WHERE t.tripId=:tripId
        ");
        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":tripId", $tripId, PDO::PARAM_INT);
        return $command->queryAll();
    }

    /**
     * 添加随游中景区
     * @param TravelTripScenic $travelTripScenic
     * @throws \yii\db\Exception
     */
    public function addTravelTripScenic(TravelTripScenic $travelTripScenic)
    {
        $sql = sprintf("
            INSERT INTO travel_trip_scenic
            (
              tripId,name,lon,lat
            )
            VALUES
            (
              :tripId,:name,:lon,:lat
            )
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":tripId", $travelTripScenic->tripId, PDO::PARAM_INT);
        $command->bindParam(":name", $travelTripScenic->name, PDO::PARAM_STR);
        $command->bindParam(":lon", $travelTripScenic->lon, PDO::PARAM_STR);
        $command->bindParam(":lat", $travelTripScenic->lat, PDO::PARAM_STR);

        $command->execute();
    }

    /**
     * 根据随游批量删除景区
     * @param $tripId
     * @throws \yii\db\Exception
     */
    public function deleteTravelTripScenicByTripId($tripId)
    {
        $sql=sprintf("
            DELETE FROM travel_trip_scenic
            WHERE tripId=:tripId
        ");
        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":tripId", $tripId, PDO::PARAM_INT);
        $command->execute();
    }

    /**
     * 查找随游景区列表
     * @param $tripId
     * @return array
     */
    public function getTravelTripScenicList($tripId)
    {
        $sql=sprintf("
            SELECT * FROM travel_trip_scenic
            WHERE tripId=:tripId
        ");
        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":tripId", $tripId, PDO::PARAM_INT);
        return $command->queryAll();
    }

    /**
     * 添加随游中的专项服务
     * @param TravelTripService $travelTripService
     * @throws \yii\db\Exception
     */
    public function addTravelTripService(TravelTripService $travelTripService)
    {
        $sql = sprintf("
            INSERT INTO travel_trip_service
            (
              tripId,title,money,type
            )
            VALUES
            (
              :tripId,:title,:money,:type
            )
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":tripId", $travelTripService->tripId, PDO::PARAM_INT);
        $command->bindParam(":title", $travelTripService->title, PDO::PARAM_STR);
        $command->bindParam(":money", $travelTripService->money, PDO::PARAM_STR);
        $command->bindParam(":type", $travelTripService->type, PDO::PARAM_INT);

        $command->execute();
    }

    /**
     * 根据随游批量删除专项服务
     * @param $tripId
     * @throws \yii\db\Exception
     */
    public function deleteTravelTripServiceByTripId($tripId)
    {
        $sql=sprintf("
            DELETE FROM travel_trip_service
            WHERE tripId=:tripId
        ");
        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":tripId", $tripId, PDO::PARAM_INT);
        $command->execute();
    }

    /**
     * 查找随游专项服务列表
     * @param $tripId
     * @return array
     */
    public function getTravelTripServiceList($tripId)
    {
        $sql=sprintf("
            SELECT * FROM travel_trip_service
            WHERE tripId=:tripId
        ");
        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":tripId", $tripId, PDO::PARAM_INT);
        return $command->queryAll();
    }

    /**
     * 添加随游申请
     * @param TravelTripApply $travelTripApply
     * @throws \yii\db\Exception
     */
    public function addTravelTripApply(TravelTripApply $travelTripApply)
    {
        $sql=sprintf("
            INSERT INTO travel_trip_apply
            (
              tripId,publisherId,sendTime,info,status
            )
            VALUES
            (
              :tripId,:publisherId,now(),:info,:status
            )
        ");
        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":tripId", $travelTripApply->tripId, PDO::PARAM_INT);
        $command->bindParam(":publisherId", $travelTripApply->publisherId, PDO::PARAM_INT);
        $command->bindParam(":info", $travelTripApply->info, PDO::PARAM_STR);
        $command->bindParam(":status", $travelTripApply->status, PDO::PARAM_INT);

        $command->execute();

    }

    /**
     * 获取申请详情
     * @param $applyId
     * @return array|bool
     */
    public function findTravelTripApplyById($applyId)
    {
        $sql=sprintf("
            SELECT * FROM travel_trip_apply
            WHERE applyId=:applyId
        ");
        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":applyId", $applyId, PDO::PARAM_INT);
        return $command->queryOne();
    }

    /**
     * 改变申请加入随游状态
     * @param $applyId
     * @param $status
     * @throws \yii\db\Exception
     */
    public function changeTravelTripApplyStatus($applyId,$status)
    {
        $sql=sprintf("
            UPDATE travel_trip_apply SET
            status=:status
            WHERE applyId=:applyId
        ");

        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":applyId", $applyId, PDO::PARAM_INT);
        $command->bindParam(":status", $status, PDO::PARAM_INT);

        $command->execute();
    }

    /**
     * 获取随游申请列表(根据状态)
     * @param $tripId
     * @param $status
     */
    public function getTelTripApplyList($tripId,$status)
    {
        $sql=sprintf("
            FROM travel_trip_apply
            WHERE tripId=:tripId
        ");

        $this->setParam("tripId",$tripId);
        if(!empty($tripId))
        {
            $this->setParam("status",$status);
            $sql.=" AND status=:status";
        }

        $this->setSql($sql);
        $this->findListBySql();
    }

    /**
     * 删除随友关联
     * @param TravelTripPublisher $travelTripPublisher
     * @throws \yii\db\Exception
     */
    public function deleteTravelTripPublisher(TravelTripPublisher $travelTripPublisher)
    {
        $sql=sprintf("
            DELETE FROM travel_trip_publisher
            WHERE tripId=:tripId AND tripPublisherId:tripPublisherId
        ");

        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":tripId", $travelTripPublisher->tripId, PDO::PARAM_INT);
        $command->bindParam(":tripPublisherId", $travelTripPublisher->tripPublisherId, PDO::PARAM_INT);

        $command->execute();
    }

    /**
     * 获取我的随游列表
     * @param $createPublisherId
     * @return array
     */
    public function getMyTripList($createPublisherId)
    {
        $sql=sprintf("
            FROM travel_trip AS t
            LEFT JOIN user_publisher AS uo ON uo.userPublisherId=t.createPublisherId
            LEFT JOIN user_base AS u ON uo.userId=u.userSign
            LEFT JOIN
            (
                SELECT tta.tripId,COUNT(*) AS count FROM travel_trip_apply tta WHERE tta.status=:status GROUP BY tta.tripId
            ) AS apply
            ON apply.tripId=t.tripId
            LEFT JOIN
            (
                SELECT tts.tripId,GROUP_CONCAT(tts.title) as names FROM travel_trip_service tts GROUP BY tts.tripId
            )
            AS service
            ON service.tripId=t.tripId
            WHERE t.createPublisherId=:createPublisherId AND t.status=:tripStatus
        ");
        $this->setParam("createPublisherId",$createPublisherId);
        $this->setParam("status",TravelTripApply::TRAVEL_TRIP_APPLY_STATUS_WAIT);
        $this->setParam("tripStatus",TravelTrip::TRAVEL_TRIP_STATUS_NORMAL);

        $this->setSql($sql);
        $this->setSelectInfo(" t.*,u.nickname,u.headImg,apply.count,service.names");

        return $this->findListBySql();
    }

    /**
     * 获取某个随友加入的随游
     * @param $publisherId
     * @return array
     */
    public function getMyJoinTripList($publisherId)
    {
        $sql=sprintf("
            FROM travel_trip AS t
            LEFT JOIN user_publisher AS uo ON uo.userPublisherId=t.createPublisherId
            LEFT JOIN user_base AS u ON uo.userId=u.userSign
            LEFT JOIN
            (
                SELECT tta.tripId,COUNT(*) AS count FROM travel_trip_apply tta WHERE tta.status=:status GROUP BY tta.tripId
            ) AS apply
            ON apply.tripId=t.tripId
            LEFT JOIN
            (
                SELECT tts.tripId,GROUP_CONCAT(tts.title) as names FROM travel_trip_service tts GROUP BY tts.tripId
            )
            AS service
            ON service.tripId=t.tripId
            WHERE t.tripId in
			(
				SELECT tripId  FROM travel_trip_publisher
				WHERE publisherId =:publisherId
			)
			AND t.createPublisherId!=:publisherId  AND t.status=:tripStatus
        ");
        $this->setParam("publisherId",$publisherId);
        $this->setParam("status",TravelTripApply::TRAVEL_TRIP_APPLY_STATUS_WAIT);
        $this->setParam("tripStatus",TravelTrip::TRAVEL_TRIP_STATUS_NORMAL);

        $this->setSql($sql);
        $this->setSelectInfo(" t.*,u.nickname,u.headImg,apply.count,service.names");

        return $this->findListBySql();
    }

    /**
     * 获取随友申请加入列表
     * @param $tripId
     * @return array
     */
    public function getPublisherApplyList($tripId)
    {
        $sql=sprintf("
            SELECT up.*,tta.* ,ub.nickname,ub.phone,ub.areaCode,ub.email,ub.sex,ub.birthday,ub.headImg,ub.hobby,
            ub.profession,ub.school,ub.intro,ub.info,ub.cityId,ub.countryId,ub.travelCount
            FROM user_publisher up
            LEFT JOIN user_base ub ON ub.userSign=up.userId
            LEFT JOIN travel_trip_apply tta ON tta.publisherId=up.userPublisherId
            WHERE tta.tripId=:tripId AND tta.status=:status
        ");

        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":tripId", $tripId, PDO::PARAM_INT);
        $command->bindValue(":status", TravelTripApply::TRAVEL_TRIP_APPLY_STATUS_WAIT, PDO::PARAM_INT);

        return $command->queryAll();
    }


}