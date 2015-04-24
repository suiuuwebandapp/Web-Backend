<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/4/20
 * Time: 下午6:00
 */

namespace frontend\models;


use common\models\ProxyDb;
use common\entity\CircleArticle;
use common\entity\UserBase;
use yii\db\mssql\PDO;
use common\entity\CircleComment;

class CircleDb extends ProxyDb
{
    /**
     * 添加文章
     * @param CircleArticle $CircleArticleEntity
     * @return int
     * @throws \yii\db\Exception
     */
    public function addArticle(CircleArticle $CircleArticleEntity)
    {
        $sql = sprintf("
            INSERT INTO circle_article
            (
             cId,aTitle,aContent,aImg,aCmtCount,aSupportCount,aCreateUserSign,aCreateTime,aLastUpdateTime,aStatus,aAddr
            )
            VALUES
            (
              :cId,:aTitle,:aContent,:aImg,:aCmtCount,:aSupportCount,:aCreateUserSign,now(),now(),:aStatus,:aAddr
            )
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":cId", $CircleArticleEntity->cId, PDO::PARAM_INT);
        $command->bindParam(":aTitle", $CircleArticleEntity->aTitle, PDO::PARAM_STR);
        $command->bindParam(":aContent", $CircleArticleEntity->aContent, PDO::PARAM_STR);
        $command->bindParam(":aImg", $CircleArticleEntity->aImg, PDO::PARAM_STR);
        $command->bindParam(":aCmtCount", $CircleArticleEntity->aCmtCount, PDO::PARAM_INT);
        $command->bindParam(":aSupportCount", $CircleArticleEntity->aSupportCount, PDO::PARAM_INT);
        $command->bindParam(":aCreateUserSign", $CircleArticleEntity->aCreateUserSign, PDO::PARAM_INT);
        $command->bindValue(":aStatus", $CircleArticleEntity::ARTICLE_STATUS_NORMAL, PDO::PARAM_INT);
        $command->bindParam(":aAddr", $CircleArticleEntity->aAddr, PDO::PARAM_STR);

        return $command->execute();

    }

    /**
     * 删除圈子文章
     * @param $articleId
     * @return int|bool
     */
    public function deleteArticleById($articleId,$userSign)
    {
        $sql = sprintf("
            UPDATE  circle_article SET
              aStatus=:aStatus
            WHERE articleId=:articleId AND aCreateUserSign=:aCreateUserSign

        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindValue(":aStatus", CircleArticle::ARTICLE_STATUS_DISABLED, PDO::PARAM_INT);
        $command->bindParam(":articleId", $articleId, PDO::PARAM_INT);
        $command->bindParam(":aCreateUserSign", $userSign, PDO::PARAM_INT);
        return $command->execute();
    }



    /**
     * 更新圈子文章
     * @param CircleArticle $CircleArticleEntity
     * @return int
     * @throws \yii\db\Exception
     */
    public function updateArticleInfo(CircleArticle $CircleArticleEntity)
    {


        $sql = sprintf("
            UPDATE  circle_article SET
              aTitle=:aTitle,aImg=:aImg,aContent=:aContent,aLastUpdateTime=now(),aAddr=:aAddr
            WHERE articleId=:articleId AND aCreateUserSign=:aCreateUserSign

        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":aTitle", $CircleArticleEntity->aTitle, PDO::PARAM_INT);
        $command->bindParam(":aImg", $CircleArticleEntity->aImg, PDO::PARAM_STR);
        $command->bindParam(":aContent", $CircleArticleEntity->aContent, PDO::PARAM_STR);
        $command->bindParam(":aAddr", $CircleArticleEntity->aAddr, PDO::PARAM_INT);
        $command->bindParam(":articleId", $CircleArticleEntity->articleId, PDO::PARAM_INT);
        $command->bindParam(":aCreateUserSign", $CircleArticleEntity->aCreateUserSign, PDO::PARAM_INT);
        return $command->execute();
    }
    /**
     * 更新圈子文章评论数
     * @param CircleArticle $CircleArticleEntity
     * @return int
     * @throws \yii\db\Exception
     */
    public function upDateArticleCommentNumb(CircleArticle $CircleArticleEntity)
    {


        $sql = sprintf("
            UPDATE  circle_article SET
            aCmtCount=:aCmtCount,aSupportCount=:aSupportCount
            WHERE articleId=:articleId AND aCreateUserSign=:aCreateUserSign

        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":aCmtCount", $CircleArticleEntity->aCmtCount, PDO::PARAM_INT);
        $command->bindParam(":aSupportCount", $CircleArticleEntity->aSupportCount, PDO::PARAM_INT);
        $command->bindParam(":articleId", $CircleArticleEntity->articleId, PDO::PARAM_INT);
        $command->bindParam(":aCreateUserSign", $CircleArticleEntity->aCreateUserSign, PDO::PARAM_INT);
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
            Left JOIN user_base b ON a.aCreateUserSign=b.userSign
            WHERE articleId=:articleId AND aStatus=:aStatus AND b.status=:userStatus;
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":articleId", $articleId, PDO::PARAM_INT);
        $command->bindValue(":aStatus", CircleArticle::ARTICLE_STATUS_NORMAL, PDO::PARAM_INT);
        $command->bindValue(":userStatus", UserBase::USER_STATUS_NORMAL, PDO::PARAM_INT);
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
          SELECT a.cId,a.aTitle,a.aImg,a.aCmtCount,a.aSupportCount,a.aCreateUserSign,a.aCreateTime,a.aLastUpdateTime,a.aStatus,a.aAddr,b.nickname,b.headImg FROM circle_article a
          Left JOIN user_base b ON a.aCreateUserSign=b.userSign WHERE cId=:cId AND aStatus=:aStatus AND b.status=:userStatus
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":cId", $circleId, PDO::PARAM_INT);
        $command->bindValue(":aStatus", CircleArticle::ARTICLE_STATUS_NORMAL, PDO::PARAM_INT);
        $command->bindValue(":userStatus", UserBase::USER_STATUS_NORMAL, PDO::PARAM_INT);
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
     * @param CircleComment $CircleCommentEntity
     * @return int
     * @throws \yii\db\Exception
     */
    public function addComment(CircleComment $CircleCommentEntity)
    {
        $sql = sprintf("
            INSERT INTO circle_article_comment
            (
             userSign,content,relativeCommentId,supportCount,opposeCount,cTime,cStatus,cLastTime,articleId
            )
            VALUES
            (
              :userSign,:content,:relativeCommentId,:supportCount,:opposeCount,now(),:cStatus,now(),:articleId
            )
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":userSign", $CircleCommentEntity->userSign, PDO::PARAM_INT);
        $command->bindParam(":content", $CircleCommentEntity->content, PDO::PARAM_STR);
        $command->bindParam(":relativeCommentId", $CircleCommentEntity->relativeCommentId, PDO::PARAM_INT);
        $command->bindParam(":supportCount", $CircleCommentEntity->supportCount, PDO::PARAM_INT);
        $command->bindParam(":opposeCount", $CircleCommentEntity->opposeCount, PDO::PARAM_INT);
        $command->bindValue(":cStatus", CircleComment::COMMENT_STATUS_NORMAL, PDO::PARAM_INT);
        $command->bindValue(":articleId",$CircleCommentEntity->articleId, PDO::PARAM_INT);
        return $command->execute();

    }

    /**
     * 删除评论
     * @param $commentId
     * @return array|bool
     */
    public function deleteCommentById($commentId,$userSign)
    {
        $sql = sprintf("
            UPDATE  circle_article_comment SET
              cStatus=:cStatus
            WHERE commentId=:commentId AND userSign=:userSign

        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindValue(":cStatus", CircleComment::COMMENT_STATUS_DISABLED, PDO::PARAM_INT);
        $command->bindParam(":commentId", $commentId, PDO::PARAM_INT);
        $command->bindParam(":userSign", $userSign, PDO::PARAM_INT);
        return $command->execute();
    }



    /**
     * 更新评论
     * @param CircleComment $CircleCommentEntity
     * @return int
     * @throws \yii\db\Exception
     */
    public function updateCircleComment(CircleComment $CircleCommentEntity)
    {


        $sql = sprintf("
            UPDATE  circle_article_comment SET
              content=:content,cLastTime=now()
            WHERE commentId=:commentId AND userSign=:userSign

        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":content", $CircleCommentEntity->content, PDO::PARAM_STR);
        $command->bindParam(":commentId", $CircleCommentEntity->commentId, PDO::PARAM_INT);
        $command->bindParam(":userSign", $CircleCommentEntity->userSign, PDO::PARAM_INT);
        return $command->execute();
    }

    /**
     * 更新评论支持反对数
     * @param CircleComment $CircleCommentEntity
     * @return int
     * @throws \yii\db\Exception
     */
    public function updateCircleCommentNumb(CircleComment $CircleCommentEntity)
    {


        $sql = sprintf("
            UPDATE  circle_article_comment SET
              supportCount=:supportCount,opposeCount=:opposeCount
            WHERE commentId=:commentId AND userSign=:userSign

        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":supportCount", $CircleCommentEntity->supportCount, PDO::PARAM_INT);
        $command->bindParam(":opposeCount", $CircleCommentEntity->opposeCount, PDO::PARAM_INT);
        $command->bindParam(":commentId", $CircleCommentEntity->commentId, PDO::PARAM_INT);
        $command->bindParam(":userSign", $CircleCommentEntity->userSign, PDO::PARAM_INT);
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
            Left JOIN user_base b ON a.userSign=b.userSign
            WHERE articleId=:articleId AND cStatus=:cStatus AND b.status=:userStatus
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":articleId", $articleId, PDO::PARAM_INT);
        $command->bindValue(":cStatus", CircleComment::COMMENT_STATUS_NORMAL, PDO::PARAM_INT);
        $command->bindValue(":userStatus", UserBase::USER_STATUS_NORMAL, PDO::PARAM_INT);
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
        $command->bindValue(":cStatus", CircleComment::COMMENT_STATUS_DISABLED, PDO::PARAM_INT);
        $command->bindParam(":articleId", $articleId, PDO::PARAM_INT);
        return $command->execute();
    }

}