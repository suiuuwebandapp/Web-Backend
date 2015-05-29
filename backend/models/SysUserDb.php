<?php
namespace backend\models;

use common\models\ProxyDb;
use yii\db\mssql\PDO;
use backend\entity\SysUser;

/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/3/31
 * Time : 下午1:56
 * Email: zhangxinmailvip@foxmail.com
 */
class SysUserDb extends ProxyDb
{


    /**
     * 添加用户
     * @param $sysUser
     * @return mixed
     */
    public function addSysUser(SysUser $sysUser)
    {
        $sql = sprintf("
            INSERT INTO sys_user
            (
              username,password,phone,email,nickname,registerTime,registerIp,lastLoginTime,lastLoginIp,sex,birthday,isAdmin,isEnabled,isDelete
            )
            VALUES
            (
              :username,:password,:phone,:email,:nickname,now(),:registerIp,now(),:lastLoginIp,:sex,:birthday,:isAdmin,:isEnabled,false
            )
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":username", $sysUser->username, PDO::PARAM_INT);
        $command->bindParam(":password", $sysUser->password, PDO::PARAM_STR);
        $command->bindParam(":phone", $sysUser->phone, PDO::PARAM_STR);
        $command->bindParam(":email", $sysUser->email, PDO::PARAM_STR);
        $command->bindParam(":nickname", $sysUser->nickname, PDO::PARAM_STR);
        $command->bindParam(":registerIp", $sysUser->registerIp, PDO::PARAM_STR);
        $command->bindParam(":lastLoginIp", $sysUser->lastLoginIp, PDO::PARAM_STR);
        $command->bindParam(":sex", $sysUser->sex, PDO::PARAM_INT);
        $command->bindParam(":birthday", $sysUser->birthday, PDO::PARAM_STR);
        $command->bindParam(":isAdmin", $sysUser->isAdmin, PDO::PARAM_BOOL);
        $command->bindParam(":isEnabled", $sysUser->isEnabled, PDO::PARAM_BOOL);

        return $command->execute();

    }

    public function getList()
    {

    }

    /**
     * 查找用户，根据用户ID
     * @param $userId
     * @return int
     * @throws \yii\db\Exception
     */
    public function findById($userId)
    {
        $sql=sprintf("
            SELECT * FROM sys_user WHERE userId=:userId AND isDelete=false;
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":userId", $userId, PDO::PARAM_INT);
        return $command->execute();
    }

    /**
     * 查找用户
     * @param $username
     * @param $password
     * @return array
     */
    public function findUser($username,$password){
        $sql=sprintf("
            SELECT * FROM sys_user WHERE username=:username AND password=:password AND isDelete=false;
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":username", $username, PDO::PARAM_INT);
        $command->bindParam(":password", $password, PDO::PARAM_STR);
        return $command->queryOne();
    }

    /**
     * 根据用户名查找用户
     * @param $username
     * @return array|bool
     */
    public function findUserByUsername($username)
    {
        $sql=sprintf("
            SELECT * FROM sys_user WHERE username=:username AND isDelete=false;
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":username", $username, PDO::PARAM_INT);
        return $command->queryOne();
    }

    /**
     * 根据用户唯一标示（userSign）查找用户
     * @param $userSign
     * @return array|bool
     */
    public function findUserByUserSign($userSign)
    {
        $sql=sprintf("
            SELECT * FROM sys_user WHERE userSign=:userSign AND isDelete=false;
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":userSign", $userSign, PDO::PARAM_STR);
        return $command->queryOne();
    }


    /**
     * 删除用户（伪删除） 根据用户Id
     * @param $userId
     * @return array|bool
     */
    public function deleteById($userId)
    {
        $sql=sprintf("
            UPDATE sys_user SET isDelete=TRUE WHERE userId=:userId;
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":userId", $userId, PDO::PARAM_INT);
        return $command->queryOne();
    }

    public function findUserByPhoneOrMail($str)
    {
        $sql=sprintf("
            SELECT * FROM user_base WHERE phone=:phone OR email=:email
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":phone", $str, PDO::PARAM_STR);
        $command->bindParam(":email", $str, PDO::PARAM_STR);
        return $command->queryOne();
    }


}