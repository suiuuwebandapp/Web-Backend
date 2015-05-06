<?php
namespace frontend\services;


use common\components\Common;
use common\entity\CircleSort;
use common\entity\UserAttention;
use common\entity\UserMessageRemind;
use common\models\BaseDb;
use common\entity\CircleComment;
use common\models\UserAttentionDb;
use common\models\UserMessageRemindDb;
use frontend\components\Page;
use frontend\models\CircleDb;
use frontend\models\UserBaseDb;
use yii\base\Exception;
use common\entity\CircleArticle;
use common\components\Code;
use yii\base\Object;

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
    private $userBaseDb;
    private $remindDb;
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
            return $rst;
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
    public function getArticleInfoById($articleId,$userSign,Page $page)
    {

        try {
            $conn = $this->getConnection();
            $this->CircleDb = new CircleDb($conn);
            $data=$this->CircleDb->getArticleInfoById($articleId);
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
                    $data['commentList']=$commentList->getList();
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
     * @param $page
     * @param $userSign
     * @param $type 1
     * @throws Exception
     * @return array
     */
    public function getArticleByCircleId($circleId,Page $page,$userSign,$type)
    {

        try {
            $data1=array();
            $conn = $this->getConnection();
            $this->CircleDb = new CircleDb($conn);

            if($type==CircleSort::CIRCLE_TYPE_PLACE)
            {
                $data = $this->CircleDb->getArticleListByAddrId($circleId,$page);
            }else
            {
                $data = $this->CircleDb->getArticleListByThemeId($circleId,$page);
            }
            $this->userAttentionDb =new UserAttentionDb($conn);
            $attention= new UserAttention();
            $attention->relativeType = UserAttention::TYPE_FOR_CIRCLE;
            $attention->relativeId = $circleId;
            $attention->userSign = $userSign;
            $str='';
            if(!empty($data)){
                $rst =  $this->userAttentionDb->getAttentionResult($attention);

                if($rst!=false) {
                    $str = $rst['attentionId'];
                }
            }
            return $this->unifyReturn(array('data'=>$data->getList(),'attentionId'=>$str,'msg'=>$data));
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
    public function getCircleByType($type,$page)
    {
        try {

            $conn = $this->getConnection();
            $this->CircleDb = new CircleDb($conn);
            $data = $this->CircleDb->getCircleByType($type,$page);
            return $this->unifyReturn(array('data'=>$data->getList(),'msg'=>$data));
        } catch (Exception $e) {
            throw new Exception('查询圈子异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }

    }


    /**
     * 添加圈子文章评论
     * @param CircleComment $CircleComment
     * @param $relativeUserSign
     * @param $isReply
     * @throws Exception
     */
    public function CreateArticleComment(CircleComment $CircleComment,$relativeUserSign,$isReply,$isAt)
    {

        try {

            $conn = $this->getConnection();
            $this->CircleDb = new CircleDb($conn);
            $transaction = $conn->beginTransaction();
            $rst = $this->CircleDb ->addComment($CircleComment);
            $this->remindDb =new UserMessageRemindDb($conn);
            if(empty($rst))
            {
                throw new Exception('添加圈子文章评论失败',Code::FAIL);
            }else{
                $this->upDateArticleCommentNumb($this->CircleDb,$CircleComment->articleId,true);
                if($isAt)
                {
                    $this->remindDb->addUserMessageRemind($rst,UserMessageRemind::TYPE_AT,$CircleComment->userSign,$relativeUserSign);
                }
                if($isReply){
                    $this->remindDb->addUserMessageRemind($rst,UserMessageRemind::TYPE_REPLY,$CircleComment->userSign,$relativeUserSign);
                }else
                {
                    $this->remindDb->addUserMessageRemind($rst,UserMessageRemind::TYPE_COMMENT,$CircleComment->userSign,$relativeUserSign);
                }
                $transaction->commit();
            }

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
    public function getCommentByArticleId($articleId,Page $page)
    {

        try {
            $conn = $this->getConnection();
            $this->CircleDb = new CircleDb($conn);
            $data=$this->CircleDb->getCircleCommentByArticleId($articleId,$page);

            return $this->unifyReturn(array('data'=>$data->getList(),'msg'=>$data));
        } catch (Exception $e) {
            throw new Exception('查询圈子文章评论异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }

    }

    /**
     * 查询搜索的文章结果
     * @param $str
     * @param $pageNumb
     * @throws Exception
     * @return array
     */
    public function getSeekResult($str,$page)
    {

        try {
            $conn = $this->getConnection();
            $this->CircleDb = new CircleDb($conn);
            $data=$this->CircleDb->getSeekResult($str,$page);

            return $this->unifyReturn(array('data'=>$data->getList(),'msg'=>$data));
        } catch (Exception $e) {
            throw new Exception('查询圈子文章评论异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }

    }

    public function getHomepageInfo($userSign,$page,$mySign)
    {
        try {
            $data = array();
            $conn = $this->getConnection();
            $this->CircleDb = new CircleDb($conn);
            $this->userAttentionDb =new UserAttentionDb($conn);
            $this->userBaseDb =new UserBaseDb($conn);
            $userArr  = $this->userBaseDb->findByUserSign($userSign);
            if(empty($userArr)||$userArr==false)
            {
                return $data;
            }
            $data['user']=$userArr;
            $userId=$userArr['userId'];
            $fansArr= $this->userAttentionDb->getAttentionFansCount($userId);
            $data['fansNumb']='0';
            if(!empty($userArr)&&$userArr!=false)
            {
                $data['fansNumb']=$fansArr['numb'];
            }
            $userAttention = new UserAttention();
            $userAttention->userSign=$mySign;
            $userAttention->relativeId=$userId;
            $userAttention->relativeType=UserAttention::TYPE_FOR_USER;
            $attRst=$this->userAttentionDb->getAttentionResult($userAttention);
            $data['attentionRst']=array();
            if(!empty($attRst)&&$attRst!=false)
            {
                $data['attentionRst']=$attRst;
            }
            $AttArr= $this->userAttentionDb->getAttentionCount($userSign);
            $data['AttentionNumb']='0';
            if(!empty($userArr)&&$userArr!=false)
            {
                $data['AttentionNumb']=$AttArr['numb'];
            }
            $articleList=$this->CircleDb->getArticleListByUserSign($userSign,$page);
            $data['articleList'] = $articleList->getList();
            $data['msg'] = $articleList;
            return $this->unifyReturn($data);
        } catch (Exception $e) {
            throw new Exception('查询用户主页异常',Code::FAIL,$e);
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