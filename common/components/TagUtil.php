<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/23
 * Time : 下午7:12
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\components;


class TagUtil
{

    private static $tagUtil;

    private $defaultTags=['家庭','美食','惊悚','博物馆','猎奇','自然','浪漫','购物'];
    private function __construct()
    {

    }

    public static function getInstance()
    {
       if(!isset(self::$tagUtil)){
           self::$tagUtil=new TagUtil();
       }
        return self::$tagUtil;
    }

    public function getTagList()
    {
        $tags=\Yii::$app->redis->get(Code::SYS_TAGS_REDIS_KEY);
        if(empty($tags)||$tags=='null'){
            $tags=$this->defaultTags;
            \Yii::$app->redis->set(Code::SYS_TAGS_REDIS_KEY,json_encode($tags));
        }else{
            $tags=json_decode($tags);
        }
        return $tags;

    }

    public function updateTagValList($tagList,$tripId)
    {
        $arrIntersect=array_intersect($tagList,$this->defaultTags);
        $arrDiff=array_diff($this->defaultTags,$tagList);
        foreach($arrIntersect as $val){
         $tagV=json_decode(\Yii::$app->redis->get(Code::TRAVEL_TRIP_TAG_PREFIX.md5($val)),true);
            if(!empty($tagV)){
             if(!in_array($tripId,$tagV)){
                 $tagV[]=$tripId;
                  \Yii::$app->redis->set(Code::TRAVEL_TRIP_TAG_PREFIX.md5($val),json_encode($tagV));
                 }
            }else{
                $tagV[]=$tripId;
                \Yii::$app->redis->set(Code::TRAVEL_TRIP_TAG_PREFIX.md5($val),json_encode($tagV));
            }
        }
        foreach($arrDiff as $vald) {
            $tagVd = json_decode(\Yii::$app->redis->get(Code::TRAVEL_TRIP_TAG_PREFIX . md5($vald)), true);
            if (!empty($tagVd)) {
                $rst = array_search($tripId, $tagVd);
                if ($rst) {
                    $nArr = array_splice($tagVd, $rst, 1);
                    \Yii::$app->redis->set(Code::TRAVEL_TRIP_TAG_PREFIX . md5($vald), json_encode($nArr));
                }
            }
        }
    }



}