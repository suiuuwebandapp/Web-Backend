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
use common\entity\ArticleComment;
use common\entity\ArticleInfo;
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


    public function getOldArticleList($page)
    {
        $sql=sprintf("
            SELECT title,titleImg,name,articleId
            FROM article_info WHERE status=:status
        ");
        $sql.=$page;
        $command=$this->getConnection()->createCommand($sql);
        $command->bindValue(":status", ArticleInfo::ARTICLE_STATUS_OUTLINE, PDO::PARAM_STR);
        return  $command->queryAll();

    }
    public function getOnArticle()
    {
        $sql=sprintf("
            SELECT title,titleImg,name,articleId
            FROM article_info WHERE status=:status
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindValue(":status", ArticleInfo::ARTICLE_STATUS_ONLINE, PDO::PARAM_STR);
        return $command->queryOne();

    }

    /**
     * 得到目的地详情
     * @param $articleId
     * @return array|bool
     */
    public function getArticleInfoById($articleId)
    {
        $sql=sprintf("
            SELECT title,titleImg,name,articleId,content,createUserId,createTime,lastUpdateTime
            FROM article_info WHERE articleId=:articleId
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":articleId", $articleId, PDO::PARAM_INT);
        return $command->queryOne();
    }
    /**
     * 添加评论
     * @param ArticleComment $articleComment
     * @return int
     * @throws \yii\db\Exception
     */
    public function addArticleComment(ArticleComment $articleComment)
    {
        $sql = sprintf("
            INSERT INTO article_comment
            (
             userSign,content,replayCommentId,supportCount,opposeCount,cTime,articleId
            )
            VALUES
            (
              :userSign,:content,:replayCommentId,:supportCount,:opposeCount,now(),:articleId
            )
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":userSign", $articleComment->userSign, PDO::PARAM_STR);
        $command->bindParam(":content", $articleComment->content, PDO::PARAM_STR);
        $command->bindParam(":replayCommentId", $articleComment->relativeCommentId, PDO::PARAM_INT);
        $command->bindParam(":supportCount", $articleComment->supportCount, PDO::PARAM_INT);
        $command->bindParam(":opposeCount", $articleComment->opposeCount, PDO::PARAM_INT);
        $command->bindValue(":articleId",$articleComment->articleId, PDO::PARAM_INT);
        $command->execute();
    }


    /**
     * 删除评论
     * @param $commentId
     * @param $userSign
     * @return array|bool
     */
    public function deleteCommentById($commentId,$userSign)
    {
        $sql = sprintf("
        DELETE FROM article_comment WHERE commentId=:commentId AND userSign =:userSign
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":commentId", $commentId, PDO::PARAM_INT);
        $command->bindParam(":userSign", $userSign, PDO::PARAM_STR);
        return $command->execute();
    }

    /**
     * 更新评论支持反对数
     * @param ArticleComment $articleComment
     * @return int
     * @throws \yii\db\Exception
     */
    public function updateCommentNumb(ArticleComment $articleComment)
    {


        $sql = sprintf("
            UPDATE  article_comment SET
              supportCount=:supportCount,opposeCount=:opposeCount
            WHERE commentId=:commentId
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":supportCount", $articleComment->supportCount, PDO::PARAM_INT);
        $command->bindParam(":opposeCount", $articleComment->opposeCount, PDO::PARAM_INT);
        $command->bindParam(":commentId", $articleComment->commentId, PDO::PARAM_INT);
        return $command->execute();
    }

    /**得到评论列表8代表相对类型为目的地评论
     * @param $articleId
     * @param $userSign
     * @param $page
     * @return array
     */
    public function getCommentListByArticleId($articleId,$page,$userSign)
    {
        $sql=sprintf("
           SELECT a.commentId,a.userSign as cSign,a.content,b.`status`,c.nickname,c.headImg,c.userSign
FROM article_comment a
LEFT JOIN user_base c ON c.userSign=a.userSign
LEFT JOIN (SELECT * FROM user_attention bd WHERE bd.userSign=:userSign AND bd.relativeType=8 ) b ON a.commentId=b.relativeId
WHERE a.articleId=:articleId
        ");
        $sql.=$page;
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":articleId", $articleId, PDO::PARAM_INT);
        $command->bindParam(":userSign", $userSign, PDO::PARAM_STR);
        return $command->queryAll();
    }
    /**得到评论列表8代表相对类型为目的地评论 数量
     * @param $articleId
     * @param $userSign
     * @return array
     */
    public function getCommentListByArticleIdCount($articleId,$userSign)
    {
        $sql=sprintf("
           SELECT COUNT(a.commentId) as numb
FROM article_comment a
LEFT JOIN user_base c ON c.userSign=a.userSign
LEFT JOIN (SELECT * FROM user_attention bd WHERE bd.userSign=:userSign AND bd.relativeType=8 ) b ON a.commentId=b.relativeId
WHERE a.articleId=:articleId
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":articleId", $articleId, PDO::PARAM_INT);
        $command->bindParam(":userSign", $userSign, PDO::PARAM_STR);
        return $command->queryOne();
    }
}