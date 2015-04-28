<?php
namespace common\models;
use common\entity\CircleArticle;
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
            SELECT a.userSign,a.headImg,a.nickname FROM user_base a
            LEFT JOIN recommend_list r ON r.relativeId=a.userId
            WHERE r.relativeType=:relativeType AND a.`status`=:userStatus AND r.`status`=:rStatus
        ");
        $sql.=$page;
        $command=$this->getConnection()->createCommand($sql);
        $command->bindValue(":relativeType", RecommendList::TYPE_FOR_USER, PDO::PARAM_INT);
        $command->bindValue(":rStatus", RecommendList::RECOMMEND_STATUS_NORMAL, PDO::PARAM_INT);
        $command->bindValue(":userStatus", UserBase::USER_STATUS_NORMAL, PDO::PARAM_INT);
        return $command->queryAll();
    }


    /**
     * 查找圈子动态
     * @param $userSign
     *  @param $page
     * @return array
     * @throws \yii\db\Exception
     */
    public function getAttentionCircleDynamicTheme($userSign,$page)
    {
        /**
         * explain (SELECT d.cId,d.cName,d.cpic,c.aImg,c.cId,c.aTitle,c.articleId FROM
        (SELECT * FROM (SELECT a.aImg,a.cId,a.aTitle,a.articleId,a.aStatus,a.aCreateUserSign FROM circle_article a LEFT JOIN user_base f ON f.userSign = a.aCreateUserSign WHERE f.status=1 ORDER BY articleId DESC ) b GROUP BY b.cId) c
        LEFT JOIN sys_circle_sort d ON d.cId = c.cId
        LEFT JOIN user_attention e ON e.relativeId=c.cId
        WHERE e.userSign='085963dc0af031709b032725e3ef18f5' AND c.aStatus=1 AND e.status=1
        );


        explain (SELECT * FROM (SELECT c.cId,c.cName,c.cpic,d.aImg,d.aTitle,d.articleId FROM circle_article d LEFT JOIN
        (SELECT a.cId,a.cName,a.cpic FROM sys_circle_sort a LEFT JOIN user_attention b ON a.cId=b.relativeId WHERE b.userSign='085963dc0af031709b032725e3ef18f5') c ON d.cId=c.cId
        ORDER BY d.articleId DESC) e GROUP BY e.cId)
         */
        $sql=sprintf("
           SELECT d.cId,d.cName,d.cpic,c.aImg,c.cId,c.aTitle,c.articleId FROM
(SELECT * FROM (SELECT a.aImg,a.cId,a.aTitle,a.articleId,a.aStatus,a.aCreateUserSign FROM circle_article a
LEFT JOIN user_base f ON f.userSign = a.aCreateUserSign WHERE f.status=:userStatus AND a.aStatus=:aStatus  ORDER BY articleId DESC ) b GROUP BY b.cId) c
LEFT JOIN sys_circle_sort d ON d.cId = c.cId
LEFT JOIN user_attention e ON e.relativeId=c.cId
WHERE e.userSign=:userSign AND e.status=:rStatus AND e.relativeType=:relativeType
        ");
        $sql.=$page;
        $command=$this->getConnection()->createCommand($sql);
        $command->bindValue(":aStatus", CircleArticle::ARTICLE_STATUS_NORMAL, PDO::PARAM_INT);
        $command->bindValue(":relativeType", UserAttention::TYPE_FOR_CIRCLE, PDO::PARAM_INT);
        $command->bindValue(":rStatus", UserAttention::ATTENTION_STATUS_NORMAL, PDO::PARAM_INT);
        $command->bindValue(":userStatus", UserBase::USER_STATUS_NORMAL, PDO::PARAM_INT);
        $command->bindParam(':userSign',$userSign,PDO::PARAM_STR);

        return $command->queryAll();
    }

    /**
     * 查找圈子动态
     * @param $userSign
     *  @param $page
     * @return array
     * @throws \yii\db\Exception
     */
    public function getAttentionCircleDynamicAddr($userSign,$page)
    {

        $sql=sprintf("
           SELECT d.cName,d.cpic,c.aImg,c.cAddrId,c.aTitle,c.articleId FROM
(SELECT * FROM (SELECT a.aImg,a.cAddrId,a.aTitle,a.articleId,a.aStatus,a.aCreateUserSign FROM circle_article a
LEFT JOIN user_base f ON f.userSign = a.aCreateUserSign WHERE f.status=:userStatus AND a.aStatus=:aStatus  ORDER BY articleId DESC ) b GROUP BY b.cAddrId) c
LEFT JOIN sys_circle_sort d ON d.cId = c.cAddrId
LEFT JOIN user_attention e ON e.relativeId=c.cAddrId
WHERE e.userSign=:userSign AND e.status=:rStatus AND e.relativeType=:relativeType
        ");
        $sql.=$page;
        $command=$this->getConnection()->createCommand($sql);
        $command->bindValue(":aStatus", CircleArticle::ARTICLE_STATUS_NORMAL, PDO::PARAM_INT);
        $command->bindValue(":relativeType", UserAttention::TYPE_FOR_CIRCLE, PDO::PARAM_INT);
        $command->bindValue(":rStatus", UserAttention::ATTENTION_STATUS_NORMAL, PDO::PARAM_INT);
        $command->bindValue(":userStatus", UserBase::USER_STATUS_NORMAL, PDO::PARAM_INT);
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
          SELECT * FROM (
SELECT a.aImg,a.aTitle,a.articleId,a.aStatus,f.headImg,f.nickname,f.userSign FROM circle_article a
 LEFT JOIN user_base f ON f.userSign = a.aCreateUserSign
LEFT JOIN user_attention e ON e.relativeId=f.userId
WHERE f.status=1 AND a.aStatus=1 AND e.userSign=:userSign AND e.status=1  AND e.relativeType=:relativeType
ORDER BY articleId DESC
 )
b GROUP BY b.userSign
        ");
        $sql.=$page;
        $command=$this->getConnection()->createCommand($sql);
        $command->bindValue(":relativeType", UserAttention::TYPE_FOR_USER, PDO::PARAM_INT);
        $command->bindParam(':userSign',$userSign,PDO::PARAM_STR);
        return $command->queryAll();
    }

}