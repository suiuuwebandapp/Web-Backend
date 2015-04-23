<?php
namespace frontend\services;


use common\models\BaseDb;
use frontend\entity\CircleCommentEntity;
use frontend\models\CircleDb;
use yii\base\Exception;
use frontend\entity\CircleArticleEntity;
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

    public function __construct()
    {

    }


    /**
     * 添加圈子文章
     * @param CircleArticleEntity $CircleArticleEntity
     * @return CircleArticleEntity
     * @throws Exception
     */
    public function CreateArticle(CircleArticleEntity $CircleArticleEntity)
    {

        try {
            $conn = $this->getConnection();
            $this->CircleDb = new CircleDb($conn);
            $rst = $this->CircleDb ->addArticle($CircleArticleEntity);
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
     * @param CircleArticleEntity $articleInfo
     * @throws Exception
     */
    public function updateArticleInfo(CircleArticleEntity $articleInfo)
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
     * @param $userId
     * @throws Exception
     */
    public function deleteArticleInfoById($articleId,$userId)
    {

        try {
            $conn = $this->getConnection();
            $this->CircleDb = new CircleDb($conn);
            $rst =$this->CircleDb->deleteArticleById($articleId,$userId);
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
    public function getArticleInfoById($articleId)
    {

        try {
            $conn = $this->getConnection();
            $this->CircleDb = new CircleDb($conn);
            $data=$this->CircleDb->getArticleInfoById($articleId);
            $commentList=$this->CircleDb->getCircleCommentByArticleId($articleId);
            if($data!=false)
            {
                $data['commentList']=$commentList;
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
     * @throws Exception
     * @return array
     */
    public function getArticleByCircleId($circleId)
    {

        try {
            $conn = $this->getConnection();
            $this->CircleDb = new CircleDb($conn);
            $data = $this->CircleDb->getArticleListByCircleId($circleId);
            return $this->unifyReturn($data);
        } catch (Exception $e) {
            throw new Exception('根据圈子查询文章异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }

    }


    /**
     * 查询圈子
     * @throws Exception
     * @return array
     */
    public function getCircleByType($type)
    {
        try {

            $conn = $this->getConnection();
            $this->CircleDb = new CircleDb($conn);
            $data = $this->CircleDb->getCircleByType($type);
            return $this->unifyReturn($data);
        } catch (Exception $e) {
            throw new Exception('查询圈子异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }

    }


    /**
     * 添加圈子文章评论
     * @param CircleCommentEntity $CircleCommentEntity
     * @throws Exception
     */
    public function CreateArticleComment(CircleCommentEntity $CircleCommentEntity)
    {

        try {

            $conn = $this->getConnection();
            $this->CircleDb = new CircleDb($conn);
            $transaction = $conn->beginTransaction();
            $rst = $this->CircleDb ->addComment($CircleCommentEntity);
            if($rst==0)
            {
                throw new Exception('添加圈子文章评论失败',Code::FAIL);
            }
            $this->upDateArticleCommentNumb($this->CircleDb,$CircleCommentEntity->articleId,true);
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
     * @param CircleCommentEntity $CircleCommentEntity
     * @throws Exception
     */
    public function updateCircleComment(CircleCommentEntity $CircleCommentEntity)
    {

        try {
            $conn = $this->getConnection();
            $this->CircleDb = new CircleDb($conn);
            $rst = $this->CircleDb->updateCircleComment($CircleCommentEntity);
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
     * @param $userId
     * @throws Exception
     */
    public function deleteCommentById($articleId,$commentId,$userId)
    {

        try {

            $conn = $this->getConnection();
            $this->CircleDb = new CircleDb($conn);
            $transaction = $conn->beginTransaction();
            $rst = $this->CircleDb->deleteCommentById($commentId,$userId);
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
     * @throws Exception
     * @return array
     */
    public function getCommentByArticleId($articleId)
    {

        try {
            $conn = $this->getConnection();
            $this->CircleDb = new CircleDb($conn);
            $data=$this->CircleDb->getCircleCommentByArticleId($articleId);

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

        $articleEntity=$this->arrayCastObject($article,CircleArticleEntity::class);
        $cmt=$articleEntity->aCmtCount;
        if($isAdd){
        $cmt++;
        }else{
            $cmt--;
            if($cmt<0)
            {
                $cmt=0;
            }
        }
        $articleEntity->aCmtCount=$cmt;
        $CircleDb->upDateArticleCommentNumb($articleEntity);
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