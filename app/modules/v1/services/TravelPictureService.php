<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/7/15
 * Time: 上午10:43
 */

namespace app\modules\v1\services;


use common\components\Code;
use  app\modules\v1\entity\TravelPicture;
use  app\modules\v1\entity\TravelPictureComment;
use  app\modules\v1\entity\UserAttention;
use  app\modules\v1\entity\UserMessageRemind;
use common\models\BaseDb;
use  app\modules\v1\models\TravelPictureDb;
use  app\modules\v1\models\UserAttentionDb;
use frontend\components\Page;
use yii\base\Exception;

class TravelPictureService  extends BaseDb {

    private $tpDb;
    private $tpcDb;
    public function addTravelPicture(TravelPicture $travelPicture)
    {
        $conn=$this->getConnection();
        try{
            $this->tpDb=new TravelPictureDb($conn);
            $tagSer = new TagListService();
            $qId = $this->tpDb->addTravelPicture($travelPicture);
            $tagSer->updateTpTagValList($travelPicture->tags,$qId);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }

    public function addTravelPictureComment(TravelPictureComment $travelPictureComment)
    {
        $conn=$this->getConnection();
        try{
            $this->tpcDb=new TravelPictureDb($conn);
            $this->tpcDb->addTravelPictureComment($travelPictureComment);
            $info = $this->updateTravelPictureCommentCount($travelPictureComment->tpId,true);
            if(empty($info))
            {
                echo json_encode(Code::statusDataReturn(Code::FAIL,'未知旅图'));
                exit;
            }
            $messageRemindSer = new UserMessageRemindService();
            $content="###".$info['title'];
            $url="###";
            $messageRemindSer->addMessageRemind($travelPictureComment->tpId,UserMessageRemind::TYPE_COMMENT,$travelPictureComment->userSign,$info['userSign'],UserMessageRemind::R_TYPE_TRAVEL_PICTURE,$content,$url);

        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }

    public function getInfoById($page,$id,$userSign)
    {
        $conn=$this->getConnection();
        try{
            $this->tpDb=new TravelPictureDb($conn);
            $info = $this->tpDb->getTravelPictureInfoById($id);
            $comment = $this->tpDb->getCommentListByTpId($page,$id);
            $attentionDb =new UserAttentionDb($conn);
            $attentionEntity = new UserAttention();
            $attentionEntity->relativeId=$id;
            $attentionEntity->relativeType=UserAttention::TYPE_FOR_TRAVEL_PICTURE;
            $attentionEntity->userSign = $userSign;
            $attention = $attentionDb->getAttentionResult($attentionEntity);
            $attention = $attention==false?array():array($attention);
            $likePage = new Page();
            $likePage->pageSize=5;
            $rst = $this->getLike($likePage,$id);
            return array('info'=>$info,'comment'=>$comment->getList(),'attention'=>$attention,'like'=>$rst['data']);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }

    public function getList($page,$tags,$search)
    {
        $conn=$this->getConnection($page);
        try{
            $tagStr='';
            if(!empty($tags)&&$tags!='全部')
            {
                $intersection=array();
                $tagList=explode(',',$tags);
                foreach($tagList as $val){
                    $valArr=json_decode(\Yii::$app->redis->get(Code::TRAVEL_PICTURE_TAG_PREFIX.md5($val)),true);
                    if(!empty($valArr)){
                        $intersection=array_merge($intersection,$valArr);
                    }
                }
                if(!empty($intersection)){
                    $arr = array_count_values($intersection);
                    arsort($arr);
                    $result = array_keys($arr);
                    $tagStr=implode(',',$result);
                }else{
                    $tagStr='-1';
                }
            }
            $this->tpDb=new TravelPictureDb($conn);
            $page= $this->tpDb->getTravelPictureList($page,$tagStr,$search);
            return array('data'=>$page->getList(),'msg'=>$page);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }

    public function updateTravelPictureCommentCount($id,$add)
    {
        try{
            $conn=$this->getConnection();
            $this->tpDb=new TravelPictureDb($conn);
            $info = $this->tpDb->getTravelPictureInfoById($id);
            $count = $info['commentCount']?$info['commentCount']:0;
            if($add)
            {
                $count++;
            }else
            {
                $count--;
                if($count<1)
                {
                    $count=0;
                }
            }
            $this->tpDb->updateCommentCount($id,$count);
            return $info;
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }

    public function updateTravelPictureAttentionCount($id,$add)
    {
        $conn=$this->getConnection();
        try{
            $this->tpDb=new TravelPictureDb($conn);
            $info = $this->tpDb->getTravelPictureInfoById($id);
            $count = $info['attentionCount']?$info['attentionCount']:0;
            if($add)
            {
                $count++;
            }else
            {
                $count--;
                if($count<1)
                {
                    $count=0;
                }
            }
            $this->tpDb->updateAttentionCount($id,$count);
            return $info;
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }

    public function getLike($page,$id)
    {
        try{
            $conn=$this->getConnection($page);
            $this->tpDb=new TravelPictureDb($conn);
            $info = $this->tpDb->getTravelPictureInfoById($id);
            $city = $info['city'];
            $country = $info['country'];
            $tags = $info['tags'];
            $tagStr='';
            if(!empty($tags))
            {
                $intersection=array();
                $tagList=explode(',',$tags);
                foreach($tagList as $val){
                    $valArr=json_decode(\Yii::$app->redis->get(Code::TRAVEL_PICTURE_TAG_PREFIX.md5($val)),true);
                    if(!empty($valArr)){
                        $intersection=array_merge($intersection,$valArr);
                    }
                }
                if(!empty($intersection)){
                    $arr = array_count_values($intersection);
                    arsort($arr);
                    $result = array_keys($arr);
                    $tagStr=implode(',',$result);
                }else{
                    $tagStr='-1';
                }
            }
            if(empty($city))
            {
                $search=$country;
            }else
            {
                $search=$city;
            }
            $page= $this->tpDb->getTravelPictureList($page,$tagStr,$search);
            return array('data'=>$page->getList(),'msg'=>$page);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }

    public function getUserTp($page,$userSign)
    {
        try{
            $conn=$this->getConnection();
            $this->tpDb=new TravelPictureDb($conn);
            $page = $this->tpDb->getUserTp($page,$userSign);
            return array('data'=>$page->getList(),'msg'=>$page);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }
}