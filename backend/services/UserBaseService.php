<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/29
 * Time : 下午5:46
 * Email: zhangxinmailvip@foxmail.com
 */

namespace backend\services;


use backend\components\Page;
use backend\models\UserBaseDb;
use common\entity\UserBase;
use common\models\BaseDb;
use common\models\UserPublisherDb;
use yii\base\Exception;

class UserBaseService extends BaseDb{


    /**
     * @param Page $page
     * @param $search
     * @return Page|null
     * @throws Exception
     * @throws \Exception
     */
    public function getUserBaseListByPage(Page $page,$search)
    {
        try{
            $conn=$this->getConnection();
            $userBaseDb=new UserBaseDb($conn);
            $page=$userBaseDb->getUserBaseListByPage($page,$search);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
        return $page;
    }


    /**
     * 获取用户信息
     * @param $userSign
     * @return mixed|null
     * @throws Exception
     * @throws \Exception
     */
    public function findUserByUserSign($userSign)
    {
        if(empty($userSign)){
            throw new Exception("UserSign Is Not Allow Empty");
        }
        $userBase=null;
        try {
            $conn = $this->getConnection();
            $this->userBaseDb = new UserBaseDb($conn);
            $result = $this->userBaseDb->findByUserSign($userSign);
            $userBase=$this->arrayCastObject($result,UserBase::class);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $userBase;
    }

    /**
     * 获取用户详情，包含随友信息
     * @param $userSign
     * @return null
     * @throws Exception
     * @throws \Exception
     */
    public function findUserInfoByUserSign($userSign)
    {
        if(empty($userSign)){
            throw new Exception("UserSign Is Not Allow Empty");
        }
        $userInfo=[];
        try {
            $conn = $this->getConnection();
            $userBaseDb=new UserBaseDb($conn);
            $userPublisherDb=new UserPublisherDb($conn);
            $userBase=$userBaseDb->findByUserSign($userSign);
            $userPublisher=$userPublisherDb->findUserPublisherByUserId($userSign);
            $userInfo['userBase']=$userBase;
            $userInfo['userPublisher']=$userPublisher;
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $userInfo;
    }
}