<?php
namespace frontend\services;

use common\components\Code;
use common\components\Validate;
use common\entity\UserAccess;
use common\models\BaseDb;
use common\entity\UserBase;
use frontend\models\UserBaseDb;
use yii\base\Exception;
use common\components\Easemob;

/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/3/31
 * Time : 下午2:07
 * Email: zhangxinmailvip@foxmail.com
 */
class UserBaseService extends BaseDb
{

    private $userBaseDb;

    public function __construct()
    {

    }

    /**
     * 添加用户
     * 1.注册环信
     * 2.注册前台用户基本信息
     * 3.如果是第三方登录，记录第三方登录信息
     *
     * @param UserBase $userBase
     * @param UserAccess $userAccess
     * @return array|bool|UserBase
     * @throws Exception
     */
    public function addUser(UserBase $userBase,UserAccess $userAccess=null)
    {
        $conn = $this->getConnection();
        $tran=$conn->beginTransaction();

        try {

            $this->userBaseDb = new UserBaseDb($conn);
            //验证手机或邮箱格式是否正确
            $userInfo=null;
            //如果不是第三方登录，验证手机或者邮箱是否存在
            if($userAccess==null){
                if(!empty($userBase->email)){
                    $userInfo=$this->userBaseDb->findByEmail($userBase->email);
                    if($userInfo!=false) throw new Exception(Code::USER_EMAIL_EXIST);

                }else{
                    $userInfo=$this->userBaseDb->findByPhone($userBase->phone);
                    if($userInfo!=false) throw new Exception(Code::USER_PHONE_EXIST);
                }
            }
            //环信im注册
            $im=new Easemob(\Yii::$app->params['imConfig']);

            //对用户密码进行加密
            $userBase->password = $this->encryptPassword($userBase->password);
            $userBase=$this->initRegisterUserInfo($userBase,$userAccess);
            $imPassword = \Yii::$app->params['imPassword'];
            $options=array('username'=>$userBase->userSign,'password'=>$imPassword,'nickname'=>$userBase->nickname);
            $imRes=$im->accreditRegister($options);
            $arrRes=json_decode($imRes,true);
            if(isset($arrRes['error']))
            {
                throw new Exception(Code::USER_IM_REGISTER_ERROR);
            }else
            {
                $this->userBaseDb->addUser($userBase);
                if($userAccess!=null){
                    $userAccess->userId=$userBase->userSign;//用户关联Id为UUID
                    $this->userBaseDb->addUserAccess($userAccess);
                }
                $userBase=$this->userBaseDb->findByUserSign($userBase->userSign);
                $userBase=$this->arrayCastObject($userBase,UserBase::class);
            }
            $this->commit($tran);
        } catch (Exception $e) {
            $this->rollback($tran);
            throw new Exception(Code::SYSTEM_EXCEPTION,Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
        return $userBase;
    }

    /**
     * 查找用户（根据用户名和密码）
     * @param $userName
     * @param $password
     * @return mixed|null
     * @throws Exception
     */
    public function findUserByUserNameAndPwd($userName, $password)
    {
        $userBase=null;
        try {
            $conn = $this->getConnection();
            $this->userBaseDb = new UserBaseDb($conn);
            //对用户密码进行加密
            $password = $this->encryptPassword($password);
            $valMsg = Validate::validateEmail($userName);
            if(!empty($valMsg))
            {
                $result = $this->userBaseDb->findByPhoneAndPwd($userName, $password);
            }else
            {
                $result = $this->userBaseDb->findByEmailAndPwd($userName, $password);
            }

            $userBase=$this->arrayCastObject($result,UserBase::class);
        } catch (Exception $e) {
            throw new Exception(Code::SYSTEM_EXCEPTION,Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
       return $userBase;
    }

    /**
     * 查找用户（根据邮箱）
     * @param $email
     * @return mixed
     * @throws Exception
     */
    public function findUserByEmail($email)
    {
        $userBase=null;
        try {
            $conn = $this->getConnection();
            $this->userBaseDb = new UserBaseDb($conn);
            $result = $this->userBaseDb->findByEmail($email);
            $userBase=$this->arrayCastObject($result,UserBase::class);
        } catch (Exception $e) {
            throw new Exception(Code::SYSTEM_EXCEPTION,Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
        return $userBase;
    }

    /**
     * 查找用户（根据userSign）
     * @param $userSign
     * @return mixed|null
     * @throws Exception
     */
    public function findUserByUserSign($userSign)
    {
        $userBase=null;
        try {
            $conn = $this->getConnection();
            $this->userBaseDb = new UserBaseDb($conn);
            $result = $this->userBaseDb->findByUserSign($userSign);
            $userBase=$this->arrayCastObject($result,UserBase::class);
        } catch (Exception $e) {
            throw new Exception(Code::SYSTEM_EXCEPTION,Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
        return $userBase;
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


    /**
     * 初始化注册用户信息
     * @param UserBase $userBase
     * @param UserAccess $userAccess
     * @return UserBase
     */
    private function initRegisterUserInfo(UserBase $userBase,UserAccess $userAccess=null)
    {

        if($userAccess==null){
            $userBase->sex=UserBase::USER_SEX_SECRET;
            $userBase->headImg='';

            if(!empty($userBase->email)){
                $userBase->phone=null;
                $userBase->nickname=$userBase->email;
            }else{
                $userBase->email=null;
                $userBase->nickname=$userBase->phone;
            }
        }

        $userBase->areaCode='';
        $userBase->hobby='';
        $userBase->info='';
        $userBase->intro='';
        $userBase->school='';
        $userBase->birthday='0000-00-00';
        $userBase->userSign=Code::getUUID();
        $userBase->status=UserBase::USER_STATUS_NORMAL;
        $userBase->registerIp=$_SERVER['REMOTE_ADDR'];
        $userBase->lastLoginIp=$_SERVER['REMOTE_ADDR'];
        $userBase->isPublisher=false;

        return $userBase;
    }


    /**
     * 根据OpenId 接入类型查询用户
     * @param $openId
     * @param $type
     * @return mixed|null
     * @throws Exception
     */
    public function findUserAccessByOpenIdAndType($openId,$type)
    {
        $userBase=null;
        try {
            $conn = $this->getConnection();
            $this->userBaseDb = new UserBaseDb($conn);
            $result = $this->userBaseDb->findUserByOpenIdAndType($openId,$type);
            $userBase=$this->arrayCastObject($result,UserBase::class);
        } catch (Exception $e) {
            throw new Exception(Code::SYSTEM_EXCEPTION,Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
        return $userBase;
    }


}