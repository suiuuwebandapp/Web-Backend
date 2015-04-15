<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/10
 * Time : 下午1:52
 * Email: zhangxinmailvip@foxmail.com
 */

namespace backend\controllers;


use backend\components\Page;
use backend\entity\ArticleInfo;
use backend\services\ArticleService;
use common\components\Code;
use yii\base\Exception;

class ArticleController extends CController{



    private $articleService;


    public function __construct($id, $module = null)
    {
        $this->articleService=new ArticleService();
        parent::__construct($id, $module);
    }



    public function actionList(){

        return $this->render('list');
    }


    public function actionAdd(){
        return $this->render('add');
    }

    public function actionArticleList()
    {
        $page=new Page(\Yii::$app->request);


        var_dump($page);
    }


    /**
     * 添加专栏文章(AJAX)
     * @return mixed
     */
    public function actionAddArticle()
    {
        $title=\Yii::$app->request->post("title","");
        $name=\Yii::$app->request->post("name","");
        $titleImg=\Yii::$app->request->post("titleImg","");
        $content=\Yii::$app->request->post("content","");


        try{
            $articleInfo=new ArticleInfo();
            $articleInfo->title=$title;
            $articleInfo->name=$name;
            $articleInfo->titleImg=$titleImg;
            $articleInfo->content=$content;
            $articleInfo->createUserId=$this->userObj->userId;
            $articleInfo->status=ArticleInfo::ARTICLE_STATUS_DOWN_LINE;//初始为下线状态

            $this->articleService->addArticleInfo($articleInfo);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            var_dump($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,$e->getMessage()));
        }

    }



}