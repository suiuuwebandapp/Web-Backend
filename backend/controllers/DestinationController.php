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
use common\entity\DestinationInfo;
use backend\services\CountryService;
use backend\services\DestinationService;
use common\components\Code;
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


    public function actionToMap()
    {
        return $this->render("map");
    }




}

