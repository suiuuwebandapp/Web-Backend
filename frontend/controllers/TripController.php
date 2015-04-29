<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/27
 * Time : 下午6:29
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\controllers;


use common\components\Code;
use common\components\GoogleMap;
use common\components\TagUtil;
use frontend\services\CountryService;
use yii\base\Exception;

class TripController extends CController{


    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
    }


    public function actionNewTrip()
    {
        $countryService = new CountryService();
        $countryList=$countryService->getCountryList();
        $tagList=TagUtil::getInstance()->getTagList();

        return $this->render("newTrip",[
            'countryList'=>$countryList,
            'tagList'=>$tagList
        ]);
    }

    public function actionGetScenicMapInfo()
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