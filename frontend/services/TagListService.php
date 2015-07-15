<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/7/10
 * Time: 下午3:34
 */

namespace frontend\services;


use common\components\Code;
use common\entity\TagList;
use common\models\BaseDb;
use common\models\TagListDb;
use yii\base\Exception;

class TagListService extends BaseDb{

    private $tagDb;
    public function __construct()
    {
    }

    public function addTagList($name)
    {

        try{
            $conn=$this->getConnection();
            $this->tagDb=new TagListDb($conn);
            $rst = $this->tagDb->getTagByName($name);
            if(empty($rst)){
                $tagList= new TagList();
                $tagList->tName=$name;
                $tagList->tType=TagList::TYPE_TRIP_PIC_USER;
                return $this->tagDb->addTagList($tagList);
            }else
            {
               return $rst['tId'];
            }
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }

    //得到问答系统标签
    public function getQaSysTag()
    {

        $rst=null;
        try{
            $conn=$this->getConnection();
            $this->tagDb=new TagListDb($conn);
            $rst = $this->tagDb->getAllTag(TagList::TYPE_TRIP_PIC_SYS);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
        return $rst;
    }

    public function updateQaTagValList($tagList,$qId)
    {
        $arr = explode(',',$tagList);
        if(count($arr)>20){return;}
        foreach($arr as $val){
            $tagV=json_decode(\Yii::$app->redis->get(Code::QUESTION_COMMUNITY_TAG_PREFIX.md5($val)),true);
            if(!empty($tagV)){
                if(!in_array($qId,$tagV)){
                    $tagV[]=$qId;
                    \Yii::$app->redis->set(Code::QUESTION_COMMUNITY_TAG_PREFIX.md5($val),json_encode($tagV));
                }
            }else{
                $tagV[]=$qId;
                \Yii::$app->redis->set(Code::QUESTION_COMMUNITY_TAG_PREFIX.md5($val),json_encode($tagV));
            }
        }
    }

    public function updateTpTagValList($tagList,$qId)
    {
        $arr = explode(',',$tagList);
        if(count($arr)>20){return;}
        foreach($arr as $val){
            $tagV=json_decode(\Yii::$app->redis->get(Code::TRAVEL_PICTURE_TAG_PREFIX.md5($val)),true);
            if(!empty($tagV)){
                if(!in_array($qId,$tagV)){
                    $tagV[]=$qId;
                    \Yii::$app->redis->set(Code::TRAVEL_PICTURE_TAG_PREFIX.md5($val),json_encode($tagV));
                }
            }else{
                $tagV[]=$qId;
                \Yii::$app->redis->set(Code::TRAVEL_PICTURE_TAG_PREFIX.md5($val),json_encode($tagV));
            }
        }
    }

}