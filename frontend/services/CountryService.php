<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/25
 * Time : 下午4:15
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\services;


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

}