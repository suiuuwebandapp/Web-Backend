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
}