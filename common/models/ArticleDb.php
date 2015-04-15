<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/15
 * Time : 下午2:43
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\models;


use backend\components\Page;
use backend\entity\ArticleInfo;
use yii\db\Command;
use yii\db\mssql\PDO;

class ArticleDb extends ProxyDb{



    public function getArticleInfoList(Page $page,$search,$status)
    {
        $sql=sprintf("
            SELECT title,titleImg,name,content,createUserId,createTime,lastUpdateTime,status
            FROM article_info
            WHERE articleId=:articleId
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":articleId", $articleId, PDO::PARAM_INT);

        $command->execute();

    }

    /**
     * 添加专栏文章
     * @param ArticleInfo $articleInfo
     * @return int
     * @throws \yii\db\Exception
     */
    public function addArticleInfo(ArticleInfo $articleInfo)
    {
        $sql = sprintf("
            INSERT INTO article_info
            (
              title,titleImg,name,content,createUserId,createTime,lastUpdateTime,status
            )
            VALUES
            (
              :title,:titleImg,:name,:content,:createUserId,now(),now(),:status
            )
        ");


        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":title", $articleInfo->title, PDO::PARAM_STR);
        $command->bindParam(":titleImg", $articleInfo->titleImg, PDO::PARAM_STR);
        $command->bindParam(":name", $articleInfo->name, PDO::PARAM_STR);
        $command->bindParam(":content", $articleInfo->content, PDO::PARAM_STR);
        $command->bindParam(":createUserId", $articleInfo->createUserId, PDO::PARAM_STR);
        $command->bindParam(":status", $articleInfo->status, PDO::PARAM_INT);

        $command->execute();

    }


    /**
     * 更新专栏文章
     * @param ArticleInfo $articleInfo
     * @throws \yii\db\Exception
     */
    public function updateArticleInfo(ArticleInfo $articleInfo)
    {
        $sql=sprintf("
            UPDATE article_INFO SET
            (
              title=:title,titleImg=:titleImg,name=:name,content=:content,lastUpdateTime=now(),status=:status
            )
            WHERE articleId=:articleId
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":title", $articleInfo->title, PDO::PARAM_STR);
        $command->bindParam(":titleImg", $articleInfo->titleImg, PDO::PARAM_STR);
        $command->bindParam(":name", $articleInfo->name, PDO::PARAM_STR);
        $command->bindParam(":content", $articleInfo->content, PDO::PARAM_STR);
        $command->bindParam(":status", $articleInfo->status, PDO::PARAM_INT);
        $command->bindParam(":articleId", $articleInfo->articleId, PDO::PARAM_INT);

        $command->execute();
    }


    /**
     * 删除专栏文章
     * @param $articleId
     * @throws \yii\db\Exception
     */
    public function deleteArticleInfoById($articleId){
        $sql=sprintf("
            DELETE FROM article_info WHERE articleId=:articleId
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":articleId", $articleId, PDO::PARAM_INT);

        $command->execute();
    }
}