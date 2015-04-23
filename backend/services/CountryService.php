<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/19
 * Time : 下午3:17
 * Email: zhangxinmailvip@foxmail.com
 */

namespace backend\services;


use backend\components\Page;
use common\entity\City;
use common\entity\Country;
use common\models\BaseDb;
use common\models\CountryDb;
use yii\base\Exception;

class CountryService extends BaseDb{

    private $countryDb;

    public function __construct()
    {
    }


    /**
     * 添加国家
     * @param Country $country
     * @throws Exception
     * @throws \Exception
     */
    public function addCountry(Country $country)
    {
        try {
            $conn = $this->getConnection();
            $this->countryDb = new CountryDb($conn);
            $this->countryDb->addCountry($country);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }

    /**
     * 添加城市
     * @param City $city
     * @throws Exception
     * @throws \Exception
     */
    public function addCity(City $city)
    {
        try {
            $conn = $this->getConnection();
            $this->countryDb = new CountryDb($conn);
            $this->countryDb->addCity($city);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }

    /**
     * 更新国家
     * @param Country $country
     * @throws Exception
     * @throws \Exception
     */
    public function updateCountry(Country $country)
    {
        try {
            $conn = $this->getConnection();
            $this->countryDb = new CountryDb($conn);
            $this->countryDb->updateCountry($country);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }


    /**
     * 更新城市
     * @param City $city
     * @throws Exception
     * @throws \Exception
     */
    public function updateCity(City $city)
    {
        try {
            $conn = $this->getConnection();
            $this->countryDb = new CountryDb($conn);
            $this->countryDb->updateCity($city);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }


    /**
     * 事务删除国家
     * @param $id
     * @throws Exception
     * @throws \Exception
     */
    public function deleteCountryById($id)
    {
        $conn = $this->getConnection();
        $tran=$conn->beginTransaction();
        try {
            $this->countryDb = new CountryDb($conn);
            $this->countryDb->deleteCountryById($id);//删除国家
            $this->countryDb->deleteCityByCountryId($id);//删除国家下面所有城市
            $this->commit($tran);
        } catch (Exception $e) {
            $this->rollback($tran);
            throw $e;
        } finally {
            $this->closeLink();
        }
    }


    /**
     * 根据Id删除城市
     * @param $id
     * @throws Exception
     * @throws \Exception
     */
    public function deleteCityById($id)
    {
        try {
            $conn = $this->getConnection();
            $this->countryDb = new CountryDb($conn);
            $this->countryDb->deleteCityById($id);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }




    /**
     * 获取国家列表
     * @param Page $page
     * @param $name
     * @return array|null
     * @throws Exception
     * @throws \Exception
     */
    public function getCountryList(Page $page,$name=null)
    {
        try {
            $conn = $this->getConnection();
            $this->countryDb = new CountryDb($conn);
            $page=$this->countryDb->getCountryList($page,$name);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $page;
    }

    /**
     * 获取城市列表
     * @param Page $page
     * @param $countryId
     * @param $name
     * @return array|null
     * @throws Exception
     * @throws \Exception
     */
    public function getCityList(Page $page,$countryId,$name)
    {
        if(empty($countryId)){
            throw new Exception("countryId is not Allow Empty");
        }
        try {
            $conn = $this->getConnection();
            $this->countryDb = new CountryDb($conn);
            $page=$this->countryDb->getCityList($page,$countryId,$name);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $page;
    }


    /**
     * 根据Id获取国家详情
     * @param $id
     * @return mixed|null
     * @throws Exception
     * @throws \Exception
     */
    public function findCountryById($id)
    {
        if(empty($id)){
            throw new Exception("countryId is not Allow Empty");
        }
        $country=null;
        try{
            $conn = $this->getConnection();
            $this->countryDb = new CountryDb($conn);
            $rst=$this->countryDb->findCountryById($id);
            $country=$this->arrayCastObject($rst,Country::class);
        }catch (Exception $e){
            throw $e;
        }finally {
            $this->closeLink();
        }
        return $country;
    }


    /**
     * 根据Id获取城市详情
     * @param $id
     * @return mixed|null
     * @throws Exception
     * @throws \Exception
     */
    public function findCityById($id)
    {
        if(empty($id)){
            throw new Exception("cityId is not Allow Empty");
        }
        $city=null;
        try{
            $conn = $this->getConnection();
            $this->countryDb = new CountryDb($conn);
            $rst=$this->countryDb->findCityById($id);
            $city=$this->arrayCastObject($rst,City::class);
        }catch (Exception $e){
            throw $e;
        }finally {
            $this->closeLink();
        }
        return $city;
    }
}