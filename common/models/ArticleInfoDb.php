<?php
namespace common\models;

use backend\components\Page;
use common\entity\ArticleInfo;
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
     *
     * 获取文章列表
     * @param Page $page
     * @param $search
     * @param $status
     * @return array
     */
    public function getList(Page $page,$search,$status)
    {
        $sql=sprintf("
            FROM ".self::TABLE_NAME." WHERE 1=1
        ");
        if(!empty($search)){
            $sql.=" AND (title like :search OR name like :search ) ";
            $this->setParam("search",$search."%");
        }
        if(!empty($status)){
            $sql.=" and status=:status ";
            $this->setParam("status",$status);
        }

        $this->setSql($sql);
        return $this->find($page);
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
        $command->bindParam(":createUserId", $articleInfo->createUserId, PDO::PARAM_INT);
        $command->bindParam(":status", $articleInfo->status, PDO::PARAM_INT);

        return $command->execute();

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
            title=:title,titleImg=:titleImg,name=:name,content=:content,lastUpdateTime=now()
            WHERE articleId=:articleId

        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":articleId", $articleInfo->articleId, PDO::PARAM_INT);
        $command->bindParam(":title", $articleInfo->title, PDO::PARAM_STR);
        $command->bindParam(":titleImg", $articleInfo->titleImg, PDO::PARAM_STR);
        $command->bindParam(":name", $articleInfo->name, PDO::PARAM_STR);
        $command->bindParam(":content", $articleInfo->content, PDO::PARAM_STR);

        $command->execute();
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
        return $command->queryOne();
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
        $command->execute();
    }


    /**
     * 改变专栏状态
     * @param $articleId
     * @param $status
     * @throws \yii\db\Exception
     */
    public function changeStatus($articleId,$status)
    {
        $sql = sprintf("
            UPDATE  article_info SET
            status=:status
            WHERE articleId=:articleId

        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":articleId", $articleId, PDO::PARAM_INT);
        $command->bindParam(":status", $status, PDO::PARAM_INT);

        $command->execute();
    }


}