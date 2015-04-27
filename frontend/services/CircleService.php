<?php
namespace frontend\services;


use common\components\Common;
use common\entity\UserAttention;
use common\models\BaseDb;
use common\entity\CircleComment;
use common\models\UserAttentionDb;
use frontend\models\CircleDb;
use yii\base\Exception;
use common\entity\CircleArticle;
use common\components\Code;
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/4/20
 * Time: 下午5:56
 */
class CircleService extends BaseDb
{

    private $CircleDb;
    private $userAttentionDb;

    public function __construct()
    {

    }


    /**
     * 添加圈子文章
     * @param CircleArticle $CircleArticle
     * @return CircleArticle
     * @throws Exception
     */
    public function CreateArticle(CircleArticle $CircleArticle)
    {

        try {
            $conn = $this->getConnection();
            $this->CircleDb = new CircleDb($conn);
            $rst = $this->CircleDb ->addArticle($CircleArticle);
            if($rst==0)
            {
                throw new Exception('添加圈子文章失败',Code::FAIL);
            }
        } catch (Exception $e) {
            throw new Exception('添加圈子文章异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }

    }
    /**
     * 更新圈子文章
     * @param CircleArticle $articleInfo
     * @throws Exception
     */
    public function updateArticleInfo(CircleArticle $articleInfo)
    {

        try {
            $conn = $this->getConnection();
            $this->CircleDb = new CircleDb($conn);
            $rst = $this->CircleDb->updateArticleInfo($articleInfo);
            if($rst==0)
            {
                throw new Exception('更新圈子文章失败',Code::FAIL);
            }
        } catch (Exception $e) {
            throw new Exception('更新圈子文章异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }

    }
    /**
     * 删除圈子文章
     * @param $articleId
     * @param $userSign
     * @throws Exception
     */
    public function deleteArticleInfoById($articleId,$userSign)
    {

        try {
            $conn = $this->getConnection();
            $this->CircleDb = new CircleDb($conn);
            $rst =$this->CircleDb->deleteArticleById($articleId,$userSign);
            if($rst==1)
            {
                $this->CircleDb->deleteAllCommentById($articleId);
            }else
            {
                throw new Exception('删除圈子文章失败',Code::FAIL);
            }
        } catch (Exception $e) {
            throw new Exception('删除圈子文章异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }

    }
    /**
     * 查询圈子文章详情根据文章id
     * @param $articleId
     * @throws Exception
     * @return array
     */
    public function getArticleInfoById($articleId,$pageNumb,$userSign)
    {

        try {
            $conn = $this->getConnection();
            $this->CircleDb = new CircleDb($conn);
            $data=$this->CircleDb->getArticleInfoById($articleId);
            $page=Common::PageResult($pageNumb);
            $commentList=$this->CircleDb->getCircleCommentByArticleId($articleId,$page);
            $this->userAttentionDb =new UserAttentionDb($conn);
            $attention= new UserAttention();
            $attention->relativeType = UserAttention::TYPE_COLLECT_FOR_ARTICLE;
            $attention->relativeId = $articleId;
            $attention->userSign = $userSign;
            $rst =  $this->userAttentionDb->getAttentionResult($attention);
            if(!empty($data)){
                $data['attentionId']='';
                $data['commentList']='';
                if($rst!=false)
                {
                    $data['attentionId'] = $rst['attentionId'];
                }
                if($data!=false)
                {
                    $data['commentList']=$commentList;
                }
            }
            return $this->unifyReturn($data);
        } catch (Exception $e) {
            throw new Exception('查询圈子文章异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }

    }

    /**
     * 查询圈子文章详情根据圈子
     * @param $circleId
     * @param $pageNumb
     * @param$userSign
     * @throws Exception
     * @return array
     */
    public function getArticleByCircleId($circleId,$pageNumb,$userSign)
    {

        try {
            $conn = $this->getConnection();
            $this->CircleDb = new CircleDb($conn);
            $page=Common::PageResult($pageNumb);
            $data = $this->CircleDb->getArticleListByCircleId($circleId,$page);
            $this->userAttentionDb =new UserAttentionDb($conn);
            $attention= new UserAttention();
            $attention->relativeType = UserAttention::TYPE_FOR_CIRCLE;
            $attention->relativeId = $circleId;
            $attention->userSign = $userSign;

            if(!empty($data)){
                $rst =  $this->userAttentionDb->getAttentionResult($attention);
                $data['attentionId']='';
                if($rst!=false) {
                    $data['attentionId'] = $rst['attentionId'];
                }
            }
            return $this->unifyReturn($data);
        } catch (Exception $e) {
            throw new Exception('根据圈子查询文章异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }

    }


    /**
     * 查询圈子
     * @param $type
     * @param $pageNumb
     * @throws Exception
     * @return array
     */
    public function getCircleByType($type,$pageNumb)
    {
        try {

            $conn = $this->getConnection();
            $this->CircleDb = new CircleDb($conn);
            $page=Common::PageResult($pageNumb);
            $data = $this->CircleDb->getCircleByType($type,$page);
            return $this->unifyReturn($data);
        } catch (Exception $e) {
            throw new Exception('查询圈子异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }

    }


    /**
     * 添加圈子文章评论
     * @param CircleComment $CircleComment
     * @throws Exception
     */
    public function CreateArticleComment(CircleComment $CircleComment)
    {

        try {

            $conn = $this->getConnection();
            $this->CircleDb = new CircleDb($conn);
            $transaction = $conn->beginTransaction();
            $rst = $this->CircleDb ->addComment($CircleComment);
            if($rst==0)
            {
                throw new Exception('添加圈子文章评论失败',Code::FAIL);
            }
            $this->upDateArticleCommentNumb($this->CircleDb,$CircleComment->articleId,true);
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();
            throw new Exception('添加圈子文章评论异常',Code::FAIL,$e);

        } finally {
            $this->closeLink();
        }

    }
    /**
     * 更新圈子文章评论
     * @param CircleComment $CircleComment
     * @throws Exception
     */
    public function updateCircleComment(CircleComment $CircleComment)
    {

        try {
            $conn = $this->getConnection();
            $this->CircleDb = new CircleDb($conn);
            $rst = $this->CircleDb->updateCircleComment($CircleComment);
            if($rst==0)
            {
                throw new Exception('更新圈子文章评论失败',Code::FAIL);
            }
        } catch (Exception $e) {
            throw new Exception('更新圈子文章评论异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }

    }
    /**
     * 删除圈子文章评论
     * @param $articleId
     * @param $commentId
     * @param $userSign
     * @throws Exception
     */
    public function deleteCommentById($articleId,$commentId,$userSign)
    {

        try {

            $conn = $this->getConnection();
            $this->CircleDb = new CircleDb($conn);
            $transaction = $conn->beginTransaction();
            $rst = $this->CircleDb->deleteCommentById($commentId,$userSign);
            if($rst==0)
            {
                throw new Exception('更新圈子文章评论失败',Code::FAIL);
            }
            $this->upDateArticleCommentNumb($this->CircleDb,$articleId,false);
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();
            throw new Exception('删除圈子文章评论异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }

    }
    /**
     * 查询圈子文章评论详情根据文章id
     * @param $articleId
     * @param $pageNumb
     * @throws Exception
     * @return array
     */
    public function getCommentByArticleId($articleId,$pageNumb)
    {

        try {
            $conn = $this->getConnection();
            $this->CircleDb = new CircleDb($conn);
            $page=Common::PageResult($pageNumb);
            $data=$this->CircleDb->getCircleCommentByArticleId($articleId,$page);

            return $this->unifyReturn($data);
        } catch (Exception $e) {
            throw new Exception('查询圈子文章评论异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }

    }

    /**
     * @param CircleDb $CircleDb db对象
     * @param $articleId 评论的id
     * @param $isAdd 是否添加  true 添加 false 减少
     * @throws Exception
     */
    private function upDateArticleCommentNumb(CircleDb $CircleDb,$articleId,$isAdd)
    {

        $article=$CircleDb->getArticleInfoById($articleId);

        $article=$this->arrayCastObject($article,CircleArticle::class);
        $cmt=$article->aCmtCount;
        if($isAdd){
        $cmt++;
        }else{
            $cmt--;
            if($cmt<0)
            {
                $cmt=0;
            }
        }
        $article->aCmtCount=$cmt;
        $CircleDb->upDateArticleCommentNumb($article);
    }





    private function unifyReturn($data)
    {
        if($data==false)
        {
            $data=array();
        }
        return $data;
    }

}