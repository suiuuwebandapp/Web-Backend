<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/6/1
 * Time: 下午3:10
 */

namespace backend\controllers;


use backend\components\Page;
use backend\components\TableResult;
use backend\services\RecommendListService;
use common\components\Code;
use common\entity\RecommendList;
use Yii;
use yii\base\Exception;

class RecommendListController extends CController {

    private $recommendSer;

    public function __construct($id, $module = null)
    {
        $this->recommendSer=new RecommendListService();
        parent::__construct($id, $module);
    }

    public function actionList()
    {
        return $this->render('list');
    }

    public function actionGetList()
    {
        $page=new Page(Yii::$app->request);
        //$page->sortName="status";
        $search=\Yii::$app->request->get("searchText","");
        $type=Yii::$app->request->get('type');
        $page = $this->recommendSer->getList($page,$search,$type);
        $tableResult=new TableResult($page->draw,count($page->getList()),$page->totalCount,$page->getList());
        echo json_encode($tableResult);
    }

    /**
     *
     * 添加目的地详情
     * @return string
     */
    public function actionAdd()
    {
        $rId=\Yii::$app->request->post("rId");
        $rType=\Yii::$app->request->post("type");
        $img=\Yii::$app->request->post("img");
        if(empty($rId)){return json_encode(Code::statusDataReturn(Code::FAIL,"编号不能为空"));}
        if(empty($rType)){return json_encode(Code::statusDataReturn(Code::FAIL,"类型不能为空"));}
        try{
            $recommend=new RecommendList();
            $recommend->relativeId=$rId;
            $recommend->relativeType=$rType;
            $recommend->rImg=$img;
            $this->recommendSer->addRecommend($recommend);
        }catch (Exception $e){
            return json_encode(Code::statusDataReturn(Code::FAIL,$e->getName()));
        }
        return json_encode(Code::statusDataReturn(Code::SUCCESS));
    }

    public function actionShowAdd()
    {
        return $this->render("add");
    }
}