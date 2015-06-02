<?php
namespace common\models;
use common\entity\CircleArticle;
use common\entity\CircleSort;
use common\entity\RecommendList;
use common\entity\TravelTrip;
use common\entity\UserAttention;
use common\entity\UserBase;
use yii\db\mssql\PDO;

/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/4/24
 * Time: 下午5:52
 */
class RecommendListDb extends ProxyDb
{


    public function addRecommend(RecommendList $recommend)
    {
        $sql = sprintf("
            INSERT INTO recommend_list
            (
             relativeId,relativeType,status,rImg
            )
            VALUES
            (
            :relativeId,:relativeType,:status,:rImg
            )
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":relativeId", $recommend->relativeId, PDO::PARAM_INT);
        $command->bindParam(":relativeType", $recommend->relativeType, PDO::PARAM_INT);
        $command->bindValue(":status",RecommendList::RECOMMEND_STATUS_NORMAL, PDO::PARAM_INT);
        $command->bindParam(":rImg", $recommend->rImg, PDO::PARAM_STR);
        return $command->execute();
    }


    public function getList($page,$type)
    {
        $sql=sprintf("
        FROM recommend_list a
            WHERE 1=1
        ");
        if(!empty($type))
        {
            $sql.=" AND relativeType = :relativeType ";
            $this->setParam("relativeType",$type);
        }
        $this->setSelectInfo('a.*');
        $this->setSql($sql);
        return $this->find($page);
    }

    public function delete($id)
    {
        $sql = sprintf("
            DELETE  FROM recommend_list WHERE recommendId =:recommendId;
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":recommendId", $id, PDO::PARAM_INT);
        return $command->execute();
    }

    public function change($id,$status)
    {
        $sql = sprintf("
           UPDATE recommend_list set `status`=:status WHERE recommendId =:recommendId;
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":status", $status, PDO::PARAM_INT);
        $command->bindParam(":recommendId", $id, PDO::PARAM_INT);
        return $command->execute();
    }

    public function editRecommend(RecommendList $recommend)
    {
        $sql = sprintf("
           UPDATE recommend_list set relativeType=:relativeType,rImg=:rImg,relativeId=:relativeId  WHERE recommendId =:recommendId;
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":relativeId",$recommend->relativeId , PDO::PARAM_INT);
        $command->bindParam(":rImg",$recommend->rImg , PDO::PARAM_STR);
        $command->bindParam(":relativeType",$recommend->relativeType , PDO::PARAM_INT);
        $command->bindParam(":recommendId", $recommend->recommendId, PDO::PARAM_INT);
        return $command->execute();
    }

    public function getInfo($id)
    {
        $sql = sprintf("
            SELECT *  FROM recommend_list WHERE recommendId =:recommendId;
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":recommendId", $id, PDO::PARAM_INT);
        return $command->queryOne();
    }
    /**
     * 查找推荐主题文章  //暂时用不上 只推荐了圈子
     * @return array
     * @throws \yii\db\Exception
     */
    public function getRecommendCircleArticle($page)
    {
        $this->clearParam();
        $sql=sprintf("
        FROM circle_article b
            LEFT JOIN user_base a ON a.userSign=b.aCreateUserSign
            LEFT JOIN recommend_list c ON c.relativeId = b.articleId
            LEFT JOIN sys_circle_sort d  ON d.cId=b.cId
            WHERE c.relativeType=:relativeType AND a.status=:status AND b.aStatus=:aStatus AND c.status=:rStatus
        ");
        $this->setParam("relativeType", RecommendList::TYPE_FOR_CIRCLE_ARTICLE);
        $this->setParam("status", UserBase::USER_STATUS_NORMAL);
        $this->setParam("aStatus", CircleArticle::ARTICLE_STATUS_NORMAL);
        $this->setParam("rStatus", RecommendList::RECOMMEND_STATUS_NORMAL);
        $this->setSelectInfo('a.nickname,a.headImg,b.aTitle,b.aImg,d.cName,b.articleId');
        $this->setSql($sql);
        return $this->find($page);
    }

    /**
     * 查找推荐随游文章
     * @return array
     * @throws \yii\db\Exception
     */
    public function getRecommendTravel($page)
    {
        $this->clearParam();
        $sql=sprintf("
        FROM travel_trip a
LEFT JOIN user_publisher c ON c.userPublisherId = a.createPublisherId
LEFT JOIN user_base b ON b.userSign=c.userId
LEFT JOIN recommend_list d ON d.relativeId = a.tripId
WHERE a.`status`=:tStatus AND b.`status`=:userStatus AND d.relativeType=:relativeType AND d.`status`=:rStatus
        ");
        $this->setParam("relativeType", RecommendList::TYPE_FOR_TRAVEL);
        $this->setParam("userStatus", UserBase::USER_STATUS_NORMAL);
        $this->setParam("rStatus", RecommendList::RECOMMEND_STATUS_NORMAL);
        $this->setParam("tStatus", TravelTrip::TRAVEL_TRIP_STATUS_NORMAL);
        $this->setSelectInfo('a.tripId,a.titleImg,a.title,a.intro,a.score,a.basePrice,b.userSign,b.headImg,b.nickname');
        $this->setSql($sql);
        return $this->find($page);
    }
    /**
     * 查找推荐用户
     * @return array
     * @throws \yii\db\Exception
     */
    public function getRecommendUser($page)
    {
        $this->clearParam();
        $sql=sprintf("
        FROM user_base a
            LEFT JOIN recommend_list r ON r.relativeId=a.userId
            LEFT JOIN user_attention b ON a.userId = b.relativeId
            WHERE r.relativeType=:relativeType AND a.`status`=:userStatus AND r.`status`=:rStatus AND b.relativeType=:bType AND b.`status`=:bStatus
            GROUP BY a.userSign
        ");
        $this->setParam("relativeType", RecommendList::TYPE_FOR_USER);
        $this->setParam("rStatus", RecommendList::RECOMMEND_STATUS_NORMAL);
        $this->setParam("userStatus", UserBase::USER_STATUS_NORMAL);
        $this->setParam("bType", UserAttention::TYPE_FOR_USER);
        $this->setParam("bStatus", UserAttention::ATTENTION_STATUS_NORMAL);
        $this->setSelectInfo('a.userSign,a.headImg,a.nickname,r.rImg,COUNT(b.userSign) as numb');
        $this->setSql($sql);
        return $this->find($page);

    }

    /**
     * 查找推荐圈子
     *  @param $page
     * @return array
     * @throws \yii\db\Exception
     */
    public function getRecommendCircle($page)
    {
        $this->clearParam();
        $sql=sprintf("
        FROM recommend_list a LEFT JOIN sys_circle_sort b ON a.relativeId=b.cId
WHERE a.relativeType=:relativeType AND a.`status`=:rStatus
        ");
        $this->setParam("relativeType", RecommendList::TYPE_FOR_CIRCLE);
        $this->setParam("rStatus", RecommendList::RECOMMEND_STATUS_NORMAL);
        $this->setSelectInfo('b.cId,b.cName,b.cType,b.cpic');
        $this->setSql($sql);
        return $this->find($page);
    }


    /**
     * 查找圈子动态 主题
     * @param $userSign
     *  @param $page
     * @return array
     * @throws \yii\db\Exception
     */
    public function getAttentionCircleDynamicTheme($userSign,$page)
    {
        $this->clearParam();
        $sql=sprintf("
        FROM (   SELECT b.aTitle,b.articleId,b.aImg,s.cpic,s.cName,b.cId FROM (SELECT * FROM  user_attention l WHERE l.userSign=:userSign AND l.status=1 AND l.relativeType=4) c
LEFT JOIN (SELECT a.aImg,a.cId,a.aTitle,a.articleId,a.aStatus,a.aCreateUserSign FROM circle_article a
LEFT JOIN user_base f ON f.userSign = a.aCreateUserSign WHERE f.status=1 AND a.aStatus=1 AND a.cId<>0  ORDER BY articleId DESC ) b
ON c.relativeId=b.cId
LEFT JOIN sys_circle_sort s ON s.cId= c.relativeId
WHERE b.cId<>0 AND s.cType=:cType
GROUP BY b.cId ORDER BY b.articleId DESC  )as ss
        ");
        $this->setParam("cType", CircleSort::CIRCLE_TYPE_THEME);
        $this->setParam('userSign',$userSign);
        $this->setSql($sql);
        return $this->find($page);
    }

    /**
     * 查找圈子动态 地点
     * @param $userSign
     *  @param $page
     * @return array
     * @throws \yii\db\Exception
     */
    public function getAttentionCircleDynamicAddr($userSign,$page)
    {
        $this->clearParam();
        $sql=sprintf("
       FROM (   SELECT b.aTitle,b.articleId,b.aImg,s.cpic,s.cName,b.cAddrId as cId FROM (SELECT * FROM  user_attention l WHERE l.userSign=:userSign AND l.status=1 AND l.relativeType=4) c
LEFT JOIN (SELECT a.aImg,a.cAddrId,a.aTitle,a.articleId,a.aStatus,a.aCreateUserSign FROM circle_article a
LEFT JOIN user_base f ON f.userSign = a.aCreateUserSign WHERE f.status=1 AND a.aStatus=1 AND a.cAddrId<>0  ORDER BY articleId DESC ) b
ON c.relativeId=b.cAddrId
LEFT JOIN sys_circle_sort s ON s.cId= c.relativeId
WHERE b.cAddrId<>0 AND s.cType=:cType
GROUP BY b.cAddrId ORDER BY b.articleId DESC )as ss
        ");
        $this->setParam("cType", CircleSort::CIRCLE_TYPE_PLACE);
        $this->setParam('userSign',$userSign);
        $this->setSql($sql);
        return $this->find($page);
    }

    /**
     * 查找用户动态
     * @param $userSign
     *  @param $page
     * @return array
     * @throws \yii\db\Exception
     */
    public function getAttentionUserDynamic($userSign,$page)
    {
        $this->clearParam();
        $sql=sprintf("
        FROM ( SELECT * FROM (
SELECT a.aImg,a.aTitle,a.articleId,a.aStatus,f.headImg,f.nickname,f.userSign,a.aContent FROM circle_article a
 LEFT JOIN user_base f ON f.userSign = a.aCreateUserSign
LEFT JOIN user_attention e ON e.relativeId=f.userId
WHERE f.status=1 AND a.aStatus=1 AND e.userSign=:userSign AND e.status=1  AND e.relativeType=:relativeType
ORDER BY articleId DESC
 )
b GROUP BY b.userSign )as ss
        ");
        $this->setParam("relativeType", UserAttention::TYPE_FOR_USER);
        $this->setParam('userSign',$userSign);
        $this->setSql($sql);
        return $this->find($page);
    }





}