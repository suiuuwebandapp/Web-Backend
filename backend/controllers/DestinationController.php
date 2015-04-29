<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/17
 * Time : 上午11:01
 * Email: zhangxinmailvip@foxmail.com
 */

namespace backend\controllers;


use backend\components\Page;
use backend\components\TableResult;
use common\components\DateUtils;
use common\components\GoogleMap;
use common\entity\DestinationInfo;
use backend\services\CountryService;
use backend\services\DestinationService;
use common\components\Code;
use common\entity\DestinationScenic;
use yii\base\Exception;

class DestinationController extends CController
{


    private $destinationService;


    public function __construct($id, $module = null)
    {
        $this->destinationService=new DestinationService();
        parent::__construct($id, $module);
    }


    public function actionToDesList()
    {
        return $this->render("desList");
    }

    public function actionToScenicList()
    {
        $desId=\Yii::$app->request->get("desId");
        $desInfo=$this->destinationService->findDestinationById($desId);
        return $this->render("scenicList",[
            'desInfo'=>$desInfo
        ]);
    }


    public function actionToEditDes()
    {
        $desId=\Yii::$app->request->get("desId");
        $desInfo=$this->destinationService->findDestinationById($desId);

        $countryService=new CountryService();
        $page=new Page();
        $page->showAll=true;
        $page=$countryService->getCountryList($page,null);
        $countryList=$page->getList();


        return $this->render("editDes",[
            'desInfo'=>$desInfo,
            'countryList'=>$countryList
        ]);
    }



    public function actionToAddDes()
    {
        $countryService=new CountryService();
        $page=new Page();
        $page->showAll=true;
        $page=$countryService->getCountryList($page,null);
        $countryList=$page->getList();
        return $this->render("addDes",[
            'countryList'=>$countryList
        ]);
    }




    public function actionDesList()
    {
        $search=\Yii::$app->request->get("searchText","");
        $status=\Yii::$app->request->get("status","");

        $page=new Page(\Yii::$app->request);

        $page=$this->destinationService->getDesList($page,$search,$status);

        $tableResult=new TableResult($page->draw,count($page->getList()),$page->totalCount,$page->getList());

        echo json_encode($tableResult);
    }


    /**
     *
     * 添加目的地详情
     * @return string
     */
    public function actionAddDestination()
    {
        $title=\Yii::$app->request->post("title","");
        $titleImg=\Yii::$app->request->post("titleImg","");
        $countryId=\Yii::$app->request->post("countryId");
        $cityId=\Yii::$app->request->post("cityId");


        try{
            $desInfo=new DestinationInfo();
            $desInfo->title=$title;
            $desInfo->titleImg=$titleImg;
            $desInfo->countryId=$countryId;
            $desInfo->cityId=$cityId;
            $desInfo->createUserId=$this->userObj->userId;
            $desInfo->status=DestinationInfo::DES_STATUS_OUTLINE;

            $this->destinationService->addDestinationInfo($desInfo);
        }catch (Exception $e){
            return json_encode(Code::statusDataReturn(Code::FAIL,$e->getName()));
        }
        return json_encode(Code::statusDataReturn(Code::SUCCESS));
    }


    /**
     * 更新目的地
     * @return string
     */
    public function actionUpdateDestination()
    {
        $desId=\Yii::$app->request->post("desId");
        $title=\Yii::$app->request->post("title","");
        $titleImg=\Yii::$app->request->post("titleImg","");
        $countryId=\Yii::$app->request->post("countryId");
        $cityId=\Yii::$app->request->post("cityId");


        try{
            $desInfo=new DestinationInfo();
            $desInfo->destinationId=$desId;
            $desInfo->title=$title;
            $desInfo->titleImg=$titleImg;
            $desInfo->countryId=$countryId;
            $desInfo->cityId=$cityId;

            $this->destinationService->updateDestinationInfo($desInfo);
        }catch (Exception $e){
            return json_encode(Code::statusDataReturn(Code::FAIL,$e->getName()));
        }
        return json_encode(Code::statusDataReturn(Code::SUCCESS));
    }



    /**
     * 目的地上线
     * @return string
     */
    public function actionDelete()
    {
        $desId=\Yii::$app->request->post("destinationId","");
        if(empty($desId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"参数异常"));
        }
        try{
            $this->destinationService->deleteDestinationById($desId);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            return json_encode(Code::statusDataReturn(Code::FAIL,$e->getName()));
        }

    }


    /**
     * 目的地上线
     * @return string
     */
    public function actionOnline()
    {
        $desId=\Yii::$app->request->post("destinationId","");
        if(empty($desId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"参数异常"));
        }
        try{
            $this->destinationService->changeStatus($desId,DestinationInfo::DES_STATUS_ONLINE);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            return json_encode(Code::statusDataReturn(Code::FAIL,$e->getName()));
        }

    }

    /**
     * 目的地下线
     * @return string
     */
    public function actionOutline()
    {
        $desId=\Yii::$app->request->post("destinationId","");
        if(empty($desId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"参数异常"));
        }
        try{
            $this->destinationService->changeStatus($desId,DestinationInfo::DES_STATUS_OUTLINE);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            return json_encode(Code::statusDataReturn(Code::FAIL,$e->getName()));
        }

    }


    /**
     * 跳转到添加景区页面
     * @return string
     */
    public function actionToAddScenic()
    {
        $desId=\Yii::$app->request->get("desId");
        return $this->render("addScenic",[
            'desId'=>$desId
        ]);
    }


    /**
     * 添加景区
     * @return string
     */
    public function actionAddScenic()
    {
        $title=\Yii::$app->request->post("title","");
        $titleImg=\Yii::$app->request->post("titleImg","");
        $beginTime=\Yii::$app->request->post("beginTime","");
        $endTime=\Yii::$app->request->post("endTime","");
        $lon=\Yii::$app->request->post("lon");
        $lat=\Yii::$app->request->post("lat");
        $desId=\Yii::$app->request->post("desId");


        //TODO 验证时间顺序以及有效性
        try{
            $scenicInfo=new DestinationScenic();
            $scenicInfo->title=$title;
            $scenicInfo->titleImg=$titleImg;
            $scenicInfo->beginTime=DateUtils::convertTimePicker($beginTime);
            $scenicInfo->endTime=DateUtils::convertTimePicker($endTime);
            $scenicInfo->lon=$lon;
            $scenicInfo->lat=$lat;
            $scenicInfo->destinationId=$desId;
            $scenicInfo->address="";
            $this->destinationService->addDestinationScenic($scenicInfo);
        }catch (Exception $e){
            return json_encode(Code::statusDataReturn(Code::FAIL,$e->getName()));
        }
        return json_encode(Code::statusDataReturn(Code::SUCCESS));
    }



    public function actionUpdateScenic()
    {
        $scenicId=\Yii::$app->request->post("scenicId","");
        $title=\Yii::$app->request->post("title","");
        $titleImg=\Yii::$app->request->post("titleImg","");
        $beginTime=\Yii::$app->request->post("beginTime","");
        $endTime=\Yii::$app->request->post("endTime","");
        $lon=\Yii::$app->request->post("lon");
        $lat=\Yii::$app->request->post("lat");
        $desId=\Yii::$app->request->post("desId");


        //TODO 验证时间顺序以及有效性
        try{
            $scenicInfo=$this->destinationService->findScenicById($scenicId);
            $scenicInfo->title=$title;
            $scenicInfo->titleImg=$titleImg;
            $scenicInfo->beginTime=DateUtils::convertTimePicker($beginTime);
            $scenicInfo->endTime=DateUtils::convertTimePicker($endTime);
            $scenicInfo->lon=$lon;
            $scenicInfo->lat=$lat;
            $scenicInfo->destinationId=$desId;
            $this->destinationService->updateDestinationScenic($scenicInfo);

        }catch (Exception $e){
            return json_encode(Code::statusDataReturn(Code::FAIL,$e->getName()));
        }
        return json_encode(Code::statusDataReturn(Code::SUCCESS));
    }


    /**
     * 删除景区
     * @return string
     */
    public function actionDeleteScenic()
    {
        $desId=\Yii::$app->request->post("scenicId","");
        if(empty($desId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"参数异常"));
        }
        try{
            $this->destinationService->deleteScenicById($desId);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            return json_encode(Code::statusDataReturn(Code::FAIL,$e->getName()));
        }

    }


    public function actionToEditScenic()
    {
        $scenicId=\Yii::$app->request->get("scenicId");
        $scenicInfo=$this->destinationService->findScenicById($scenicId);

        return $this->render("editScenic",[
            'scenicInfo'=>$scenicInfo
        ]);
    }

    /**
     * 跳转到景区列表
     * @throws Exception
     * @throws \Exception
     */
    public function actionScenicList()
    {
        $desId=\Yii::$app->request->get("desId");
        $search=\Yii::$app->request->get("searchText","");

        $page=new Page(\Yii::$app->request);

        $page=$this->destinationService->getScenicList($page,$desId,$search);

        $tableResult=new TableResult($page->draw,count($page->getList()),$page->totalCount,$page->getList());

        echo json_encode($tableResult);
    }

    public function actionToMap()
    {
        //默认坐标 为 北京
        $lon=\Yii::$app->request->get("lon","116.40617084503174");
        $lat=\Yii::$app->request->get("lat","39.91295943669406");

        return $this->render("map",[
            'lon'=>$lon,
            'lat'=>$lat
        ]);
    }



}

