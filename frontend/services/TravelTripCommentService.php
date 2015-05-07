<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/6
 * Time: 下午5:08
 */
namespace frontend\services;

use common\components\Code;
use common\entity\TravelTripComment;
use common\entity\UserAttention;
use common\models\BaseDb;
use common\models\TravelTripCommentDb;
use common\models\UserAttentionDb;
use yii\base\Exception;

class TravelTripCommentService extends BaseDb
{

    public $TravelTripCommentDb;
    public $AttentionDb;
    /**得到评论列表
     * @param $tripId
     * @param $page
     * @return array
     * @throws Exception
     */
    public function getTravelComment($tripId,$page,$userSign)
    {
        try {
            $conn = $this->getConnection();
            $this->TravelTripCommentDb = new TravelTripCommentDb($conn);
            $data=$this->TravelTripCommentDb->getCommentListByTripId($tripId,$page,$userSign);
            return array('data'=>$data->getList(),'msg'=>$data);
        } catch (Exception $e) {
            throw new Exception('获取评论异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }

    public function addComment($userSign,$content,$replayCommentId,$tripId,$rTitle,$rUserSign)
    {
        try {
            $conn = $this->getConnection();
            $this->TravelTripCommentDb = new TravelTripCommentDb($conn);
            $comment=new TravelTripComment();
            $comment->tripId=$tripId;
            $comment->userSign=$userSign;
            $comment->content=$content;
            $comment->replayCommentId=$replayCommentId;
            $comment->rTitle=$rTitle;
            $rst = $this->TravelTripCommentDb->getTravelOrderByUserSign($userSign,$tripId);
            if(empty($rst)||$rst==false)
            {
                $comment->isTravel=TravelTripComment::TYPE_IS_TRAVEL_N;
            }else
            {
                $comment->isTravel=TravelTripComment::TYPE_IS_TRAVEL_Y;
            }
            $comment->rUserSign=$rUserSign;
            //得到是否玩过暂未修改
            $this->TravelTripCommentDb->addTripComment($comment);
        } catch (Exception $e) {
            throw new Exception('获取评论异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }


    /**
     * 添加支持或反对
     * @param $commentId //评论id
     * @param $userSign,//收藏用户
     * @throws Exception
     */
    public function addCommentSupport($commentId,$userSign,$isSupport)
    {

        try {
            $conn = $this->getConnection();
            $this->AttentionDb = new UserAttentionDb($conn);
            $this->TravelTripCommentDb = new TravelTripCommentDb($conn);
            $attention =new UserAttention();
            $attention->relativeType=UserAttention::TYPE_COMMENT_FOR_TRAVEL;
            $attention->relativeId=$commentId;
            $attention->userSign = $userSign;
            $result = $this->AttentionDb->getAttentionResult($attention,true);
            $TravelTripComment=$this->TravelTripCommentDb->getCommentInfoById($commentId);
            if(empty($TravelTripComment)||$TravelTripComment==false){echo json_encode(Code::statusDataReturn(Code::FAIL,'无法为未知随游评论点赞'));exit;}
            if(empty($result)||$result==false)
            {
                $this->AttentionDb ->addUserAttention($commentId,UserAttention::TYPE_COMMENT_FOR_TRAVEL,$userSign,$isSupport);
                $rst=$this->upDateCommentSupport($TravelTripComment,$isSupport);
                $this->TravelTripCommentDb->updateCommentSupportNumb($rst);
            }else{
                echo json_encode(Code::statusDataReturn(Code::FAIL,'已经点赞无需继续点赞'));exit;
            }
        } catch (Exception $e) {
            throw new Exception('添加点赞异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }


    public function getCommentTripList($page,$userSign)
    {
        try {
            $conn = $this->getConnection();
            $this->TravelTripCommentDb = new TravelTripCommentDb($conn);
            $TripList=$this->TravelTripCommentDb->getCommentByUser($page,$userSign);
            return array('data'=>$TripList->getList(),'msg'=>$TripList);
        } catch (Exception $e) {
            throw new Exception('查询发言异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }

    private function upDateCommentSupport($TravelTripComment,$isSupport)
    {
        $Comment=$this->arrayCastObject($TravelTripComment,TravelTripComment::class);
        $supportCount=$Comment->supportCount;
        $opposeCount=$Comment->opposeCount;
        if($isSupport==1){
            $supportCount++;
        }else{
            $opposeCount++;
        }
        $Comment->supportCount=$supportCount;
        $Comment->opposeCount=$opposeCount;
       return $Comment;
    }

}