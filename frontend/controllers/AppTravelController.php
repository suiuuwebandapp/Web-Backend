<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/3
 * Time: 下午2:39
 */
namespace frontend\controllers;

use backend\components\Page;
use common\components\Code;
use common\components\TagUtil;
use frontend\services\CountryService;
use frontend\services\TripService;
use yii\base\Exception;
use Yii;
class AppTravelController extends AController
{
    private $travelSer;
    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        $this->travelSer=new TripService();
    }

    public function actionTest()
    {
    }
    //得到随游列表 根据筛选条件
    public function actionGetTravelList()
    {
        $this->loginValid();
        try{
            $page=new Page();
            $page->showAll=true;
            $title=Yii::$app->request->post('title');
            $countryId=Yii::$app->request->post('countryId');
            $cityId=Yii::$app->request->post('cityId');
            $peopleCount=Yii::$app->request->post('peopleCount');
            $startPrice=Yii::$app->request->post('startPrice');
            $endPrice=Yii::$app->request->post('endPrice');
            $tag=Yii::$app->request->post('tag');
            $data=$this->travelSer->getList($page,$title,$countryId,$cityId,$peopleCount,$startPrice,$endPrice,$tag);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }

    }

    //得到国家城市
    public function actionGetCountry()
    {
        $this->loginValid();
        try{
            $countryService = new CountryService();
            $countryList = $countryService->getCountryList();
            //$tagList = TagUtil::getInstance()->getTagList();
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$countryList));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }

    }
    //得到国家城市
    public function actionGetCity()
    {
        $this->loginValid();
        try{
            $countryId=Yii::$app->request->post('countryId');
            $cityName=Yii::$app->request->post('cityName');
            $countryService = new CountryService();
            $cityList = $countryService->getCityList(336,$cityName);
            //$tagList = TagUtil::getInstance()->getTagList();
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$cityList));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }

    }
    //得到类型
    public function actionGetTagList()
    {
        $this->loginValid();
        try{
            $tagList = TagUtil::getInstance()->getTagList();

            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$tagList));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }

    }
//得到随游详情
    public function actionGetTravelInfo()
    {
        $this->loginValid();
        try{
            $userSign=$this->userObj->userSign;
            $trId=Yii::$app->request->post('trId');
            $data=$this->travelSer->getTravelTripInfoById($trId,$userSign);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$this->unifyReturn($data)));
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }
    private function ob2ar($obj) {
        $obj=$this->unifyReturn($obj);
        if(is_object($obj)) {
            $obj = (array)$obj;
            $obj = $this->ob2ar($obj);
        } elseif(is_array($obj)) {
            foreach($obj as $key => $value) {
                $obj[$key] = $this->ob2ar($value);
            }
        }
        return $obj;
    }
    private function unifyReturn($data)
    {
        if($data==false)
        {
            $data=array();
        }
        return $data;
    }
}