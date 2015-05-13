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
use frontend\services\TripService;
use yii\base\Exception;

class GoogleMapController extends CController{


    public $layout=false;
    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
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
     * 获取地点详细信息
     */
    public function actionSearchMapInfo()
    {
        $search=\Yii::$app->request->get("search");
        try{
            $googleMap=GoogleMap::getInstance();
            //$rst=$googleMap->searchSiteInfo($search);
            $rst='{
                   "results" : [
                      {
                         "address_components" : [
                            {
                               "long_name" : "Paris",
                               "short_name" : "Paris",
                               "types" : [ "locality", "political" ]
                            },
                            {
                               "long_name" : "Paris",
                               "short_name" : "75",
                               "types" : [ "administrative_area_level_2", "political" ]
                            },
                            {
                               "long_name" : "Île-de-France",
                               "short_name" : "IDF",
                               "types" : [ "administrative_area_level_1", "political" ]
                            },
                            {
                               "long_name" : "France",
                               "short_name" : "FR",
                               "types" : [ "country", "political" ]
                            }
                         ],
                         "formatted_address" : "Paris, France",
                         "geometry" : {
                            "bounds" : {
                               "northeast" : {
                                  "lat" : 48.9021449,
                                  "lng" : 2.4699208
                               },
                               "southwest" : {
                                  "lat" : 48.815573,
                                  "lng" : 2.224199
                               }
                            },
                            "location" : {
                               "lat" : 48.856614,
                               "lng" : 2.3522219
                            },
                            "location_type" : "APPROXIMATE",
                            "viewport" : {
                               "northeast" : {
                                  "lat" : 48.9021449,
                                  "lng" : 2.4699208
                               },
                               "southwest" : {
                                  "lat" : 48.815573,
                                  "lng" : 2.224199
                               }
                            }
                         },
                         "place_id" : "ChIJD7fiBh9u5kcRYJSMaMOCCwQ",
                         "types" : [ "locality", "political" ]
                      }
                   ],
                   "status" : "OK"
                }';
            $rst=json_decode($rst);
            if($rst->status=="OK"){
                $location=$rst->results[0]->geometry->location;
                echo json_encode(Code::statusDataReturn(Code::SUCCESS,$location));
            }else{
                echo json_encode(Code::statusDataReturn(Code::FAIL));
            }
        }catch (Exception $e){
            echo json_encode(Code::statusDataReturn(Code::FAIL,$e->getName()));
        }
    }


}