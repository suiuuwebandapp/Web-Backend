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
class CircleController extends Controller{

    private $userBaseService;

    private $CircleService;
    public $enableCsrfValidation=false;
    private $userObj;
    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        $this->userBaseService=new UserBaseService();
        $this->CircleService =new CircleService();
        $this->userObj =new UserBase();
        $this->userObj->userSign='085963dc0af031709b032725e3ef18f5';
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

        try{

            $CircleArticleEntity = new CircleArticle();
            $CircleArticleEntity->articleId=\Yii::$app->request->post('articleId');
            $CircleArticleEntity->aTitle=\Yii::$app->request->post('title');
            $CircleArticleEntity->aContent=\Yii::$app->request->post('content');
            $CircleArticleEntity->aImg=\Yii::$app->request->post('img');
            $CircleArticleEntity->aAddr=\Yii::$app->request->post('addr');
            $CircleArticleEntity->aImgList=\Yii::$app->request->post('imgList');
            $CircleArticleEntity->aType=\Yii::$app->request->post('type');
            $CircleArticleEntity->aCreateUserSign=$this->userObj->userSign;
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
            $page = \Yii::$app->request->post('page');
            $userSign=$this->userObj->userSign;
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


        try{
            $circleId=\Yii::$app->request->post('circleId');
            $page = \Yii::$app->request->post('page');
            $type=\Yii::$app->request->post('type');
            $userSign=$this->userObj->userSign;
            $data=$this->CircleService->getArticleByCircleId(1,$page,$userSign,1);
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
        try {

            $CircleCommentEntity = new CircleComment();
            $CircleCommentEntity->userSign =$this->userObj->userSign;
            $CircleCommentEntity->articleId = \Yii::$app->request->post('articleId');
            $CircleCommentEntity->content = \Yii::$app->request->post('content');
            $CircleCommentEntity->relativeCommentId = \Yii::$app->request->post('rId');
            $relativeUserSign = \Yii::$app->request->post('rUserSign');
            $isReply = \Yii::$app->request->post('reply');
            $isAt = \Yii::$app->request->post('at');//是否艾特@
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
        try{
            $articleId=\Yii::$app->request->post('articleId');
            $commentId=\Yii::$app->request->post('commentId');
            $userSign=$this->userObj->userSign;
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
        try{
            $str=\Yii::$app->request->post('str');
            $page = \Yii::$app->request->post('page');
            $this->CircleService->getSeekResult($str,$page);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }
    //得到主页
    public function actionGetHomepageInfo()
    {
        try{
            $userSign=\Yii::$app->request->post('userSign');
            $page = \Yii::$app->request->post('page');
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
        $arr = array ('a'=>1,'b'=>2,'c'=>3,'d'=>4,'e'=>array('a'=>1,'b'=>2));
        $arr['ss']=1;
        echo json_encode($arr);
    }

}