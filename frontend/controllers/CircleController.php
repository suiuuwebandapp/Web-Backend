<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/4/19
 * Time: 下午2:47
 */
namespace frontend\controllers;


use frontend\entity\CircleArticleEntity;
use frontend\entity\CircleCommentEntity;
use frontend\services\CircleService;
use frontend\services\UserBaseService;
use common\components\Code;
use yii\base\Exception;
use yii\web\Controller;
use common\components\ValidateCode;
use yii;
//AController
class CircleController extends Controller{

    private $userBaseService;

    private $CircleService;
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
        try{
        $CircleArticleEntity = new CircleArticleEntity();
        $CircleArticleEntity->cId=\Yii::$app->request->post('circleId');
        $CircleArticleEntity->aTitle=\Yii::$app->request->post('title');
        $CircleArticleEntity->aContent=\Yii::$app->request->post('content');
        $CircleArticleEntity->aImg=\Yii::$app->request->post('img');
        $CircleArticleEntity->aAddr=\Yii::$app->request->post('addr');
        $CircleArticleEntity->aCreateUserId=1;
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
        try{
            $articleId=\Yii::$app->request->post('articleId');
            $userId = 1;
            $this->CircleService->deleteArticleInfoById($articleId,$userId);
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

        try{

            $CircleArticleEntity = new CircleArticleEntity();
            $CircleArticleEntity->articleId=\Yii::$app->request->post('articleId');
            $CircleArticleEntity->aTitle=\Yii::$app->request->post('title');
            $CircleArticleEntity->aContent=\Yii::$app->request->post('content');
            $CircleArticleEntity->aImg=\Yii::$app->request->post('img');
            $CircleArticleEntity->aAddr=\Yii::$app->request->post('addr');
            $CircleArticleEntity->aCreateUserId=1;
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

        try{
            $articleId=\Yii::$app->request->post('articleId');
            $data=$this->CircleService->getArticleInfoById(2);
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


        try{
            $circleId=\Yii::$app->request->post('circleId');
            $data=$this->CircleService->getArticleByCircleId($circleId);

            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
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


        try{
            $type = \Yii::$app->request->post('type');
            $data=$this->CircleService->getCircleByType($type);
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
        try {

            $CircleCommentEntity = new CircleCommentEntity();
            $CircleCommentEntity->userId = 1;
            $CircleCommentEntity->articleId = \Yii::$app->request->post('articleId');
            $CircleCommentEntity->content = \Yii::$app->request->post('content');
            $CircleCommentEntity->relativeCommentId = \Yii::$app->request->post('rId');
            $this->CircleService->CreateArticleComment($CircleCommentEntity);
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
        try{
            $articleId=\Yii::$app->request->post('articleId');
            $commentId=\Yii::$app->request->post('commentId');
            $userId = 6;
            $this->CircleService->deleteCommentById($articleId,$commentId,$userId);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }

    }


    public function actionSendTxt()
    {
        try{
            throw new Exception('更新圈子文章评论失败',Code::FAIL);
        }catch(Exception $e)
        {
            throw $e;
        }
    }

    public function actionTest()
    {
       var_dump( $currentUser=\Yii::$app->session->get(Code::APP_USER_LOGIN_SESSION));
        //var_dump(Yii::$app->redis->get('test'));
        //var_dump(Yii::$app->redis->keys('*'));exit;
        echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,123));
    }

}