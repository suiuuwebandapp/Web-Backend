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
use backend\components\TableResult;
use common\components\Code;
use common\components\LogUtils;
use common\components\PageResult;
use frontend\services\CountryService;
use frontend\services\DestinationService;
use frontend\services\TripService;
use yii\base\Exception;

class DestinationController extends UnCController{


    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
    }


    /**
     * 目的地列表
     * @return string
     * @throws Exception
     * @throws \Exception
     */
    public function actionList()
    {
        $countryService = new CountryService();
        $countryList = $countryService->getCountryList();
        $destinationService=new DestinationService();
        $ccList=$destinationService->getDesCountryAndCity();
        $countryArr='';$cityArr='';
        if(!empty($ccList['countryList']['countryIds'])){
            $countryArr=explode(",",$ccList['countryList']['countryIds']);
        }
        if(!empty($ccList['cityList']['cityIds'])){
            $cityArr=explode(",",$ccList['cityList']['cityIds']);
        }

        //默认加载第一屏
        $page=new Page();
        $page->pageSize=10;
        $page->setCurrentPage(1);

        $destinationService=new DestinationService();
        $page=$destinationService->getList($page,null,null,null);

        return $this->render("list",[
            'countryList' => $countryList,
            'countryArr'=>$countryArr,
            'cityArr'=>$cityArr,
            'desList'=>$page->getList()

        ]);
    }


    /**
     * 目的地详情页面
     * @return string
     * @throws Exception
     * @throws \Exception
     */
    public function actionInfo()
    {
        $desId=\Yii::$app->request->get("des");
        $destinationService=new DestinationService();
        $rst=$destinationService->findInfoById($desId);
        $tripService=new TripService();
        $recommendPage=new Page();
        $recommendPage->pageSize=3;
        $recommendPage->sortName="score";
        $recommendPage->sortType="DESC";
        $recommendPage=$tripService->getRelateRecommendTrip($recommendPage,$rst['info']['countryId'],$rst['info']['cityId']);

        return $this->render("info",[
            'desInfo'=>$rst,
            'relateRecommend'=>$recommendPage->getList()
        ]);
    }


    /**
     * 获取目的地列表（AJAX）
     * @return string
     */
    public function actionFindList()
    {
        $countryId=\Yii::$app->request->post("countryId");
        $cityId=\Yii::$app->request->post("cityId");
        $p=\Yii::$app->request->post("p",1);


        try{
            $page=new Page();
            $page->pageSize=10;
            $page->setCurrentPage($p);

            $destinationService=new DestinationService();
            $page=$destinationService->getList($page,null,$countryId,$cityId);
            $pageResult=new PageResult($page);

            return json_encode(Code::statusDataReturn(Code::SUCCESS,$pageResult));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"获取目的地列表失败"));
        }


    }

}