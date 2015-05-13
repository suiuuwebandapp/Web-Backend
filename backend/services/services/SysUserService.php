<?php
namespace backend\services;

use common\components\Code;
use backend\entity\SysUser;
use common\models\BaseDb;
use yii\base\Exception;
use backend\models\SysUserDb;

/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/3/31
 * Time : 下午2:07
 * Email: zhangxinmailvip@foxmail.com
 */
class SysUserService extends BaseDb
{

    private $sysUserDb;

    public function __construct()
    {

    }

    /**
     * 添加用户
     * @param $sysUser
     * @throws Exception
     * @throws \Exception
     */
    public function addSysUser(SysUser $sysUser)
    {
        try {
            $conn = $this->getConnection();
            $this->sysUserDb = new SysUserDb($conn);
            //对用户密码进行加密
            $sysUser->password = $this->encryptPassword($sysUser->password);
            $userInfo=$this->sysUserDb->findUserByUsername($sysUser->username);
            if($userInfo!=false){
                throw new Exception(Code::SYS_USER_NAME_EXISTED);
            }
            $this->sysUserDb->addSysUser($sysUser);
        } catch (Exception $e) {
            throw new Exception(Code::SYS_SYSTEM_EXCEPTION,Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }

    /**
     * 查找用户（根据用户名密码）
     * @param $username
     * @param $password
     * @return mixed
     * @throws Exception
     * @throws \Exception
     */
    public function findUser($username, $password)
    {
        $sysUser=null;
        try {
            $conn = $this->getConnection();
            $this->sysUserDb = new SysUserDb($conn);
            //对用户密码进行加密
            $password = $this->encryptPassword($password);
            $result = $this->sysUserDb->findUser($username, $password);
            $sysUser=$this->arrayCastObject($result,SysUser::class);
        } catch (Exception $e) {
            throw new Exception(Code::SYS_SYSTEM_EXCEPTION,Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
       return $sysUser;
    }

    /**
     * 查找用户（根据userSign）
     * @param $userSign
     * @return mixed|null
     * @throws Exception
     */
    public function findUserByUserSign($userSign)
    {
        $sysUser=null;
        try {
            $conn = $this->getConnection();
            $this->sysUserDb = new SysUserDb($conn);
            $result = $this->sysUserDb->findUserByUserSign($userSign);
            $sysUser=$this->arrayCastObject($result,SysUser::class);
        } catch (Exception $e) {
            throw new Exception(Code::SYS_SYSTEM_EXCEPTION,Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
        return $sysUser;
    }


    /**
     * 密码加密方法
     * @param $password
     * @return string
     */
    private function encryptPassword($password)
    {
        $encrypt = \Yii::$app->params['encryptPassword'];
        return md5(md5($encrypt . $password) . $encrypt);
    }


}