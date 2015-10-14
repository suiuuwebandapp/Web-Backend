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
use common\components\Code;
use common\entity\AnswerCommunity;
use frontend\services\TagListService;
use yii;
use yii\base\Exception;

class QaController extends CController{



    private $qaService;


    public function __construct($id, $module = null)
    {
        $this->qaService=new QaService();
        parent::__construct($id, $module);
    }
    public function actionQuestionList(){

        return $this->render("questionList");
    }
    public function actionAnswerList(){

        return $this->render("answerList");
    }

    public function actionAdd(){
        return $this->render("add");
    }

    public function actionAddAnswer(){
        return $this->render("addAnswer");
    }


    public function actionGetQaList()
    {
        $page=new Page(Yii::$app->request);
        $page->sortName="qId";
        $page->sortType="desc";
        $search=Yii::$app->request->get("searchText","");
        $tag=Yii::$app->request->get('tag');
        $page = $this->qaService->getQaList($page,null,null,$tag,$search);
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
            $userSign=Yii::$app->request->post("userSign");
            $type=AnswerCommunity::SYS_TYPE;
            try {

                if(empty($id)){return json_encode(Code::statusDataReturn(Code::FAIL, "标题不能为空"));}
                if(empty($aContent)){return json_encode(Code::statusDataReturn(Code::FAIL, "内容不能为空"));}
                if(empty($userSign)){$userSign=Code::RECOMMEND_ANSWER_USER;$type=AnswerCommunity::USER_TYPE;}
                $answer = new AnswerCommunity();
                $answer->qId = $id;
                $answer->aContent = $aContent;
                $answer->aUserSign = $userSign;
                $answer->type=$type;
                $this->qaService->addAnswer($answer);
                return  json_encode(Code::statusDataReturn(Code::SUCCESS,""));
            }catch (Exception $e) {
                return json_encode(Code::statusDataReturn(Code::FAIL,"回答问题异常"));
            }

        }
        $id=Yii::$app->request->get("id");
        $type=Yii::$app->request->get("type");
        $rst = $this->qaService->getQuestionInfo($id);
        $userRst=array();
        if(isset($rst["qInviteAskUser"])&&!empty($rst["qInviteAskUser"]))
        {
            $userRst=$this->qaService->getInvitationUser(($rst["qInviteAskUser"]));

        }
        $page=new Page(Yii::$app->request);
        $sysAnswer = $this->qaService->getAnswerListByQid($page,$type,$id);
        return $this->render("sysAnswer",['id'=>$id,'info'=>$rst,"answer"=>$sysAnswer->getList(),'userRst'=>$userRst]);
    }
}