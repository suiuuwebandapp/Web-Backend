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
use frontend\components\Page;
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
             cId,aTitle,aContent,aImg,aCmtCount,aSupportCount,aCreateUserSign,aCreateTime,aLastUpdateTime,aStatus,aAddr,aImgList,aType,cAddrId
            )
            VALUES
            (
              :cId,:aTitle,:aContent,:aImg,:aCmtCount,:aSupportCount,:aCreateUserSign,now(),now(),:aStatus,:aAddr,:aImgList,:aType,:cAddrId
            )
        ");
        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":cId", $CircleArticleEntity->cId, PDO::PARAM_INT);
        $command->bindParam(":aTitle", $CircleArticleEntity->aTitle, PDO::PARAM_STR);
        $command->bindParam(":aContent", $CircleArticleEntity->aContent, PDO::PARAM_STR);
        $command->bindParam(":aImg", $CircleArticleEntity->aImg, PDO::PARAM_STR);
        $command->bindParam(":aCmtCount", $CircleArticleEntity->aCmtCount, PDO::PARAM_INT);
        $command->bindParam(":aSupportCount", $CircleArticleEntity->aSupportCount, PDO::PARAM_INT);
        $command->bindParam(":aCreateUserSign", $CircleArticleEntity->aCreateUserSign, PDO::PARAM_STR);
        $command->bindValue(":aStatus", $CircleArticleEntity::ARTICLE_STATUS_NORMAL, PDO::PARAM_INT);
        $command->bindParam(":aAddr", $CircleArticleEntity->aAddr, PDO::PARAM_STR);
        $command->bindParam(":aImgList", $CircleArticleEntity->aImgList, PDO::PARAM_STR);
        $command->bindParam(":aType", $CircleArticleEntity->aType, PDO::PARAM_INT);
        $command->bindParam(":cAddrId", $CircleArticleEntity->cAddrId, PDO::PARAM_INT);
        $command->execute();
        return $this->getConnection()->lastInsertID;

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
              aTitle=:aTitle,aImg=:aImg,aContent=:aContent,aLastUpdateTime=now(),aAddr=:aAddr,aImgList=:aImgList
            WHERE articleId=:articleId AND aCreateUserSign=:aCreateUserSign

        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":aTitle", $CircleArticleEntity->aTitle, PDO::PARAM_INT);
        $command->bindParam(":aImg", $CircleArticleEntity->aImg, PDO::PARAM_STR);
        $command->bindParam(":aContent", $CircleArticleEntity->aContent, PDO::PARAM_STR);
        $command->bindParam(":aAddr", $CircleArticleEntity->aAddr, PDO::PARAM_INT);
        $command->bindParam(":articleId", $CircleArticleEntity->articleId, PDO::PARAM_INT);
        $command->bindParam(":aCreateUserSign", $CircleArticleEntity->aCreateUserSign, PDO::PARAM_INT);
        $command->bindParam(":aImgList", $CircleArticleEntity->aImgList, PDO::PARAM_STR);
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
     * 查找文章根据用户sign
     * @param $userSign
     * @param $page
     * @return array
     * @throws \yii\db\Exception
     */
    public function getArticleListByUserSign($userSign,$page)
    {
        $sql=sprintf("
         FROM circle_article a
            WHERE aCreateUserSign=:aCreateUserSign AND aStatus=:aStatus ORDER BY a.articleId DESC
        ");
        $this->setParam("aCreateUserSign", $userSign);
        $this->setParam("aStatus", CircleArticle::ARTICLE_STATUS_NORMAL);
        $this->setSelectInfo('a.cId,a.cAddrId,a.aTitle,a.aImg,a.aCmtCount,a.aSupportCount,a.articleId');

        $this->setSql($sql);
        return $this->find($page);
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
            SELECT a.cId,a.cAddrId,a.aTitle,a.aContent,a.aImg,a.aCmtCount,a.aSupportCount,a.aCreateUserSign,a.aCreateTime,a.aLastUpdateTime,a.aStatus,a.aAddr,a.aImgList,a.aType,b.nickname,b.headImg,a.articleId FROM circle_article a
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
     * 查找文章根据地区id
     * @param $cAddrId
     *  @param $page
     * @return array
     * @throws \yii\db\Exception
     */
    public function getArticleListByAddrId($cAddrId,Page $page)
    {
        $sql=sprintf("
         FROM circle_article a
          Left JOIN user_base b ON a.aCreateUserSign=b.userSign WHERE cAddrId=:cAddrId AND aStatus=:aStatus AND b.status=:userStatus
        ");
        $this->setParam("cAddrId", $cAddrId);
        $this->setParam("aStatus", CircleArticle::ARTICLE_STATUS_NORMAL);
        $this->setParam("userStatus", UserBase::USER_STATUS_NORMAL);
        $this->setSelectInfo('a.articleId,a.cId,a.cAddrId,a.aTitle,a.aImg,a.aCmtCount,a.aSupportCount,a.aCreateUserSign,a.aCreateTime,a.aLastUpdateTime,a.aStatus,a.aAddr,a.aImgList,b.nickname,b.headImg ');

        $this->setSql($sql);
        return $this->find($page);

    }
    /**
     * 查找文章根据主题id
     * @param $circleId
     *  @param Page $page
     * @return array
     * @throws \yii\db\Exception
     */
    public function getArticleListByThemeId($circleId,Page $page)
    {
        $sql=sprintf("
         FROM circle_article a
          Left JOIN user_base b ON a.aCreateUserSign=b.userSign WHERE cId=:cId AND aStatus=:aStatus AND b.status=:userStatus ORDER BY a.articleId DESC
        ");
        $this->setParam("cId", $circleId);
        $this->setParam("aStatus", CircleArticle::ARTICLE_STATUS_NORMAL);
        $this->setParam("userStatus", UserBase::USER_STATUS_NORMAL);
        $this->setSelectInfo('a.articleId,a.cId,a.cAddrId,a.aTitle,a.aImg,a.aCmtCount,a.aSupportCount,a.aCreateUserSign,a.aCreateTime,a.aLastUpdateTime,a.aStatus,a.aAddr,a.aImgList,b.nickname,b.headImg');

        $this->setSql($sql);
        return $this->find($page);
    }
    /**
     * 查找圈子
     * @param $type
     * @param $page
     * @return array
     * @throws \yii\db\Exception
     */
    public function getCircleByType($type,Page $page)
    {
        $sql=sprintf("
        FROM sys_circle_sort WHERE cType=:cType
        ");
        $this->setParam("cType", $type);
        $this->setSql($sql);
        return $this->find($page);
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
        $command->bindParam(":userSign", $CircleCommentEntity->userSign, PDO::PARAM_STR);
        $command->bindParam(":content", $CircleCommentEntity->content, PDO::PARAM_STR);
        $command->bindParam(":relativeCommentId", $CircleCommentEntity->relativeCommentId, PDO::PARAM_INT);
        $command->bindParam(":supportCount", $CircleCommentEntity->supportCount, PDO::PARAM_INT);
        $command->bindParam(":opposeCount", $CircleCommentEntity->opposeCount, PDO::PARAM_INT);
        $command->bindValue(":cStatus", CircleComment::COMMENT_STATUS_NORMAL, PDO::PARAM_INT);
        $command->bindValue(":articleId",$CircleCommentEntity->articleId, PDO::PARAM_INT);
        $command->execute();

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
        $command->bindParam(":userSign", $userSign, PDO::PARAM_STR);
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
        $command->bindParam(":userSign", $CircleCommentEntity->userSign, PDO::PARAM_STR);
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
            WHERE commentId=:commentId

        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":supportCount", $CircleCommentEntity->supportCount, PDO::PARAM_INT);
        $command->bindParam(":opposeCount", $CircleCommentEntity->opposeCount, PDO::PARAM_INT);
        $command->bindParam(":commentId", $CircleCommentEntity->commentId, PDO::PARAM_INT);
        //$command->bindParam(":userSign", $CircleCommentEntity->userSign, PDO::PARAM_STR);
        return $command->execute();
    }
    /**
     * 查找文章下所有评论
     * @param $articleId
     * @param $page
     * @return array
     * @throws \yii\db\Exception
     */
    public function getCircleCommentByArticleId($articleId,$page)
    {
        $sql=sprintf("
         FROM circle_article_comment a
            Left JOIN user_base b ON a.userSign=b.userSign
            WHERE articleId=:articleId AND cStatus=:cStatus AND b.status=:userStatus  ORDER BY a.commentId DESC
        ");
        $this->setParam("articleId", $articleId);
        $this->setParam("cStatus", CircleComment::COMMENT_STATUS_NORMAL);
        $this->setParam("userStatus", UserBase::USER_STATUS_NORMAL);
        $this->setSelectInfo('a.*,b.nickname,b.headImg');

        $this->setSql($sql);
        return $this->find($page);

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


    /**
     * 得到搜索结果
     * @param $str
     * @return array|bool
     */
    public function getSeekResult($str,$page)
    {
        $sql=sprintf("
         FROM circle_article a LEFT JOIN user_base b
ON a.aCreateUserSign = b.userSign WHERE b.`status`=:userStatus AND a.aStatus=:aStatus AND a.aTitle LIKE '%:str%'
        ");
        $this->setParam("str", $str);
        $this->setParam("aStatus", CircleArticle::ARTICLE_STATUS_NORMAL);
        $this->setParam("userStatus", UserBase::USER_STATUS_NORMAL);
        $this->setSelectInfo('a.articleId,a.aImg,a.aTitle,b.nickname,b.headImg');

        $this->setSql($sql);
        return $this->find($page);
    }
}