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
        $search=\Yii::$app->request->get("searchText","");
        $type=Yii::$app->request->get('type');
        $page = $this->circleSer->getCircleList($page,$search,$type);
        $tableResult=new TableResult($page->draw,count($page->getList()),$page->totalCount,$page->getList());
        echo json_encode($tableResult);
    }
}