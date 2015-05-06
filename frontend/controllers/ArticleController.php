<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/1
 * Time: 上午9:41
 */
namespace frontend\controllers;

use backend\components\Page;
use common\components\Code;
use common\components\Common;
use common\entity\ArticleComment;
use common\entity\ArticleInfo;
use common\entity\UserBase;
use frontend\services\ArticleService;
use Yii;
use yii\base\Exception;

class ArticleController extends UnCController{

    private $aritcleSer;
    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        $this->aritcleSer = new ArticleService();
    }

    public function actionIndex()
    {

        $page=Yii::$app->request->get('page');
        $numb =Yii::$app->request->get('numb');
        $page=new Page();
        $page->showAll=true;
        $select='title,titleImg,name,articleId';
        //ArticleInfo::ARTICLE_STATUS_ONLINE
        $data= $this->aritcleSer->getArticleList($page,null,ArticleInfo::ARTICLE_STATUS_ONLINE,$select);

        return $this->render('index',['oldList'=>$data['old']->list,'onList'=>$data['on']]);
    }

    public function actionGetArticleInfo()
    {
        try {
            $id=Yii::$app->request->post('id');
            $data=$this->aritcleSer->getArticleInfoById($id);
            if(empty($data))
            {
                return json_encode(Code::statusDataReturn(Code::FAIL,'未找到对应的文章'));
            }

            return json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }

    public function actionGetArticleComment()
    {
        try {
            $id=Yii::$app->request->post('id');
            $page=Yii::$app->request->post('page');
            $numb =Yii::$app->request->post('numb');
            $userSign=$this->userObj->userSign;
            if(empty($page))
            {
                $page = 1;
            }
            $numb=5;
            $data=$this->aritcleSer->getArticleCommentById($id,$page,$numb,$userSign);
            $str='';
            if(intval($data['count'])!=0)
            {
                $count=intval($data['count']);
                $str=Common::pageHtml($page,$numb,$count);
            }
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$data,$str));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }
    /**
     *@var 发表评论
     */
    public function actionAddArticleComment()
    {
        try {
            $userSign =$this->userObj->userSign;

            $articleId = \Yii::$app->request->post('articleId');
            $content = \Yii::$app->request->post('content');
            $rId= \Yii::$app->request->post('rId');
            $rTitle= \Yii::$app->request->post('rTitle');
            if(empty($userSign))
            {
                echo json_encode(Code::statusDataReturn(Code::FAIL,'登陆之后才能评论'));
                exit;
            }
            if(empty($articleId))
            {
                echo json_encode(Code::statusDataReturn(Code::FAIL,'无法评论未知文章'));
                exit;
            }
            $this->aritcleSer->addArticleComment($userSign,$content,$rId,$articleId,$rTitle);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }

    public function actionAddSupport()
    {
        try {
            $userSign =$this->userObj->userSign;
            if(empty($userSign))
            {
                echo json_encode(Code::statusDataReturn(Code::FAIL,'登陆之后才能点赞'));
                exit;
            }
            $rId= \Yii::$app->request->post('rId');
            $this->aritcleSer->addCommentSupport($rId,$userSign,ArticleComment::TYPE_SUPPORT);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }
    public function actionTest()
    {
    }

}