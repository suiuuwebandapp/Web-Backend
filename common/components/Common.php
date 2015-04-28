<?php
namespace common\components;
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/4/19
 * Time: 下午3:08
 */

class Common{

    public static function CurlHandel($url,$data=null, $header = array(), $type = 'POST')
    {
        if($header==null){
            $header=array();
        }
        array_push($header, 'Accept:application/json');
        array_push($header, 'Content-Type:application/json');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        //curl_setopt($ch, $method, 1);

        switch ($type) {
            case "GET" :
                curl_setopt($ch, CURLOPT_HTTPGET, true);
                break;
            case "POST":
                curl_setopt($ch, CURLOPT_POST, true);
                break;
            case "PUT" :
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                break;
            case "DELETE":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
        }

        curl_setopt($ch, CURLOPT_USERAGENT, 'SSTS Browser/1.0');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        if (! empty ( $data )) {
            $options = json_encode ( $data );
            curl_setopt ( $ch, CURLOPT_POSTFIELDS, $options ); // Post提交的数据包
        }
        if (count($header) > 0) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }

        $ret = curl_exec($ch);
        $err = curl_error($ch);

        curl_close($ch);

        if ($err) {
            return Code::statusDataReturn(Code::FAIL,$err);
        }
        if (empty($ret)) {
            return Code::statusDataReturn(Code::FAIL);
        }
        return Code::statusDataReturn(Code::SUCCESS, $ret);
    }

    public static function PageResult($page,$count=0)
    {
        $result='';
        if($count==0)
        {
        $pageCount =\Yii::$app->params['pageCount'];
        }else{
            $pageCount= $count;
        }
        if($page!=0)
        {
            $start=intval($page-1)*$pageCount;
            $result='LIMIT '.$start.', '.$pageCount;
        }

        return $result;


    }
}

