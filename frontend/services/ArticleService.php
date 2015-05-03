<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/4/30
 * Time: 下午4:23
 */
namespace frontend\services;

use common\components\Code;
use common\components\Common;
use common\entity\ArticleComment;
use common\entity\UserAttention;
use common\models\ArticleDb;
use common\models\BaseDb;
use common\models\UserAttentionDb;
use yii\base\Exception;
use yii\filters\PageCache;

class ArticleService extends BaseDb
{

    private $articleDb;
    private $AttentionDb;
    public function __construct()
    {

    }
    public function getOldArticleList($page,$numb)
    {
        try {
            $conn = $this->getConnection();
            $this->articleDb = new ArticleDb($conn);
            $page = Common::PageResult($page,$numb);
            $rstOld = $this->articleDb->getOldArticleList($page);
            return $rstOld;
        } catch (Exception $e) {
            throw new Exception('查询目的地列表异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }
    public function getOnArticle()
    {
        try {
            $conn = $this->getConnection();
            $this->articleDb = new ArticleDb($conn);
            $rstOn = $this->articleDb->getOnArticle();

            return $rstOn;
        } catch (Exception $e) {
            throw new Exception('查询目的地列表异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }
    /**
     * 目的地详情
     * @param $id 目的地id
     * @param $page 第几页
     * @param $numb 每页个数
     * @return array|bool
     * @throws Exception
     */
    public function getArticleInfoById($id)
    {
        try {
            $conn = $this->getConnection();
            $this->articleDb = new ArticleDb($conn);
            $rst =$this->articleDb->getArticleInfoById($id);
            return $rst;
        } catch (Exception $e) {
            throw new Exception('查询目的地详情异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }
    /**
     * 目的地评论
     * @param $id 目的地id
     * @param $page 第几页
     * @param $numb 每页个数
     * @return array|bool
     * @throws Exception
     */
    public function getArticleCommentById($id,$page,$numb,$userSign)
    {
        try {
            $data=array();
            $conn = $this->getConnection();
            $this->articleDb = new ArticleDb($conn);
            $page = Common::PageResult($page,$numb);
            $comment=$this->articleDb->getCommentListByArticleId($id,$page,$userSign);
            $count= $this->articleDb->getCommentListByArticleIdCount($id,$userSign);
            $data['count']=isset($count['numb'])?$count['numb']:0;
            $data['comment']=$comment;
            return $data;
        } catch (Exception $e) {
            throw new Exception('查询目的地详情异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }
    /**
     * 添加评论
     * @param $userSing
     * @param $content
     * @param $relativeCommentId
     * @param $articleId
     * @throws Exception
     */
    public function addArticleComment($userSing,$content,$relativeCommentId,$articleId)
    {
        try {
            $conn = $this->getConnection();
            $this->articleDb = new ArticleDb($conn);
            $commentEntity=new ArticleComment();
            $commentEntity->articleId=$articleId;
            $commentEntity->userSign=$userSing;
            $commentEntity->content=$content;
            $commentEntity->relativeCommentId=$relativeCommentId;
            $this->articleDb->addArticleComment($commentEntity);
        } catch (Exception $e) {
            throw new Exception('添加评论异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }
        /**
         * 添加支持或反对
         * @param $articleId //圈子文章id
         * @param $userSign,//收藏用户
         * @throws Exception
         */
        public function addCommentSupport($articleId,$userSign,$isSupport)
        {

            try {
                $conn = $this->getConnection();
                $this->AttentionDb = new UserAttentionDb($conn);
                $this->articleDb = new ArticleDb($conn);
                $attention =new UserAttention();
                $attention->relativeType=UserAttention::TYPE_COMMENT_FOR_ARTICLE_MDD;
                $attention->relativeId=$articleId;
                $attention->userSign = $userSign;
                $result = $this->AttentionDb->getAttentionResult($attention,true);
                if(empty($result)||$result==false)
                {
                    $this->AttentionDb ->addUserAttention($articleId,UserAttention::TYPE_COMMENT_FOR_ARTICLE_MDD,$userSign,$isSupport);
                    $this->upDateCommentSupport($this->articleDb,$articleId,$isSupport);
                }else{
                    echo json_encode(Code::statusDataReturn(Code::FAIL,'已经收藏无需继续收藏'));
                }
            } catch (Exception $e) {
                throw new Exception('添加圈子文章收藏异常',Code::FAIL,$e);
            } finally {
                $this->closeLink();
            }
        }

    private function upDateCommentSupport(ArticleDb $articleDb,$articleId,$isSupport)
    {

        $articleComment=$articleDb->getArticleInfoById($articleId);

        $articleComment=$this->arrayCastObject($articleComment,CircleArticle::class);
        $supportCount=$articleComment->supportCount;
        $opposeCount=$articleComment->opposeCount;
        if($isSupport==1){
            $supportCount++;
        }else{
            $opposeCount++;
        }
        $articleComment->supportCount=$supportCount;
        $articleComment->opposeCount=$opposeCount;
        $articleDb->updateCommentNumb($articleComment);
    }



}