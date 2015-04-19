<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/17
 * Time : 上午11:01
 * Email: zhangxinmailvip@foxmail.com
 */

namespace backend\controllers;


use backend\entity\DestinationInfo;
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


    public function actionList()
    {
        echo "list";
    }


    public function actionAdd()
    {
        return $this->render("add");
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




}

