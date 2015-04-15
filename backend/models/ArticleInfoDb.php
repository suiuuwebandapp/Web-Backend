<?php
namespace backend\models;

use backend\entity\ArticleInfo;
use common\models\ProxyDb;
use yii\db\mssql\PDO;

/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/3/31
 * Time : 下午1:56
 * Email: zhangxinmailvip@foxmail.com
 */
class ArticleInfoDb extends ProxyDb
{


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
        $command->bindParam(":createUserId", $articleInfo->createUserId, PDO::PARAM_INT);
        $command->bindParam(":status", $articleInfo->status, PDO::PARAM_INT);

        return $command->execute();

    }

    public function getList()
    {

    }


    /**
     * 更新专题文章
     * @param ArticleInfo $articleInfo
     * @return int
     * @throws \yii\db\Exception
     */
    public function updateArticleInfo(ArticleInfo $articleInfo)
    {
        $sql = sprintf("
            UPDATE  article_info SET
            (
              title=:title,titleImg=:titleImg,name=:name,content=:content,lastUpdateTime=now(),status=:status
            )
            WHERE articleId=:articleId

        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":articleId", $articleInfo->articleId, PDO::PARAM_INT);
        $command->bindParam(":title", $articleInfo->title, PDO::PARAM_STR);
        $command->bindParam(":titleImg", $articleInfo->titleImg, PDO::PARAM_STR);
        $command->bindParam(":name", $articleInfo->name, PDO::PARAM_STR);
        $command->bindParam(":content", $articleInfo->content, PDO::PARAM_STR);
        $command->bindParam(":status", $articleInfo->status, PDO::PARAM_INT);

        return $command->execute();
    }

    /**
     * 查找专题文章
     * @param $articleId
     * @return int
     * @throws \yii\db\Exception
     */
    public function findById($articleId)
    {
        $sql=sprintf("
            SELECT * FROM article_info WHERE articleId=:articleId
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":articleId", $articleId, PDO::PARAM_INT);
        return $command->execute();
    }


    /**
     * 删除专题文章
     * @param $articleId
     * @return array|bool
     */
    public function deleteById($articleId)
    {
        $sql=sprintf("
            DELETE FROM  article_info WHERE articleId=:articleId;
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":articleId", $articleId, PDO::PARAM_INT);
        return $command->queryOne();
    }


}