<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/27
 * Time : 下午8:36
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\controllers;


use common\components\Code;
use common\components\GoogleMap;
use common\components\LogUtils;
use frontend\services\TripService;
use yii\base\Exception;

class GoogleMapController extends UnCController{


    public $layout=false;
    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
    }


    /**
     * 跳转到地图页面
     * @return string
     */
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

    /**
     * 获取景区列表
     * @return string
     * @throws Exception
     * @throws \Exception
     */
    public function actionViewScenicMap()
    {
        $tripId=\Yii::$app->request->get("tripId");
        $tripService=new TripService();
        $scenicList=$tripService->getTravelTripScenicList($tripId);
        return $this->render("viewScenicMap",[
            'scenicList'=>$scenicList
        ]);
    }


    /**
     * 编辑景区  获取景区地图
     * @return string
     * @throws Exception
     * @throws \Exception
     */
    public function actionEditScenicMap()
    {
        $tripId=\Yii::$app->request->get("tripId");
        $scenicList=null;
        if(!empty($tripId)){
            $tripService=new TripService();
            $scenicList=$tripService->getTravelTripScenicList($tripId);
        }
        return $this->render("editScenicMap",[
            'scenicList'=>$scenicList
        ]);
    }

    /**
     * 获取地点详细信息
     */
    public function actionSearchMapInfo()
    {

        //return json_encode(Code::statusDataReturn(Code::SUCCESS,json_decode('{"lat" : 35.3605555,"lng" : 138.7277777} ')));

        $search=\Yii::$app->request->get("search");

        try{
            $search=str_replace(" ","",$search);
            $googleMap=GoogleMap::getInstance();
            $rst=$googleMap->searchSiteInfo($search);
            $rst=json_decode($rst);
            if(isset($rst->status)&&$rst->status=="OK"){
                $location=$rst->results[0]->geometry->location;
                return json_encode(Code::statusDataReturn(Code::SUCCESS,$location));
            }else{
                return json_encode(Code::statusDataReturn(Code::FAIL));
            }
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }


}