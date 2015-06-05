<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/6/2
 * Time: 上午10:17
 */

namespace backend\controllers;

use backend\components\Page;
use backend\components\TableResult;
use backend\services\CircleService;
use common\components\Code;
use common\entity\CircleSort;
use Yii;
use yii\base\Exception;

class CircleController extends CController {

    private $circleSer;

    public function __construct($id, $module = null)
    {
        $this->circleSer=new CircleService();
        parent::__construct($id, $module);
    }

    public function actionList()
    {
        return $this->render('list');
    }

    public function actionGetList()
    {
        $page=new Page(Yii::$app->request);
        $page->sortName="cId";
        $page->sortType="desc";
        $search=\Yii::$app->request->get("searchText","");
        $type=Yii::$app->request->get('type');
        $page = $this->circleSer->getCircleList($page,$search,$type);
        $tableResult=new TableResult($page->draw,count($page->getList()),$page->totalCount,$page->getList());
        echo json_encode($tableResult);
    }

    public function actionAdd()
    {
        $cName=\Yii::$app->request->post("name");
        $rType=\Yii::$app->request->post("type");
        $img=\Yii::$app->request->post("img");
        if(empty($cName)){return json_encode(Code::statusDataReturn(Code::FAIL,"名称不能为空"));}
        if(empty($rType)){return json_encode(Code::statusDataReturn(Code::FAIL,"类型不能为空"));}
        if(empty($img)){return json_encode(Code::statusDataReturn(Code::FAIL,"圈子背景不能为空"));}
        try{
            $circleSort=new CircleSort();
            $circleSort->cName=$cName;
            $circleSort->cpic=$img;
            $circleSort->cType=$rType;
            $this->circleSer->addCircleSort($circleSort);
        }catch (Exception $e){
            return json_encode(Code::statusDataReturn(Code::FAIL,$e->getName()));
        }
        return json_encode(Code::statusDataReturn(Code::SUCCESS));
    }

    public function actionShowAdd()
    {
        return $this->render("add");
    }

    public function actionDelete()
    {
        $id=\Yii::$app->request->post("id");
        if(empty($id)){return json_encode(Code::statusDataReturn(Code::FAIL,"编号不能为空"));}
        try{
            $this->circleSer->delete($id);
        }catch (Exception $e){
            return json_encode(Code::statusDataReturn(Code::FAIL,$e->getName()));
        }
        return json_encode(Code::statusDataReturn(Code::SUCCESS));
    }

    public function actionChange()
    {
        $id=\Yii::$app->request->post("id");
        $status=\Yii::$app->request->post("status");
        if(empty($id)){return json_encode(Code::statusDataReturn(Code::FAIL,"编号不能为空"));}
        try{
            $this->circleSer->change($id,$status);
        }catch (Exception $e){
            return json_encode(Code::statusDataReturn(Code::FAIL,$e->getName()));
        }
        return json_encode(Code::statusDataReturn(Code::SUCCESS));
    }

    public function actionEdit()
    {
        $id=\Yii::$app->request->post("id");
        $cName=\Yii::$app->request->post("name");
        $rType=\Yii::$app->request->post("type");
        $img=\Yii::$app->request->post("img");
        if(empty($id)){return json_encode(Code::statusDataReturn(Code::FAIL,"编号不能为空"));}
        if(empty($cName)){return json_encode(Code::statusDataReturn(Code::FAIL,"名称不能为空"));}
        if(empty($rType)){return json_encode(Code::statusDataReturn(Code::FAIL,"类型不能为空"));}
        if(empty($img)){return json_encode(Code::statusDataReturn(Code::FAIL,"圈子背景不能为空"));}
        try{
            $circleSort=new CircleSort();
            $circleSort->cId=$id;
            $circleSort->cName=$cName;
            $circleSort->cType=$rType;
            $circleSort->cpic=$img;
            $this->circleSer->edit($circleSort);
        }catch (Exception $e){
            return json_encode(Code::statusDataReturn(Code::FAIL,$e->getName()));
        }
        return json_encode(Code::statusDataReturn(Code::SUCCESS));
    }

    public function actionShowEdit()
    {
        $id=\Yii::$app->request->get("id");
        if(empty($id)){return json_encode(Code::statusDataReturn(Code::FAIL,"编号不能为空"));}
        $data = $this->circleSer->getInfo($id);
        return $this->render("edit",['info'=>$data]);
    }

    public function actionGetArticleList()
    {
        $page=new Page(Yii::$app->request);
        $page->sortName="articleId";
        $page->sortType="desc";
        $search=Yii::$app->request->get("searchText","");
        $type=Yii::$app->request->get('type');
        $cId=Yii::$app->request->get('cId');
        $aStatus=Yii::$app->request->get('status',"1");
        $page = $this->circleSer->getArticleList($page,$search,$type,$cId,$aStatus);
        $tableResult=new TableResult($page->draw,count($page->getList()),$page->totalCount,$page->getList());
        echo json_encode($tableResult);
    }

    public function actionArticleList()
    {
        return $this->render('articleList');
    }

    public function actionDeleteArticle()
    {
        $id=\Yii::$app->request->post("id");
        if(empty($id)){return json_encode(Code::statusDataReturn(Code::FAIL,"编号不能为空"));}
        try{
            $this->circleSer->deleteArticle($id);
        }catch (Exception $e){
            return json_encode(Code::statusDataReturn(Code::FAIL,$e->getName()));
        }
        return json_encode(Code::statusDataReturn(Code::SUCCESS));

    }

    public function actionGetCommentList()
    {
        $page=new Page(Yii::$app->request);
        $page->sortName="commentId";
        $page->sortType="desc";
        $search=Yii::$app->request->get("searchText","");
        $aStatus=Yii::$app->request->get('status',"1");
        $page = $this->circleSer->getCommentList($page,$search,$aStatus);
        $tableResult=new TableResult($page->draw,count($page->getList()),$page->totalCount,$page->getList());
        echo json_encode($tableResult);
    }

    public function actionCommentList()
    {
        return $this->render('commentList');
    }
    public function actionDeleteComment()
    {
        $id=\Yii::$app->request->post("id");
        if(empty($id)){return json_encode(Code::statusDataReturn(Code::FAIL,"编号不能为空"));}
        try{
            $this->circleSer->deleteComment($id);
        }catch (Exception $e){
            return json_encode(Code::statusDataReturn(Code::FAIL,$e->getName()));
        }
        return json_encode(Code::statusDataReturn(Code::SUCCESS));

    }
}