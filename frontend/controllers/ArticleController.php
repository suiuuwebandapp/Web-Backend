<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/1
 * Time: 上午9:41
 */
namespace frontend\controllers;

use common\components\Code;
use common\components\Common;
use common\entity\ArticleComment;
use common\entity\UserBase;
use frontend\services\ArticleService;
use Yii;
use yii\base\Exception;

class ArticleController extends UnCController{

    private $aritcleSer;
    public $userObj;
    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        $this->aritcleSer = new ArticleService();
        $this->userObj =new UserBase();
        $this->userObj->userSign='085963dc0af031709b032725e3ef18f5';
    }

    public function actionIndex()
    {

        $page=Yii::$app->request->get('page');
        $numb =Yii::$app->request->get('numb');
        $oldList=$this->aritcleSer->getOldArticleList($page,$numb);
        $onList= $this->aritcleSer->getOnArticle();
        return $this->render('index',['onList'=>$onList,'oldList'=>$oldList]);
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
            $page=Yii::$app->request->get('page');
            $numb =Yii::$app->request->get('numb');

            $userSign=$this->userObj->userSign;
            $id=3;
            $page=1;
            $numb=2;
            //$id=3;
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
            if(empty($articleId))
            {
                echo json_encode(Code::statusDataReturn(Code::FAIL,'无法评论未知文章'));
                exit;
            }
            $this->aritcleSer->addArticleComment($userSign,$content,$rId,$articleId);
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