<?php
namespace common\models;
use common\entity\CircleArticle;
use common\entity\RecommendList;
use common\entity\UserAttention;
use common\entity\UserBase;
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
     */
    public function addUserAttention($relativeId,$relativeType,$userSign)
    {
        $sql=sprintf("
            INSERT INTO user_attention (relativeId,relativeType,status,addTime,userSign) VALUES (:relativeId,:relativeType,:status,now(),:userSign);
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":relativeId", $relativeId, PDO::PARAM_INT);
        $command->bindParam(":relativeType", $relativeType, PDO::PARAM_INT);
        $command->bindParam(":userSign", $userSign, PDO::PARAM_STR);
        $command->bindValue(":status", UserAttention::ATTENTION_STATUS_NORMAL, PDO::PARAM_INT);
        $command->execute();
    }

    /**
     * 取消关注
     * @param $attentionId
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
        $command->execute();
    }
    /**
     * 查找关注圈子文章
     * @param $userSign
     * @return array
     * @throws \yii\db\Exception
     */
    public function getAttentionCircleArticle($userSign,$page)
    {
        $sql=sprintf("
            SELECT a.articleId,a.aContent,a.aTitle,a.aImg FROM  circle_article a
            LEFT JOIN user_base b ON b.userSign = a.aCreateUserSign
            LEFT JOIN user_attention c ON c.relativeId = a.articleId
            LEFT JOIN user_base d ON d.userSign = c.userSign
            WHERE b.`status`=:userStatus AND c.relativeType=:relativeType AND a.aStatus=:aStatus AND c.`status`=:attentionStatus AND c.userSign=:userSign

        ");
        $sql.=$page;
        $command=$this->getConnection()->createCommand($sql);
        $command->bindValue(":userStatus", UserBase::USER_STATUS_NORMAL, PDO::PARAM_INT);
        $command->bindValue(":relativeType", UserAttention::TYPE_FOR_CIRCLE_ARTICLE, PDO::PARAM_INT);
        $command->bindValue(":aStatus", CircleArticle::ARTICLE_STATUS_NORMAL, PDO::PARAM_INT);
        $command->bindValue(":attentionStatus", UserAttention::ATTENTION_STATUS_NORMAL, PDO::PARAM_INT);
        $command->bindParam(":userSign", $userSign, PDO::PARAM_STR);
        return $command->queryAll();
    }

    /**
     * 查找关注圈子
     * @param $userSign
     * @return array
     */
    public function getAttentionCircle($userSign,$page)
    {
        $sql=sprintf("
            SELECT a.cName,a.cId,a.cpic FROM  sys_circle_sort a
            LEFT JOIN user_attention c ON c.relativeId = a.cId
            LEFT JOIN user_base b ON c.userSign =b.userSign
            WHERE b.`status`=:userStatus AND c.relativeType=:relativeType AND c.`status`=:attentionStatus AND c.userSign=:userSign

        ");
        $sql.=$page;
        $command=$this->getConnection()->createCommand($sql);
        $command->bindValue(":userStatus", UserBase::USER_STATUS_NORMAL, PDO::PARAM_INT);
        $command->bindValue(":relativeType", UserAttention::TYPE_FOR_CIRCLE, PDO::PARAM_INT);
        $command->bindValue(":attentionStatus", UserAttention::ATTENTION_STATUS_NORMAL, PDO::PARAM_INT);
        $command->bindParam(":userSign", $userSign, PDO::PARAM_STR);
        return $command->queryAll();
    }

    /**
     * 查找关注用户
     * @param $userSign
     * @return array
     */
    public function getAttentionUser($userSign,$page)
    {
        $sql=sprintf("
           SELECT a.nickname,a.headImg,a.intro,a.hobby FROM  user_base a
            LEFT JOIN user_attention b ON b.relativeId = a.userId
            WHERE a.`status`=:userStatus AND b.relativeType=:relativeType  AND b.`status`=:attentionStatus AND b.userSign=:userSign
        ");
        $sql.=$page;
        $command=$this->getConnection()->createCommand($sql);
        $command->bindValue(":userStatus", UserBase::USER_STATUS_NORMAL, PDO::PARAM_INT);
        $command->bindValue(":relativeType", UserAttention::TYPE_FOR_USER, PDO::PARAM_INT);
        $command->bindValue(":attentionStatus", UserAttention::ATTENTION_STATUS_NORMAL, PDO::PARAM_INT);
        $command->bindParam(":userSign", $userSign, PDO::PARAM_STR);
        return $command->queryAll();
    }

    /**
     * 得到关注结果
     * @param UserAttention $userAttention
     * @return array|bool
     */
    public function getAttentionResult(UserAttention $userAttention)
    {
        $sql=sprintf("
           SELECT attentionId,relativeId,relativeType,status,addTime,userSign FROM  user_attention
            WHERE userSign=:userSign AND relativeType=:relativeType AND relativeId=:relativeId AND status=:attentionStatus
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":userSign", $userAttention->userSign, PDO::PARAM_STR);
        $command->bindParam(":relativeType", $userAttention->relativeType, PDO::PARAM_INT);
        $command->bindParam(":relativeId", $userAttention->relativeId, PDO::PARAM_INT);
        $command->bindValue(":attentionStatus", UserAttention::ATTENTION_STATUS_NORMAL, PDO::PARAM_INT);
        return $command->queryOne();
    }
    /**
     * 得到收藏随游列表
     * @param $userSign
     * @return array|bool
     */
    public function getCollectTravelList($userSign,$page)
    {
        $sql=sprintf("

        ");
        $sql.=$page;
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":userSign",$userSign, PDO::PARAM_STR);
        $command->bindValue(":relativeType", UserAttention::TYPE_COLLECT_FOR_TRAVEL, PDO::PARAM_INT);
        $command->bindValue(":attentionStatus", UserAttention::ATTENTION_STATUS_NORMAL, PDO::PARAM_INT);
        return $command->queryAll();
    }
    /**
     * 得到收藏圈子文章列表
     * @param $page
     * @param $userSign
     * @return array|bool
     */
    public function getCollectArticleList($userSign,$page)
    {
        $sql=sprintf("
            SELECT c.headImg,c.nickname,a.articleId,a.aTitle,a.aImg,a.aCmtCount,a.aSupportCount,a.aContent FROM circle_article a
            LEFT JOIN user_attention b ON a.articleId = b.relativeId
            LEFT JOIN user_base c ON c.userSign=a.aCreateUserSign
            LEFT JOIN user_base d  ON d.userSign=b.userSign
            WHERE a.aStatus=1 AND b.`status`=1 AND c.`status`=1 AND b.relativeType=:relativeType AND b.userSign=:userSign
        ");
        $sql.=$page;
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":userSign", $userSign, PDO::PARAM_STR);
        $command->bindValue(":relativeType", UserAttention::TYPE_COLLECT_FOR_ARTICLE, PDO::PARAM_INT);
        return $command->queryAll();
    }

    /**
     * 得到粉丝
     * @param $page
     * @param $userSign
     * @return array|bool
     */
    public function getAttentionFans($userSign,$page)
    {
        $sql=sprintf("
           SELECT  FROM user_base a LEFT JOIN user_attention b ON a.userSign = b.userSign
           WHERE b.relativeType=4 AND b.relativeId=1;
        ");
        $sql.=$page;
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":userSign", $userSign, PDO::PARAM_STR);
        $command->bindValue(":relativeType", UserAttention::TYPE_COLLECT_FOR_ARTICLE, PDO::PARAM_INT);
        return $command->queryAll();
    }

}