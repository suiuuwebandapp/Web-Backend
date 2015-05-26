<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/25
 * Time : 下午4:15
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\services;


use backend\components\Page;
use common\entity\City;
use common\models\BaseDb;
use common\models\CountryDb;
use yii\base\Exception;

class CountryService extends BaseDb{
    private $countryDb;

    public function __construct()
    {

    }

    /**
     * 获取城市phoneCode 列表
     * @return array|null
     * @throws Exception
     * @throws \Exception
     */
    public function getCountryPhoneCodeList()
    {
        $areaCodeList=null;
        try {
            $conn = $this->getConnection();
            $this->countryDb = new CountryDb($conn);
            $areaCodeList=$this->countryDb->getCountryPhoneCodeList();
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $areaCodeList;
    }


    /**
     * 获取国家列表
     * @param $name
     * @return array|null
     * @throws Exception
     * @throws \Exception
     */
    public function getCountryList($name=null)
    {
        $page=new Page();
        $page->showAll=true;
        try {

            $conn = $this->getConnection();
            $this->countryDb = new CountryDb($conn);
            $page=$this->countryDb->getCountryList($page,$name);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $page->getList();
    }

    /**
     * 获取城市列表
     * @param $countryId
     * @param $name
     * @return array|null
     * @throws Exception
     * @throws \Exception
     */
    public function getCityList($countryId,$name)
    {
        if(empty($countryId)){
            throw new Exception("countryId is not Allow Empty");
        }
        $page=new Page();
        $page->showAll=true;
        try {
            $conn = $this->getConnection();
            $this->countryDb = new CountryDb($conn);
            $page=$this->countryDb->getCityList($page,$countryId,$name);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $page->getList();
    }

    /**
     * 更具国家城市的名字获取ID
     * @param $name
     * @return array
     * @throws Exception
     * @throws \Exception
     */
    public function getCC($name)
    {
        try {
            $conn = $this->getConnection();
            $this->countryDb = new CountryDb($conn);
            $countryId=$this->countryDb->getCountryByName($name);
            if(!empty($countryId))
            {
                return array($countryId['id'],null);
            }
            $cityId=$this->countryDb->getCityByName($name);
            if(!empty($cityId))
            {
                return array(null,$cityId['id']);
            }
            return array(null,null);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }

    public function findCityById($cityId)
    {
        if(empty($cityId)){
            throw new Exception("CityId is not Allow Empty");
        }
        $cityInfo=null;
        try {
            $conn = $this->getConnection();
            $this->countryDb = new CountryDb($conn);
            $cityInfo=$this->countryDb->findCityById($cityId);
            $cityInfo=$this->arrayCastObject($cityInfo,City::class);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $cityInfo;
    }

}