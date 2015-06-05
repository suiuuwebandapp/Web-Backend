<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/6/4
 * Time: 下午4:10
 */

namespace backend\controllers;


use backend\components\Page;
use backend\components\TableResult;
use backend\services\WechatNewsService;
use common\components\Code;
use common\entity\WeChatNewsList;
use Yii;
use yii\base\Exception;

class WechatNewsController extends CController {

    private $wechatNewsSer;

    public function __construct($id, $module = null)
    {
        $this->wechatNewsSer=new WechatNewsService();
        parent::__construct($id, $module);
    }

    public function actionList()
    {
        return $this->render('list');
    }

    public function actionGetList()
    {
        $page=new Page(Yii::$app->request);
        $page->sortName="newsId";
        $page->sortType="desc";
        $search=\Yii::$app->request->get("searchText","");
        $status=Yii::$app->request->get('status',"1");
        $type=Yii::$app->request->get('type');
        $page = $this->wechatNewsSer->getList($page,$search,$status,$type);
        $tableResult=new TableResult($page->draw,count($page->getList()),$page->totalCount,$page->getList());
        echo json_encode($tableResult);
    }

    public function actionDeleteNews()
    {
        $id=\Yii::$app->request->post("id");
        if(empty($id)){return json_encode(Code::statusDataReturn(Code::FAIL,"编号不能为空"));}
        try{
            $this->wechatNewsSer->deleteNews($id);
        }catch (Exception $e){
            return json_encode(Code::statusDataReturn(Code::FAIL,$e->getName()));
        }
        return json_encode(Code::statusDataReturn(Code::SUCCESS));

    }

    public function actionAdd()
    {

        $nTid=\Yii::$app->request->post("nTid");
        $nTitle=\Yii::$app->request->post("nTitle");
        $nType=\Yii::$app->request->post("nType");
        $nAntistop=\Yii::$app->request->post("nAntistop");
        $nIntro=\Yii::$app->request->post("nIntro");
        $nContent=\Yii::$app->request->post("nContent");
        $nUrl=\Yii::$app->request->post("nUrl");
        $nCover=\Yii::$app->request->post("nCover");
        if(empty($nType)){return json_encode(Code::statusDataReturn(Code::FAIL,"类型不能为空"));}
        try{
            $wechatNews=new WeChatNewsList();
            $wechatNews->nTid=$nTid;
            $wechatNews->nTitle=$nTitle;
            $wechatNews->nType=$nType;
            $wechatNews->nAntistop=$nAntistop;
            $wechatNews->nIntro=$nIntro;
            $wechatNews->nContent=$nContent;
            $wechatNews->nUrl=$nUrl;
            $wechatNews->nCover=$nCover;
            $this->wechatNewsSer->addNews($wechatNews);
        }catch (Exception $e){
            return json_encode(Code::statusDataReturn(Code::FAIL,$e->getName()));
        }
        return json_encode(Code::statusDataReturn(Code::SUCCESS));
    }
    
    public function actionShowAdd()
    {
        return $this->render('add');
    }

    public function actionChange()
    {
        $id=\Yii::$app->request->post("id");
        $status=\Yii::$app->request->post("status");
        if(empty($id)){return json_encode(Code::statusDataReturn(Code::FAIL,"编号不能为空"));}
        try{
            $this->wechatNewsSer->change($id,$status);
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
}