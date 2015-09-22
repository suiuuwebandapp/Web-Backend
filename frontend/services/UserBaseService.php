<?php
namespace frontend\services;

use common\components\Code;
use common\components\LogUtils;
use common\components\SysMessageUtils;
use common\entity\UserAccess;
use common\entity\UserAptitude;
use common\entity\UserCard;
use common\entity\UserPhoto;
use common\entity\UserPublisher;
use common\models\BaseDb;
use common\entity\UserBase;
use frontend\models\UserBaseDb;
use common\models\UserPublisherDb;
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
    public function addUser(UserBase $userBase, UserAccess $userAccess = null, UserPublisher $userPublisher = null)
    {
        $conn = $this->getConnection();
        $tran = $conn->beginTransaction();

        try {

            $this->userBaseDb = new UserBaseDb($conn);
            //验证手机或邮箱格式是否正确
            $userInfo = null;
            //验证手机或者邮箱是否存在
            if (!empty($userBase->phone) && !empty($userBase->email)) {
                $userInfo = $this->userBaseDb->findByEmail($userBase->email);
                if ($userInfo != false) throw new Exception(Code::USER_EMAIL_EXIST);
                $userInfo = $this->userBaseDb->findByPhone($userBase->phone);
                if ($userInfo != false) throw new Exception(Code::USER_PHONE_EXIST);
            } else if (!empty($userBase->email)) {
                $userInfo = $this->userBaseDb->findByEmail($userBase->email);
                if ($userInfo != false) throw new Exception(Code::USER_EMAIL_EXIST);

            } else if (!empty($userBase->phone)) {
                $userInfo = $this->userBaseDb->findByPhone($userBase->phone);
                if ($userInfo != false) throw new Exception(Code::USER_PHONE_EXIST);
            }
            //对用户密码进行加密
            $userBase->password = $this->encryptPassword($userBase->password);
            $userBase = $this->initRegisterUserInfo($userBase, $userAccess, $userPublisher);

            //环信im注册
            //$im=new Easemob(\Yii::$app->params['imConfig']);
            //$imPassword = \Yii::$app->params['imPassword'];
            //$options=array('username'=>$userBase->userSign,'password'=>$imPassword,'nickname'=>$userBase->nickname);
            //$imRes=$im->accreditRegister($options);
            //$arrRes=json_decode($imRes,true);
            if (false)//&&isset($arrRes['error']
            {
                throw new Exception(Code::USER_IM_REGISTER_ERROR);
            } else {
                $this->userBaseDb->addUser($userBase);
                if ($userAccess != null) {
                    $userAccess->userId = $userBase->userSign;//用户关联Id为UUID
                    $this->userBaseDb->addUserAccess($userAccess);
                }
                if ($userPublisher != null) {
                    $userPublisherDb = new UserPublisherDb($conn);
                    $userPublisher->userId = $userBase->userSign;
                    $userPublisherDb->addPublisher($userPublisher);
                }
                $userBase = $this->userBaseDb->findByUserSign($userBase->userSign);
                $userBase = $this->arrayCastObject($userBase, UserBase::class);
            }
            $this->commit($tran);

            $sysMessageUtil = new SysMessageUtils();
            $sysMessageUtil->sendUserRegisterUserInfoMessage($userBase->userSign);
        } catch (Exception $e) {
            $this->rollback($tran);
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $userBase;
    }


    /**
     * 添加第三方登录信息
     * @param UserAccess $userAccess
     * @throws Exception
     */
    public function addUserAccess(UserAccess $userAccess)
    {
        try {
            $conn = $this->getConnection();
            $this->userBaseDb = new UserBaseDb($conn);
            $this->userBaseDb->addUserAccess($userAccess);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }


    /**
     * 更新用户基本信息，并且注册随游
     * @param UserBase $userBase
     * @param UserPublisher $userPublisher
     * @return array|bool|UserBase|mixed
     * @throws Exception
     */
    public function updateUserBaseAndAddUserPublisher(UserBase $userBase, UserPublisher $userPublisher = null)
    {
        $conn = $this->getConnection();
        $tran = $conn->beginTransaction();

        try {

            $this->userBaseDb = new UserBaseDb($conn);
            //验证手机或邮箱格式是否正确
            $userInfo = null;
            if (!empty($userBase->email)) {
                $userInfo = $this->userBaseDb->findByEmail($userBase->email);
                if ($userInfo != false && $userInfo['userId'] != $userBase->userId) throw new Exception(Code::USER_EMAIL_EXIST);

            } else {
                $userInfo = $this->userBaseDb->findByPhone($userBase->phone);
                if ($userInfo != false && $userInfo['userId'] != $userBase->userId) throw new Exception(Code::USER_PHONE_EXIST);
            }
            if ($userPublisher != null) {
                $userPublisherDb = new UserPublisherDb($conn);
                $userBase->isPublisher = true;
                $userPublisher->userId = $userBase->userSign;
                if (empty($userPublisher->userPublisherId)) {
                    $userPublisherDb->addPublisher($userPublisher);
                } else {
                    $userPublisherDb->updateUserPublisher($userPublisher);
                }
            }
            //$im=new Easemob(\Yii::$app->params['imConfig']);
            //$options=array('username'=>$userBase->userSign,'nickname'=>$userBase->nickname);
            //$imRes=$im->updateNickname($options);
            //$arrRes=json_decode($imRes,true);
            if (false)//&&isset($arrRes['error'])
            {
                throw new Exception('修改环信昵称错误');
            }
            //添加顺序不要移动
            $this->userBaseDb->updateUserBase($userBase);

            $userBase = $this->userBaseDb->findByUserSign($userBase->userSign);
            $userBase = $this->arrayCastObject($userBase, UserBase::class);
            $this->commit($tran);
        } catch (Exception $e) {
            $this->rollback($tran);
            throw new Exception(Code::SYSTEM_EXCEPTION, Code::FAIL, $e);
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
        $userBase = null;
        try {
            $conn = $this->getConnection();
            $this->userBaseDb = new UserBaseDb($conn);
            //对用户密码进行加密
            $password = $this->encryptPassword($password);
            if (!empty($userName) && strpos($userName, "@")) {
                $result = $this->userBaseDb->findByEmailAndPwd($userName, $password);
            } else {
                $result = $this->userBaseDb->findByPhoneAndPwd($userName, $password);
            }

            $userBase = $this->arrayCastObject($result, UserBase::class);
        } catch (Exception $e) {
            throw new Exception(Code::SYSTEM_EXCEPTION, Code::FAIL, $e);
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
        $userBase = null;
        try {
            $conn = $this->getConnection();
            $this->userBaseDb = new UserBaseDb($conn);
            $result = $this->userBaseDb->findByEmail($email);
            $userBase = $this->arrayCastObject($result, UserBase::class);
        } catch (Exception $e) {
            throw new Exception(Code::SYSTEM_EXCEPTION, Code::FAIL, $e);
        } finally {
            $this->closeLink();
        }
        return $userBase;
    }

    /**
     * 查找用户根据Id
     * @param $userId
     * @return mixed|null
     * @throws Exception
     */
    public function findUserById($userId)
    {
        $userBase = null;
        try {
            $conn = $this->getConnection();
            $this->userBaseDb = new UserBaseDb($conn);
            $result = $this->userBaseDb->findById($userId, UserBase::USER_STATUS_NORMAL);
            $userBase = $this->arrayCastObject($result, UserBase::class);
        } catch (Exception $e) {
            throw new Exception(Code::SYSTEM_EXCEPTION, Code::FAIL, $e);
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
        $userBase = null;
        try {
            $conn = $this->getConnection();
            $this->userBaseDb = new UserBaseDb($conn);
            $result = $this->userBaseDb->findByPhone($phone);
            $userBase = $this->arrayCastObject($result, UserBase::class);
        } catch (Exception $e) {
            throw new Exception(Code::SYSTEM_EXCEPTION, Code::FAIL, $e);
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
        $userBase = null;
        if (empty($userSign)) {
            throw new Exception("UserSign Is  Not Allow Empty");
        }
        try {
            $conn = $this->getConnection();
            $this->userBaseDb = new UserBaseDb($conn);
            $result = $this->userBaseDb->findByUserSign($userSign);
            $userBase = $this->arrayCastObject($result, UserBase::class);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $userBase;
    }

    /**
     * 查找用户（根据userSign） 返回数组结果
     * @param $userSign
     * @return mixed|null
     * @throws Exception
     */
    public function findUserByUserSignArray($userSign)
    {
        $userBase = null;
        if (empty($userSign)) {
            throw new Exception("UserSign Is  Not Allow Empty");
        }
        try {
            $conn = $this->getConnection();
            $this->userBaseDb = new UserBaseDb($conn);
            $userBase = $this->userBaseDb->findByUserSign($userSign);
        } catch (Exception $e) {
            throw new Exception(Code::SYSTEM_EXCEPTION, Code::FAIL, $e);
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
    public function findPasswordByUserSign($userSign)
    {
        $userBase = null;
        try {
            $conn = $this->getConnection();
            $this->userBaseDb = new UserBaseDb($conn);
            $result = $this->userBaseDb->findPasswordByUserSign($userSign, UserBase::USER_STATUS_NORMAL);
            $userBase = $this->arrayCastObject($result, UserBase::class);
        } catch (Exception $e) {
            throw new Exception(Code::SYSTEM_EXCEPTION, Code::FAIL, $e);
        } finally {
            $this->closeLink();
        }
        return $userBase;
    }

    /**
     * 获取用户基本信息
     * @param $userSign
     * @return mixed|null
     * @throws Exception
     */
    public function findBaseInfoBySign($userSign)
    {
        $userBase = null;
        try {
            $conn = $this->getConnection();
            $this->userBaseDb = new UserBaseDb($conn);
            $result = $this->userBaseDb->findBaseInfoBySign($userSign);
            $userBase = $this->arrayCastObject($result, UserBase::class);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $userBase;
    }


    /**
     * 获取用户基本信息
     * @param $userSign
     * @return array|null
     * @throws Exception
     */
    public function findBaseInfoBySignArray($userSign)
    {
        $userBase = null;
        try {
            $conn = $this->getConnection();
            $this->userBaseDb = new UserBaseDb($conn);
            $userBase = $this->userBaseDb->findBaseInfoBySign($userSign);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $userBase;
    }

    /**
     * 获取用户所有信息
     * @param $userSign
     * @return mixed|null
     * @throws Exception
     */
    public function findBaseAllBySign($userSign)
    {
        $userBase = null;
        try {
            $conn = $this->getConnection();
            $this->userBaseDb = new UserBaseDb($conn);
            $result = $this->userBaseDb->findBaseAllBySign($userSign);
            $userBase = $this->arrayCastObject($result, UserBase::class);
        } catch (Exception $e) {
            throw $e;
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
    private function initRegisterUserInfo(UserBase $userBase, UserAccess $userAccess = null, UserPublisher $userPublisher = null)
    {

        if ($userAccess == null) {
            $userBase->sex = UserBase::USER_SEX_SECRET;
            if(empty($userBase->headImg)){
                $userBase->headImg = \Yii::$app->params["base_dir"] . '/assets/images/user_default.png';
            }

            if (!empty($userBase->email)) {
                if ($userPublisher == null) {
                    $userBase->phone = null;
                }
                if (empty($userBase->nickname)) {
                    $str = $userBase->email;
                    $arr = explode('@', $str);
                    $userBase->nickname = substr($arr[0], 0, 4) . '*****' . $arr[1];
                }
            } else {
                if ($userPublisher == null) {
                    $userBase->email = null;
                }
                if (empty($userBase->nickname)) {
                    $str1 = $userBase->phone;
                    $userBase->nickname = substr($str1, 0, 4) . '*****' . substr($str1, -2);
                }

            }
        }

        if ($userPublisher != null) {
            $userBase->isPublisher = true;
        } else {
            $userBase->isPublisher = false;
        }


        $userBase->hobby = '';
        $userBase->info = '';
        $userBase->intro = '';
        if(empty($userBase->school)){
            $userBase->school = '';
        }
        $userBase->surname = '';
        $userBase->name = '';
        $userBase->qq = '';
        $userBase->wechat = '';
        $userBase->birthday = '0000-00-00';
        $userBase->userSign = Code::getUUID();
        $userBase->status = UserBase::USER_STATUS_NORMAL;
        if(empty($userBase->registerIp)){
            $userBase->registerIp = $_SERVER['REMOTE_ADDR'];
        }
        if(empty($userBase->lastLoginIp)){
            $userBase->lastLoginIp = $_SERVER['REMOTE_ADDR'];
        }
        $userBase->profession = '';
        return $userBase;
    }


    /**
     * 根据OpenId 接入类型查询用户
     * @param $openId
     * @param $type
     * @return mixed|null
     * @throws Exception
     */
    public function findUserAccessByOpenIdAndType($openId, $type)
    {
        $userBase = null;
        try {
            $conn = $this->getConnection();
            $this->userBaseDb = new UserBaseDb($conn);
            $result = $this->userBaseDb->findUserByOpenIdAndType($openId, $type);
            $userBase = $this->arrayCastObject($result, UserBase::class);
        } catch (Exception $e) {
            throw $e;
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
    public function validatePhoneExist($phone, $userId)
    {
        $userInfo = $this->findUserByPhone($phone);
        if ($userInfo != null) {
            if ($userInfo->userId != $userId) {
                return true;
            }
        }
        return false;
    }

    /**
     * 验证邮箱是否存在
     * @param $email
     * @param $userId
     * @return bool
     * @throws Exception
     */
    public function validateEmailExist($email, $userId)
    {
        $userInfo = $this->findUserByEmail($email);
        if ($userInfo != null) {
            if ($userInfo->userId != $userId) {
                return true;
            }
        }
        return false;
    }

    /**
     * 验证密码
     * @param $password
     * @param $p
     * @return bool
     */
    public function validatePassword($password, $p)
    {
        return $p == $this->encryptPassword($password);
    }

    public function updatePassword($userSign, $password)
    {
        try {
            $conn = $this->getConnection();
            $this->userBaseDb = new UserBaseDb($conn);
            $userBase = new UserBase();
            $userBase->password = $this->encryptPassword($password);
            $userBase->userSign = $userSign;
            return $this->userBaseDb->updatePassword($userBase);

        } catch (Exception $e) {
            throw new Exception(Code::SYSTEM_EXCEPTION, Code::FAIL, $e);
        } finally {
            $this->closeLink();
        }
    }


    public function findUserPublisherByUserSign($userSign)
    {
        $userBase = null;
        try {
            $conn = $this->getConnection();
            $userPublisherDb = new UserPublisherDb($conn);
            $result = $userPublisherDb->findUserPublisherByUserId($userSign);
            $userBase = $this->arrayCastObject($result, UserPublisher::class);
        } catch (Exception $e) {
            throw new Exception(Code::SYSTEM_EXCEPTION, Code::FAIL, $e);
        } finally {
            $this->closeLink();
        }
        return $userBase;
    }

    public function findUserByPublisherId($publisherId)
    {
        $userBase = null;
        try {
            $conn = $this->getConnection();
            $this->userBaseDb = new UserBaseDb($conn);
            $result=$this->userBaseDb->findByPublisherId($publisherId);
            $userBase = $this->arrayCastObject($result, UserBase::class);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $userBase;
    }

    public function findUserPublisherByPublisherId($publisherId)
    {
        $userBase = null;
        try {
            $conn = $this->getConnection();
            $userPublisherDb = new UserPublisherDb($conn);
            $result = $userPublisherDb->findUserPublisherById($publisherId);
            $userBase = $this->arrayCastObject($result, UserPublisher::class);
        } catch (Exception $e) {
            throw new Exception(Code::SYSTEM_EXCEPTION, Code::FAIL, $e);
        } finally {
            $this->closeLink();
        }
        return $userBase;
    }


    public function updateUserHeadImg($userId, $headImg)
    {
        try {
            $conn = $this->getConnection();
            $this->userBaseDb = new UserBaseDb($conn);
            $this->userBaseDb->uploadHeadImg($userId, $headImg);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }


    public function updateUserBase(UserBase $userBase)
    {
        try {
            //$im=new Easemob(\Yii::$app->params['imConfig']);
            //$options=array('username'=>$userBase->userSign,'nickname'=>$userBase->nickname);
            //$imRes=$im->updateNickname($options);
            //$arrRes=json_decode($imRes,true);
            if (false)//isset($arrRes['error'])
            {
                throw new Exception('修改环信昵称错误');
            }
            $conn = $this->getConnection();
            $this->userBaseDb = new UserBaseDb($conn);
            $this->userBaseDb->updateUserBase($userBase);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }


    /**
     * 添加随友信息
     * @param UserPublisher $userPublisher
     * @throws Exception
     * @throws \Exception
     */
    public function addUserPublisher(UserPublisher $userPublisher)
    {
        try {
            $conn = $this->getConnection();
            $userPublisherDb = new UserPublisherDb($conn);
            $userPublisherDb->addPublisher($userPublisher);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }

    /**
     * 更新随游信息
     * @param UserPublisher $userPublisher
     * @throws Exception
     * @throws \Exception
     */
    public function updateUserPublisher(UserPublisher $userPublisher)
    {
        try {
            $conn = $this->getConnection();
            $userPublisherDb = new UserPublisherDb($conn);
            $userPublisherDb->updateUserPublisher($userPublisher);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }

    /**
     * 添加用户相册
     * @param UserPhoto $userPhoto
     * @throws Exception
     * @throws \Exception
     * @return UserPhoto
     */
    public function addUserPhoto(UserPhoto $userPhoto)
    {
        try {
            $this->saveObject($userPhoto);
            $userPhoto->photoId=$this->getLastInsertId();
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $userPhoto;
    }


    /**
     * 获取用户相册列表
     * @param $userId
     * @return array|null
     * @throws Exception
     * @throws \Exception
     */
    public function getUserPhotoList($userId)
    {
        $photoList=null;
        try{
            $photoList=$this->findObjectByType(UserPhoto::class,"userId",$userId);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
        return $photoList;
    }

    /**
     * 删除用户照片
     * @param $photoId
     * @param $userId
     * @throws Exception
     * @throws \Exception
     */
    public function deleteUserPhoto($photoId,$userId)
    {
        try {
            $conn = $this->getConnection();
            $userBaseDb = new UserBaseDb($conn);
            $userBaseDb->deleteUserPhoto($photoId,$userId);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }


    /**
     * 存储用户UserCard
     * @param $userId
     * @param $url
     * @throws Exception
     * @throws \Exception
     */
    public function saveUserCard($userId,$url)
    {
        if(empty($url)){
            throw new Exception("Invalid Url ");
        }
        try{
            $rst=$this->findObjectByType(UserCard::class,"userId",$userId);
            if(empty($rst)){
                $userCard=new UserCard();
                $userCard->userId=$userId;
                $userCard->img=$url;
                $userCard->status=UserCard::USER_CARD_STATUS_WAIT;
                $userCard->authHistory="";
                $userCard->name="";
                $userCard->number="";
                $userCard->updateTime=BaseDb::DB_PARAM_NOW;

                $this->saveObject($userCard);
            }else{
                $userCard=$this->arrayCastObject($rst[0],UserCard::class);
                //如果不等于失败 那么是没办法更新用户证件信息
                if($userCard->status!=UserCard::USER_CARD_STATUS_FAIL){
                    throw new Exception("Invalid User Card Status");
                }
                $userCard->status=UserCard::USER_CARD_STATUS_WAIT;
                $userCard->img=$url;
                $userCard->updateTime=BaseDb::DB_PARAM_NOW;
                $this->updateObject($userCard);
            }
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }


    /**
     * 获取用户证件信息
     * @param $userId
     * @return mixed|null
     * @throws Exception
     * @throws \Exception
     */
    public function findUserCardByUserId($userId)
    {
        if(empty($userId)){
            throw new Exception("UserId Is Not Allow Empty");
        }
        $userCard=null;
        try{
            $rst=$this->findObjectByType(UserCard::class,"userId",$userId);
            if(!empty($rst)){
                $userCard=$this->arrayCastObject($rst[0],UserCard::class);
            }
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
        return $userCard;
    }


    /**
     * 添加用户资历审核
     * @param $userId
     * @throws Exception
     * @throws \Exception
     */
    public function addUserAptitude($userId)
    {
        try{
            $rst=$this->findObjectByType(UserAptitude::class,"userId",$userId);
            if(!empty($rst)){
              throw new Exception("User Aptitude Existing");
            }

            $userAptitude=new UserAptitude();
            $userAptitude->userId=$userId;
            $userAptitude->applyTime=BaseDb::DB_PARAM_NOW;
            $userAptitude->status=UserAptitude::USER_APTITUDE_STATUS_WAIT;

            $this->saveObject($userAptitude);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }


    /**
     * 获取用户资历
     * @param $userId
     * @return mixed|null
     * @throws Exception
     * @throws \Exception
     */
    public function findUserAptitudeByUserId($userId)
    {
        if(empty($userId)){
            throw new Exception("UserId Is Not Allow Empty");
        }
        $userAptitude=null;
        try{
            $rst=$this->findObjectByType(UserAptitude::class,"userId",$userId);
            if(!empty($rst)){
                $userAptitude=$this->arrayCastObject($rst[0],UserAptitude::class);
            }
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
        return $userAptitude;
    }


    /**
     * 获取用户实时余额
     * @param $userSign
     * @return array|bool|int
     * @throws Exception
     * @throws \Exception
     */
    public function findUserMoneyByUserSign($userSign)
    {
        if(empty($userSign)){
            throw new Exception("userSign Is Not Allow Empty");
        }
        $money=0;
        try {
            $conn = $this->getConnection();
            $this->userBaseDb = new UserBaseDb($conn);
            $money=$this->userBaseDb->findUserMoneyByUserSign($userSign);
            $money=$money['balance'];
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $money;
    }
    /**
     *
     * @param $userSign
     * @return array|null
     * @throws Exception
     */
    public function findUserAccessByUserSign($userSign)
    {
        $result = null;
        try {
            $conn = $this->getConnection();
            $this->userBaseDb = new UserBaseDb($conn);
            $result = $this->userBaseDb->findUserAccessByUserSign($userSign);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $result;
    }

}