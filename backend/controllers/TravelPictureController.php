<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/9/23
 * Time: 上午9:29
 */

namespace backend\controllers;


use backend\components\Page;
use backend\components\TableResult;
use backend\services\TravelPictureService;
use common\components\Code;
use frontend\services\TagListService;
use yii;
use yii\base\Exception;

class TravelPictureController extends CController{



    private $tpService;


    public function __construct($id, $module = null)
    {
        $this->tpService=new TravelPictureService();
        parent::__construct($id, $module);
    }
    public function actionTpList(){

        return $this->render("tpList");
    }
    public function actionCommentList(){

        return $this->render("commentList");
    }

    public function actionAdd(){
        return $this->render("add");
    }


    public function actionGetTpList()
    {
        $page=new Page(Yii::$app->request);
        $page->sortName="id";
        $page->sortType="desc";
        $search=Yii::$app->request->get("searchText","");
        $tag=Yii::$app->request->get('tag');
        $id="";
        if(is_numeric($search)&&strlen($search)<8)
        {
            $id=$search;
        }
        $page = $this->tpService->getTpList($page,$tag,$search,$id);
        $tableResult=new TableResult($page->draw,count($page->getList()),$page->totalCount,$page->getList());
        echo json_encode($tableResult);
    }

    public function actionGetCommentList()
    {
        $page=new Page(Yii::$app->request);
        $page->sortName="id";
        $page->sortType="desc";
        $search=Yii::$app->request->get("searchText","");
        $page = $this->tpService->getCommentList($page,$search);
        $tableResult=new TableResult($page->draw,count($page->getList()),$page->totalCount,$page->getList());
        echo json_encode($tableResult);
    }
    /**
     * @return string
     */
    public function actionDeleteTp()
    {
        try{
            $id=Yii::$app->request->post("id");
            $i=$this->tpService->deleteTp($id);
        }catch (Exception $e){
            return json_encode(Code::statusDataReturn(Code::FAIL,$e->getName()));
        }
        return  json_encode(Code::statusDataReturn(Code::SUCCESS,$i));
    }

    public function actionDeleteComment()
    {
        try{
            $id=Yii::$app->request->post("id");
            $tpId=Yii::$app->request->post("tpId");
            $i=$this->tpService->deleteComment($id,$tpId);
        }catch (Exception $e){
            return json_encode(Code::statusDataReturn(Code::FAIL,$e->getName()));
        }
        return  json_encode(Code::statusDataReturn(Code::SUCCESS,$i));
    }


    public function actionToInfo()
    {
        $id=Yii::$app->request->get("id");
        $rst = $this->tpService->getTpInfo($id);
        return $this->render("tpInfo",['info'=>$rst]);
    }


}