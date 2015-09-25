<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/9/25
 * Time: 上午9:21
 */

namespace console\controllers;


use backend\services\QaService;
use backend\services\TravelPictureService;
use frontend\components\Page;
use frontend\services\TagListService;
use yii\console\Controller;

class UpdateController extends Controller  {


    public function actionUpdateTp()
    {
        $page=new Page();
        $page->showAll=true;
        $tagSer=new TagListService();
        $tpService=new TravelPictureService();
        $rst = $tpService->getTpList($page,null,null,null,null);
        $list=$rst->getList();
        foreach($list as $val)
        {
            $tagSer->updateTpTagValList($val["tags"],$val["id"]);
        }
        echo "ok";exit;
    }

    public function actionUpdateQa()
    {
        $page=new Page();
        $page->showAll=true;
        $tagSer=new TagListService();
        $qaService=new QaService();
        $rst = $qaService->getQaList($page,null,null,null,null);
        $list=$rst->getList();
        foreach($list as $val)
        {
            $tagSer->updateQaTagValList($val["qTag"],$val["qId"]);
        }
        echo "ok";exit;
    }

}