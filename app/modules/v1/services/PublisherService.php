<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/1
 * Time : 上午10:02
 * Email: zhangxinmailvip@foxmail.com
 */

namespace app\modules\v1\services;


use app\modules\v1\entity\UserPublisher;
use common\models\BaseDb;
use app\modules\v1\models\UserPublisherDb;
use yii\base\Exception;

class PublisherService extends BaseDb
{

    private $userPublisherDb;


    public function findById($publisherId)
    {
        $publisherObj=null;
        try{
            $conn=$this->getConnection();
            $this->userPublisherDb=new UserPublisherDb($conn);
            $publisherObj=$this->userPublisherDb->findUserPublisherById($publisherId);

            $publisherObj=$this->arrayCastObject($publisherObj,UserPublisher::class);

        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
        return $publisherObj;
    }

    public function findUserPublisherByUserSign($userSign)
    {
        $publisherObj=null;
        try{
            $conn=$this->getConnection();
            $this->userPublisherDb=new UserPublisherDb($conn);
            $publisherObj=$this->userPublisherDb->findUserPublisherByUserId($userSign);

            $publisherObj=$this->arrayCastObject($publisherObj,UserPublisher::class);

        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
        return $publisherObj;
    }
}