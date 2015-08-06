<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/2
 * Time : 下午4:52
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\models;


use common\entity\UserAccess;
use common\models\ProxyDb;
use common\entity\UserBase;
use yii\db\mssql\PDO;


class UserBaseDb extends ProxyDb
{
    /**
     * 添加用户
     * @param UserBase $userBase
     * @return int
     * @throws \yii\db\Exception
     */
    public function addUser(UserBase $userBase)
    {
        $sql = sprintf("
            INSERT INTO user_base
            (
              nickname,password,phone,areaCode,email,registerTime,registerIp,lastLoginTime,lastLoginIp,sex,birthday,surname,
              name,qq,wechat,headImg,hobby,profession,school,intro,info,travelCount,userSign,status,isPublisher,countryId,cityId,lon,lat
            )
            VALUES
            (
              :nickname,:password,:phone,:areaCode,:email,now(),:registerIp,now(),:lastLoginIp,:sex,:birthday,:surname,:name,:qq,:wechat,
              :headImg,:hobby,:profession,:school,:intro,:info,0,:userSign,:status,:isPublisher,:countryId,:cityId,:lon,:lat
            )
        ");

        $command = $this->getConnection()->createCommand($sql);

        $command->bindParam(":password", $userBase->password, PDO::PARAM_STR);
        $command->bindParam(":phone", $userBase->phone, PDO::PARAM_STR);
        $command->bindParam(":areaCode", $userBase->areaCode, PDO::PARAM_STR);
        $command->bindParam(":email", $userBase->email, PDO::PARAM_STR);
        $command->bindParam(":nickname", $userBase->nickname, PDO::PARAM_STR);
        $command->bindParam(":surname", $userBase->surname, PDO::PARAM_STR);
        $command->bindParam(":name", $userBase->name, PDO::PARAM_STR);
        $command->bindParam(":registerIp", $userBase->registerIp, PDO::PARAM_STR);
        $command->bindParam(":lastLoginIp", $userBase->lastLoginIp, PDO::PARAM_STR);
        $command->bindParam(":sex", $userBase->sex, PDO::PARAM_INT);
        $command->bindParam(":birthday", $userBase->birthday, PDO::PARAM_STR);
        $command->bindParam(":headImg", $userBase->headImg, PDO::PARAM_STR);
        $command->bindParam(":hobby", $userBase->hobby, PDO::PARAM_STR);
        $command->bindParam(":profession", $userBase->profession, PDO::PARAM_STR);
        $command->bindParam(":school", $userBase->school, PDO::PARAM_STR);
        $command->bindParam(":qq", $userBase->qq, PDO::PARAM_STR);
        $command->bindParam(":wechat", $userBase->wechat, PDO::PARAM_STR);
        $command->bindParam(":intro", $userBase->intro, PDO::PARAM_STR);
        $command->bindParam(":info", $userBase->info, PDO::PARAM_STR);
        $command->bindParam(":userSign", $userBase->userSign, PDO::PARAM_STR);
        $command->bindParam(":status", $userBase->status, PDO::PARAM_INT);
        $command->bindParam(":isPublisher", $userBase->isPublisher, PDO::PARAM_INT);
        $command->bindParam(":countryId", $userBase->countryid, PDO::PARAM_INT);
        $command->bindParam(":cityId", $userBase->cityId, PDO::PARAM_INT);
        $command->bindParam(":lon", $userBase->lon, PDO::PARAM_STR);
        $command->bindParam(":lat", $userBase->lat, PDO::PARAM_STR);


        return $command->execute();

    }

    /**
     * 查找用户（根据邮箱和密码）
     * @param $email
     * @param $password
     * @return array|bool
     */
    public function findByEmailAndPwd($email, $password)
    {
        $sql = sprintf("
            SELECT * FROM user_base WHERE email=:email AND password=:password
        ");
        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":email", $email, PDO::PARAM_STR);
        $command->bindParam(":password", $password, PDO::PARAM_STR);

        return $command->queryOne();
    }

    /**
     * 查找用户（根据手机和密码）
     * @param $phone
     * @param $password
     * @return array|bool
     */
    public function findByPhoneAndPwd($phone, $password)
    {
        $sql = sprintf("
            SELECT * FROM user_base WHERE phone=:phone AND password=:password
        ");
        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":phone", $phone, PDO::PARAM_STR);
        $command->bindParam(":password", $password, PDO::PARAM_STR);

        return $command->queryOne();
    }

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
    public function findPasswordByUserSign($userSign, $status = null)
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
     * 查找用户（根据用户标示）
     * @param $userSign
     * @param null $status
     * @return array|bool
     */
    public function findByUserSign($userSign, $status = null)
    {
        $sql = sprintf("
            SELECT userId,nickname,surname,name,email,phone,ub.areaCode,sex,birthday,headImg,hobby,school,qq,wechat,intro,info,
            travelCount,registerIp,status,registerTime,lastLoginTime,userSign,isPublisher,ub.cityId,ub.countryId,lon,lat,profession,balance,
            co.cname AS countryCname,co.ename AS countryEname,ci.cname AS cityCname,ci.ename AS cityEname
            FROM user_base AS ub
            LEFT JOIN country AS co ON co.id=ub.countryId
            LEFT JOIN city AS ci ON ci.id=ub.cityId
            WHERE userSign=:userSign
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
            co.ename AS countryEname,ci.cname AS cityCname,ci.ename AS cityEname,surname,name
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
     * @param int|null $status
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
     * 根据openId 接入 类型获取用户Id
     * @param $openId
     * @param $type
     * @return array|bool
     */
    public function findUserByOpenIdAndType($openId, $type)
    {
        $sql = sprintf("
            SELECT userId,nickname,surname,name,qq,wechat,email,phone,areaCode,sex,birthday,headImg,hobby,school,intro,info,travelCount,registerIp,registerTime,lastLoginTime,userSign,status,isPublisher
            FROM user_base WHERE userSign=
            (
              SELECT userId FROM user_access WHERE openId=:openId AND type=:type
            )
        ");
        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":openId", $openId, PDO::PARAM_STR);
        $command->bindParam(":type", $type, PDO::PARAM_STR);

        return $command->queryOne();
    }

    /**
     * 添加用户接入关联
     * @param UserAccess $userAccess
     * @throws \yii\db\Exception
     */
    public function addUserAccess(UserAccess $userAccess)
    {
        $sql = sprintf("
            INSERT INTO user_access
            (
              userId,openId,type
            )VALUES
            (
              :userId,:openId,:type
            )
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":userId", $userAccess->userId, PDO::PARAM_STR);
        $command->bindParam(":openId", $userAccess->openId, PDO::PARAM_STR);
        $command->bindParam(":type", $userAccess->type, PDO::PARAM_INT);

        $command->execute();
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
            birthday=:birthday,headImg=:headImg,hobby=:hobby,school=:school,qq=:qq,wechat=:wechat,intro=:intro,info=:info,isPublisher=:isPublisher,
            countryId=:countryId,cityId=:cityId,lon=:lon,lat=:lat,surname=:surname,name=:name
            WHERE userId=:userId
        ");
        $command = $this->getConnection()->createCommand($sql);

        $command->bindParam(":phone", $userBase->phone, PDO::PARAM_STR);
        $command->bindParam(":areaCode", $userBase->areaCode, PDO::PARAM_STR);
        $command->bindParam(":email", $userBase->email, PDO::PARAM_STR);
        $command->bindParam(":nickname", $userBase->nickname, PDO::PARAM_STR);
        $command->bindParam(":name", $userBase->name, PDO::PARAM_STR);
        $command->bindParam(":surname", $userBase->surname, PDO::PARAM_STR);
        $command->bindParam(":lastLoginIp", $userBase->lastLoginIp, PDO::PARAM_STR);
        $command->bindParam(":sex", $userBase->sex, PDO::PARAM_INT);
        $command->bindParam(":birthday", $userBase->birthday, PDO::PARAM_STR);
        $command->bindParam(":headImg", $userBase->headImg, PDO::PARAM_STR);
        $command->bindParam(":hobby", $userBase->hobby, PDO::PARAM_STR);
        $command->bindParam(":profession", $userBase->profession, PDO::PARAM_STR);
        $command->bindParam(":school", $userBase->school, PDO::PARAM_STR);
        $command->bindParam(":qq", $userBase->qq, PDO::PARAM_STR);
        $command->bindParam(":wechat", $userBase->wechat, PDO::PARAM_STR);
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
     * 更新用户头像
     * @param $userId
     * @param $headImg
     */
    public function uploadHeadImg($userId,$headImg)
    {
        $sql = sprintf("
            UPDATE user_base SET
            headImg=:headImg
            WHERE userId=:userId
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":headImg", $headImg, PDO::PARAM_STR);
        $command->bindParam(":userId", $userId, PDO::PARAM_INT);

        $command->execute();
    }

    /**
     * 批量查找用户基本信息
     * @param $userIds
     * @return array
     */
    public function getUserBaseByUserIds($userIds)
    {
        $sql=sprintf("
            SELECT nickname,surname,name,areaCode,sex,birthday,headImg,hobby,school,intro,info,travelCount,userSign,isPublisher,
            cityId,countryId,lon,lat,profession
            FROM user_base
            WHERE userSign in (".$userIds.");
        ");

        $command = $this->getConnection()->createCommand($sql);

        return $command->queryAll();

    }


    public function addUserTravelCount($userSign){
        $sql = sprintf("
            UPDATE user_base SET
            travelCount=travelCount+1
            WHERE userSign=:userSign
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":userSign", $userSign, PDO::PARAM_STR);

        $command->execute();
    }

    /**
     * 删除用户相册
     * @param $photoId
     * @param $userId
     * @return int
     */
    public function deleteUserPhoto($photoId,$userId)
    {
        $sql = sprintf("
            DELETE FROM user_photo
            WHERE userId=:userId AND photoId=:photoId
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":photoId", $photoId, PDO::PARAM_INT);
        $command->bindParam(":userId", $userId, PDO::PARAM_STR);

        return $command->execute();
    }



}