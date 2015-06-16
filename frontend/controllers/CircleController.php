<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/4/19
 * Time: 下午2:47
 */
namespace frontend\controllers;


use common\components\Common;
use common\components\LogUtils;
use common\entity\CircleArticle;
use common\entity\CircleComment;
use common\entity\UserBase;
use frontend\components\Page;
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

    /**
     *  发布圈子文章
     * @return string
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
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'请选择所属圈子'));
            }
            if(empty( $CircleArticleEntity->aType))
            {
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'请选择文章类型'));
            }
            $data = $this->CircleService->CreateArticle($CircleArticleEntity);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }


    /**
     * 删除圈子文章
     * @return string
     */
    public function actionDeleteArticle()
    {
        $this->loginValid();
        try{
            $articleId=\Yii::$app->request->post('articleId');
            if(empty($articleId))
            {
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'无法删除未知文章'));
            }
            $userSign =$this->userObj->userSign;
            $this->CircleService->deleteArticleInfoById($articleId,$userSign);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }

    /**
     * 修改圈子文章
     * @return string
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
                return json_encode(Code::statusDataReturn(Code::FAIL,'无法修改未知文章'));
            }
            $this->CircleService->updateArticleInfo($CircleArticleEntity);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 查询圈子文章根据文章id
     * @return string
     */
    public function actionGetArticleById()
    {
        $this->loginValid(false);

        try{
            $articleId=\Yii::$app->request->post('articleId');
            $page = new Page();
            $page->startRow=0;
            $page->pageSize=4;
            $userSign=$this->userObj->userSign;
            if(empty($articleId))
            {
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'无法查看未知文章'));
            }
            $data=$this->CircleService->getArticleInfoById($articleId,$userSign,$page);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    public function actionGetCommentByArticleId()
    {
        $this->loginValid(false);

        try{
            $articleId=\Yii::$app->request->post('articleId');
            $page = new Page(\Yii::$app->request);
            if(empty($articleId))
            {
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'无法查看未知文章的评论'));
            }
            $data=$this->CircleService->getCommentByArticleId($articleId,$page);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 查询文章列表根据圈子id
     * @return string
     */
    public function actionGetArticleByCircleId()
    {
        $this->loginValid(false);

        try{
            $circleId=\Yii::$app->request->post('circleId');
            $type=\Yii::$app->request->post('type');
            $userSign=$this->userObj->userSign;
            $page=new Page(\Yii::$app->request);
            if(empty($circleId))
            {
                return json_encode(Code::statusDataReturn(Code::FAIL,'无法查看未知圈子列表'));
            }
            if(empty($type))
            {
                return json_encode(Code::statusDataReturn(Code::FAIL,'无法查看未知圈子类型列表'));
            }
            $data=$this->CircleService->getArticleByCircleId($circleId,$page,$userSign,$type);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,(object)$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 查询圈子
     * @return string
     */
    public function actionGetCircle()
    {

        $this->loginValid(false);
        try{
            $type = \Yii::$app->request->post('type');
            $page =new Page(\Yii::$app->request);
            $data=$this->CircleService->getCircleByType($type,$page);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 发表评论
     * @return string
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
            $CircleCommentEntity->rUserSign=$relativeUserSign;
            $isReply = \Yii::$app->request->post('reply');
            $isAt = \Yii::$app->request->post('at');//是否艾特@
            if(empty($CircleCommentEntity->articleId))
            {
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'无法评论未知文章'));
            }
            $this->CircleService->CreateArticleComment($CircleCommentEntity,$relativeUserSign,$isReply,$isAt);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }


    /**
     * 删除评论
     * @return string
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
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'无法删除未知评论'));
            }
            $this->CircleService->deleteCommentById($articleId,$commentId,$userSign);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }


    public function actionTest()
    {
        $str='519414839@qq.com';
        $arr= explode('@',$str);
        echo substr($arr[0],0,4).'*****'.$arr[1];
        $str1='18611517517';
        echo substr($str1,0,4).'*****'.substr($str1,-2);
    }



    public function actionWebInfo()
    {
        $this->loginValid(false);

        try{
            $articleId=\Yii::$app->request->get('infoId');
            $page = new Page();
            $page->startRow=0;
            $page->pageSize=4;
            $userSign=$this->userObj->userSign;
            if(empty($articleId))
            {
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'无法查看未知文章'));
            }
            $data=$this->CircleService->getArticleInfoById($articleId,$userSign,$page);
            //Yii::$app->params['app_circle_article_img']
            return $this->renderPartial('info',['data'=> $data,'pr'=>'']);
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

}