<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/4/19
 * Time: 下午2:47
 */
namespace frontend\controllers;


use common\components\Common;
use common\entity\CircleArticle;
use common\entity\CircleComment;
use common\entity\UserBase;
use frontend\services\CircleService;
use frontend\services\UserBaseService;
use common\components\Code;
use yii\base\Exception;
use yii\web\Controller;
use yii;
//AController
class CircleController extends AController{

    private $userBaseService;

    private $CircleService;
    public $enableCsrfValidation=false;
    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        $this->userBaseService=new UserBaseService();
        $this->CircleService =new CircleService();


    }

    public function actionIndex()
    {
        echo 1;

    }






    /**
     * @var 发布圈子文章
     */
    public function actionCreateArticle()
    {
        $this->loginValid();
        try{
            $CircleArticleEntity = new CircleArticle();
            $CircleArticleEntity->cId=\Yii::$app->request->post('circleId');
            $CircleArticleEntity->aTitle=\Yii::$app->request->post('title');
            $CircleArticleEntity->aContent=\Yii::$app->request->post('content');
            $CircleArticleEntity->aImg=\Yii::$app->request->post('img');
            $CircleArticleEntity->aAddr=\Yii::$app->request->post('addr');
            $CircleArticleEntity->aImgList=\Yii::$app->request->post('imgList');
            $CircleArticleEntity->aType=\Yii::$app->request->post('type');
            $CircleArticleEntity->cAddrId=\Yii::$app->request->post('addrId');
            $CircleArticleEntity->aCreateUserSign=$this->userObj->userSign;
            if(empty( $CircleArticleEntity->cId)&&empty( $CircleArticleEntity->cAddrId))
            {
                echo json_encode(Code::statusDataReturn(Code::FAIL,'请选择所属圈子'));
                exit;
            }
            if(empty( $CircleArticleEntity->aType))
            {
                echo json_encode(Code::statusDataReturn(Code::FAIL,'请选择文章类型'));
                exit;
            }
            $this->CircleService->CreateArticle($CircleArticleEntity);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }
    /**
     * @var 删除圈子文章
     */
    public function actionDeleteArticle()
    {
        $this->loginValid();
        try{
            $articleId=\Yii::$app->request->post('articleId');
            if(empty($articleId))
            {
                echo json_encode(Code::statusDataReturn(Code::FAIL,'无法删除未知文章'));
                exit;
            }
            $userSign =$this->userObj->userSign;
            $this->CircleService->deleteArticleInfoById($articleId,$userSign);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }

    }

    /**
     * @var 修改圈子文章
     */
    public function actionUpDateArticle()
    {
        $this->loginValid();
        try{

            $CircleArticleEntity = new CircleArticle();
            $CircleArticleEntity->articleId=\Yii::$app->request->post('articleId');
            $CircleArticleEntity->aTitle=\Yii::$app->request->post('title');
            $CircleArticleEntity->aContent=\Yii::$app->request->post('content');
            $CircleArticleEntity->aImg=\Yii::$app->request->post('img');
            $CircleArticleEntity->aAddr=\Yii::$app->request->post('addr');
            $CircleArticleEntity->aImgList=\Yii::$app->request->post('imgList');
            $CircleArticleEntity->aCreateUserSign=$this->userObj->userSign;
            if(empty( $CircleArticleEntity->articleId))
            {
                echo json_encode(Code::statusDataReturn(Code::FAIL,'无法修改未知文章'));
                exit;
            }
            $this->CircleService->updateArticleInfo($CircleArticleEntity);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }
    /**
     * @var 查询圈子文章根据文章id
     */
    public function actionGetArticleById()
    {
        $this->loginValid(false);

        try{
            $articleId=\Yii::$app->request->post('articleId');
            $page = \Yii::$app->request->post('page');
            $userSign=$this->userObj->userSign;
            if(empty($articleId))
            {
                echo json_encode(Code::statusDataReturn(Code::FAIL,'无法查看未知文章'));
                exit;
            }
            $data=$this->CircleService->getArticleInfoById($articleId,$page, $userSign);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }

    /**
     * @var 查询文章列表根据圈子id
     */
    public function actionGetArticleByCircleId()
    {
        $this->loginValid(false);

        try{
            $circleId=\Yii::$app->request->post('circleId');
            $page = \Yii::$app->request->post('page');
            $type=\Yii::$app->request->post('type');
            $userSign=$this->userObj->userSign;
            if(empty($circleId))
            {
                echo json_encode(Code::statusDataReturn(Code::FAIL,'无法查看未知圈子列表'));
                exit;
            }
            if(empty($type))
            {
                echo json_encode(Code::statusDataReturn(Code::FAIL,'无法查看未知圈子类型列表'));
                exit;
            }
            $data=$this->CircleService->getArticleByCircleId($circleId,$page,$userSign,$type);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,(object)$data));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }

    /**
     * @var 查询圈子
     */
    public function actionGetCircle()
    {

        $this->loginValid(false);
        try{
            $type = \Yii::$app->request->post('type');
            $page = \Yii::$app->request->post('page');
            $data=$this->CircleService->getCircleByType($type,$page);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }

    /**
     *@var 发表评论
     */
    public function actionCreateComment()
    {
        $this->loginValid();
        try {
            $CircleCommentEntity = new CircleComment();
            $CircleCommentEntity->userSign =$this->userObj->userSign;
            $CircleCommentEntity->articleId = \Yii::$app->request->post('articleId');
            $CircleCommentEntity->content = \Yii::$app->request->post('content');
            $CircleCommentEntity->relativeCommentId = \Yii::$app->request->post('rId');
            $relativeUserSign = \Yii::$app->request->post('rUserSign');
            $isReply = \Yii::$app->request->post('reply');
            $isAt = \Yii::$app->request->post('at');//是否艾特@
            if(empty($CircleCommentEntity->articleId))
            {
                echo json_encode(Code::statusDataReturn(Code::FAIL,'无法评论未知文章'));
                exit;
            }
            $this->CircleService->CreateArticleComment($CircleCommentEntity,$relativeUserSign,$isReply,$isAt);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }
    /**
     * @var 删除评论
     */
    public function actionDeleteComment()
    {
        $this->loginValid();
        try{
            $articleId=\Yii::$app->request->post('articleId');
            $commentId=\Yii::$app->request->post('commentId');
            $userSign=$this->userObj->userSign;
            if(empty($articleId))
            {
                echo json_encode(Code::statusDataReturn(Code::FAIL,'无法删除未知评论'));
                exit;
            }
            $this->CircleService->deleteCommentById($articleId,$commentId,$userSign);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }

    }

    //得到搜索结果
    public function actionGetSeek()
    {
        $this->loginValid(false);
        try{
            $str=\Yii::$app->request->post('str');
            $page = \Yii::$app->request->post('page');
            if(empty($str))
            {
                echo json_encode(Code::statusDataReturn(Code::FAIL,'无法搜索未知标题'));
                exit;
            }
            $this->CircleService->getSeekResult($str,$page);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }
    //得到用户主页
    public function actionGetHomepageInfo()
    {
        $this->loginValid();
        try{
            $userSign=\Yii::$app->request->post('userSign');
            $page = \Yii::$app->request->post('page');
            if(empty($userSign))
            {
                echo json_encode(Code::statusDataReturn(Code::FAIL,'无法得到未知用户主页'));
                exit;
            }
            $this->CircleService->getHomepageInfo($userSign,$page);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }

    public function actionTest()
    {
        $now = date('Y-m-d H:i:s', time());
        echo strtotime($now);
    }

}