<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/6/2
 * Time: 上午10:53
 */

namespace common\models;


use common\entity\CircleArticle;
use common\entity\CircleSort;
use yii\db\mssql\PDO;

class CircleDb extends ProxyDb{

    public function getCircleList($page,$search,$type)
    {
        $sql=sprintf("
        FROM sys_circle_sort
            WHERE 1=1
        ");
        if(!empty($type))
        {
            $sql.=" AND cType = :cType ";
            $this->setParam("cType",$type);
        }
        if(!empty($search))
        {
            $sql.=" AND cName like :search ";
            $this->setParam("search","%".$search."%");
        }
        $this->setSql($sql);
        return $this->find($page);
    }

    /**
     * 添加圈子
     * @param CircleSort $circleSort
     * @return int
     * @throws \yii\db\Exception
     */
    public function addCircleSort(CircleSort $circleSort)
    {
        $sql = sprintf("
            INSERT INTO sys_circle_sort
            (
            cName,cType,cpic,cStatus
            )
            VALUES
            (
            :cName,:cType,:cpic,:cStatus
            )
        ");
        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":cName", $circleSort->cName, PDO::PARAM_STR);
        $command->bindParam(":cType", $circleSort->cType, PDO::PARAM_INT);
        $command->bindParam(":cpic", $circleSort->cpic, PDO::PARAM_STR);
        $command->bindValue(":cStatus", CircleSort::STATUS_NORMAL, PDO::PARAM_INT);
        $command->execute();
    }

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

    }


    public function delete($id)
    {
        $sql = sprintf("
            DELETE FROM sys_circle_sort
            WHERE cId=:cId
        ");
        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":cId", $id, PDO::PARAM_INT);
        $command->execute();
    }
    public function change($id,$status)
    {
        $sql = sprintf("
            UPDATE sys_circle_sort
             SET cStatus=:cStatus
            WHERE cId=:cId
        ");
        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":cId", $id, PDO::PARAM_INT);
        $command->bindParam(":cStatus",$status, PDO::PARAM_INT);
        $command->execute();
    }

    public function edit(CircleSort $circleSort)
    {
        $sql = sprintf("
            UPDATE sys_circle_sort
             SET cName=:cName,cType=:cType,cpic=:cpic
            WHERE cId=:cId
        ");
        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":cId", $circleSort->cId, PDO::PARAM_INT);
        $command->bindParam(":cName", $circleSort->cName, PDO::PARAM_STR);
        $command->bindParam(":cType", $circleSort->cType, PDO::PARAM_INT);
        $command->bindParam(":cpic", $circleSort->cpic, PDO::PARAM_STR);
        $command->execute();
    }

    public function getInfo($id)
    {
        $sql = sprintf("
            SELECT * FROM sys_circle_sort
            WHERE cId=:cId
        ");
        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":cId", $id, PDO::PARAM_INT);
        return $command->queryOne();
    }

    public function getArticleList($page,$search,$type,$cId,$status)
    {
        $sql=sprintf("
        FROM circle_article a
        LEFT JOIN user_base  b ON a.aCreateUserSign=b.userSign
        LEFT JOIN sys_circle_sort  c ON c.cId=a.cId
        LEFT JOIN sys_circle_sort  d ON d.cId=a.cAddrId
        WHERE 1=1
        ");
        if(!empty($type))
        {
            $sql.=" AND aType = :aType ";
            $this->setParam("aType",$type);
        }
        if(!empty($cId))
        {
            $sql.=" AND (a.cId = :cId OR a.cAddrId=:cId)";
            $this->setParam("cId",$cId);
        }
        if(!empty($status))
        {
            $sql.=" AND a.aStatus=:aStatus";
            $this->setParam("aStatus",$status);
        }
        if(!empty($search))
        {
            $sql.=" AND (b.nickname like :search OR a.aTitle like :search  OR c.cName like :search OR d.cName like :search )";
            $this->setParam("search","%".$search."%");
        }
        $this->setSelectInfo('a.*,b.nickname,c.cName as ztName,d.cName as dqName');
        $this->setSql($sql);
        return $this->find($page);
    }

    public function deleteArticle($id)
    {
        $sql = sprintf("
            UPDATE circle_article
             SET aStatus=:aStatus
            WHERE articleId=:articleId
        ");
        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":articleId",$id, PDO::PARAM_INT);
        $command->bindValue(":aStatus",CircleArticle::ARTICLE_STATUS_DISABLED, PDO::PARAM_INT);
        $command->execute();
    }

    public function getCommentList($page,$search,$status)
    {
        $sql=sprintf("
        FROM circle_article_comment a
        LEFT JOIN user_base  b ON a.userSign=b.userSign
        LEFT JOIN circle_article  c ON c.articleId=a.articleId
        WHERE 1=1
        ");
        if(!empty($status))
        {
            $sql.=" AND a.cStatus=:cStatus";
            $this->setParam("cStatus",$status);
        }
        if(!empty($search))
        {
            $sql.=" AND (b.nickname like :search OR c.aTitle like :search  OR a.content like :search )";
            $this->setParam("search","%".$search."%");
        }
        $this->setSelectInfo('a.*,b.nickname,c.aTitle');
        $this->setSql($sql);
        return $this->find($page);
    }
    public function getCommentInfoById()
    {
        $sql = sprintf("
            SELECT * FROM  circle_article_comment
            WHERE commentId=:commentId
        ");
        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":commentId",$id, PDO::PARAM_INT);
        return $command->queryOne();
    }

    public function deleteComment($id)
    {
        $sql = sprintf("
            UPDATE circle_article_comment
             SET cStatus=:cStatus
            WHERE commentId=:commentId
        ");
        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":commentId",$id, PDO::PARAM_INT);
        $command->bindValue(":cStatus",CircleArticle::ARTICLE_STATUS_DISABLED, PDO::PARAM_INT);
        $command->execute();
    }
}