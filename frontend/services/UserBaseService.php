<?php
namespace frontend\services;

use common\components\Code;
use common\components\Validate;
use common\entity\UserAccess;
use common\entity\UserPublisher;
use common\models\BaseDb;
use common\entity\UserBase;
use frontend\models\UserBaseDb;
use frontend\models\UserPublisherDb;
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
     * @param UserPublisher|null $userPublisher
     * @return array|bool|UserBase
     * @throws Exception
     */
    public function addUser(UserBase $userBase,UserAccess $userAccess=null,UserPublisher $userPublisher=null)
    {
        $conn = $this->getConnection();
        $tran=$conn->beginTransaction();

        try {

            $this->userBaseDb = new UserBaseDb($conn);
            //验证手机或邮箱格式是否正确
            $userInfo=null;
            //如果不是第三方登录，验证手机或者邮箱是否存在
            if($userAccess==null){
                if(!empty($userBase->phone)&&!empty($userBase->email)){
                    $userInfo=$this->userBaseDb->findByEmail($userBase->email);
                    if($userInfo!=false) throw new Exception(Code::USER_EMAIL_EXIST);
                    $userInfo=$this->userBaseDb->findByPhone($userBase->phone);
                    if($userInfo!=false) throw new Exception(Code::USER_PHONE_EXIST);
                }else  if(!empty($userBase->email)){
                    $userInfo=$this->userBaseDb->findByEmail($userBase->email);
                    if($userInfo!=false) throw new Exception(Code::USER_EMAIL_EXIST);

                }else if(!empty($userBase->phone)){
                    $userInfo=$this->userBaseDb->findByPhone($userBase->phone);
                    if($userInfo!=false) throw new Exception(Code::USER_PHONE_EXIST);
                }
            }
            //对用户密码进行加密
            $userBase->password = $this->encryptPassword($userBase->password);
            $userBase=$this->initRegisterUserInfo($userBase,$userAccess,$userPublisher);

            //环信im注册
            $im=new Easemob(\Yii::$app->params['imConfig']);
            $options=array('username'=>$userBase->userSign,'password'=>$userBase->password,'nickname'=>$userBase->nickname);
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
                if($userPublisher!=null){
                    $userPublisherDb=new UserPublisherDb($conn);
                    $userPublisher->userId=$userBase->userSign;
                    $userPublisherDb->addPublisher($userPublisher);
                }
                $userBase=$this->userBaseDb->findByUserSign($userBase->userSign);
                $userBase=$this->arrayCastObject($userBase,UserBase::class);
            }
            $this->commit($tran);
        } catch (Exception $e) {
            $this->rollback($tran);
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $userBase;
    }


    /**
     * 更新用户基本信息，并且注册随游
     * @param UserBase $userBase
     * @param UserPublisher $userPublisher
     * @return array|bool|UserBase|mixed
     * @throws Exception
     */
    public function updateUserBaseAndAddUserPublisher(UserBase $userBase,UserPublisher $userPublisher=null)
    {
        $conn = $this->getConnection();
        $tran=$conn->beginTransaction();

        try {

            $this->userBaseDb = new UserBaseDb($conn);
            //验证手机或邮箱格式是否正确
            $userInfo=null;
            if(!empty($userBase->email)){
                $userInfo=$this->userBaseDb->findByEmail($userBase->email);
                if($userInfo!=false&&$userInfo['userId']!=$userBase->userId) throw new Exception(Code::USER_EMAIL_EXIST);

            }else{
                $userInfo=$this->userBaseDb->findByPhone($userBase->phones);
                if($userInfo!=false&&$userInfo['userId']!=$userBase->userId) throw new Exception(Code::USER_PHONE_EXIST);
            }
            if($userPublisher!=null){
                $userPublisherDb=new UserPublisherDb($conn);
                $userBase->isPublisher=true;
                $userPublisher->userId=$userBase->userSign;
                $userPublisherDb->addPublisher($userPublisher);
            }
            //添加顺序不要移动
            $this->userBaseDb->updateUserBase($userBase);

            $userBase=$this->userBaseDb->findByUserSign($userBase->userSign);
            $userBase=$this->arrayCastObject($userBase,UserBase::class);
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
     * 查找用户（根据手机）
     * @param $phone
     * @return mixed|null
     * @throws Exception
     */
    public function findUserByPhone($phone)
    {
        $userBase=null;
        try {
            $conn = $this->getConnection();
            $this->userBaseDb = new UserBaseDb($conn);
            $result = $this->userBaseDb->findByPhone($phone);
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
     * @param UserPublisher $userPublisher
     * @return UserBase
     */
    private function initRegisterUserInfo(UserBase $userBase,UserAccess $userAccess=null,UserPublisher $userPublisher=null)
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

        if($userPublisher!=null){
            $userBase->isPublisher=true;
        }else{
            $userBase->isPublisher=false;
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

    /**
     * 验证手机是否存在
     * @param $phone
     * @param $userId
     * @return bool
     * @throws Exception
     */
    public function validatePhoneExist($phone,$userId)
    {
        $userInfo=$this->findUserByPhone($phone);
        if($userInfo!=null){
            if($userInfo->userId!=$userId){
                return true;
            }
        }
        return false;
    }

    /**
     * 验证邮箱是否存在
     * @param $phone
     * @param $userId
     * @return bool
     * @throws Exception
     */
    public function validateEmailExist($phone,$userId)
    {
        $userInfo=$this->findUserByPhone($phone);
        if($userInfo!=null){
            if($userInfo->userId!=$userId){
                return true;
            }
        }
        return false;
    }

    public function updatePassword($userId,$password)
    {
        try {
            $conn = $this->getConnection();
            $this->userBaseDb = new UserBaseDb($conn);
            $userBase =new UserBase();
            $userBase->password = $this->encryptPassword($password);
            $userBase->userId=$userId;
           return $this->userBaseDb->updatePassword($userBase);

        }catch (Exception $e) {
            throw new Exception(Code::SYSTEM_EXCEPTION, Code::FAIL, $e);
        }
    }

    public function findUserPublisherByUserSign($userSign)
    {
        $userBase=null;
        try {
            $conn = $this->getConnection();
            $userPublisherDb=new UserPublisherDb($conn);
            $result=$userPublisherDb->findUserPublisherByUserId($userSign);
            $userBase=$this->arrayCastObject($result,UserPublisher::class);
        } catch (Exception $e) {
            throw new Exception(Code::SYSTEM_EXCEPTION,Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
        return $userBase;
    }
}