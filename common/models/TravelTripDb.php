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
use common\components\Code;
use common\entity\TravelTrip;
use common\entity\TravelTripApply;
use common\entity\TravelTripDetail;
use common\entity\TravelTripHighlight;
use common\entity\TravelTripPicture;
use common\entity\TravelTripPrice;
use common\entity\TravelTripPublisher;
use common\entity\TravelTripScenic;
use common\entity\TravelTripService;
use yii\db\mssql\PDO;

class TravelTripDb extends ProxyDb
{



    public function getTripSelectInfo()
    {
        $selectInfo="t.tripId,t.createPublisherId,t.createTime,t.title,t.titleImg,t.countryId,t.cityId,t.lon,t.lat,
        ceil(t.basePrice*".Code::TRIP_SERVICE_PRICE.") AS basePrice,t.basePrice as oldPrice,scheduledTime,
        t.basePriceType,t.maxUserCount,t.score,t.tripCount,t.startTime,t.endTime,t.travelTime,t.travelTimeType,t.intro,t.info,t.tags,
        t.commentCount,t.collectCount,t.isHot,t.type,t.status";

        return $selectInfo;
    }


    /**
     * 获取推荐随游
     * @param Page $page
     * @param $countryId
     * @param $cityId
     * @return Page|null
     */
    public function getRelateRecommendTrip(Page $page, $countryId, $cityId)
    {
        $sql = sprintf("
            FROM travel_trip
            WHERE 1=1
        ");
        $sql .= " AND status=:status";
        $this->setParam("status", TravelTrip::TRAVEL_TRIP_STATUS_NORMAL);
        if (!empty($countryId) && !empty($cityId)) {
            $sql .= " AND (countryId =:countryId or cityId=:cityId) ";
            $this->setParam("cityId", $cityId);
            $this->setParam("countryId", $countryId);
        } else if (!empty($countryId)) {

            $sql .= " AND countryId =:countryId";
            $this->setParam("countryId", $countryId);
        } else if (!empty($cityId)) {
            $sql .= " AND cityId =:cityId";
            $this->setParam("cityId", $cityId);
        }
        $this->setSelectInfo("tripId,title,titleImg,countryId,cityId,ceil(basePrice*".Code::TRIP_SERVICE_PRICE.") AS basePrice,
        basePriceType,score,tripCount,commentCount,collectCount,isHot,type,status ");
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
     * @param $isHot
     * @param $status
     * @param $typeArray
     * @return Page|null
     */
    public function getList($page, $title, $countryId, $cityId, $peopleCount, $startPrice, $endPrice, $tag, $isHot,$typeArray, $status)
    {

        $sql = sprintf("
            FROM travel_trip AS t
            LEFT JOIN user_publisher AS uo ON uo.userPublisherId=t.createPublisherId
            LEFT JOIN user_base AS u ON uo.userId=u.userSign
            LEFT JOIN country AS c ON c.id=t.countryId
            LEFT JOIN city AS ci ON ci.id=t.cityId
            WHERE 1=1
        ");
        $sql .= " AND t.status=:status";
        $this->setParam("status", $status);
        if (!empty($countryId) || !empty($cityId)) {
            if (!empty($countryId)) {
                $sql .= " AND t.countryId in(".$countryId.")";
            }
            if (!empty($cityId)) {
                $sql .= " AND t.cityId in(".$cityId.")";
            }
        } else {
            if (!empty($title)) {
                $sql .= " AND t.title like :title ";
                $this->setParam("title", "%" . $title . "%");
            }
        }
        if (!empty($peopleCount) && $peopleCount != 0) {
            $sql .= " AND t.maxUserCount>=:peopleCount ";
            $this->setParam("peopleCount", $peopleCount);
        }
        if (!empty($isHot) ) {
            $sql .= " AND t.isHot=:isHot ";
            $this->setParam("isHot", $isHot);
        }
        if (!empty($startPrice)) {
            $sql .= " AND t.basePrice>=:startPrice ";
            $this->setParam("startPrice", $startPrice);
        }
        if (!empty($endPrice)) {
            $sql .= " AND t.basePrice<=:endPrice ";
            $this->setParam("endPrice", $endPrice);
        }
        if (!empty($tag)) {
            $sql .= " AND t.tripId IN (";
            $sql .= $tag;
            $sql .= ")";
        }
        if(!empty($typeArray&&count($typeArray)>0)){
            $sql .= " AND t.type IN (";
            $sql .= implode(",",$typeArray);
            $sql .= ")";
        }
        $this->setSql($sql);
        $this->setSelectInfo("t.tripId,t.title,t.titleImg,t.countryId,t.cityId,ceil(t.basePrice*".Code::TRIP_SERVICE_PRICE.") AS basePrice,
        t.basePriceType,t.score,t.tripCount,t.commentCount,t.collectCount,t.isHot,t.type,t.status,
        u.nickname,u.headImg,c.cname,c.ename,ci.cname,ci.ename,u.userSign ");
        return $this->find($page);
    }

    /**
     * 全文检索根据列表
     * @param $page
     * @param $tripIds
     * @return \backend\components\Page|null
     */
    public function getListBySearch($page, $tripIds)
    {

        $sql = sprintf("
            FROM travel_trip AS t
            LEFT JOIN user_publisher AS uo ON uo.userPublisherId=t.createPublisherId
            LEFT JOIN user_base AS u ON uo.userId=u.userSign
            LEFT JOIN country AS c ON c.id=t.countryId
            LEFT JOIN city AS ci ON ci.id=t.cityId
            WHERE 1=1
        ");

        if(!empty($tripIds)){
            $sql .= " AND t.tripId in (" . $tripIds . ")";
        }

        $this->setSql($sql);
        $this->setSelectInfo(" t.tripId,t.title,t.titleImg,t.countryId,t.cityId,ceil(t.basePrice*".Code::TRIP_SERVICE_PRICE.") AS basePrice,
        t.basePriceType,t.score,t.tripCount,t.commentCount,t.collectCount,t.isHot,t.type,t.status,
        u.nickname,u.headImg,c.cname,c.ename,ci.cname,ci.ename ");


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
              createPublisherId,createTime,title,titleImg,countryId,cityId,lon,lat,basePrice,basePriceType,maxUserCount,
              scheduledTime,score,tripCount,startTime,endTime,travelTime,travelTimeType,intro,info,tags,status,type
            )
            VALUES
            (
              :createPublisherId,now(),:title,:titleImg,:countryId,:cityId,:lon,:lat,:basePrice,:basePriceType,:maxUserCount,
              :scheduledTime,:score,0,:startTime,:endTime,:travelTime,:travelTimeType,:intro,:info,:tags,:status,:type
            )
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":createPublisherId", $travelTrip->createPublisherId, PDO::PARAM_INT);
        $command->bindParam(":title", $travelTrip->title, PDO::PARAM_STR);
        $command->bindParam(":titleImg", $travelTrip->titleImg, PDO::PARAM_STR);
        $command->bindParam(":countryId", $travelTrip->countryId, PDO::PARAM_INT);
        $command->bindParam(":cityId", $travelTrip->cityId, PDO::PARAM_INT);
        $command->bindParam(":lon", $travelTrip->lon, PDO::PARAM_STR);
        $command->bindParam(":lat", $travelTrip->lat, PDO::PARAM_STR);
        $command->bindParam(":basePrice", $travelTrip->basePrice, PDO::PARAM_STR);
        $command->bindParam(":basePriceType", $travelTrip->basePriceType, PDO::PARAM_INT);
        $command->bindParam(":maxUserCount", $travelTrip->maxUserCount, PDO::PARAM_INT);
        $command->bindParam(":scheduledTime", $travelTrip->scheduledTime, PDO::PARAM_STR);
        $command->bindParam(":score", $travelTrip->score, PDO::PARAM_STR);
        $command->bindParam(":startTime", $travelTrip->startTime, PDO::PARAM_STR);
        $command->bindParam(":endTime", $travelTrip->endTime, PDO::PARAM_STR);
        $command->bindParam(":travelTime", $travelTrip->travelTime, PDO::PARAM_STR);
        $command->bindParam(":travelTimeType", $travelTrip->travelTimeType, PDO::PARAM_INT);
        $command->bindParam(":intro", $travelTrip->intro, PDO::PARAM_STR);
        $command->bindParam(":info", $travelTrip->info, PDO::PARAM_STR);
        $command->bindParam(":tags", $travelTrip->tags, PDO::PARAM_STR);
        $command->bindParam(":type", $travelTrip->type, PDO::PARAM_STR);
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
            basePriceType=:basePriceType,maxUserCount=:maxUserCount,scheduledTime=:scheduledTime,score=:score,
            tripCount=:tripCount,startTime=:startTime,endTime=:endTime,travelTime=:travelTime,travelTimeType=:travelTimeType,
            intro=:intro,info=:info,tags=:tags,status=:status
            WHERE tripId=:tripId
        ");
        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":title", $travelTrip->title, PDO::PARAM_STR);
        $command->bindParam(":titleImg", $travelTrip->titleImg, PDO::PARAM_STR);
        $command->bindParam(":countryId", $travelTrip->countryId, PDO::PARAM_INT);
        $command->bindParam(":cityId", $travelTrip->cityId, PDO::PARAM_INT);
        $command->bindParam(":lon", $travelTrip->lon, PDO::PARAM_STR);
        $command->bindParam(":lat", $travelTrip->lat, PDO::PARAM_STR);
        $command->bindParam(":basePrice", $travelTrip->basePrice, PDO::PARAM_STR);
        $command->bindParam(":basePriceType", $travelTrip->basePriceType, PDO::PARAM_INT);
        $command->bindParam(":maxUserCount", $travelTrip->maxUserCount, PDO::PARAM_INT);
        $command->bindParam(":scheduledTime", $travelTrip->scheduledTime, PDO::PARAM_INT);
        $command->bindParam(":score", $travelTrip->score, PDO::PARAM_STR);
        $command->bindParam(":tripCount", $travelTrip->tripCount, PDO::PARAM_INT);
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
    public function changeTravelStatus($tripId, $status)
    {
        $sql = sprintf("
            UPDATE  travel_trip SET
            status=:status
            WHERE tripId=:tripId
        ");
        $command = $this->getConnection()->createCommand($sql);
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
        $sql = sprintf("
            SELECT %s,c.cname AS countryCname,c.ename AS countryEname,ci.cname AS cityCname,ci.ename AS cityEname FROM travel_trip AS t
            LEFT JOIN country AS c ON c.id=t.countryId
            LEFT JOIN city AS ci ON ci.id=t.cityId
            WHERE tripId=:tripId
        ",self::getTripSelectInfo());
        $command = $this->getConnection()->createCommand($sql);
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
        $command = $this->getConnection()->createCommand($sql);
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
        $sql = sprintf("
            DELETE FROM travel_trip_picture
            WHERE tripId=:tripId
        ");
        $command = $this->getConnection()->createCommand($sql);

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
        $sql = sprintf("
            SELECT * FROM travel_trip_picture
            WHERE tripId=:tripId
        ");
        $command = $this->getConnection()->createCommand($sql);

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
        $command = $this->getConnection()->createCommand($sql);
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
        $sql = sprintf("
            DELETE FROM travel_trip_price
            WHERE tripId=:tripId
        ");
        $command = $this->getConnection()->createCommand($sql);

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
        $sql = sprintf("
            SELECT priceId,tripId,minCount,maxCount,ceil(price * ".Code::TRIP_SERVICE_PRICE.") AS price,price As oldPrice FROM travel_trip_price
            WHERE tripId=:tripId
        ");
        $command = $this->getConnection()->createCommand($sql);

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
        $command = $this->getConnection()->createCommand($sql);
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
        $sql = sprintf("
            DELETE FROM travel_trip_publisher
            WHERE tripId=:tripId
        ");
        $command = $this->getConnection()->createCommand($sql);

        $command->bindParam(":tripId", $tripId, PDO::PARAM_INT);
        $command->execute();
    }

    /**
     * 批量删除随游特殊亮点
     * @param $tripId
     * @throws \yii\db\Exception
     */
    public function deleteTravelTripSpecialByTripId($tripId)
    {
        $sql = sprintf("
            DELETE FROM travel_trip_special
            WHERE tripId=:tripId
        ");
        $command = $this->getConnection()->createCommand($sql);

        $command->bindParam(":tripId", $tripId, PDO::PARAM_INT);
        $command->execute();
    }

    /**
     * 获取随游特殊亮点列表
     * @param $tripId
     * @return array
     */
    public function getTravelTripSpecialList($tripId)
    {
        $sql = sprintf("
            SELECT * FROM travel_trip_special
            WHERE tripId=:tripId
        ");
        $command = $this->getConnection()->createCommand($sql);

        $command->bindParam(":tripId", $tripId, PDO::PARAM_INT);
        return $command->queryAll();
    }

    /**
     * 查找随游随友列表
     * @param $tripId
     * @return array
     */
    public function getTravelTripPublisherList($tripId)
    {
        $sql = sprintf("
            SELECT  ub.nickname,ub.phone,ub.areaCode,ub.email,ub.sex,ub.birthday,ub.headImg,ub.hobby,ub.countryId,ub.cityId,
            ub.profession,ub.school,ub.intro,ub.info,ub.travelCount,ub.userSign,t.*,cit.cname as cityName,cty.cname as countryName
            FROM travel_trip_publisher t
            LEFT JOIN user_publisher up ON up.userPublisherId=t.publisherId
            LEFT JOIN user_base ub ON ub.userSign=up.userId
			LEFT JOIN city cit ON cit.id=ub.cityId
			LEFT JOIN country cty ON cty.id=ub.countryId
            WHERE t.tripId=:tripId
        ");
        $command = $this->getConnection()->createCommand($sql);

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
        $command = $this->getConnection()->createCommand($sql);
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
        $sql = sprintf("
            DELETE FROM travel_trip_scenic
            WHERE tripId=:tripId
        ");
        $command = $this->getConnection()->createCommand($sql);

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
        $sql = sprintf("
            SELECT * FROM travel_trip_scenic
            WHERE tripId=:tripId
        ");
        $command = $this->getConnection()->createCommand($sql);

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
        $command = $this->getConnection()->createCommand($sql);
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
        $sql = sprintf("
            DELETE FROM travel_trip_service
            WHERE tripId=:tripId
        ");
        $command = $this->getConnection()->createCommand($sql);

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
        $sql = sprintf("
            SELECT serviceId,tripId,title,money as oldMoney,ceil(money*".Code::TRIP_SERVICE_PRICE.") as money,type FROM travel_trip_service
            WHERE tripId=:tripId
        ");
        $command = $this->getConnection()->createCommand($sql);

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
        $sql = sprintf("
            INSERT INTO travel_trip_apply
            (
              tripId,publisherId,sendTime,info,status
            )
            VALUES
            (
              :tripId,:publisherId,now(),:info,:status
            )
        ");
        $command = $this->getConnection()->createCommand($sql);

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
        $sql = sprintf("
            SELECT * FROM travel_trip_apply
            WHERE applyId=:applyId
        ");
        $command = $this->getConnection()->createCommand($sql);

        $command->bindParam(":applyId", $applyId, PDO::PARAM_INT);
        return $command->queryOne();
    }

    /**
     * 改变申请加入随游状态
     * @param $applyId
     * @param $status
     * @throws \yii\db\Exception
     */
    public function changeTravelTripApplyStatus($applyId, $status)
    {
        $sql = sprintf("
            UPDATE travel_trip_apply SET
            status=:status
            WHERE applyId=:applyId
        ");

        $command = $this->getConnection()->createCommand($sql);

        $command->bindParam(":applyId", $applyId, PDO::PARAM_INT);
        $command->bindParam(":status", $status, PDO::PARAM_INT);

        $command->execute();
    }

    /**
     * 获取随游申请列表(根据状态)
     * @param $tripId
     * @param $status
     */
    public function getTelTripApplyList($tripId, $status)
    {
        $sql = sprintf("
            FROM travel_trip_apply
            WHERE tripId=:tripId
        ");

        $this->setParam("tripId", $tripId);
        if (!empty($tripId)) {
            $this->setParam("status", $status);
            $sql .= " AND status=:status";
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
        $sql = sprintf("
            DELETE FROM travel_trip_publisher
            WHERE tripId=:tripId AND tripPublisherId=:tripPublisherId
        ");

        $command = $this->getConnection()->createCommand($sql);

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
        $sql = sprintf("
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
            WHERE t.createPublisherId=:createPublisherId AND t.status!=:tripStatus
            ORDER BY t.status ASC
        ");
        $this->setParam("createPublisherId", $createPublisherId);
        $this->setParam("status", TravelTripApply::TRAVEL_TRIP_APPLY_STATUS_WAIT);
        $this->setParam("tripStatus", TravelTrip::TRAVEL_TRIP_STATUS_DELETE);

        $this->setSql($sql);
        $this->setSelectInfo(self::getTripSelectInfo().",u.nickname,u.headImg,apply.count,service.names,u.userSign");

        return $this->findListBySql();
    }

    /**
     * 获取某个随友加入的随游
     * @param $publisherId
     * @return array
     */
    public function getMyJoinTripList($publisherId)
    {
        $sql = sprintf("
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
        $this->setParam("publisherId", $publisherId);
        $this->setParam("status", TravelTripApply::TRAVEL_TRIP_APPLY_STATUS_WAIT);
        $this->setParam("tripStatus", TravelTrip::TRAVEL_TRIP_STATUS_NORMAL);

        $this->setSql($sql);
        $this->setSelectInfo(self::getTripSelectInfo().",u.nickname,u.headImg,apply.count,service.names");

        return $this->findListBySql();
    }

    /**
     * 获取随友申请加入列表
     * @param $tripId
     * @return array
     */
    public function getPublisherApplyList($tripId)
    {
        $sql = sprintf("
            SELECT up.*,tta.* ,ub.nickname,ub.phone,ub.areaCode,ub.email,ub.sex,ub.birthday,ub.headImg,ub.hobby,ub.userSign,
            ub.profession,ub.school,ub.intro,ub.cityId,ub.countryId,ub.travelCount
            FROM user_publisher up
            LEFT JOIN user_base ub ON ub.userSign=up.userId
            LEFT JOIN travel_trip_apply tta ON tta.publisherId=up.userPublisherId
            WHERE tta.tripId=:tripId AND tta.status=:status
        ");

        $command = $this->getConnection()->createCommand($sql);

        $command->bindParam(":tripId", $tripId, PDO::PARAM_INT);
        $command->bindValue(":status", TravelTripApply::TRAVEL_TRIP_APPLY_STATUS_WAIT, PDO::PARAM_INT);

        return $command->queryAll();
    }

    /**
     * 添加随游被参与次数
     * @param $tripId
     * @throws \yii\db\Exception
     */
    public function addTravelTripCount($tripId)
    {
        $sql = sprintf("
            UPDATE travel_trip SET
            tripCount=tripCount+1
            WHERE tripId=:tripId
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":tripId", $tripId, PDO::PARAM_INT);

        $command->execute();
    }


    /**
     * 添加评论次数
     * @param $tripId
     * @throws \yii\db\Exception
     */
    public function addCommentCount($tripId)
    {
        $sql = sprintf("
            UPDATE travel_trip SET
            commentCount=commentCount+1
            WHERE tripId=:tripId
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":tripId", $tripId, PDO::PARAM_INT);
        $command->execute();
    }

    /**
     * 添加收藏次数
     * @param $tripId
     * @throws \yii\db\Exception
     */
    public function addCollectCount($tripId)
    {
        $sql = sprintf("
            UPDATE travel_trip SET
            collectCount=collectCount+1
            WHERE tripId=:tripId
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":tripId", $tripId, PDO::PARAM_INT);
        $command->execute();
    }

    /**
     * 减少收藏次数
     * @param $tripId
     * @throws \yii\db\Exception
     */
    public function removeCollectCount($tripId)
    {

        $sql = sprintf("
            UPDATE travel_trip SET
            collectCount=collectCount-1
            WHERE tripId=:tripId
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":tripId", $tripId, PDO::PARAM_INT);
        $command->execute();

    }

    /**
     * 获取是否有申请正在等待审核
     * @param $tripId
     * @param $publisherId
     * @return array|bool
     */
    public function findTravelTripApplyByTripIdAndUser($tripId, $publisherId)
    {
        $sql = sprintf("
            SELECT * FROM travel_trip_apply
            WHERE tripId=:tripId AND publisherId=:publisherId AND status=:status
        ");

        $command = $this->getConnection()->createCommand($sql);

        $command->bindParam(":tripId", $tripId, PDO::PARAM_INT);
        $command->bindParam(":publisherId", $publisherId, PDO::PARAM_INT);
        $command->bindValue(":status", TravelTripApply::TRAVEL_TRIP_APPLY_STATUS_WAIT, PDO::PARAM_INT);

        return $command->queryOne();
    }

    /**
     * 更新随游封面图
     * @param $tripId
     * @param $titleImg
     * @throws \yii\db\Exception
     */
    public function updateTravelTripTitleImg($tripId, $titleImg)
    {
        $sql = sprintf("
            UPDATE travel_trip SET titleImg=:titleImg
            WHERE tripId=:tripId
        ");

        $command = $this->getConnection()->createCommand($sql);

        $command->bindParam(":tripId", $tripId, PDO::PARAM_INT);
        $command->bindParam(":titleImg", $titleImg, PDO::PARAM_STR);

        $command->execute();
    }

    /**后台得到随游列表
     * @param $page
     * @param $search
     * @param $peopleCount
     * @param $startPrice
     * @param $endPrice
     * @param $status
     * @return Page|null
     */
    public function sysGetList($page, $search, $peopleCount, $startPrice, $endPrice, $status)
    {

        $sql = sprintf("
            FROM travel_trip AS t
            LEFT JOIN user_publisher AS uo ON uo.userPublisherId=t.createPublisherId
            LEFT JOIN user_base AS u ON uo.userId=u.userSign
            LEFT JOIN country AS c ON c.id=t.countryId
            LEFT JOIN city AS ci ON ci.id=t.cityId
            WHERE 1=1
        ");
        if (!empty($status)) {
            $sql .= " AND t.status=:status";
            $this->setParam("status", $status);
        }
        if (!empty($search)) {
            $sql .= " AND ( t.title like :search OR c.cname like :search OR ci.cname like :search OR u.nickname like :search ) ";
            $this->setParam("search", "%" . $search . "%");
        }
        if (!empty($peopleCount) && $peopleCount != 0) {
            $sql .= " AND t.maxUserCount>=:peopleCount ";
            $this->setParam("peopleCount", $peopleCount);
        }
        if (!empty($startPrice)) {
            $sql .= " AND t.basePrice>=:startPrice ";
            $this->setParam("startPrice", $startPrice);
        }
        if (!empty($endPrice)) {
            $sql .= " AND t.basePrice<=:endPrice ";
            $this->setParam("endPrice", $endPrice);
        }
        $this->setSql($sql);
        $this->setSelectInfo(self::getTripSelectInfo().",u.nickname,u.headImg,c.cname,c.ename,ci.cname as ctName,ci.ename as cteName ");

        return $this->find($page);
    }

    /**
     * 获取随游评论
     * @param $page
     * @param $search
     * @return Page|null
     */
    public function getComment($page, $search)
    {
        $sql = sprintf("
            FROM travel_trip_comment a
            LEFT JOIN travel_trip b ON a.tripId=b.tripId
            LEFT JOIN user_base c ON a.userSign = c.userSign
            WHERE 1=1
        ");
        if (!empty($search)) {
            $sql .= " AND ( b.title like :search OR c.nickname like :search OR a.content like :search ) ";
            $this->setParam("search", "%" . $search . "%");
        }
        $this->setSql($sql);
        $this->setSelectInfo("a.*,b.title,c.nickname");
        return $this->find($page);
    }


    /**
     * 删除随游评论
     * @param $id
     * @throws \yii\db\Exception
     */
    public function deleteComment($id)
    {
        $sql = sprintf("
            DELETE FROM travel_trip_comment
            WHERE commentId=:commentId
        ");
        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":commentId", $id, PDO::PARAM_INT);
        $command->execute();
    }


    /**
     * 添加随游明细
     * @param TravelTripDetail $travelTripDetail
     * @throws \yii\db\Exception
     */
    public function addTravelTripDetail(TravelTripDetail $travelTripDetail)
    {
        $sql = sprintf("
            INSERT INTO travel_trip_detail
            (
              tripId,name,type
            )
            VALUES
            (
              :tripId,:name,:type
            )
        ");

        $command = $this->getConnection()->createCommand($sql);

        $command->bindParam(":tripId", $travelTripDetail->tripId, PDO::PARAM_INT);
        $command->bindParam(":name", $travelTripDetail->name, PDO::PARAM_STR);
        $command->bindParam(":type", $travelTripDetail->type, PDO::PARAM_STR);

        $command->execute();
    }

    /**
     * 删除随游相关的明细
     * @param $tripId
     * @throws \yii\db\Exception
     */
    public function deleteTravelTripDetailByTripId($tripId)
    {
        $sql = sprintf("
            DELETE FROM travel_trip_detail
            WHERE tripId=:tripId
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":tripId", $tripId, PDO::PARAM_INT);
        $command->execute();
    }

    /**
     * 添加随游亮点
     * @param TravelTripHighlight $travelTripHighlight
     * @throws \yii\db\Exception
     */
    public function addTravelTripHighLight(TravelTripHighlight $travelTripHighlight)
    {
        $sql = sprintf("
            INSERT INTO travel_trip_highlight
            (
              tripId,value
            )
            VALUES
            (
              :tripId,:value
            )
        ");

        $command = $this->getConnection()->createCommand($sql);

        $command->bindParam(":tripId", $travelTripHighlight->tripId, PDO::PARAM_INT);
        $command->bindParam(":value", $travelTripHighlight->value, PDO::PARAM_STR);

        $command->execute();
    }

    /**
     * 删除随游相关的亮点
     * @param $tripId
     * @throws \yii\db\Exception
     */
    public function deleteTravelTripHighLightByTripId($tripId)
    {
        $sql = sprintf("
            DELETE FROM travel_trip_highlight
            WHERE tripId=:tripId
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":tripId", $tripId, PDO::PARAM_INT);
        $command->execute();
    }


    /**
     * 获取明细根据随游Id
     * @param $tripId
     * @return array
     */
    public function getTravelTripDetailList($tripId)
    {
        $sql = sprintf("
            SELECT * FROM travel_trip_detail
            WHERE tripId=:tripId
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":tripId", $tripId, PDO::PARAM_INT);
        return $command->queryAll();
    }

    /**
     * 获取亮点 根据随游Id
     * @param $tripId
     * @return array
     */
    public function getTravelTripHighlightList($tripId)
    {
        $sql = sprintf("
            SELECT * FROM travel_trip_highlight
            WHERE tripId=:tripId
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":tripId", $tripId, PDO::PARAM_INT);
        return $command->queryAll();
    }

    /**
     * 获取用户推荐信息
     * @param $tripId
     * @return array|bool
     */
    public function findTravelTripRecommendByTripId($tripId)
    {
        $sql = sprintf("
            SELECT ub.userSign,ub.nickname,ub.headImg,ttr.content FROM travel_trip_recommend ttr
            LEFT JOIN user_base ub ON ttr.userId=ub.userSign
            WHERE ttr.tripId=:tripId
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":tripId", $tripId, PDO::PARAM_INT);

        return $command->queryOne();
    }


    /**
     * 更新随游评分和随游次数
     * @param $tripId
     * @param $score
     * @param $tripCount
     * @param $isHot
     * @param $type
     * @return int
     * @throws \yii\db\Exception
     */
    public function updateTravelTripBase($tripId, $score, $tripCount,$isHot,$type)
    {
        $sql = sprintf("
            UPDATE travel_trip SET score=:score , tripCount=:tripCount,isHot=:isHot,type=:type
            WHERE tripId=:tripId
        ");
        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":tripId", $tripId, PDO::PARAM_INT);
        $command->bindParam(":score", $score, PDO::PARAM_INT);
        $command->bindParam(":tripCount", $tripCount, PDO::PARAM_INT);
        $command->bindParam(":isHot", $isHot, PDO::PARAM_INT);
        $command->bindParam(":type", $type, PDO::PARAM_INT);

        return $command->execute();
    }


    /**
     * 获取随游目的地 和 目的地数量
     * @param Page $page
     * @param $search
     * @return Page|null
     */
    public function getTripDesSearchList(Page $page,$search)
    {
        $sql = sprintf("
            FROM
            (
                SELECT c.*,t.count FROM  (SELECT countryId,COUNT(tripId) as count FROM travel_trip WHERE status=:status GROUP BY countryId HAVING count>0  ) t

                LEFT JOIN country c ON c.id=t.countryId

                UNION

                SELECT c.*,t.count FROM  (SELECT cityId,COUNT(tripId) as count FROM travel_trip WHERE status=:status GROUP by cityId HAVING count>0  ) t

                LEFT JOIN city c ON c.id=t.cityId
            )  AS s
            WHERE 1=1 ORDER BY s.count
        ");
        $this->setParam("status", TravelTrip::TRAVEL_TRIP_STATUS_NORMAL);
        if (!empty($search)) {
            $sql .= " AND ( s.cname like :search OR  s.ename like :search) ";
            $this->setParam("search", "%" . $search . "%");
        }
        $this->setSql($sql);

        return $this->find($page);
    }


}