<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/4/20
 * Time: 下午6:00
 */

namespace frontend\models;


use common\models\ProxyDb;
use frontend\entity\CircleArticleEntity;
use yii\db\mssql\PDO;
use frontend\entity\CircleCommentEntity;

class CircleDb extends ProxyDb
{
    /**
     * 添加文章
     * @param CircleArticleEntity $CircleArticleEntity
     * @return int
     * @throws \yii\db\Exception
     */
    public function addArticle(CircleArticleEntity $CircleArticleEntity)
    {
        $sql = sprintf("
            INSERT INTO circle_article
            (
             cId,aTitle,aContent,aImg,aCmtCount,aSupportCount,aCreateUserId,aCreateTime,aLastUpdateTime,aStatus,aAddr
            )
            VALUES
            (
              :cId,:aTitle,:aContent,:aImg,:aCmtCount,:aSupportCount,:aCreateUserId,now(),now(),:aStatus,:aAddr
            )
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":cId", $CircleArticleEntity->cId, PDO::PARAM_INT);
        $command->bindParam(":aTitle", $CircleArticleEntity->aTitle, PDO::PARAM_STR);
        $command->bindParam(":aContent", $CircleArticleEntity->aContent, PDO::PARAM_STR);
        $command->bindParam(":aImg", $CircleArticleEntity->aImg, PDO::PARAM_STR);
        $command->bindParam(":aCmtCount", $CircleArticleEntity->aCmtCount, PDO::PARAM_INT);
        $command->bindParam(":aSupportCount", $CircleArticleEntity->aSupportCount, PDO::PARAM_INT);
        $command->bindParam(":aCreateUserId", $CircleArticleEntity->aCreateUserId, PDO::PARAM_INT);
        $command->bindValue(":aStatus", CircleArticleEntity::ARTICLE_STATUS_NORMAL, PDO::PARAM_INT);
        $command->bindParam(":aAddr", $CircleArticleEntity->aAddr, PDO::PARAM_STR);

        return $command->execute();

    }

    /**
     * 删除圈子文章
     * @param $articleId
     * @return int|bool
     */
    public function deleteArticleById($articleId,$userId)
    {
        $sql = sprintf("
            UPDATE  circle_article SET
              aStatus=:aStatus
            WHERE articleId=:articleId AND aCreateUserId=:aCreateUserId

        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindValue(":aStatus", CircleArticleEntity::ARTICLE_STATUS_DISABLED, PDO::PARAM_INT);
        $command->bindParam(":articleId", $articleId, PDO::PARAM_INT);
        $command->bindParam(":aCreateUserId", $userId, PDO::PARAM_INT);
        return $command->execute();
    }



    /**
     * 更新圈子文章
     * @param CircleArticleEntity $CircleArticleEntity
     * @return int
     * @throws \yii\db\Exception
     */
    public function updateArticleInfo(CircleArticleEntity $CircleArticleEntity)
    {


        $sql = sprintf("
            UPDATE  circle_article SET
              aTitle=:aTitle,aImg=:aImg,aContent=:aContent,aLastUpdateTime=now(),aAddr=:aAddr
            WHERE articleId=:articleId AND aCreateUserId=:aCreateUserId

        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":aTitle", $CircleArticleEntity->aTitle, PDO::PARAM_INT);
        $command->bindParam(":aImg", $CircleArticleEntity->aImg, PDO::PARAM_STR);
        $command->bindParam(":aContent", $CircleArticleEntity->aContent, PDO::PARAM_STR);
        $command->bindParam(":aAddr", $CircleArticleEntity->aAddr, PDO::PARAM_INT);
        $command->bindParam(":articleId", $CircleArticleEntity->articleId, PDO::PARAM_INT);
        $command->bindParam(":aCreateUserId", $CircleArticleEntity->aCreateUserId, PDO::PARAM_INT);
        return $command->execute();
    }
    /**
     * 更新圈子文章评论数
     * @param CircleArticleEntity $CircleArticleEntity
     * @return int
     * @throws \yii\db\Exception
     */
    public function upDateArticleCommentNumb(CircleArticleEntity $CircleArticleEntity)
    {


        $sql = sprintf("
            UPDATE  circle_article SET
            aCmtCount=:aCmtCount,aSupportCount=:aSupportCount
            WHERE articleId=:articleId AND aCreateUserId=:aCreateUserId

        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":aCmtCount", $CircleArticleEntity->aCmtCount, PDO::PARAM_INT);
        $command->bindParam(":aSupportCount", $CircleArticleEntity->aSupportCount, PDO::PARAM_INT);
        $command->bindParam(":articleId", $CircleArticleEntity->articleId, PDO::PARAM_INT);
        $command->bindParam(":aCreateUserId", $CircleArticleEntity->aCreateUserId, PDO::PARAM_INT);
        return $command->execute();
    }
    /**
     * 查找文章
     * @param $articleId
     * @return array
     * @throws \yii\db\Exception
     */
    public function getArticleInfoById($articleId)
    {
        $sql=sprintf("
            SELECT a.*,b.nickname,b.headImg FROM circle_article a
            Left JOIN user_base b ON a.aCreateUserId=b.userId
            WHERE articleId=:articleId AND aStatus=:aStatus
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":articleId", $articleId, PDO::PARAM_INT);
        $command->bindValue(":aStatus", CircleArticleEntity::ARTICLE_STATUS_NORMAL, PDO::PARAM_INT);
        return $command->queryOne();
    }
    /**
     * 查找文章根据圈子id
     * @param $circleId
     * @return array
     * @throws \yii\db\Exception
     */
    public function getArticleListByCircleId($circleId)
    {

        $sql=sprintf("
          SELECT a.cId,a.aTitle,a.aImg,a.aCmtCount,a.aSupportCount,a.aCreateUserId,a.aCreateTime,a.aLastUpdateTime,a.aStatus,a.aAddr,b.nickname,b.headImg FROM circle_article a
          Left JOIN user_base b ON a.aCreateUserId=b.userId WHERE cId=:cId AND aStatus=:aStatus
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":cId", $circleId, PDO::PARAM_INT);
        $command->bindValue(":aStatus", CircleArticleEntity::ARTICLE_STATUS_NORMAL, PDO::PARAM_INT);
        return $command->queryAll();
    }

    /**
     * 查找圈子
     *
     * @return array
     * @throws \yii\db\Exception
     */
    public function getCircleByType($type)
    {
        $sql=sprintf("
            SELECT * FROM sys_circle_sort WHERE cType=:cType;
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":cType", $type, PDO::PARAM_INT);
        return $command->queryAll();
    }

    /**
     * 添加评论
     * @param CircleCommentEntity $CircleCommentEntity
     * @return int
     * @throws \yii\db\Exception
     */
    public function addComment(CircleCommentEntity $CircleCommentEntity)
    {
        $sql = sprintf("
            INSERT INTO circle_article_comment
            (
             userId,content,relativeCommentId,supportCount,opposeCount,cTime,cStatus,cLastTime,articleId
            )
            VALUES
            (
              :userId,:content,:relativeCommentId,:supportCount,:opposeCount,now(),:cStatus,now(),:articleId
            )
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":userId", $CircleCommentEntity->userId, PDO::PARAM_INT);
        $command->bindParam(":content", $CircleCommentEntity->content, PDO::PARAM_STR);
        $command->bindParam(":relativeCommentId", $CircleCommentEntity->relativeCommentId, PDO::PARAM_INT);
        $command->bindParam(":supportCount", $CircleCommentEntity->supportCount, PDO::PARAM_INT);
        $command->bindParam(":opposeCount", $CircleCommentEntity->opposeCount, PDO::PARAM_INT);
        $command->bindValue(":cStatus", CircleCommentEntity::COMMENT_STATUS_NORMAL, PDO::PARAM_INT);
        $command->bindValue(":articleId",$CircleCommentEntity->articleId, PDO::PARAM_INT);
        return $command->execute();

    }

    /**
     * 删除评论
     * @param $commentId
     * @return array|bool
     */
    public function deleteCommentById($commentId,$userId)
    {
        $sql = sprintf("
            UPDATE  circle_article_comment SET
              cStatus=:cStatus
            WHERE commentId=:commentId AND userId=:userId

        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindValue(":cStatus", CircleCommentEntity::COMMENT_STATUS_DISABLED, PDO::PARAM_INT);
        $command->bindParam(":commentId", $commentId, PDO::PARAM_INT);
        $command->bindParam(":userId", $userId, PDO::PARAM_INT);
        return $command->execute();
    }



    /**
     * 更新评论
     * @param CircleCommentEntity $CircleCommentEntity
     * @return int
     * @throws \yii\db\Exception
     */
    public function updateCircleComment(CircleCommentEntity $CircleCommentEntity)
    {


        $sql = sprintf("
            UPDATE  circle_article_comment SET
              content=:content,cLastTime=now()
            WHERE commentId=:commentId AND userId=:userId

        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":content", $CircleCommentEntity->content, PDO::PARAM_STR);
        $command->bindParam(":commentId", $CircleCommentEntity->commentId, PDO::PARAM_INT);
        $command->bindParam(":userId", $CircleCommentEntity->userId, PDO::PARAM_INT);
        return $command->execute();
    }

    /**
     * 更新评论支持反对数
     * @param CircleCommentEntity $CircleCommentEntity
     * @return int
     * @throws \yii\db\Exception
     */
    public function updateCircleCommentNumb(CircleCommentEntity $CircleCommentEntity)
    {


        $sql = sprintf("
            UPDATE  circle_article_comment SET
              supportCount=:supportCount,opposeCount=:opposeCount
            WHERE commentId=:commentId AND userId=:userId

        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":supportCount", $CircleCommentEntity->supportCount, PDO::PARAM_INT);
        $command->bindParam(":opposeCount", $CircleCommentEntity->opposeCount, PDO::PARAM_INT);
        $command->bindParam(":commentId", $CircleCommentEntity->commentId, PDO::PARAM_INT);
        $command->bindParam(":userId", $CircleCommentEntity->userId, PDO::PARAM_INT);
        return $command->execute();
    }
    /**
     * 查找文章下所有评论
     * @param $articleId
     * @return array
     * @throws \yii\db\Exception
     */
    public function getCircleCommentByArticleId($articleId)
    {
        $sql=sprintf("
            SELECT a.*,b.nickname,b.headImg FROM circle_article_comment a
            Left JOIN user_base b ON a.userId=b.userId
            WHERE articleId=:articleId AND cStatus=:cStatus
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":articleId", $articleId, PDO::PARAM_INT);
        $command->bindValue(":cStatus", CircleCommentEntity::COMMENT_STATUS_NORMAL, PDO::PARAM_INT);
        return $command->queryAll();
    }

    /**
     * 删除文章下所有
     * @param $articleId
     * @return array|bool
     */
    public function deleteAllCommentById($articleId)
    {
        $sql = sprintf("
            UPDATE  circle_article_comment SET
              cStatus=:cStatus
            WHERE articleId=:articleId

        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindValue(":cStatus", CircleCommentEntity::COMMENT_STATUS_DISABLED, PDO::PARAM_INT);
        $command->bindParam(":articleId", $articleId, PDO::PARAM_INT);
        return $command->execute();
    }

}