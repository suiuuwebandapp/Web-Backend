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
              nickname,password,phone,areaCode,email,registerTime,registerIp,lastLoginTime,lastLoginIp,sex,birthday,
              headImg,hobby,school,intro,info,travelCount,userSign,status,isPublisher
            )
            VALUES
            (
              :nickname,:password,:phone,:areaCode,:email,now(),:registerIp,now(),:lastLoginIp,:sex,:birthday,
              :headImg,:hobby,:school,:intro,:info,0,:userSign,:status,:isPublisher
            )
        ");

        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":password", $userBase->password, PDO::PARAM_STR);
        $command->bindParam(":phone", $userBase->phone, PDO::PARAM_STR);
        $command->bindParam(":areaCode", $userBase->areaCode, PDO::PARAM_STR);
        $command->bindParam(":email", $userBase->email, PDO::PARAM_STR);
        $command->bindParam(":nickname", $userBase->nickname, PDO::PARAM_STR);
        $command->bindParam(":registerIp", $userBase->registerIp, PDO::PARAM_STR);
        $command->bindParam(":lastLoginIp", $userBase->lastLoginIp, PDO::PARAM_STR);
        $command->bindParam(":sex", $userBase->sex, PDO::PARAM_INT);
        $command->bindParam(":birthday", $userBase->birthday, PDO::PARAM_STR);
        $command->bindParam(":headImg", $userBase->headImg, PDO::PARAM_STR);
        $command->bindParam(":hobby", $userBase->hobby, PDO::PARAM_STR);
        $command->bindParam(":school", $userBase->school, PDO::PARAM_STR);
        $command->bindParam(":intro", $userBase->intro, PDO::PARAM_STR);
        $command->bindParam(":info", $userBase->info, PDO::PARAM_STR);
        $command->bindParam(":userSign", $userBase->userSign, PDO::PARAM_STR);
        $command->bindParam(":status", $userBase->status, PDO::PARAM_INT);
        $command->bindParam(":isPublisher", $userBase->isPublisher, PDO::PARAM_INT);


        return $command->execute();

    }

    /**
     * 查找用户（根据邮箱和密码）
     * @param $email
     * @param $password
     * @return array|bool
     */
    public function findByEmailAndPwd($email,$password)
    {
        $sql=sprintf("
            SELECT * FROM user_base WHERE email=:email AND password=:password
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":email",$email, PDO::PARAM_STR);
        $command->bindParam(":password",$password, PDO::PARAM_STR);

        return $command->queryOne();
    }

    /**
     * 查找用户（根据手机和密码）
     * @param $phone
     * @param $password
     * @return array|bool
     */
    public function findByPhoneAndPwd($phone,$password)
    {
        $sql=sprintf("
            SELECT * FROM user_base WHERE phone=:phone AND password=:password
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":phone",$phone, PDO::PARAM_STR);
        $command->bindParam(":password",$password, PDO::PARAM_STR);

        return $command->queryOne();
    }

    /**
     * 查找用户（根据邮箱）
     * @param $email
     * @return array|bool
     */
    public function findByEmail($email)
    {
        $sql=sprintf("
            SELECT * FROM user_base WHERE email=:email
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":email",$email, PDO::PARAM_STR);

        return $command->queryOne();
    }

    /**
     * 查找用户（根据手机）
     * @param $phone
     * @return array|bool
     */
    public function findByPhone($phone)
    {
        $sql=sprintf("
            SELECT * FROM user_base WHERE phone=:phone
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":phone",$phone, PDO::PARAM_STR);

        return $command->queryOne();
    }

    /**
     * 查找用户（根据ID）
     * @param $userId
     * @param null $status
     * @return array|bool
     */
    public function findById($userId,$status=null)
    {
        $sql=sprintf("
            SELECT * FROM user_base WHERE userId=:userId
        ");
        if($status!=null){
            $sql.=" AND status=:status";
        }
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":userId",$userId, PDO::PARAM_INT);
        if($status!=null){
            $command->bindParam(":status",$status, PDO::PARAM_INT);
        }
        return $command->queryOne();
    }


    /**
     * 查找用户（根据用户标示）
     * @param $userSign
     * @param null $status
     * @return array|bool
     */
    public function findByUserSign($userSign,$status=null)
    {
        $sql=sprintf("
            SELECT userId,nickname,email,phone,areaCode,sex,birthday,headImg,hobby,school,intro,info,travelCount,registerIp,registerTime,lastLoginTime,userSign,isPublisher
            FROM user_base WHERE userSign=:userSign
        ");
        if($status!=null){
            $sql.=" AND status=:status";
        }
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":userSign",$userSign, PDO::PARAM_STR);
        if($status!=null){
            $command->bindParam(":status",$status, PDO::PARAM_INT);
        }

        return $command->queryOne();
    }


    /**
     * 根据openId 接入 类型获取用户Id
     * @param $openId
     * @param $type
     * @return array|bool
     */
    public function findUserByOpenIdAndType($openId,$type)
    {
        $sql=sprintf("
            SELECT userId,nickname,email,phone,areaCode,sex,birthday,headImg,hobby,school,intro,info,travelCount,registerIp,registerTime,lastLoginTime,userSign,status,isPublisher
            FROM user_base WHERE userSign=
            (
              SELECT userId FROM user_access WHERE openId=:openId AND type=:type
            )
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":openId",$openId, PDO::PARAM_STR);
        $command->bindParam(":type",$type, PDO::PARAM_STR);

        return $command->queryOne();
    }


    /**
     * 添加用户接入关联
     * @param UserAccess $userAccess
     * @throws \yii\db\Exception
     */
    public function addUserAccess(UserAccess $userAccess)
    {
        $sql=sprintf("
            INSERT INTO user_access
            (
              userId,openId,type
            )VALUES
            (
              :userId,:openId,:type
            )
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":userId",$userAccess->userId, PDO::PARAM_STR);
        $command->bindParam(":openId",$userAccess->openId, PDO::PARAM_STR);
        $command->bindParam(":type",$userAccess->type, PDO::PARAM_INT);

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
            nickname=:nickname,phone=:phone,areaCode=:areaCode,email=:email,lastLoginIp=:lastLoginIp,sex=:sex,
            birthday=:birthday,headImg=:headImg,hobby=:hobby,school=:school,intro=:intro,info=:info,isPublisher=:isPublisher

            WHERE userId=:userId
        ");

        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":phone", $userBase->phone, PDO::PARAM_STR);
        $command->bindParam(":areaCode", $userBase->areaCode, PDO::PARAM_STR);
        $command->bindParam(":email", $userBase->email, PDO::PARAM_STR);
        $command->bindParam(":nickname", $userBase->nickname, PDO::PARAM_STR);
        $command->bindParam(":lastLoginIp", $userBase->lastLoginIp, PDO::PARAM_STR);
        $command->bindParam(":sex", $userBase->sex, PDO::PARAM_INT);
        $command->bindParam(":birthday", $userBase->birthday, PDO::PARAM_STR);
        $command->bindParam(":headImg", $userBase->headImg, PDO::PARAM_STR);
        $command->bindParam(":hobby", $userBase->hobby, PDO::PARAM_STR);
        $command->bindParam(":school", $userBase->school, PDO::PARAM_STR);
        $command->bindParam(":intro", $userBase->intro, PDO::PARAM_STR);
        $command->bindParam(":info", $userBase->info, PDO::PARAM_STR);
        $command->bindParam(":isPublisher", $userBase->isPublisher, PDO::PARAM_INT);

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
            WHERE userId=:userId
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":password", $userBase->password, PDO::PARAM_STR);
        $command->bindParam(":userId", $userBase->userId, PDO::PARAM_INT);

        return $command->execute();
    }

}