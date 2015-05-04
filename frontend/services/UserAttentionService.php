<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/4/26
 * Time: 下午3:09
 */
namespace frontend\services;

use common\components\Code;
use common\components\Common;
use common\entity\UserAttention;
use common\entity\UserBase;
use common\entity\UserMessageRemind;
use common\models\BaseDb;
use common\models\RecommendListDb;
use common\models\UserAttentionDb;
use common\models\UserMessageRemindDb;
use frontend\models\UserBaseDb;
use yii\base\Exception;

class UserAttentionService extends BaseDb
{

    private $AttentionDb;
    private $userBaseDb;
    private $recommendDb;
    private $remindDb;
    public function __construct()
    {

    }

    /**
     * 添加圈子关注
     * @param $circleId,
     *  @param $userSign
     * @throws Exception
     */
    public function CreateAttentionToCircle($circleId,$userSign)
    {

        try {
            $conn = $this->getConnection();
            $this->AttentionDb = new UserAttentionDb($conn);
            $attention =new UserAttention();
            $attention->relativeType=UserAttention::TYPE_FOR_CIRCLE;
            $attention->relativeId=$circleId;
            $attention->userSign = $userSign;
            $result = $this->AttentionDb->getAttentionResult($attention);
            if(empty($result)||$result==false)
            {
                $this->AttentionDb ->addUserAttention($circleId,UserAttention::TYPE_FOR_CIRCLE,$userSign);
            }else{
                echo json_encode(Code::statusDataReturn(Code::FAIL,'已经关注无需继续关注'));
            }

        } catch (Exception $e) {
            throw new Exception('添加圈子关注异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }
    /**
     * 添加用户关注
     * @param $userSign,//关注谁
     *  @param $cUserSign //谁关注的
     * @throws Exception
     */
    public function CreateAttentionToUser($userSign,$cUserSign)
    {

        try {
            $conn = $this->getConnection();
            $this->AttentionDb = new UserAttentionDb($conn);
            $this->userBaseDb =new UserBaseDb($conn);
            $this->remindDb =new UserMessageRemindDb($conn);
            $userInfo = $this->userBaseDb->findByUserSign($userSign);
            $rId =isset($userInfo['userId'])?$userInfo['userId']:0;
            $attention =new UserAttention();
            $attention->relativeType=UserAttention::TYPE_FOR_USER;
            $attention->relativeId=$rId;
            $attention->userSign = $cUserSign;
            $result = $this->AttentionDb->getAttentionResult($attention);
            if(empty($result)||$result==false)
            {
                $rstId = $this->AttentionDb ->addUserAttention($rId,UserAttention::TYPE_FOR_USER,$userSign);
                $this->remindDb->addUserMessageRemind($rstId,UserMessageRemind::TYPE_ATTENTION,$cUserSign,$userSign);

            }else{
                echo json_encode(Code::statusDataReturn(Code::FAIL,'已经关注无需继续关注'));
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
                $this->AttentionDb ->addUserAttention($articleId,UserAttention::TYPE_COLLECT_FOR_ARTICLE,$userSign);
            }else{
                echo json_encode(Code::statusDataReturn(Code::FAIL,'已经收藏无需继续收藏'));

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
                $this->AttentionDb ->addUserAttention($travelId,UserAttention::TYPE_COLLECT_FOR_ARTICLE,$userSign);
            }else{
                echo json_encode(Code::statusDataReturn(Code::FAIL,'已经收藏无需继续收藏'));

            }
        } catch (Exception $e) {
            throw new Exception('添加随游收藏异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }

    /**
     * 取消关注收藏
     * @param $rId 相对id
     * @param $userSign 用户
     * @throws Exception
     */
    public function deleteAttention($rId,$userSign)
    {
        try {
            $conn = $this->getConnection();
            $this->AttentionDb = new UserAttentionDb($conn);
            $this->AttentionDb->deleteUserAttention($rId, $userSign);
        } catch (Exception $e) {
            throw new Exception('收藏关注操作异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }

    /**得到关注的圈子
     * @param $userSign
     * @param $pageNumb
     * @return array
     * @throws Exception
     */
    public function getUserAttentionCircle($userSign,$pageNumb)
    {
        try {
            $conn = $this->getConnection();
            $this->AttentionDb = new UserAttentionDb($conn);
            $page = Common::PageResult($pageNumb);
            return $this->AttentionDb->getAttentionCircle($userSign,$page);
        } catch (Exception $e) {
            throw new Exception('获取关注圈子异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }
    /**得到关注的用户
     * @param $userSign
     * @param $pageNumb
     * @return array
     * @throws Exception
     */
    public function getUserAttentionUser($userSign,$pageNumb)
    {
        try {
            $conn = $this->getConnection();
            $this->AttentionDb = new UserAttentionDb($conn);
            $page = Common::PageResult($pageNumb);
            return $this->AttentionDb->getAttentionUser($userSign,$page);
        } catch (Exception $e) {
            throw new Exception('获取关注用户异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }
    /**得到收藏的随游
     * @param $userSign
     * @param $pageNumb
     * @return array
     * @throws Exception
     */
    public function getUserCollectionTravel($userSign,$pageNumb)
    {
        try {
            $conn = $this->getConnection();
            $this->AttentionDb = new UserAttentionDb($conn);
            $page = Common::PageResult($pageNumb);
            return $this->AttentionDb->getCollectTravelList($userSign,$page);
        } catch (Exception $e) {
            throw new Exception('获取收藏的随游异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }
    /**得到收藏的圈子文章
     * @param $userSign
     * @param $pageNumb
     * @return array
     * @throws Exception
     */
    public function getUserCollectionArticle($userSign,$pageNumb)
    {
        try {
            $conn = $this->getConnection();
            $this->AttentionDb = new UserAttentionDb($conn);
            $page = Common::PageResult($pageNumb);
            return $this->AttentionDb->getCollectArticleList($userSign,$page);
        } catch (Exception $e) {
            throw new Exception('获取收藏的圈子文章异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }

    /**
     * 得到推荐用户
     * @param $pageNumb
     * @return array
     * @throws Exception
     */
    public function getRecommendUser($pageNumb)
    {
        try {
            $conn = $this->getConnection();
            $this->recommendDb = new RecommendListDb($conn);
            $page = Common::PageResult($pageNumb);
            return $this->recommendDb->getRecommendUser($page);
        } catch (Exception $e) {
            throw new Exception('获取推荐用户异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }
    /**
     * 得到推荐随游
     * @param $pageNumb
     * @return array
     * @throws Exception
     */
    public function getRecommendTravel($pageNumb)
    {
        try {
            $conn = $this->getConnection();
            $this->recommendDb = new RecommendListDb($conn);
            $page = Common::PageResult($pageNumb);
            return $this->recommendDb->getRecommendTravel($page);
        } catch (Exception $e) {
            throw new Exception('获取推荐随游异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }
    /**
     * 得到关注圈子动态
     * @param $userSign
     * @param $pageNumb
     * @return array
     * @throws Exception
     */
    public function getAttentionCircleDynamic($userSign,$pageNumb)
    {
        try {
            $rstArr=array();
            $conn = $this->getConnection();
            $this->recommendDb = new RecommendListDb($conn);
            $page = Common::PageResult($pageNumb,6);//返回第几页 ， 返回几个数据
            $rst1 = $this->recommendDb->getAttentionCircleDynamicTheme($userSign,$page);
            $rst2 = $this->recommendDb->getAttentionCircleDynamicAddr($userSign,$page);

            if(empty($rst1)&&empty($rst2))
            {
                return $rstArr;
            }else
            {

                $arr = array_merge($rst1,$rst2);
                if(count($arr)>6)
                {
                    usort($arr, function($a, $b){
                        if ($a['articleId'] < $b['articleId'])
                            return 1;
                        else if ($a['articleId'] == $b['articleId'])
                            return 0;
                        else return -1;
                    });
                }
                return array_slice($arr,0,6);
            }
        } catch (Exception $e) {
            throw new Exception('获取圈子动态异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }

    /**
     * 得到关注用户动态
     * @param $userSign
     * @param $pageNumb
     * @param $count
     * @return array
     * @throws Exception
     */
    public function getAttentionUserDynamic($userSign,$pageNumb,$count=null)
    {
        try {

            $conn = $this->getConnection();
            $this->recommendDb = new RecommendListDb($conn);
            if(empty($count))
            {
                $page = Common::PageResult($pageNumb);//返回第几页 ， 返回几个数据
            }else
            {
                $page = Common::PageResult($pageNumb,$count);//返回第几页 ， 返回几个数据
            }

            return $this->recommendDb->getAttentionUserDynamic($userSign,$page);

        } catch (Exception $e) {
            throw new Exception('获取用户动态异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }

    /**
     * 得到粉丝
     * @param $userSign
     * @param $pageNumb
     * @return array
     * @throws Exception
     */
    public function getUserFans($userSign,$pageNumb)
    {
        try {
            $conn = $this->getConnection();
            $this->AttentionDb = new UserAttentionDb($conn);
            $this->userBaseDb =new UserBaseDb($conn);
            $page = Common::PageResult($pageNumb);

            $rst = $this->userBaseDb->findByUserSign($userSign);

            $userId=0;
            if(empty($rst)||$rst==false)
            {
                echo json_encode(Code::statusDataReturn(Code::FAIL,'未知的用户'));
            }else{
                $userId = $rst['userId'];

                return $this->AttentionDb->getAttentionFans($userId,$page);
            }
        } catch (Exception $e) {
            throw new Exception('获取用户粉丝异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }

    public function getMessageRemind($userSign,$pageNumb,$type)
    {
        try {
            $page = Common::PageResult($pageNumb);
            $conn = $this->getConnection();
            $this->remindDb=new UserMessageRemindDb($conn);
            return $this->remindDb->getAttentionCircleArticle($userSign,$page,$type);
        } catch (Exception $e) {
            throw new Exception('获取用户消息异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }


    public function deleteUserMessageRemind($rid,$userSign)
    {
        try {
            $conn = $this->getConnection();
            $this->remindDb=new UserMessageRemindDb($conn);

            return $this->remindDb->deleteUserMessageRemind($rid,$userSign);
        } catch (Exception $e) {
            throw new Exception('删除用户消息异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }

}