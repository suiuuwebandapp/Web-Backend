<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/27
 * Time : 下午1:49
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\models;


use common\entity\UserPublisher;
use common\models\ProxyDb;
use yii\db\mssql\PDO;

class UserPublisherDb extends ProxyDb
{
    /**
     * 添加随友
     * @param UserPublisher $userPublisher
     * @return int
     * @throws \yii\db\Exception
     */
    public function addPublisher(UserPublisher $userPublisher)
    {
        $sql = sprintf("
            INSERT INTO user_publisher
            (
              userId,countryId,cityId,lon,lat,idCard,idCardImg,kind,tripCount,leadCount,registerTime,lastUpdateTime,score
            )
            VALUES
            (
              :userId,:countryId,:cityId,:lon,:lat,:idCard,:idCardImg,:kind,0,0,now(),now(),0
            )
        ");

        $command = $this->getConnection()->createCommand($sql);

        $command->bindParam(":userId", $userPublisher->userId, PDO::PARAM_STR);
        $command->bindParam(":countryId", $userPublisher->countryId, PDO::PARAM_INT);
        $command->bindParam(":cityId", $userPublisher->cityId, PDO::PARAM_INT);
        $command->bindParam(":lon", $userPublisher->lon, PDO::PARAM_STR);
        $command->bindParam(":lat", $userPublisher->lat, PDO::PARAM_STR);
        $command->bindParam(":idCard", $userPublisher->idCard, PDO::PARAM_STR);
        $command->bindParam(":idCardImg", $userPublisher->idCardImg, PDO::PARAM_STR);
        $command->bindParam(":kind", $userPublisher->kind, PDO::PARAM_INT);


        return $command->execute();

    }

    /**
     * 更新随友信息表
     * @param UserPublisher $userPublisher
     * @return int
     * @throws \yii\db\Exception
     */
    public function updateUserPublisher(UserPublisher $userPublisher)
    {
        $sql = sprintf("
            UPDATE user_publisher SET
            countryId=:countryId,cityId=:cityId,lon=:lon,lat=:lat,idCard=:idCard,idCardImg=:idCardImg,kind=:kind,lastUpdateTime=now(),score=:score
            WHERE userPublisherId=:userPublisherId

        ");

        $command = $this->getConnection()->createCommand($sql);

        $command->bindParam(":countryId", $userPublisher->countryId, PDO::PARAM_INT);
        $command->bindParam(":cityId", $userPublisher->cityId, PDO::PARAM_INT);
        $command->bindParam(":lon", $userPublisher->lon, PDO::PARAM_STR);
        $command->bindParam(":lat", $userPublisher->lat, PDO::PARAM_STR);
        $command->bindParam(":idCard", $userPublisher->idCard, PDO::PARAM_STR);
        $command->bindParam(":idCardImg", $userPublisher->idCardImg, PDO::PARAM_STR);
        $command->bindParam(":kind", $userPublisher->kind, PDO::PARAM_INT);
        $command->bindParam(":score", $userPublisher->score, PDO::PARAM_STR);

        $command->bindParam(":userPublisherId", $userPublisher->userPublisherId, PDO::PARAM_INT);


        return $command->execute();
    }

    /**
     * 根据Id获取随友详情
     * @param $userPublisherId
     * @return int
     * @throws \yii\db\Exception
     */
    public function findUserPublisherById($userPublisherId)
    {
        $sql = sprintf("
            SELECT * FROM  user_publisher
            WHERE userPublisherId=:userPublisherId

        ");

        $command = $this->getConnection()->createCommand($sql);

        $command->bindParam(":userPublisherId", $userPublisherId, PDO::PARAM_INT);

        return $command->queryOne();
    }

    public function findUserBaseByPublisherId($userPublisherId)
    {
        $sql = sprintf("
            SELECT ub.userId,ub.userSign,ub.nickname,ub.phone,ub.email,ub.areaCode FROM  user_base ub
            LEFT JOIN user_publisher up  ON ub.userSign=up.userId
            WHERE up.userPublisherId=:userPublisherId

        ");

        $command = $this->getConnection()->createCommand($sql);

        $command->bindParam(":userPublisherId", $userPublisherId, PDO::PARAM_INT);

        return $command->queryOne();
    }

    /**
     * 根据用户Id获取随友详情
     * @param $userId
     * @return int
     * @throws \yii\db\Exception
     */
    public function findUserPublisherByUserId($userId)
    {
        $sql = sprintf("
            SELECT * FROM  user_publisher
            WHERE userId=:userId

        ");

        $command = $this->getConnection()->createCommand($sql);

        $command->bindParam(":userId", $userId, PDO::PARAM_STR);

        return $command->queryOne();
    }

    /**
     * 增加随友带队次数
     * @param $userPublisherId
     * @throws \yii\db\Exception
     */
    public function addPublisherLeadCount($userPublisherId)
    {
        $sql = sprintf("
            UPDATE user_publisher SET
            leadCount=leadCount+1
            WHERE userPublisherId=:userPublisherId
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":userPublisherId", $userPublisherId, PDO::PARAM_INT);

        $command->execute();
    }

    /**
     * 增加随游线路数
     * @param $userPublisherId
     * @throws \yii\db\Exception
     */
    public function addPublisherTripCount($userPublisherId)
    {
        $sql = sprintf("
            UPDATE user_publisher SET
            tripCount=tripCount+1
            WHERE $userPublisherId=:$userPublisherId
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":userId", $userPublisherId, PDO::PARAM_STR);

        $command->execute();
    }
}