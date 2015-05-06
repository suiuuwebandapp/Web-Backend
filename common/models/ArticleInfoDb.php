<?php
namespace common\models;

use backend\components\Page;
use common\entity\ArticleComment;
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
    public function getList(Page $page,$search,$status,$select=null)
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
        if(!empty($select))
        {
            $this->setSelectInfo($select);
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

    ///////////////////////////


    public function getTopArticle()
    {
        $sql=sprintf("
            SELECT title,titleImg,name,articleId
            FROM article_info WHERE status=:status ORDER BY articleId DESC
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
             userSign,content,replayCommentId,supportCount,opposeCount,cTime,articleId,rTitle
            )
            VALUES
            (
              :userSign,:content,:replayCommentId,:supportCount,:opposeCount,now(),:articleId,:rTitle
            )
        ");

        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam(":userSign", $articleComment->userSign, PDO::PARAM_STR);
        $command->bindParam(":content", $articleComment->content, PDO::PARAM_STR);
        $command->bindParam(":replayCommentId", $articleComment->relativeCommentId, PDO::PARAM_INT);
        $command->bindParam(":supportCount", $articleComment->supportCount, PDO::PARAM_INT);
        $command->bindParam(":opposeCount", $articleComment->opposeCount, PDO::PARAM_INT);
        $command->bindParam(":articleId",$articleComment->articleId, PDO::PARAM_INT);
        $command->bindParam(":rTitle",$articleComment->rTitle, PDO::PARAM_INT);
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
    public function updateCommentSupportNumb(ArticleComment $articleComment)
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

    /**得到评论列表8代表相对类型为目的地评论支持或反对 status 1 支持 2是反对
     * @param $articleId
     * @param $userSign
     * @param $page
     * @return array
     */
    public function getCommentListByArticleId($articleId,$page,$userSign)
    {
        $sql=sprintf("
           SELECT a.commentId,a.rTitle,a.content,b.`status`,c.nickname,c.headImg,c.userSign
FROM article_comment a
LEFT JOIN user_base c ON c.userSign=a.userSign
LEFT JOIN (SELECT * FROM user_attention bd WHERE bd.userSign=:userSign AND bd.relativeType=8 ) b ON a.commentId=b.relativeId
WHERE a.articleId=:articleId AND c.`status`=1 ORDER BY a.commentId DESC
        ");
        $sql.=$page;
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":articleId", $articleId, PDO::PARAM_INT);
        $command->bindParam(":userSign", $userSign, PDO::PARAM_STR);
        return $command->queryAll();
    }
    /**得到评论列表8代表相对类型为目的地评论支持或反对 status 1 支持 2是反对的 数量
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
WHERE a.articleId=:articleId AND c.`status`=1
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":articleId", $articleId, PDO::PARAM_INT);
        $command->bindParam(":userSign", $userSign, PDO::PARAM_STR);
        return $command->queryOne();
    }


        public function getCommentInfoById($commentId)
    {
        $sql = sprintf("
       SELECT * FROM article_comment WHERE commentId=:commentId
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":commentId", $commentId, PDO::PARAM_INT);
        return $command->queryOne();
    }

}