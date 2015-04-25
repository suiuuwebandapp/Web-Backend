<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/23
 * Time : 下午7:12
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\components;


class GoogleMap
{

    private $queryType = "json";
    private $key = "AIzaSyCZRwLJg9ZnUBBBQUzsXF5rfg3g_oDrNnM";
    private $language = "zh-cn";
    private $charset = "utf-8";
    private $mapClient = "jsaou";

    private static $googleMap;

    private function __construct()
    {

    }

    public static function getInstance()
    {
       if(!isset(self::$googleMap)){
           self::$googleMap=new GoogleMap();
       }
        return self::$googleMap;
    }

    /**
     * 搜索某个地方详情
     * @param $search
     * @return array|string
     */
    public function searchSiteInfo($search)
    {
        $url = "http://maps.google.com/maps/api/geocode/json?";
        $url .= "address=" . trim($search);
        $url .= "&sensor=false";
        $rst = Common::CurlHandel($url, null, null, "GET");
        return $rst['data'];
    }


}