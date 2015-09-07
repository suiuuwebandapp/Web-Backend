<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/29
 * Time : 下午5:43
 * Email: zhangxinmailvip@foxmail.com
 */

namespace backend\models;


use backend\components\Page;
use common\entity\UserBase;
use common\models\ProxyDb;
use yii\db\mssql\PDO;

class UserBaseDb extends ProxyDb
{

    /**
     * 基础查询
     */
    const FIND_USER_BASE_INFO_SELECT='ub.nickname,ub.sex,ub.birthday,ub.headImg,ub.hobby,ub.school,ub.intro,ub.info,ub.travelCount,ub.userSign,
            ub.isPublisher,ub.cityId,ub.countryId,ub.lon,ub.lat,ub.profession,co.cname AS countryCname,
            co.ename AS countryEname,ci.cname AS cityCname,ci.ename AS cityEname,surname,name';

    /**
     * 查找用户（根据邮箱）
     * @param $email
     * @return array|bool
     */
    public function findByEmail($email)
    {
        $sql = sprintf("
            SELECT * FROM user_base WHERE email=:email
        ");
        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":email", $email, PDO::PARAM_STR);

        return $command->queryOne();
    }

    /**
     * 查找用户（根据手机）
     * @param $phone
     * @return array|bool
     */
    public function findByPhone($phone)
    {
        $sql = sprintf("
            SELECT * FROM user_base WHERE phone=:phone
        ");
        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":phone", $phone, PDO::PARAM_STR);

        return $command->queryOne();
    }

    /**
     * 查找用户（根据ID）
     * @param $userId
     * @param null $status
     * @return array|bool
     */
    public function findById($userId, $status = null)
    {
        $sql = sprintf("
            SELECT * FROM user_base WHERE userId=:userId
        ");
        if ($status != null) {
            $sql .= " AND status=:status";
        }
        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":userId", $userId, PDO::PARAM_INT);
        if ($status != null) {
            $command->bindParam(":status", $status, PDO::PARAM_INT);
        }
        return $command->queryOne();
    }

    /**
     * 查找用户（根据用户标示）
     * @param $userSign
     * @param null $status
     * @return array|bool
     */
    public function findByUserSign($userSign, $status = null)
    {
        $sql = sprintf("
            SELECT * FROM user_base WHERE userSign=:userSign
        ");
        if ($status != null) {
            $sql .= " AND status=:status";
        }
        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":userSign", $userSign, PDO::PARAM_STR);
        if ($status != null) {
            $command->bindParam(":status", $status, PDO::PARAM_INT);
        }

        return $command->queryOne();
    }


    /**
     * 根据UserSign获取用户基本信息
     * @param $userSign
     * @return array|bool
     */
    public function findBaseInfoBySign($userSign)
    {
        $sql = sprintf("
            SELECT ub.nickname,ub.sex,ub.birthday,ub.headImg,ub.hobby,ub.school,ub.intro,ub.info,ub.travelCount,ub.userSign,
            ub.isPublisher,ub.cityId,ub.countryId,ub.lon,ub.lat,ub.profession,co.cname AS countryCname,
            co.ename AS countryEname,ci.cname AS cityCname,ci.ename AS cityEname
            FROM user_base ub
            LEFT JOIN country AS co ON co.id=ub.countryId
            LEFT JOIN city AS ci ON ci.id=ub.cityId
            WHERE userSign=:userSign AND status=:status
        ");
        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":userSign", $userSign, PDO::PARAM_STR);
        $command->bindValue(":status", UserBase::USER_STATUS_NORMAL, PDO::PARAM_INT);

        return $command->queryOne();

    }

    /**
     * 查找用户（根据用户标示）
     * @param $userSign
     * @param int $status
     * @return array|bool
     */
    public function findBaseAllBySign($userSign, $status = 1)
    {
        $sql = sprintf("
            SELECT *
            FROM user_base WHERE userSign=:userSign
        ");
        if ($status != null) {
            $sql .= " AND status=:status";
        }
        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":userSign", $userSign, PDO::PARAM_STR);
        if ($status != null) {
            $command->bindParam(":status", $status, PDO::PARAM_INT);
        }

        return $command->queryOne();
    }

    /**
     * 更新用户基本信息
     * @param UserBase $userBase
     * @return int
     * @throws \yii\db\Exception
     */
    public function updateUserBase(UserBase $userBase)
    {
        $sql = sprintf("
            UPDATE user_base SET
            nickname=:nickname,phone=:phone,areaCode=:areaCode,email=:email,lastLoginIp=:lastLoginIp,sex=:sex,profession=:profession,
            birthday=:birthday,headImg=:headImg,hobby=:hobby,school=:school,intro=:intro,info=:info,isPublisher=:isPublisher,
            countryId=:countryId,cityId=:cityId,lon=:lon,lat=:lat
            WHERE userId=:userId
        ");

        $command = $this->getConnection()->createCommand($sql);

        $command->bindParam(":phone", $userBase->phone, PDO::PARAM_STR);
        $command->bindParam(":areaCode", $userBase->areaCode, PDO::PARAM_STR);
        $command->bindParam(":email", $userBase->email, PDO::PARAM_STR);
        $command->bindParam(":nickname", $userBase->nickname, PDO::PARAM_STR);
        $command->bindParam(":lastLoginIp", $userBase->lastLoginIp, PDO::PARAM_STR);
        $command->bindParam(":sex", $userBase->sex, PDO::PARAM_INT);
        $command->bindParam(":birthday", $userBase->birthday, PDO::PARAM_STR);
        $command->bindParam(":headImg", $userBase->headImg, PDO::PARAM_STR);
        $command->bindParam(":hobby", $userBase->hobby, PDO::PARAM_STR);
        $command->bindParam(":profession", $userBase->profession, PDO::PARAM_STR);
        $command->bindParam(":school", $userBase->school, PDO::PARAM_STR);
        $command->bindParam(":intro", $userBase->intro, PDO::PARAM_STR);
        $command->bindParam(":info", $userBase->info, PDO::PARAM_STR);
        $command->bindParam(":isPublisher", $userBase->isPublisher, PDO::PARAM_INT);
        $command->bindParam(":countryId", $userBase->countryId, PDO::PARAM_INT);
        $command->bindParam(":cityId", $userBase->cityId, PDO::PARAM_INT);
        $command->bindParam(":lon", $userBase->lon, PDO::PARAM_STR);
        $command->bindParam(":lat", $userBase->lat, PDO::PARAM_STR);
        $command->bindParam(":userId", $userBase->userId, PDO::PARAM_INT);

        return $command->execute();
    }

    /**
     * 更新密码
     * @param UserBase $userBase
     * @return int
     * @throws \yii\db\Exception
     */
    public function updatePassword(UserBase $userBase)
    {
        $sql = sprintf("
            UPDATE user_base SET
            password=:password
            WHERE userSign=:userSign
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":password", $userBase->password, PDO::PARAM_STR);
        $command->bindParam(":userSign", $userBase->userSign, PDO::PARAM_INT);

        return $command->execute();
    }


    /**
     * 批量查找用户基本信息
     * @param $userIds
     * @return array
     */
    public function getUserBaseByUserIds($userIds)
    {
        $sql=sprintf("
            SELECT nickname,areaCode,sex,birthday,headImg,hobby,school,intro,info,travelCount,userSign,isPublisher,
            cityId,countryId,lon,lat,profession
            FROM user_base
            WHERE userSign in (".$userIds.");
        ");

        $command = $this->getConnection()->createCommand($sql);

        return $command->queryAll();

    }


    /**
     * 获取用户列表
     * @param Page $page
     * @param $search
     * @return Page|null
     */
    public function getUserBaseListByPage(Page $page,$search)
    {
        $sql=sprintf("
            FROM user_base ub
            LEFT JOIN user_recommend ur ON ub.userSign=ur.userId
            WHERE 1=1
        ");

        if(!empty($search)){
            $sql.=" AND (nickname like :search or phone like :search OR email like :search)";
            $this->setParam("search",$search."%");
        }
        $this->setSelectInfo("DISTINCT ub.*,ur.userRecommendId");
        $this->setSql($sql);
        $page=$this->find($page);
        return $page;
    }


    /**
     * 获取推荐用户列表
     * @param Page $page
     * @param $search
     * @return Page|null
     */
    public function getUserRecommendListByPage(Page $page,$search)
    {
        $sql=sprintf("
            FROM user_recommend ur
            LEFT JOIN user_base ub ON ur.userId=ub.userSign
            WHERE 1=1
        ");

        if(!empty($search)){
            $sql.=" AND (nickname like :search or phone like :search OR email like :search)";
            $this->setParam("search",$search."%");
        }
        $this->setSelectInfo("DISTINCT ub.*,ur.userRecommendId");
        $this->setSql($sql);
        $page=$this->find($page);
        return $page;
    }

    /**
     * 根据随友Id 获取用户详情
     * @param $publisherId
     * @return array|bool
     */
    public function findByPublisherId($publisherId)
    {
        $sql = sprintf("
            SELECT up.*,ub.*
            FROM user_base AS ub
            LEFT JOIN user_publisher AS up ON up.userId=ub.userSign
            WHERE up.userPublisherId=:userPublisherId
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":userPublisherId", $publisherId, PDO::PARAM_INT);

        return $command->queryOne();
    }

}