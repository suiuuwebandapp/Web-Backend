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
class RecommendListDb extends ProxyDb
{

    /**
     * 添加关注
     * @param $relativeId
     * @param $relativeType
     * @param $userSign
     */
    public function AddUserAttention($relativeId,$relativeType,$userSign)
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
    public function DeleteUserAttention($attentionId,$userSign)
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
    public function getAttentionCircleArticle($userSign)
    {
        $sql=sprintf("
            SELECT a.articleId,a.aContent,a.aTitle,a.aImg FROM  circle_article a
            LEFT JOIN user_base b ON b.userSign = a.aCreateUserSign
            LEFT JOIN user_attention c ON c.relativeId = a.articleId AND c.userSign =b.userSign
            WHERE b.`status`=:userStatus AND c.relativeType=:relativeType AND a.aStatus=:aStatus AND c.`status`=:attentionStatus AND c.userSign=:userSign;

        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindValue(":userStatus", UserBase::USER_STATUS_NORMAL, PDO::PARAM_INT);
        $command->bindValue(":relativeType", UserAttention::TYPE_FOR_CIRCLE_ARTICLE, PDO::PARAM_INT);
        $command->bindValue(":aStatus", CircleArticle::ARTICLE_STATUS_NORMAL, PDO::PARAM_INT);
        $command->bindValue(":attentionStatus", UserAttention::ATTENTION_STATUS_NORMAL, PDO::PARAM_INT);
        $command->bindParam(":userSign", $userSign, PDO::PARAM_STR);
        return $command->queryAll();
    }


}