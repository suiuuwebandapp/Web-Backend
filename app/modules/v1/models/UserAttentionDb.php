<?php
namespace app\modules\v1\models;

use common\components\Code;
use app\modules\v1\entity\TravelTrip;
use app\modules\v1\entity\UserAttention;
use app\modules\v1\entity\UserBase;
use common\models\ProxyDb;
use yii\db\mssql\PDO;

/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/4/24
 * Time: 下午5:52
 */
class UserAttentionDb extends ProxyDb
{

    /**
     * 添加关注
     * @param $relativeId
     * @param $relativeType
     * @param $userSign
     * @return int
     */
    public function addUserAttention($relativeId,$relativeType,$userSign,$isStatus=1)
    {
        $sql=sprintf("
            INSERT INTO user_attention (relativeId,relativeType,status,addTime,userSign) VALUES (:relativeId,:relativeType,:status,now(),:userSign);
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":relativeId", $relativeId, PDO::PARAM_INT);
        $command->bindParam(":relativeType", $relativeType, PDO::PARAM_INT);
        $command->bindParam(":userSign", $userSign, PDO::PARAM_STR);
        if($isStatus==1){
        $command->bindValue(":status", UserAttention::ATTENTION_STATUS_NORMAL, PDO::PARAM_INT);
        }else
        {
            $command->bindValue(":status", UserAttention::ATTENTION_STATUS_DISABLED, PDO::PARAM_INT);
        }
        $command->execute();
        return $this->getConnection()->lastInsertID;
    }

    /** 取消关注
     * @param $attentionId
     * @param $userSign
     * @return int
     * @throws \yii\db\Exception
     */
    public function deleteUserAttention($attentionId,$userSign)
    {
        $sql=sprintf("
           UPDATE user_attention SET status=:status,deleteTime=now() WHERE attentionId = :attentionId AND userSign=:userSign
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindValue(":status", UserAttention::ATTENTION_STATUS_DISABLED, PDO::PARAM_INT);
        $command->bindParam(":attentionId", $attentionId, PDO::PARAM_INT);
        $command->bindParam(":userSign", $userSign, PDO::PARAM_STR);
        return $command->execute();
    }

    /**
     * 查找关注用户
     * @param $userSign
     * @return array
     */
    public function getAttentionUser($userSign,$page)
    {
        $sql=sprintf("
            FROM  user_base a
            LEFT JOIN user_attention b ON b.relativeId = a.userId
            WHERE a.`status`=:userStatus AND b.relativeType=:relativeType  AND b.`status`=:attentionStatus AND b.userSign=:userSign
        ");
        $this->setParam("userStatus", UserBase::USER_STATUS_NORMAL);
        $this->setParam("relativeType", UserAttention::TYPE_FOR_USER);
        $this->setParam("attentionStatus", UserAttention::ATTENTION_STATUS_NORMAL);
        $this->setParam("userSign", $userSign);
        $this->setSelectInfo('a.nickname,a.headImg,a.intro,a.hobby,a.userSign');
        $this->setSql($sql);
        return $this->find($page);

    }

    /**
     * 得到关注结果
     * @param UserAttention $userAttention
     * @return array
     */
    public function getAttentionResult(UserAttention $userAttention,$isSupport=false)
    {
        $sql=sprintf("
            SELECT attentionId,relativeId,relativeType,status,addTime,userSign FROM  user_attention
            WHERE userSign=:userSign AND relativeType=:relativeType AND relativeId=:relativeId
        ");
        if(!$isSupport)
        {
            $sql.='AND status=:attentionStatus';
        }
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":userSign", $userAttention->userSign, PDO::PARAM_STR);
        $command->bindParam(":relativeType", $userAttention->relativeType, PDO::PARAM_INT);
        $command->bindParam(":relativeId", $userAttention->relativeId, PDO::PARAM_INT);
        if(!$isSupport)
        {
            $command->bindValue(":attentionStatus", UserAttention::ATTENTION_STATUS_NORMAL, PDO::PARAM_INT);
        }

        return $command->queryOne();
    }
    /**
     * 得到关注结果
     * @param $id
     * @return array
     */
    public function getAttentionResultById($id)
    {
        $sql=sprintf("
           SELECT attentionId,relativeId,relativeType,status,addTime,userSign FROM  user_attention
            WHERE attentionId=:attentionId
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":attentionId", $id, PDO::PARAM_INT);
        return $command->queryOne();
    }

    /**得到收藏随游列表
     * @param $userSign
     * @param $page
     * @return \backend\components\Page|null
     */
    public function getCollectTravelList($userSign,$page)
    {

        $sql=sprintf("
            FROM travel_trip a
            LEFT JOIN user_publisher c ON c.userPublisherId = a.createPublisherId
            LEFT JOIN user_base b ON b.userSign=c.userId
            LEFT JOIN user_attention d ON d.relativeId = a.tripId
            WHERE a.`status`=:tStatus AND b.`status`=:userStatus AND d.relativeType=:relativeType
            AND d.`status`=:attentionStatus AND d.userSign=:userSign
        ");
        $this->setParam("userStatus", UserBase::USER_STATUS_NORMAL);
        $this->setParam("tStatus", TravelTrip::TRAVEL_TRIP_STATUS_NORMAL);
        $this->setParam("relativeType", UserAttention::TYPE_COLLECT_FOR_TRAVEL);
        $this->setParam("attentionStatus", UserAttention::ATTENTION_STATUS_NORMAL);
        $this->setParam("userSign", $userSign);

        $this->setSelectInfo('a.tripId,a.titleImg,a.title,a.intro,a.score,ceil(a.basePrice*'.Code::TRIP_SERVICE_PRICE.') AS basePrice,a.basePriceType,a.tripCount,b.userSign,b.headImg,b.nickname,a.collectCount,a.commentCount');
        $this->setSql($sql);
        return $this->find($page);
    }

    /**
     * 得到关注的旅图
     * @param $userSign
     * @param $page
     * @return array
     */
    public function getUserAttentionTp($userSign,$page)
    {

        $sql=sprintf("
            FROM travel_picture a
            LEFT JOIN user_base b ON b.userSign=a.userSign
            LEFT JOIN user_attention d ON d.relativeId = a.id
            WHERE  b.`status`=:userStatus AND d.relativeType=:relativeType AND d.`status`=:attentionStatus AND d.userSign=:userSign
        ");
        $this->setParam("userStatus", UserBase::USER_STATUS_NORMAL);
        $this->setParam("relativeType", UserAttention::TYPE_FOR_TRAVEL_PICTURE);
        $this->setParam("attentionStatus", UserAttention::ATTENTION_STATUS_NORMAL);
        $this->setParam("userSign", $userSign);

        $this->setSelectInfo('a.id,a.title,a.contents,a.picList,a.country,a.city,a.lon,a.lat,a.tags,a.userSign,a.createTime,a.commentCount,a.attentionCount,a.titleImg,a.address,b.userSign,b.headImg,b.nickname');
        $this->setSql($sql);
        return $this->find($page);
    }

    /**
     * 得到关注的问答社区
     * @param $userSign
     * @return array
     */
    public function getUserAttentionQa($userSign,$page)
    {

        $sql=sprintf("
            FROM question_community a
            LEFT JOIN user_base b ON b.userSign=a.qUserSign
            LEFT JOIN user_attention d ON d.relativeId = a.qId
            WHERE  b.`status`=:userStatus AND d.relativeType=:relativeType AND d.`status`=:attentionStatus AND d.userSign=:userSign
        ");
        $this->setParam("userStatus", UserBase::USER_STATUS_NORMAL);
        $this->setParam("relativeType", UserAttention::TYPE_FOR_QA);
        $this->setParam("attentionStatus", UserAttention::ATTENTION_STATUS_NORMAL);
        $this->setParam("userSign", $userSign);
        $this->setSelectInfo('a.qId,a.qTitle,a.qContent,a.qAddr,a.qCountryId,a.qCityId,a.qTag,a.qUserSign,a.qCreateTime,a.qInviteAskUser,a.pvNumber,a.attentionNumber,a.aNumber,b.userSign,b.headImg,b.nickname');
        $this->setSql($sql);
        return $this->find($page);
    }

    /**
     * 得到收藏圈子文章列表
     * @param $page
     * @param $userSign
     * @return array
     */
    public function getCollectArticleList($userSign,$page)
    {
        $sql=sprintf("
            FROM circle_article a
            LEFT JOIN user_attention b ON a.articleId = b.relativeId
            LEFT JOIN user_base c ON c.userSign=a.aCreateUserSign
            LEFT JOIN user_base d  ON d.userSign=b.userSign
            WHERE a.aStatus=1 AND b.`status`=1 AND c.`status`=1 AND b.relativeType=:relativeType AND b.userSign=:userSign
        ");
        $this->setParam("relativeType", UserAttention::TYPE_COLLECT_FOR_ARTICLE);
        $this->setParam("userSign", $userSign);

        $this->setSelectInfo('c.headImg,c.nickname,a.articleId,a.aTitle,a.aImg,a.aCmtCount,a.aSupportCount,a.aContent');
        $this->setSql($sql);
        return $this->find($page);

    }

    /**
     * 得到粉丝
     * @param $userId
     * @param $page
     * @return array
     */
    public function getAttentionFans($userId,$page)
    {
        $sql=sprintf("
            FROM user_base a
            LEFT JOIN user_attention b ON a.userSign = b.userSign
            WHERE b.relativeType=:relativeType AND b.relativeId=:userId AND b.status=1
        ");
        $this->setParam("relativeType", UserAttention::TYPE_FOR_USER);
        $this->setParam("userId", $userId);

        $this->setSelectInfo('a.headImg,a.nickname,a.intro,a.userSign');
        $this->setSql($sql);
        return $this->find($page);

    }

    /**
     * 得到粉丝数量
     * @param $userId
     * @return array
     */
    public function getAttentionFansCount($userId)
    {
        $sql=sprintf("
            SELECT COUNT(*) as numb  FROM user_base a
            LEFT JOIN user_attention b ON a.userSign = b.userSign
            WHERE b.relativeType=:relativeType AND b.relativeId=:userId AND b.status=1;
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":userId", $userId, PDO::PARAM_STR);
        $command->bindValue(":relativeType", UserAttention::TYPE_FOR_USER, PDO::PARAM_INT);
        return $command->queryOne();
    }
    /**
     * 得到关注用户数量
     * @param $userSign
     * @return array
     */
    public function getAttentionCount($userSign)
    {

        $sql=sprintf("
            SELECT COUNT(*) as numb  FROM user_attention a
            WHERE a.relativeType=:relativeType AND a.status=1 AND a.userSign=:userSign
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":userSign", $userSign, PDO::PARAM_STR);
        $command->bindValue(":relativeType", UserAttention::TYPE_FOR_USER, PDO::PARAM_INT);
        return $command->queryOne();
    }

    /**
     * 得到用户关注数量
     * @param $userSign
     * @return array
     */
    public function getCount($userSign)
    {

        $sql=sprintf("
            SELECT COUNT(*) as numb  FROM user_attention a
            WHERE a.status=1 AND a.userSign=:userSign AND  (a.relativeType=:travelTrip OR a.relativeType=:tp OR a.relativeType=:qa)
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":userSign", $userSign, PDO::PARAM_STR);
        $command->bindValue(":travelTrip", UserAttention::TYPE_COLLECT_FOR_TRAVEL, PDO::PARAM_INT);
        $command->bindValue(":tp", UserAttention::TYPE_FOR_TRAVEL_PICTURE, PDO::PARAM_INT);
        $command->bindValue(":qa", UserAttention::TYPE_FOR_QA, PDO::PARAM_INT);
        return $command->queryOne();
    }


}