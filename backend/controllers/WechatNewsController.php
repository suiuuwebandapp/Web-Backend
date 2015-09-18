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
use common\components\Common;
use common\entity\WeChatNewsList;
use frontend\interfaces\WechatInterface;
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
        if(empty($nTid)){$nTid = 0;}
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
        return json_encode(Code::statusDataReturn(Code::FAIL,$e->getMessage()));
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

        $newsId=\Yii::$app->request->post("newsId");
        $nTid=\Yii::$app->request->post("nTid");
        $nTitle=\Yii::$app->request->post("nTitle");
        $nType=\Yii::$app->request->post("nType");
        $nAntistop=\Yii::$app->request->post("nAntistop");
        $nIntro=\Yii::$app->request->post("nIntro");
        $nContent=\Yii::$app->request->post("nContent");
        $nUrl=\Yii::$app->request->post("nUrl");
        $nCover=\Yii::$app->request->post("nCover");
        if(empty($nType)){$nTid = 0;}
        if(empty($newsId)){return json_encode(Code::statusDataReturn(Code::FAIL,"编号不能为空"));}
        if(empty($nType)){return json_encode(Code::statusDataReturn(Code::FAIL,"类型不能为空"));}
        try{

            $wechatNews=new WeChatNewsList();
            $wechatNews->newsId=$newsId;
            $wechatNews->nTid=$nTid;
            $wechatNews->nTitle=$nTitle;
            $wechatNews->nType=$nType;
            $wechatNews->nAntistop=$nAntistop;
            $wechatNews->nIntro=$nIntro;
            $wechatNews->nContent=$nContent;
            $wechatNews->nUrl=$nUrl;
            $wechatNews->nCover=$nCover;
            $this->wechatNewsSer->editNews($wechatNews);
        }catch (Exception $e){
            return json_encode(Code::statusDataReturn(Code::FAIL,$e->getMessage()));
        }
        return json_encode(Code::statusDataReturn(Code::SUCCESS));
    }

    public function actionShowEdit()
    {
        $id=\Yii::$app->request->get("id");
        if(empty($id)){return json_encode(Code::statusDataReturn(Code::FAIL,"编号不能为空"));}
        $data = $this->wechatNewsSer->getInfo($id);
        return $this->render("edit",['info'=>$data]);
    }


    public function actionToImgList(){
        header("Content-type:text/html;charset=utf-8");
        $wechatInterface = new WechatInterface();
        $token=$wechatInterface->readToken();
        $url ="https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=".$token;
        $jsonData=array("type"=>"image","offset"=>0,"count"=>20);
        $rst = Common::CurlHandel($url,$jsonData);
        return $this->render("imgList",['info'=>$rst["data"]]);
    }

    public function actionImgList()
    {
        $start=Yii::$app->request->post("start",0);
        $length=Yii::$app->request->post("length",10);
        header("Content-type:text/html;charset=utf-8");
        $wechatInterface = new WechatInterface();
        $token=$wechatInterface->readToken();
        $url ="https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=".$token;
        $jsonData=array("type"=>"image","offset"=>$start,"count"=>$length);
        $rst = Common::CurlHandel($url,$jsonData);
        $jsonData = json_decode($rst["data"],true);
        $page=new Page(Yii::$app->request);
        $tableResult=new TableResult($page->draw,count($jsonData["item_count"]),$jsonData["total_count"],$jsonData["item"]);
        echo json_encode($tableResult);
    }

}