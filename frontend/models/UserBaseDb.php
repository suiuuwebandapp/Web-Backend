<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/2
 * Time : 下午4:52
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\models;


use common\models\ProxyDb;
use frontend\entity\UserBase;
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
              nickname,password,phone,areaCode,email,registerTime,registerIp,lastLoginTime,lastLoginIp,sex,birthday,headImg,hobby,school,intro,info,travelCount,userSign,status
            )
            VALUES
            (
              :nickname,:password,:phone,:areaCode,:email,now(),:registerIp,now(),:lastLoginIp,:sex,:birthday,:headImg,:hobby,:school,:intro,:info,0,:userSign,:status
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
     * @return array|bool
     */
    public function findById($userId)
    {
        $sql=sprintf("
            SELECT * FROM user_base WHERE userId=:userId
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":userId",$userId, PDO::PARAM_INT);

        return $command->queryOne();
    }


    /**
     * 查找用户（根据用户标示）
     * @param $userSign
     * @return array|bool
     */
    public function findByUserSign($userSign)
    {
        $sql=sprintf("
            SELECT * FROM user_base WHERE userSign=:userSign
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":userSign",$userSign, PDO::PARAM_STR);

        return $command->queryOne();
    }
}