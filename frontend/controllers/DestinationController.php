<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/30
 * Time : 下午8:48
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\controllers;


use backend\components\Page;
use common\components\Code;
use frontend\services\CountryService;
use frontend\services\DestinationService;
use yii\base\Exception;

class DestinationController extends UnCController{


    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
    }



    public function actionList()
    {
        $countryService = new CountryService();
        $countryList = $countryService->getCountryList();

        return $this->render("list",[
            'countryList' => $countryList,
        ]);
    }

    public function actionInfo()
    {
        $desId=\Yii::$app->request->get("des");
        $destinationService=new DestinationService();
        $rst=$destinationService->findInfoById($desId);
        return $this->render("info",[
            'desInfo'=>$rst
        ]);
    }


    public function actionFindList()
    {
        $countryId=\Yii::$app->request->post("countryId");
        $cityId=\Yii::$app->request->post("cityId");

        $page=new Page();
        $page->showAll=true;
        try{
            $destinationService=new DestinationService();
            $rst=$destinationService->getList($page,null,$countryId,$cityId);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$rst->getList()));
        }catch (Exception $e){
            echo json_encode(Code::statusDataReturn(Code::FAIL,"获取目的地列表失败"));
        }


    }

}