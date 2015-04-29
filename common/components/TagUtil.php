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

}