<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/9/22
 * Time : 14:14
 * Email: zhangxinmailvip@foxmail.com
 */

namespace console\controllers;

require_once (dirname(dirname(dirname(__FILE__)))."/common/components/simple_html_dom.php");

use common\components\UrlUtil;
use common\entity\TravelPicture;
use yii\console\Controller;

class QyerController extends  Controller{



    public function actionXiangGang()
    {
        $countryId='';
        $cityId='';
        $pageCount=7;
        $keyword='香港';
        $filePath=dirname(__FILE__).'/'.'XiangGang.txt';
        //self::saveUrlList($pageCount,$keyword,$filePath);
        self::getUrlInfo($filePath,'中国','香港');


    }

    public function getUrlInfo($filePath,$country,$city)
    {
        $urlString=file_get_contents($filePath);
        $allUrlList=explode(",",$urlString);

        foreach($allUrlList as $url)
        {
            $response=UrlUtil::get($url,[]);
            $html = new \simple_html_dom();
            $html->load($response);

            $titleImg=$html->find('.userImg img')[0]->getAttribute('src');
            $title=$html->find('.title')[0]->plaintext;
            $time=$html->find('.travelTime')[0]->plaintext;
            $time=str_replace('最后更新于','',$time);
            $time=str_replace(' ','',$time);

            $headImg=$html->find('.face img')[0]->getAttribute('src');
            $userUrl=$html->find('.face a')[0]->getAttribute('href');
            $userId=str_replace('http://www.qyer.com/u/','',$userUrl);
            $nickname=$html->find('.name strong')[0]->plaintext;

            $begin=strpos($response,'<script>')+8;
            $end=strpos($response,'</script>',$begin);

            $var=substr($response,$begin,$end-$begin);
            $var=str_replace("\n",'',$var);
            $var=trim($var);

            $begin=strpos($response,'var _sequence =')+15;
            $end=strpos($response,'var _photos =',$begin);

            $sequence=substr($response,$begin,$end-$begin);
            $sequence=trim(str_replace("\n",'',$sequence));
            $sequence=substr($sequence,0,strlen($sequence)-1);


            $begin=strpos($var,'var _photos =')+13;
            $photos=trim(substr($var,$begin,strlen($var)));
            $photos=substr($photos,0,strlen($photos)-1);

            $sequence=json_decode($sequence,true);
            $photos=json_decode($photos,true);
            foreach($sequence as $key=>$value)
            {
                $contents=[];$pics=[];
                foreach($value as $key){
                    $temp=$photos[$key];
                    $pics[]=$temp['photo'];
                    $contents[]=$temp['photoDescription'];
                }
                //http://pic.qyer.com/album/user/370/9/Qk5VQhMBZA/index/400c

                //http://pic.qyer.com/album/user/370/9/Qk5VQhMBZA/index/w1080
                $travelPicture=new TravelPicture();
                $travelPicture->contents=implode(',',$contents);
                $travelPicture->picList=implode(',',$pics);

                $travelPicture->titleImg=$titleImg;
                $travelPicture->country=$country;
                $travelPicture->city=$city;
                $travelPicture->createTime=$time;
                $travelPicture->userSign=$userId;
                if($key!='unsorted'){

                    var_dump($sequence);exit;
                }else{

                }
//
//                ["photo"]=>
//  string(59) "http://pic.qyer.com/album/user/539/43/REpcRhkHYQ/index/400c"
//  ["photoUrl"]=>
//  string(46) "http://www.qyer.com/photo/5394301/?spm=pictrip"
//  ["photoDescription"]=>
//  string(0) ""
//  ["width"]=>
//  int(800)
//  ["height"]=>
//  int(534)
//  ["photoLikes"]=>
//  string(2) "98"
//  ["photoReplies"]=>
//  string(1) "8"
//  ["poiName"]=>
//  string(0) ""
//  ["poiUrl"]=>
//  string(0) ""
//  ["iLike"]=>
//  bool(false)

            }




            //var_dump($sequence,"\n",$photos);exit;
        }
    }


    private function saveUrlList($pageCount,$keyword,$filePath)
    {
        for($i=1;$i<=$pageCount;$i++)
        {
            echo '开始-存储第'.$i.'页'."\n";
            self::saveUrl($i,1,0,$keyword,$filePath);
            self::saveUrl($i,2,0,$keyword,$filePath);
            self::saveUrl($i,3,0,$keyword,$filePath);
            echo '结束-存储第'.$i.'页'."\n";

        }
    }

    private function saveUrl($page,$number,$cId,$keyword=0,$filePath)
    {
        $url='http://pictrip.qyer.com/ajax/loadPictrip';
        $params=[
            'format'=>'json',
            'page'=>$page,
            'number'=>$number,
            'countryid'=>$cId,
            'keyword'=>$keyword
        ];
        $response=UrlUtil::post($url,$params);
        $response=json_decode($response,true);
        if($response['error_code']!=0){
            return;
        }

        $response=$response['data']['html'];

        $html = new \simple_html_dom();
        $html->load($response);


        foreach($html->find('.album') as $element)
        {
            //$titleImg=$element->find('.albumCover')[0]->getAttribute('src');
            $href=$element->find('.albumLink')[0]->href;

            //$title=$element->find('.title')[0]->plaintext;
            //$date=$element->find('.date')[0]->plaintext;
            //$title=$element->find('.title')[0]->plaintext;
            //$title=$element->find('.title')[0]->plaintext;
            file_put_contents($filePath,$href.",",FILE_APPEND);
        }
    }






}