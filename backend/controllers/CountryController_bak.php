<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/19
 * Time : 下午3:40
 * Email: zhangxinmailvip@foxmail.com
 */

namespace backend\controllers;


use backend\components\Page;
use backend\components\TableResult;
use backend\services\CountryService;
use common\components\Code;
use common\entity\City;
use common\entity\Country;
use yii\base\Exception;

class CountryController extends CController{

    private $countryService;


    public function __construct($id, $module = null)
    {
        $this->countryService=new CountryService();
        parent::__construct($id, $module);
    }


    /**
     * 跳转到添加国家页面
     * @return string
     */
    public function actionToAddCountry()
    {
        return $this->render("addCountry");
    }

    /**
     * 跳转到添加城市页面
     * @return string
     */
    public function actionToAddCity()
    {
        $countryId=\Yii::$app->request->get("countryId");
        return $this->render("addCity",[
            'countryId'=>$countryId
        ]);
    }


    /**
     * 跳转到国家编辑页面
     * @return string
     */
    public function actionToEditCountry()
    {
        $countryId=\Yii::$app->request->get("countryId");
        $country = $this->countryService->findCountryById($countryId);
        return $this->render("editCountry",['country'=>$country]);
    }

    /**
     * 太哦转到编辑城市页面
     * @return string
     * @throws Exception
     * @throws \Exception
     */
    public function actionToEditCity()
    {
        $cityId=\Yii::$app->request->get("cityId");
        $city = $this->countryService->findCityById($cityId);
        return $this->render("editCity",['city'=>$city]);
    }




    /**
     * 添加国家
     * @return null
     */
    public function actionAddCountry()
    {
        $cname=\Yii::$app->request->post("cname","");
        $ename=\Yii::$app->request->post("ename","");
        $code=\Yii::$app->request->post("code","");
        $areaCode=\Yii::$app->request->post("areaCode","");

        try{
            $country=new Country();
            $country->cname=$cname;
            $country->ename=$ename;
            $country->code=$code;
            $country->areaCode=$areaCode;

            $this->countryService->addCountry($country);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            echo json_encode(Code::statusDataReturn(Code::FAIL,$e->getMessage()));

        }
        return null;
    }

    /**
     * 添加城市
     * @return null
     */
    public function actionAddCity()
    {
        $cname=\Yii::$app->request->post("cname","");
        $ename=\Yii::$app->request->post("ename","");
        $code=\Yii::$app->request->post("code","");
        $countryId=\Yii::$app->request->post("countryId");

        try{
            $city=new City();
            $city->cname=$cname;
            $city->ename=$ename;
            $city->code=$code;
            $city->countryId=$countryId;

            $this->countryService->addCity($city);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            echo json_encode(Code::statusDataReturn(Code::FAIL,$e->getMessage()));

        }
        return null;
    }


    /**
     * 更新国家
     * @return null
     */
    public function actionUpdateCountry()
    {
        $countryId=\Yii::$app->request->post("id","");
        $cname=\Yii::$app->request->post("cname","");
        $ename=\Yii::$app->request->post("ename","");
        $code=\Yii::$app->request->post("code","");
        $areaCode=\Yii::$app->request->post("areaCode","");

        try{

            $country=new Country();

            $country->id=$countryId;
            $country->cname=$cname;
            $country->ename=$ename;
            $country->code=$code;
            $country->areaCode=$areaCode;

            $this->countryService->updateCountry($country);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            echo json_encode(Code::statusDataReturn(Code::FAIL,$e->getMessage()));

        }
        return null;
    }


    /**
     * 更新城市
     * @return null
     */
    public function actionUpdateCity()
    {
        $cityId=\Yii::$app->request->post("id","");
        $cname=\Yii::$app->request->post("cname","");
        $ename=\Yii::$app->request->post("ename","");
        $code=\Yii::$app->request->post("code","");
        $countryId=\Yii::$app->request->post("countryId");

        try{

            $city=new City();

            $city->id=$cityId;
            $city->cname=$cname;
            $city->ename=$ename;
            $city->code=$code;
            $city->countryId=$countryId;

            $this->countryService->updateCity($city);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            echo json_encode(Code::statusDataReturn(Code::FAIL,$e->getMessage()));

        }
        return null;
    }


    /**
     * 删除国家
     * @return null
     */
    public function actionDeleteCountry()
    {
        $countryId=\Yii::$app->request->post("countryId","");
        try{
            $this->countryService->deleteCountryById($countryId);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            echo json_encode(Code::statusDataReturn(Code::FAIL,$e->getMessage()));

        }
        return null;
    }


    /**
     * 删除城市
     * @return null
     */
    public function actionDeleteCity()
    {
        $cityId=\Yii::$app->request->post("cityId","");
        try{
            $this->countryService->deleteCityById($cityId);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            echo json_encode(Code::statusDataReturn(Code::FAIL,$e->getMessage()));
        }
        return null;
    }




    /**
     * 获取城市列表(Ajax)
     * @return null
     */
    public function actionFindCityList(){

        $countryId=\Yii::$app->request->post("countryId");
        try{
            $page=new Page();
            $page->showAll=true;
            $list=$this->countryService->getCityList($page,$countryId,null);
            $list=$list->getList();
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$list));
        }catch (Exception $e){
            echo json_encode(Code::statusDataReturn(Code::FAIL,$e->getMessage()));
        }
        return null;
    }


    public function actionToCountryList()
    {
        return $this->render("countryList");
    }


    public function actionToCityList()
    {
        $countryId=\Yii::$app->request->get("countryId");

        $country = $this->countryService->findCountryById($countryId);
        return $this->render("cityList",['country'=>$country]);
    }

    public function actionCountryList()
    {
        $search=\Yii::$app->request->get("searchText","");

        $page=new Page(\Yii::$app->request);

        $page=$this->countryService->getCountryList($page,$search);

        $tableResult=new TableResult($page->draw,count($page->getList()),$page->totalCount,$page->getList());

        echo json_encode($tableResult);
    }

    public function actionCityList()
    {
        $countryId=\Yii::$app->request->post("countryId");
        $search=\Yii::$app->request->get("searchText","");
        $page=new Page(\Yii::$app->request);

        $page=$this->countryService->getCityList($page,$countryId,$search);

        $tableResult=new TableResult($page->draw,count($page->getList()),$page->totalCount,$page->getList());

        echo json_encode($tableResult);
    }



}