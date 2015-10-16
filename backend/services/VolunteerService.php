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
use common\models\VolunteerDb;
use frontend\services\CountryService;
use yii\base\Exception;

class VolunteerService extends BaseDb{


    public $volunteerDb;

    public function __construct()
    {
    }



    public function getVolunteerList($page,$search,$countryId,$cityId,$status)
    {
        try {
            $conn = $this->getConnection();
            $this->volunteerDb = new VolunteerDb($conn);

            if(empty($countryId)&&empty($cityId)){
                $countryService=new CountryService();
                $cc=$countryService->getCC($search);
                $countryId=$cc[0];
                $cityId=$cc[1];
                if(is_array($countryId)){
                    $countryId=implode(",",$countryId);
                }
                if(is_array($cityId)){
                    $cityId=implode(",",$cityId);
                }
            }
            $page=$this->volunteerDb->getList($page,$search,$countryId,$cityId,$status);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $page;
    }



}