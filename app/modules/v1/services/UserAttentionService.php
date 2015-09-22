<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/4/26
 * Time: 下午3:09
 */
namespace app\modules\v1\services;


use common\components\Code;
use app\modules\v1\entity\UserAttention;
use app\modules\v1\entity\UserMessageRemind;
use common\models\BaseDb;
use app\modules\v1\models\RecommendListDb;
use app\modules\v1\models\TravelTripDb;
use app\modules\v1\models\UserAttentionDb;
use app\modules\v1\models\UserBaseDb;
use yii\base\Exception;

class UserAttentionService extends BaseDb
{

    private $AttentionDb;
    private $userBaseDb;
    private $recommendDb;
    public function __construct()
    {

    }


    /**
     * 添加用户关注
     * @param $userSign,//关注谁
     *  @param $cUserSign //谁关注的
     * @throws Exception
     *  @return int
     */
    public function CreateAttentionToUser($userSign,$cUserSign)
    {

        try {
            $conn = $this->getConnection();
            $this->AttentionDb = new UserAttentionDb($conn);
            $this->userBaseDb =new UserBaseDb($conn);
            $userInfo = $this->userBaseDb->findByUserSign($userSign);
            $rId =isset($userInfo['userId'])?$userInfo['userId']:0;
            $attention =new UserAttention();
            $attention->relativeType=UserAttention::TYPE_FOR_USER;
            $attention->relativeId=$rId;
            $attention->userSign = $cUserSign;
            $result = $this->AttentionDb->getAttentionResult($attention);
            if(empty($result)||$result==false)
            {
                $rstId = $this->AttentionDb ->addUserAttention($rId,UserAttention::TYPE_FOR_USER,$cUserSign);
                $userRemind = new UserMessageRemindService();
                $content="###";
                $url="###";
                $userRemind->addMessageRemind($rstId,UserMessageRemind::TYPE_ATTENTION,$cUserSign,$userSign,UserMessageRemind::R_TYPE_USER,$content,$url);
                return $rstId;
            }else{
                echo json_encode(Code::statusDataReturn(Code::FAIL,'已经关注无需继续关注'));
                exit;
            }
        } catch (Exception $e) {
            throw new Exception('添加用户关注异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }
    /**
     * 添加文章收藏
     * @param $articleId //圈子文章id
     * @param $userSign,//收藏用户
     * @throws Exception
     *  @return int
     */
    public function CreateCollectionToArticle($articleId,$userSign)
    {

        try {
            $conn = $this->getConnection();
            $this->AttentionDb = new UserAttentionDb($conn);
            $attention =new UserAttention();
            $attention->relativeType=UserAttention::TYPE_COLLECT_FOR_ARTICLE;
            $attention->relativeId=$articleId;
            $attention->userSign = $userSign;
            $result = $this->AttentionDb->getAttentionResult($attention);
            if(empty($result)||$result==false)
            {
               return $this->AttentionDb ->addUserAttention($articleId,UserAttention::TYPE_COLLECT_FOR_ARTICLE,$userSign);
            }else{
                echo json_encode(Code::statusDataReturn(Code::FAIL,'已经收藏无需继续收藏'));
                exit;
            }
        } catch (Exception $e) {
            throw new Exception('添加圈子文章收藏异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }

    /**
     * 添加随游收藏
     * @param $travelId //随游id
     * @param $userSign,//收藏用户
     * @throws Exception
     * @return int
     */
    public function CreateCollectionToTravel($travelId,$userSign)
    {

        try {
            $conn = $this->getConnection();
            $this->AttentionDb = new UserAttentionDb($conn);
            $attention =new UserAttention();
            $attention->relativeType=UserAttention::TYPE_COLLECT_FOR_TRAVEL;
            $attention->relativeId=$travelId;
            $attention->userSign = $userSign;
            $result = $this->AttentionDb->getAttentionResult($attention);
            if(empty($result)||$result==false)
            {
                $tripDb = new TravelTripDb($conn);
                $tripDb->addCollectCount($travelId);
               return $this->AttentionDb ->addUserAttention($travelId,UserAttention::TYPE_COLLECT_FOR_TRAVEL,$userSign);
            }else{
                echo json_encode(Code::statusDataReturn(Code::FAIL,'已经收藏无需继续收藏'));
                exit;
            }
        } catch (Exception $e) {
            throw new Exception('添加随游收藏异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }

    /**
     * 添加圈子点赞
     * @param $articleId //文章id
     * @param $userSign,//收藏用户
     * @throws Exception
     * @return int
     */
    public function CreatePraiseToCircleArticle($articleId,$userSign)
    {

        try {
            $conn = $this->getConnection();
            $this->AttentionDb = new UserAttentionDb($conn);
            $attention =new UserAttention();
            $attention->relativeType=UserAttention::TYPE_PRAISE_FOR_CIRCLE_ARTICLE;
            $attention->relativeId=$articleId;
            $attention->userSign = $userSign;
            $result = $this->AttentionDb->getAttentionResult($attention);
            if(empty($result)||$result==false)
            {
                return $this->AttentionDb ->addUserAttention($articleId,UserAttention::TYPE_PRAISE_FOR_CIRCLE_ARTICLE,$userSign);
            }else{
                echo json_encode(Code::statusDataReturn(Code::FAIL,'已经点赞无需继续点赞'));
                exit;
            }
        } catch (Exception $e) {
            throw new Exception('添加圈子点赞异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }
    /**
     * 添加随游点赞
     * @param $travelId //随游id
     * @param $userSign,//收藏用户
     * @throws Exception
     * @return int
     */
    public function CreatePraiseToTravel($travelId,$userSign)
    {

        try {
            $conn = $this->getConnection();
            $this->AttentionDb = new UserAttentionDb($conn);
            $attention =new UserAttention();
            $attention->relativeType=UserAttention::TYPE_PRAISE_FOR_TRAVEL;
            $attention->relativeId=$travelId;
            $attention->userSign = $userSign;
            $result = $this->AttentionDb->getAttentionResult($attention);
            if(empty($result)||$result==false)
            {
                return $this->AttentionDb ->addUserAttention($travelId,UserAttention::TYPE_PRAISE_FOR_TRAVEL,$userSign);
            }else{
                echo json_encode(Code::statusDataReturn(Code::FAIL,'已经点赞无需继续点赞'));
                exit;
            }
        } catch (Exception $e) {
            throw new Exception('添加随游点赞异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }
    /**取消关注收藏
     * @param $rId 相对id
     * @param $userSign 用户
     * @return int
     * @throws Exception
     */
    public function deleteAttention($rId,$userSign)
    {
        try {
            $conn = $this->getConnection();
            $this->AttentionDb = new UserAttentionDb($conn);
            $rst=$this->AttentionDb->deleteUserAttention($rId, $userSign);
            if($rst==1){
                $rest=$this->AttentionDb->getAttentionResultById($rId);
                if($rest==false||empty($rest))
                {
                    throw new Exception('取消收藏关注操作异常',Code::FAIL);
                }
                switch($rest['relativeType'])
                {
                    case UserAttention::TYPE_COLLECT_FOR_TRAVEL:

                        $tripDb = new TravelTripDb($conn);
                        $tripDb->removeCollectCount($rest['relativeId']);
                        break;
                    case UserAttention::TYPE_FOR_TRAVEL_PICTURE:
                        $tpSer = new TravelPictureService();
                        $tpSer->updateTravelPictureAttentionCount($rId,false);
                        break;
                    case UserAttention::TYPE_FOR_QA:
                        $qaSer = new QaCommunityService();
                        $qaSer->updateQaAttentionCount($rId,false);
                        break;

                }

            }
            return $rst;
        } catch (Exception $e) {
            throw new Exception('取消收藏关注操作异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }
    /**得到关注的用户
     * @param $userSign
     * @param $page
     * @return array
     * @throws Exception
     */
    public function getUserAttentionUser($userSign,$page)
    {
        try {
            $conn = $this->getConnection();
            $this->AttentionDb = new UserAttentionDb($conn);
            $data = $this->AttentionDb->getAttentionUser($userSign,$page);
            return array('data'=>$data->getList(),'msg'=>$data);
        } catch (Exception $e) {
            throw new Exception('获取关注用户异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }
    /**得到收藏的随游
     * @param $userSign
     * @param $page
     * @return array
     * @throws Exception
     */
    public function getUserCollectionTravel($userSign,$page)
    {
        try {
            $conn = $this->getConnection();
            $this->AttentionDb = new UserAttentionDb($conn);
            $data = $this->AttentionDb->getCollectTravelList($userSign,$page);
            return array('data'=>$data->getList(),'msg'=>$data);
        } catch (Exception $e) {
            throw new Exception('获取收藏的随游异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }

    /**得到关注旅图
     * @param $userSign
     * @param $page
     * @return array
     * @throws Exception
     */
    public function getUserAttentionTp($userSign,$page)
    {
        try {
            $conn = $this->getConnection();
            $this->AttentionDb = new UserAttentionDb($conn);
            $data = $this->AttentionDb->getUserAttentionTp($userSign,$page);
            return array('data'=>$data->getList(),'msg'=>$data);
        } catch (Exception $e) {
            throw new Exception('获取关注旅图异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }

    /**得到关注问答社区
     * @param $userSign
     * @param $page
     * @return array
     * @throws Exception
     */
    public function getUserAttentionQa($userSign,$page)
    {
        try {
            $conn = $this->getConnection();
            $this->AttentionDb = new UserAttentionDb($conn);
            $data = $this->AttentionDb->getUserAttentionQa($userSign,$page);
            return array('data'=>$data->getList(),'msg'=>$data);
        } catch (Exception $e) {
            throw new Exception('获取关注问答社区异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }
    /**得到收藏的圈子文章
     * @param $userSign
     * @param $page
     * @return array
     * @throws Exception
     */
    public function getUserCollectionArticle($userSign,$page)
    {
        try {
            $conn = $this->getConnection();
            $this->AttentionDb = new UserAttentionDb($conn);
            $data = $this->AttentionDb->getCollectArticleList($userSign,$page);
            return array('data'=>$data->getList(),'msg'=>$data);
        } catch (Exception $e) {
            throw new Exception('获取收藏的圈子文章异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }

    /**
     * 得到推荐用户
     * @param $page
     * @return array
     * @throws Exception
     */
    public function getRecommendUser($page)
    {
        try {
            $conn = $this->getConnection();
            $this->recommendDb = new RecommendListDb($conn);
            $data= $this->recommendDb->getRecommendUser($page);
            return array('data'=>$data->getList(),'msg'=>$data);
        } catch (Exception $e) {
            throw new Exception('获取推荐用户异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }
    /**
     * 得到推荐随游
     * @param $page
     * @return array
     * @throws Exception
     */
    public function getRecommendTravel($page)
    {
        try {
            $conn = $this->getConnection();
            $this->recommendDb = new RecommendListDb($conn);
            $data= $this->recommendDb->getRecommendTravel($page);
            return array('data'=>$data->getList(),'msg'=>$data);
        } catch (Exception $e) {
            throw new Exception('获取推荐随游异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }

    /**
     * 得到关注用户动态
     * @param $userSign
     * @param $page
     * @return array
     * @throws Exception
     */
    public function getAttentionUserDynamic($userSign,$page)
    {
        try {

            $conn = $this->getConnection();
            $this->recommendDb = new RecommendListDb($conn);
            $data=$this->recommendDb->getAttentionUserDynamic($userSign,$page);
            return array('data'=>$data->getList(),'msg'=>$data);
        } catch (Exception $e) {
            throw new Exception('获取用户动态异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }

    /**
     * 得到粉丝
     * @param $userSign
     * @param $page
     * @return array
     * @throws Exception
     */
    public function getUserFans($userSign,$page)
    {
        try {
            $conn = $this->getConnection();
            $this->AttentionDb = new UserAttentionDb($conn);
            $this->userBaseDb =new UserBaseDb($conn);

            $rst = $this->userBaseDb->findByUserSign($userSign);

            $userId=0;
            if(empty($rst)||$rst==false)
            {
                echo json_encode(Code::statusDataReturn(Code::FAIL,'未知的用户'));
            }else{
                $userId = $rst['userId'];

                $data = $this->AttentionDb->getAttentionFans($userId,$page);
                return array('data'=>$data->getList(),'msg'=>$data);
            }
        } catch (Exception $e) {
            throw new Exception('获取用户粉丝异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }


    /**
     * 得到关注收藏结果
     * @param $type
     * @param $travelId
     * @param $userSign
     * @return array
     * @throws Exception
     */
    public function getAttentionResult($type,$travelId,$userSign)
    {

        try {
            $conn = $this->getConnection();
            $this->AttentionDb = new UserAttentionDb($conn);
            $attention =new UserAttention();
            $attention->relativeType=$type;
            $attention->relativeId=$travelId;
            $attention->userSign = $userSign;
            return  $this->AttentionDb->getAttentionResult($attention);

        } catch (Exception $e) {
            throw new Exception('获取关注收藏结果异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }

    public function createAttentionToQa($id,$userSign)
    {
        try {
            $conn = $this->getConnection();
            $this->AttentionDb = new UserAttentionDb($conn);
            $attention =new UserAttention();
            $attention->relativeType=UserAttention::TYPE_FOR_QA;
            $attention->relativeId=$id;
            $attention->userSign = $userSign;
            $result = $this->AttentionDb->getAttentionResult($attention);

            if(empty($result)||$result==false)
            {
                $qaSer = new QaCommunityService();
                $info = $qaSer->updateQaAttentionCount($id,true);
                if(empty($info))
                {
                    echo json_encode(Code::statusDataReturn(Code::FAIL,'未知问题'));
                    exit;
                }
                $rst = $this->AttentionDb ->addUserAttention($id,UserAttention::TYPE_FOR_QA,$userSign);
                $userRemind = new UserMessageRemindService();
                $content="###";
                $url="###";
                $userRemind->addMessageRemind($rst,UserMessageRemind::TYPE_ATTENTION,$userSign,$info['qUserSign'],UserMessageRemind::R_TYPE_QUESTION_ANSWER,$content,$url);
                return $rst;
            }else{
                echo json_encode(Code::statusDataReturn(Code::FAIL,'已经关注'));
                exit;
            }
        } catch (Exception $e) {
            throw new Exception('添加关注异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }
    public function createAttentionToTp($id,$userSign)
    {
        try {
            $conn = $this->getConnection();
            $this->AttentionDb = new UserAttentionDb($conn);
            $attention =new UserAttention();
            $attention->relativeType=UserAttention::TYPE_FOR_TRAVEL_PICTURE;
            $attention->relativeId=$id;
            $attention->userSign = $userSign;
            $result = $this->AttentionDb->getAttentionResult($attention);
            if(empty($result)||$result==false)
            {
                $tpSer = new TravelPictureService();
                $info = $tpSer->updateTravelPictureAttentionCount($id,true);
                if(empty($info))
                {
                    echo json_encode(Code::statusDataReturn(Code::FAIL,'未知旅图'));
                    exit;
                }
                $rst =$this->AttentionDb ->addUserAttention($id,UserAttention::TYPE_FOR_TRAVEL_PICTURE,$userSign);
                $userRemind = new UserMessageRemindService();
                $content="###";
                $url="###";
                $userRemind->addMessageRemind($rst,UserMessageRemind::TYPE_ATTENTION,$userSign,$info['userSign'],UserMessageRemind::R_TYPE_TRAVEL_PICTURE,$content,$url);
                return $rst;
            }else{
                echo json_encode(Code::statusDataReturn(Code::FAIL,'已经关注'));
                exit;
            }
        } catch (Exception $e) {
            throw new Exception('添加关注异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }
}