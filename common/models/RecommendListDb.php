<?php
namespace common\models;
use common\entity\CircleArticle;
use common\entity\RecommendList;
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
     * 查找推荐圈子文章
     * @return array
     * @throws \yii\db\Exception
     */
    public function getRecommendCircleArticle()
    {
        $sql=sprintf("
            SELECT a.nickname,a.headImg,b.aTitle,b.aImg,d.cName,b.articleId FROM circle_article b
            LEFT JOIN user_base a ON a.userSign=b.aCreateUserSign
            LEFT JOIN recommend_list c ON c.relativeId = b.articleId
            LEFT JOIN sys_circle_sort d  ON d.cId=b.cId
            WHERE c.relativeType=:relativeType AND a.status=:status AND b.aStatus=:aStatus AND c.status=:rStatus;
        ");

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
    public function getRecommendUser()
    {
        $sql=sprintf("
            SELECT a.userSign,a.headImg,a.nickname FROM user_base a
            LEFT JOIN recommend_list r ON r.relativeId=a.userId
            WHERE r.relativeType=:relativeType AND a.`status`=:userStatus AND r.`status`=:rStatus;
        ");

        $command=$this->getConnection()->createCommand($sql);
        $command->bindValue(":relativeType", RecommendList::TYPE_FOR_USER, PDO::PARAM_INT);
        $command->bindValue(":rStatus", RecommendList::RECOMMEND_STATUS_NORMAL, PDO::PARAM_INT);
        $command->bindValue(":userStatus", UserBase::USER_STATUS_NORMAL, PDO::PARAM_INT);
        return $command->queryAll();
    }



}