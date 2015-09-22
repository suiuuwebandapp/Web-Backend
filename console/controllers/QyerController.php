<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/9/22
 * Time : 14:14
 * Email: zhangxinmailvip@foxmail.com
 */

namespace console\controllers;

require (dirname(dirname(dirname(__FILE__)))."/common/components/simple_html_dom.php");

use common\components\UrlUtil;
use yii\console\Controller;

class QyerController extends  Controller{



    public function actionXiangGang()
    {
        $pageCount=100;
        $countryId='';$cityId='';
        for($i=1;$i<=$pageCount;$i++)
        {
            self::getList($i,1,50);
        }
    }


    private function getList($page,$number,$cId)
    {
        $url='http://pictrip.qyer.com/ajax/loadPictrip';
        $params=[
            'format'=>'json',
            'page'=>$page,
            'number'=>$number,
            'countryid'=>$cId,
            'keyword'=>0
        ];
        $response=UrlUtil::post($url,$params);
        $response=json_decode($response,true);
        if($response['error_code']!=0){
            return;
        }

        $response=$response['data']['html'];

        $html = new \simple_html_dom();
        $html->load($response);


        foreach($html->find('#jspictrip_load_list')[0]->find('.album') as $element)
        {

        }
    }






}