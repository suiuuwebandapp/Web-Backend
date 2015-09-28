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
use backend\services\QaService;
use backend\services\VersionService;
use common\components\Code;
use common\entity\AnswerCommunity;
use yii;
use yii\base\Exception;

class VersionController extends CController{



    private $versionService;


    public function __construct($id, $module = null)
    {
        $this->versionService=new VersionService();
        parent::__construct($id, $module);
    }
    public function actionList(){

        return $this->render("list");
    }
    public function actionAnswerList(){

        return $this->render("answerList");
    }

    public function actionAdd(){
        return $this->render("add");
    }




    public function actionGetList()
    {
        $page=new Page(Yii::$app->request);
        $page->sortName="id";
        $page->sortType="desc";
        $search=Yii::$app->request->get("searchText","");
        $page = $this->versionService->getList($page,$search,$search);
        $tableResult=new TableResult($page->draw,count($page->getList()),$page->totalCount,$page->getList());
        echo json_encode($tableResult);
    }

    public function actionGetAnswerList()
    {
        $page=new Page(Yii::$app->request);
        $page->sortName="aId";
        $page->sortType="desc";
        $search=Yii::$app->request->get("searchText","");
        $page = $this->qaService->getAnswerList($page,$search);
        $tableResult=new TableResult($page->draw,count($page->getList()),$page->totalCount,$page->getList());
        echo json_encode($tableResult);
    }
    /**
     * @return string
     */
    public function actionDeleteQuestion()
    {
        try{
            $id=Yii::$app->request->post("id");
            $i=$this->qaService->deleteQuestion($id);
        }catch (Exception $e){
            return json_encode(Code::statusDataReturn(Code::FAIL,$e->getName()));
        }
        return  json_encode(Code::statusDataReturn(Code::SUCCESS,$i));
    }
    public function actionDeleteAnswer()
    {
        try{
            $id=Yii::$app->request->post("id");
            $qId=Yii::$app->request->post("qId");
            $i=$this->qaService->deleteAnswer($id,$qId);
        }catch (Exception $e){
            return json_encode(Code::statusDataReturn(Code::FAIL,$e->getName()));
        }
        return  json_encode(Code::statusDataReturn(Code::SUCCESS,$i));
    }


    public function actionGetQuestionInfo()
    {
        try{
            $id=Yii::$app->request->post("id");
            $rst = $this->qaService->getQuestionInfo($id);
            return  json_encode(Code::statusDataReturn(Code::SUCCESS,$rst));
        }catch (Exception $e){
            return json_encode(Code::statusDataReturn(Code::FAIL,$e->getName()));
        }
    }

    public function actionToInfo()
    {
        $id=Yii::$app->request->get("id");
        $rst = $this->qaService->getQuestionInfo($id);
        return $this->render("questionInfo",['info'=>$rst]);
    }

    public function actionSysAnswer()
    {
        if($_POST)
        {
            $id=Yii::$app->request->post("id");
            $aContent=Yii::$app->request->post("answer");
            try {

                if(empty($id)){return json_encode(Code::statusDataReturn(Code::FAIL, "标题不能为空"));}
                if(empty($aContent)){return json_encode(Code::statusDataReturn(Code::FAIL, "内容不能为空"));}
                $answer = new AnswerCommunity();
                $answer->qId = $id;
                $answer->aContent = $aContent;
                $answer->aUserSign = Code::RECOMMEND_ANSWER_USER;
                $answer->type=AnswerCommunity::SYS_TYPE;
                $this->qaService->addAnswer($answer);
                return  json_encode(Code::statusDataReturn(Code::SUCCESS,""));
            }catch (Exception $e) {
                return json_encode(Code::statusDataReturn(Code::FAIL,"回答问题异常"));
            }

        }
        $id=Yii::$app->request->get("id");
        $type=Yii::$app->request->get("type","2");
        $rst = $this->qaService->getQuestionInfo($id);
        $page=new Page(Yii::$app->request);
        $sysAnswer = $this->qaService->getAnswerListByQid($page,$type,$id);
        return $this->render("sysAnswer",['id'=>$id,'info'=>$rst,"answer"=>$sysAnswer->getList()]);
    }
}