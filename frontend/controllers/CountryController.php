<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/19
 * Time : 下午3:40
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\controllers;


use common\components\LogUtils;
use frontend\services\CountryService;
use common\components\Code;
use yii\base\Exception;

class CountryController extends UnCController{

    private $countryService;


    public function __construct($id, $module = null)
    {
        $this->countryService=new CountryService();
        parent::__construct($id, $module);
    }


    /**
     * 获取城市列表(Ajax)
     * @return null
     */
    public function actionFindCityList(){

        $countryId=\Yii::$app->request->post("countryId");
        try{
            $list=$this->countryService->getCityList($countryId,null);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$list));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,$e->getMessage()));
        }
    }


}