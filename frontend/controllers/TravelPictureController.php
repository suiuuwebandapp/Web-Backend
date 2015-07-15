<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/7/15
 * Time: 上午10:38
 */

namespace frontend\controllers;


use common\components\Code;
use common\components\LogUtils;
use common\entity\TravelPicture;
use frontend\services\TagListService;
use frontend\services\TravelPictureService;
use yii;
use yii\base\Exception;

class TravelPictureController  extends AController {

    private $tpSer;
    private $tagSer;
    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        $this->tpSer=new TravelPictureService();
        $this->tagSer =new TagListService();
    }

    public function actionAddTravelPicture()
    {
        $this->loginValid();
        try {
            $userSign = $this->userObj->userSign;
            if (empty($userSign)) {
                return json_encode(Code::statusDataReturn(Code::FAIL, "未知用户"));
            }
            $title = Yii::$app->request->post('title');
            $country = Yii::$app->request->post('country');
            $city = Yii::$app->request->post('city');
            $lon = Yii::$app->request->post('lon');
            $lat = Yii::$app->request->post('lat');
            $tags = Yii::$app->request->post('tags');
            $contents= Yii::$app->request->post('contents');
            $picList= Yii::$app->request->post('picList');
            if(empty($title)){return json_encode(Code::statusDataReturn(Code::FAIL, "标题不能为空"));}
            if(empty($country)){return json_encode(Code::statusDataReturn(Code::FAIL, "国家不能为空"));}
            if(empty($city)){return json_encode(Code::statusDataReturn(Code::FAIL, "城市不能为空"));}
            if(empty($lon)){return json_encode(Code::statusDataReturn(Code::FAIL, "经度不能为空"));}
            if(empty($lat)){return json_encode(Code::statusDataReturn(Code::FAIL, "纬度不能为空"));}
            if(empty($tags)){return json_encode(Code::statusDataReturn(Code::FAIL, "标签不能为空"));}
            if(empty($picList)){return json_encode(Code::statusDataReturn(Code::FAIL, "图片不能为空"));}
            $tpEntity = new TravelPicture();
            $tpEntity->title=$title;
            $tpEntity->country=$country;
            $tpEntity->city=$city;
            $tpEntity->lon=$lon;
            $tpEntity->lat=$lat;
            $tpEntity->tags=$tags;
            $tpEntity->contents=$contents;
            $tpEntity->picList=$picList;
            $this->tpSer->addTravelPicture($tpEntity);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,""));
        }catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"提交问题异常"));
        }
    }
    public function actionAddTravelPictureComment()
    {
        $this->loginValid();
        try {
            $userSign = $this->userObj->userSign;
            if (empty($userSign)) {
                return json_encode(Code::statusDataReturn(Code::FAIL, "未知用户"));
            }
            $title = Yii::$app->request->post('title');
            $country = Yii::$app->request->post('country');
            $city = Yii::$app->request->post('city');
            $lon = Yii::$app->request->post('lon');
            $lat = Yii::$app->request->post('lat');
            $tags = Yii::$app->request->post('tags');
            $contents= Yii::$app->request->post('contents');
            $picList= Yii::$app->request->post('picList');
            if(empty($title)){return json_encode(Code::statusDataReturn(Code::FAIL, "标题不能为空"));}
            if(empty($country)){return json_encode(Code::statusDataReturn(Code::FAIL, "国家不能为空"));}
            if(empty($city)){return json_encode(Code::statusDataReturn(Code::FAIL, "城市不能为空"));}
            if(empty($lon)){return json_encode(Code::statusDataReturn(Code::FAIL, "经度不能为空"));}
            if(empty($lat)){return json_encode(Code::statusDataReturn(Code::FAIL, "纬度不能为空"));}
            if(empty($tags)){return json_encode(Code::statusDataReturn(Code::FAIL, "标签不能为空"));}
            if(empty($picList)){return json_encode(Code::statusDataReturn(Code::FAIL, "图片不能为空"));}
            $tpEntity = new TravelPicture();
            $tpEntity->title=$title;
            $tpEntity->country=$country;
            $tpEntity->city=$city;
            $tpEntity->lon=$lon;
            $tpEntity->lat=$lat;
            $tpEntity->tags=$tags;
            $tpEntity->contents=$contents;
            $tpEntity->picList=$picList;
            $this->tpSer->addTravelPicture($tpEntity);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,""));
        }catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"提交问题异常"));
        }
    }

}