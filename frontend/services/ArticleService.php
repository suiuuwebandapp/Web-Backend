<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/4/30
 * Time: 下午4:23
 */
namespace frontend\services;

use backend\components\Page;
use common\components\Code;
use common\components\Common;
use common\entity\ArticleComment;
use common\entity\ArticleInfo;
use common\entity\CircleArticle;
use common\entity\UserAttention;
use common\models\ArticleInfoDb;
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

    public function getArticleList(Page $page,$search,$status,$select=null)
    {
        try {
            $data=array();
            $conn = $this->getConnection();
            $this->articleDb = new ArticleInfoDb($conn,'article_info');
            $data['old']=$this->articleDb->getList($page,$search,$status,$select);
            $data['on']=$this->articleDb->getTopArticle();
            return $data;
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
            $this->articleDb = new ArticleInfoDb($conn);
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
            $this->articleDb = new ArticleInfoDb($conn);
            $page = Common::PageResult($page,$numb);
            $comment=$this->articleDb->getCommentListByArticleId($id,$page,$userSign);
            $count= $this->articleDb->getCommentListByArticleIdCount($id,$userSign);
            $data['count']=isset($count['numb'])?$count['numb']:0;
            $data['comment']=$comment;
            return $data;
        } catch (Exception $e) {
            throw new Exception('查询目的地评论异常',Code::FAIL,$e);
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
    public function addArticleComment($userSing,$content,$relativeCommentId,$articleId,$rTitle)
    {
        try {
            $conn = $this->getConnection();
            $this->articleDb = new ArticleInfoDb($conn);
            $commentEntity=new ArticleComment();
            $commentEntity->articleId=$articleId;
            $commentEntity->userSign=$userSing;
            $commentEntity->content=$content;
            $commentEntity->relativeCommentId=$relativeCommentId;
            $commentEntity->rTitle=$rTitle;
            $this->articleDb->addArticleComment($commentEntity);
        } catch (Exception $e) {
            throw new Exception('添加评论异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }
        /**
         * 添加支持或反对
         * @param $commentId //评论id
         * @param $userSign,//收藏用户
         * @throws Exception
         */
        public function addCommentSupport($commentId,$userSign,$isSupport)
        {

            try {
                $conn = $this->getConnection();
                $this->AttentionDb = new UserAttentionDb($conn);
                $this->articleDb = new ArticleInfoDb($conn);
                $attention =new UserAttention();
                $attention->relativeType=UserAttention::TYPE_COMMENT_FOR_ARTICLE_MDD;
                $attention->relativeId=$commentId;
                $attention->userSign = $userSign;
                $result = $this->AttentionDb->getAttentionResult($attention,true);
                $articleComment=$this->articleDb->getCommentInfoById($commentId);
                if(empty($articleComment)||$articleComment==false){echo json_encode(Code::statusDataReturn(Code::FAIL,'无法为未知评论点赞'));exit;}
                if(empty($result)||$result==false)
                {
                    $this->AttentionDb ->addUserAttention($commentId,UserAttention::TYPE_COMMENT_FOR_ARTICLE_MDD,$userSign,$isSupport);
                    $rst= $this->upDateCommentSupport($articleComment,$isSupport);
                    $this->articleDb->updateCommentSupportNumb($rst);
                }else{
                    echo json_encode(Code::statusDataReturn(Code::FAIL,'已经点赞无需继续点赞'));exit;
                }
            } catch (Exception $e) {
                throw new Exception('添加点赞异常',Code::FAIL,$e);
            } finally {
                $this->closeLink();
            }
        }

    private function upDateCommentSupport($articleComment,$isSupport)
    {

        $articleComment=$this->arrayCastObject($articleComment,ArticleComment::class);
        $supportCount=$articleComment->supportCount;
        $opposeCount=$articleComment->opposeCount;
        if($isSupport==1){
            $supportCount++;
        }else{
            $opposeCount++;
        }
        $articleComment->supportCount=$supportCount;
        $articleComment->opposeCount=$opposeCount;
       return $articleComment;
    }



}