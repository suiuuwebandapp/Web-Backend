<?php
namespace common\models;
use common\entity\CircleArticle;
use common\entity\CircleSort;
use common\entity\RecommendList;
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

    /**
     * 查找推荐主题文章
     * @return array
     * @throws \yii\db\Exception
     */
    public function getRecommendCircleArticle($page)
    {
        $sql=sprintf("
            SELECT a.nickname,a.headImg,b.aTitle,b.aImg,d.cName,b.articleId FROM circle_article b
            LEFT JOIN user_base a ON a.userSign=b.aCreateUserSign
            LEFT JOIN recommend_list c ON c.relativeId = b.articleId
            LEFT JOIN sys_circle_sort d  ON d.cId=b.cId
            WHERE c.relativeType=:relativeType AND a.status=:status AND b.aStatus=:aStatus AND c.status=:rStatus
        ");
        $sql.=$page;
        $command=$this->getConnection()->createCommand($sql);
        $command->bindValue(":relativeType", RecommendList::TYPE_FOR_CIRCLE_ARTICLE, PDO::PARAM_INT);
        $command->bindValue(":status", UserBase::USER_STATUS_NORMAL, PDO::PARAM_INT);
        $command->bindValue(":aStatus", CircleArticle::ARTICLE_STATUS_NORMAL, PDO::PARAM_INT);
        $command->bindValue(":rStatus", RecommendList::RECOMMEND_STATUS_NORMAL, PDO::PARAM_INT);

        return $command->queryAll();
    }
    /**
     * 查找推荐用户
     * @return array
     * @throws \yii\db\Exception
     */
    public function getRecommendUser($page)
    {
        $sql=sprintf("
            SELECT a.userSign,a.headImg,a.nickname,r.rImg,COUNT(b.userSign) as numb  FROM user_base a
            LEFT JOIN recommend_list r ON r.relativeId=a.userId
            LEFT JOIN user_attention b ON a.userId = b.relativeId
            WHERE r.relativeType=:relativeType AND a.`status`=:userStatus AND r.`status`=:rStatus AND b.relativeType=:bType AND b.`status`=:bStatus
            GROUP BY a.userSign
        ");
        $sql.=$page;
        $command=$this->getConnection()->createCommand($sql);
        $command->bindValue(":relativeType", RecommendList::TYPE_FOR_USER, PDO::PARAM_INT);
        $command->bindValue(":rStatus", RecommendList::RECOMMEND_STATUS_NORMAL, PDO::PARAM_INT);
        $command->bindValue(":userStatus", UserBase::USER_STATUS_NORMAL, PDO::PARAM_INT);
        $command->bindValue(":bType", UserAttention::TYPE_FOR_USER, PDO::PARAM_INT);
        $command->bindValue(":bStatus", UserAttention::ATTENTION_STATUS_NORMAL, PDO::PARAM_INT);

        return $command->queryAll();
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

        $sql=sprintf("
           SELECT * FROM (   SELECT b.aTitle,b.articleId,b.aImg,s.cpic,s.cName,b.cId FROM (SELECT * FROM  user_attention l WHERE l.userSign=:userSign AND l.status=1 AND l.relativeType=4) c
LEFT JOIN (SELECT a.aImg,a.cId,a.aTitle,a.articleId,a.aStatus,a.aCreateUserSign FROM circle_article a
LEFT JOIN user_base f ON f.userSign = a.aCreateUserSign WHERE f.status=1 AND a.aStatus=1 AND a.cId<>0  ORDER BY articleId DESC ) b
ON c.relativeId=b.cId
LEFT JOIN sys_circle_sort s ON s.cId= c.relativeId
WHERE b.cId<>0 AND s.cType=:cType
GROUP BY b.cId ORDER BY b.articleId DESC  )as ss
        ");
        $sql.=$page;
        $command=$this->getConnection()->createCommand($sql);
        $command->bindValue(":cType", CircleSort::CIRCLE_TYPE_THEME, PDO::PARAM_INT);
        $command->bindParam(':userSign',$userSign,PDO::PARAM_STR);


        return $command->queryAll();
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

        $sql=sprintf("
         SELECT * FROM (   SELECT b.aTitle,b.articleId,b.aImg,s.cpic,s.cName,b.cAddrId as cId FROM (SELECT * FROM  user_attention l WHERE l.userSign=:userSign AND l.status=1 AND l.relativeType=4) c
LEFT JOIN (SELECT a.aImg,a.cAddrId,a.aTitle,a.articleId,a.aStatus,a.aCreateUserSign FROM circle_article a
LEFT JOIN user_base f ON f.userSign = a.aCreateUserSign WHERE f.status=1 AND a.aStatus=1 AND a.cAddrId<>0  ORDER BY articleId DESC ) b
ON c.relativeId=b.cAddrId
LEFT JOIN sys_circle_sort s ON s.cId= c.relativeId
WHERE b.cAddrId<>0 AND s.cType=:cType
GROUP BY b.cAddrId ORDER BY b.articleId DESC )as ss
        ");
        $sql.=$page;
        $command=$this->getConnection()->createCommand($sql);
        $command->bindValue(":cType", CircleSort::CIRCLE_TYPE_PLACE, PDO::PARAM_INT);
        $command->bindParam(':userSign',$userSign,PDO::PARAM_STR);

        return $command->queryAll();
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

        $sql=sprintf("
         SELECT * FROM ( SELECT * FROM (
SELECT a.aImg,a.aTitle,a.articleId,a.aStatus,f.headImg,f.nickname,f.userSign,a.aContent FROM circle_article a
 LEFT JOIN user_base f ON f.userSign = a.aCreateUserSign
LEFT JOIN user_attention e ON e.relativeId=f.userId
WHERE f.status=1 AND a.aStatus=1 AND e.userSign=:userSign AND e.status=1  AND e.relativeType=:relativeType
ORDER BY articleId DESC
 )
b GROUP BY b.userSign )as ss
        ");
        $sql.=$page;
        $command=$this->getConnection()->createCommand($sql);
        $command->bindValue(":relativeType", UserAttention::TYPE_FOR_USER, PDO::PARAM_INT);
        $command->bindParam(':userSign',$userSign,PDO::PARAM_STR);
        return $command->queryAll();
    }

}