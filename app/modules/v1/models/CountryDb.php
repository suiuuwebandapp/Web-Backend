<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/19
 * Time : 下午3:11
 * Email: zhangxinmailvip@foxmail.com
 */

namespace app\modules\v1\models;


use app\components\Page;
use app\modules\v1\entity\City;
use app\modules\v1\entity\Country;
use common\models\ProxyDb;
use yii\db\mssql\PDO;

class CountryDb extends ProxyDb{


    /**
     * 添加国家
     * @param Country $country
     * @throws \yii\db\Exception
     */
    public function addCountry(Country $country)
    {
        $sql=sprintf("
            INSERT INTO country
            (
              cname,ename,code,areaCode
            )
            VALUES
            (
              :cname,:ename,:code,:areaCode
            )
        ");

        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":cname",$country->cname);
        $command->bindParam(":ename",$country->ename);
        $command->bindParam(":code",$country->code);
        $command->bindParam(":areaCode",$country->areaCode);

        $command->execute();
    }


    /**
     * 添加城市
     * @param City $city
     * @throws \yii\db\Exception
     */
    public function addCity(City $city)
    {
        $sql=sprintf("
            INSERT INTO city
            (
              cname,ename,code,countryId
            )
            VALUES
            (
              :cname,:ename,:code,:countryId
            )
        ");

        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":cname",$city->cname);
        $command->bindParam(":ename",$city->ename);
        $command->bindParam(":code",$city->code);
        $command->bindParam(":countryId",$city->countryId);

        $command->execute();
    }


    /**
     * 更新国家
     * @param Country $country
     * @throws \yii\db\Exception
     */
    public function updateCountry(Country $country)
    {
        $sql=sprintf("
            UPDATE  country SET
            cname=:cname,ename=:ename,code=:code,areaCode=:areaCode
            WHERE id=:id
        ");

        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":cname",$country->cname);
        $command->bindParam(":ename",$country->ename);
        $command->bindParam(":code",$country->code);
        $command->bindParam(":areaCode",$country->areaCode);
        $command->bindParam(":id",$country->id);
        $command->execute();
    }

    /**
     *  更新城市
     * @param City $city
     * @throws \yii\db\Exception
     */
    public function updateCity(City $city)
    {
        $sql=sprintf("
            UPDATE  city SET
            cname=:cname,ename=:ename,code=:code,countryId=:countryId
            WHERE id=:id
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":cname",$city->cname);
        $command->bindParam(":ename",$city->ename);
        $command->bindParam(":code",$city->code);
        $command->bindParam(":countryId",$city->countryId);
        $command->bindParam(":id",$city->id);
        $command->execute();
    }


    /**
     * 根据Id删除国家
     * @param $id
     * @throws \yii\db\Exception
     */
    public function deleteCountryById($id)
    {
        $sql=sprintf("
            DELETE FROM  country
            WHERE id=:id
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":id",$id);
        $command->execute();
    }


    public function deleteCityByCountryId($id)
    {
        $sql=sprintf("
            DELETE FROM  city
            WHERE countryId=:id
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":id",$id);
        $command->execute();
    }


    public function deleteCityById($id)
    {
        $sql=sprintf("
            DELETE FROM  city
            WHERE id=:id
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":id",$id);
        $command->execute();
    }



    /**
     * 获取国家列表
     * @param Page $page
     * @param $name
     * @return array
     */
    public function getCountryList(Page $page,$name)
    {
        $sql=sprintf(" FROM country WHERE 1=1 ");

        if(!empty($name)){
            $sql.=" AND ( cname like :name OR ename like :name ) ";
            $this->setParam("name",$name."%");
        }
        $this->setSelectInfo("id,cname,ename,areaCode");
        $this->setSql($sql);
        return $this->find($page);
    }


    /**
     * 获取城市列表（根据国家ID）
     * @param Page $page
     * @param $countryId
     * @param $name
     * @return array
     */
    public function getCityList(Page $page,$countryId,$name)
    {
        $sql=sprintf(" FROM city WHERE countryId=:countryId ");
        $this->setParam("countryId",$countryId);
        if(!empty($name)){
            $sql.=" AND ( cname like :name OR ename like :name ) ";
            $this->setParam("name",$name."%");
        }
        $this->setSelectInfo("id,cname,ename,code,countryId");
        $this->setSql($sql);
        return $this->find($page);
    }
    /**
     * 获取城市列表
     * @return array
     */
    public function getAllCity()
    {
        $sql=sprintf("
            SELECT id, cname,ename,code,countryId FROM city
        ");
        $command=$this->getConnection()->createCommand($sql);
        return $command->queryAll();
    }

    /**得到随游已有国家
     * @return array
     */
    public function getTripCountry()
    {
        $sql=sprintf("
           SELECT b.cname ,b.id  FROM (SELECT countryId FROM travel_trip a GROUP BY a.countryId) as a
LEFT JOIN country b ON a.countryId = b.id
        ");
        $command=$this->getConnection()->createCommand($sql);
        return $command->queryAll();
    }
    /**得到随游已有城市
     * @return array
     */
    public function getTripCity()
    {
        $sql=sprintf("
           SELECT b.cname ,b.id FROM (SELECT cityId FROM travel_trip a GROUP BY a.cityId) as a
LEFT JOIN city b ON a.cityId = b.id
        ");
        $command=$this->getConnection()->createCommand($sql);
        return $command->queryAll();
    }
    /**
     * 根据Id获取国家
     * @param $id
     * @return array|bool
     */
    public function findCountryById($id)
    {
        $sql=sprintf("
            SELECT id,cname,ename,areaCode FROM country WHERE id=:id
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":id",$id,PDO::PARAM_INT);

        return $command->queryOne();
    }

    /**
     * 根据Id获取城市
     * @param $id
     * @return array|bool
     */
    public function findCityById($id)
    {
        $sql=sprintf("
            SELECT id,cname,ename,code,countryId FROM city WHERE id=:id
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":id",$id);
        return $command->queryOne();
    }

    /**
     * 获取手机区号列表
     * @return array
     */
    public function getCountryPhoneCodeList()
    {
        $sql=sprintf("
            SELECT cname,ename,areaCode FROM country
            WHERE areaCode!='';
        ");
        $command=$this->getConnection()->createCommand($sql);
        return $command->queryAll();

    }

    public function getCityByName($name)
    {
        $sql=sprintf("
            SELECT id,cname,ename,code,countryId  FROM city
            WHERE cname like :name OR ename like :name;
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":name",$name);
        return $command->queryAll();
    }
    public function getCountryByName($name)
    {
        $sql=sprintf("
            SELECT id,cname,ename,areaCode FROM country
            WHERE cname like :name OR ename like :name;
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":name",$name);
        return $command->queryAll();
    }

}