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
use backend\components\TableResult;
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

        return $this->render("list");
    }


    public function actionAdd(){
        return $this->render("add");
    }


    /**
     * 获取专栏列表
     * @throws Exception
     * @throws \Exception
     */
    public function actionArticleList()
    {
        $search=\Yii::$app->request->get("searchText","");
        $status=\Yii::$app->request->get("status","");

        $page=new Page(\Yii::$app->request);

        $page=$this->articleService->getList($page,$search,$status);

        $tableResult=new TableResult($page->draw,count($page->getList()),$page->totalCount,$page->getList());

        echo json_encode($tableResult);
    }


    public function actionEdit()
    {
        $articleId = \Yii::$app->request->get("articleId");

        $articleInfo = $this->articleService->findById($articleId);

        return $this->render("edit",['articleInfo'=>$articleInfo]);
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
            $articleInfo->status=ArticleInfo::ARTICLE_STATUS_OUTLINE;//初始为下线状态

            $this->articleService->addArticleInfo($articleInfo);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            return json_encode(Code::statusDataReturn(Code::FAIL,$e->getName()));
        }

    }

    /**
     * 修改专栏信息
     * @return string
     */
    public function actionEditArticle()
    {
        $articleId=\Yii::$app->request->post("articleId","");
        $title=\Yii::$app->request->post("title","");
        $name=\Yii::$app->request->post("name","");
        $titleImg=\Yii::$app->request->post("titleImg","");
        $content=\Yii::$app->request->post("content","");


        try{
            $articleInfo=new ArticleInfo();
            $articleInfo->articleId=$articleId;
            $articleInfo->title=$title;
            $articleInfo->name=$name;
            $articleInfo->titleImg=$titleImg;
            $articleInfo->content=$content;

            $this->articleService->updateArticleInfo($articleInfo);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            return json_encode(Code::statusDataReturn(Code::FAIL,$e->getName()));
        }

    }

    /**
     * 删除专栏
     * @return string
     */
    public function actionDelete()
    {
        $articleId=\Yii::$app->request->post("articleId","");

        try{
            $this->articleService->deleteArticleInfoById($articleId);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            return json_encode(Code::statusDataReturn(Code::FAIL,$e->getName()));
        }

    }

    /**
     * 专栏上线
     * @return string
     */
    public function actionOnline()
    {
        $articleId=\Yii::$app->request->post("articleId","");
        if(empty($articleId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"参数异常"));
        }
        try{
            $this->articleService->changeStatus($articleId,ArticleInfo::ARTICLE_STATUS_ONLINE);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            return json_encode(Code::statusDataReturn(Code::FAIL,$e->getName()));
        }

    }

    /**
     * 专栏下线
     * @return string
     */
    public function actionOutline()
    {
        $articleId=\Yii::$app->request->post("articleId","");
        if(empty($articleId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"参数异常"));
        }
        try{
            $this->des->changeStatus($articleId,ArticleInfo::ARTICLE_STATUS_OUTLINE);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            return json_encode(Code::statusDataReturn(Code::FAIL,$e->getName()));
        }

    }

}