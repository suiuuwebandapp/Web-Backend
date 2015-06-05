<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/21
 * Time: 下午6:33
 */

namespace common\models;


use common\entity\WeChatNewsList;
use yii\db\mssql\PDO;

class WeChatNewsListDb  extends ProxyDb {

    public function findNewsById($newsId)
    {
        $sql = sprintf("
           SELECT * FROM wechat_news_list WHERE newsId=:newsId
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":newsId", $newsId, PDO::PARAM_INT);
        return $command->queryOne();
    }

    public function addNews(WeChatNewsList $chatNewsList)
    {
        $sql = sprintf("
          INSERT INTO wechat_news_list
          (nAntistop,nContent,nCover,nIntro,nTid,nTitle,nType,nUrl,nStatus)
          VALUES
          (:nAntistop,:nContent,:nCover,:nIntro,:nTid,:nTitle,:nType,nUrl,:nStatus)
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":nAntistop", $chatNewsList->nAntistop, PDO::PARAM_STR);
        $command->bindParam(":nContent", $chatNewsList->nContent, PDO::PARAM_STR);
        $command->bindParam(":nCover", $chatNewsList->nCover, PDO::PARAM_STR);
        $command->bindParam(":nIntro", $chatNewsList->nIntro, PDO::PARAM_STR);
        $command->bindParam(":nTid", $chatNewsList->nTid, PDO::PARAM_INT);
        $command->bindParam(":nTitle", $chatNewsList->nTitle, PDO::PARAM_STR);
        $command->bindParam(":nType", $chatNewsList->nType, PDO::PARAM_INT);
        $command->bindParam(":nUrl", $chatNewsList->nUrl, PDO::PARAM_STR);
        $command->bindValue(":nStatus",WeChatNewsList::STATUS_NORMAL, PDO::PARAM_INT);
        return $command->execute();
    }

    public function getNewsListByTid($page,$nTid)
    {
        $sql = sprintf("
           FROM wechat_news_list WHERE nTid=:nTid AND nStatus=1
        ");
        $this->setParam("nTid",$nTid);
        $this->setSql($sql);
        return $this->find($page);
    }

    public function getNewsListByAntistop($page,$nAntistop)
    {
        $sql = sprintf("
           FROM wechat_news_list WHERE nAntistop=:nAntistop AND nStatus=1
        ");
        $this->setParam("nAntistop",$nAntistop);
        $this->setSql($sql);
        return $this->find($page);
    }

    public function getNewsList($page,$search,$status,$type)
    {
        $sql=sprintf("
        FROM wechat_news_list
        WHERE 1=1
        ");
        if(!empty($status))
        {
            $sql.=" AND nStatus=:nStatus ";
            $this->setParam("nStatus",$status);
        }
        if(!empty($type))
        {
            $sql.=" AND nType=:nType ";
            $this->setParam("nType",$type);
        }
        if(!empty($search))
        {
            $sql.=" AND (nTitle like :search OR nAntistop like :search )";
            $this->setParam("search","%".$search."%");
        }
        $this->setSql($sql);
        return $this->find($page);
    }

    public function deleteNews($id)
    {
        $sql = sprintf("
            DELETE FROM  wechat_news_list
            WHERE newsId=:newsId
        ");
        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":newsId",$id, PDO::PARAM_INT);
        $command->execute();
    }

    public function change($id,$status)
    {
        $sql = sprintf("
            UPDATE wechat_news_list
             SET nStatus=:nStatus
            WHERE newsId=:newsId
        ");
        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":newsId", $id, PDO::PARAM_INT);
        $command->bindParam(":nStatus",$status, PDO::PARAM_INT);
        $command->execute();
    }
}