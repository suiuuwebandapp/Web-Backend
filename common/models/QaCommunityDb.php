<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/7/10
 * Time: 下午4:44
 */

namespace common\models;


use common\entity\AnswerCommunity;
use common\entity\QuestionCommunity;
use frontend\components\Page;
use yii\db\mssql\PDO;

class QaCommunityDb extends ProxyDb  {

    public function addQuestion(QuestionCommunity $questionCommunity)
    {
        $sql = sprintf("
            INSERT INTO question_community
            (
             qTitle,qContent,qAddr,qCountryId,qCityId,qTag,qUserSign,qCreateTime,qInviteAskUser,pvNumber
            )
            VALUES
            (
            :qTitle,:qContent,:qAddr,:qCountryId,:qCityId,:qTag,:qUserSign,now(),:qInviteAskUser,:pvNumber
            )
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":qTitle", $questionCommunity->qTitle, PDO::PARAM_STR);
        $command->bindParam(":qContent", $questionCommunity->qContent, PDO::PARAM_STR);
        $command->bindParam(":qAddr", $questionCommunity->qAddr, PDO::PARAM_STR);
        $command->bindParam(":qCountryId", $questionCommunity->qCountryId, PDO::PARAM_INT);
        $command->bindParam(":qCityId", $questionCommunity->qCityId, PDO::PARAM_INT);
        $command->bindParam(":qTag", $questionCommunity->qTag, PDO::PARAM_STR);
        $command->bindParam(":qUserSign", $questionCommunity->qUserSign, PDO::PARAM_STR);
        $command->bindParam(":qInviteAskUser", $questionCommunity->qInviteAskUser, PDO::PARAM_STR);
        $command->bindParam(":pvNumber", $questionCommunity->pvNumber, PDO::PARAM_INT);
        $command->execute();
        return $this->getConnection()->lastInsertID;
    }
    public function addAnswer(AnswerCommunity $answerCommunity)
    {
        $sql = sprintf("
            INSERT INTO answer_community
            (
             qId,aContent,aUserSign,aCreateTime
            )
            VALUES
            (
             :qId,:aContent,:aUserSign,now()
            )
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":qId", $answerCommunity->qId, PDO::PARAM_INT);
        $command->bindParam(":aContent", $answerCommunity->aContent, PDO::PARAM_STR);
        $command->bindParam(":aUserSign", $answerCommunity->aUserSign, PDO::PARAM_STR);
        $command->execute();
    }

    /**得到问题详情
     * @param $id
     * @return array|bool
     */
    public function  getQuestionById($id)
    {
        $sql = sprintf("
            SELECT qId,qTitle,qContent,qAddr,qCountryId,qCityId,qTag,qUserSign,qCreateTime,qInviteAskUser,pvNumber FROM question_community WHERE qId=:qId;
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":qId", $id, PDO::PARAM_INT);
        return $command->queryOne();
    }
    //得到问题的答案
    public function getAnswerByQid($id)
    {
        $sql = sprintf("
            SELECT * FROM answer_community WHERE qId=:qId;
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":qId", $id, PDO::PARAM_INT);
        return $command->queryAll();
    }

    public function getInviteUser($countryId,$cityId)
    {
        $sql = sprintf("
            SELECT u.nickname,u.userSign FROM (SELECT aUserSign FROM answer_community a WHERE
qId in (SELECT qId FROM question_community WHERE qCountryId=:qCountryId AND qCityId=:qCityId)
GROUP BY aUserSign ORDER BY COUNT(aUserSign) DESC) as ss LEFT JOIN user_base u ON ss.aUserSign = u.userSign
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":qCountryId", $countryId, PDO::PARAM_INT);
        $command->bindParam(":qCityId", $cityId, PDO::PARAM_INT);
        return $command->queryAll();
    }

    public function updateQuestionPv($id,$pv)
    {
        $sql = sprintf("
            UPDATE question_community a SET a.pvNumber = :pv WHERE a.qId=:qId
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":qId", $id, PDO::PARAM_INT);
        $command->bindParam(":pv", $pv, PDO::PARAM_INT);
        $command->execute();
    }

    public function getQaList($page,$countryId,$cityId,$tag,$search)
    {
        $sql=sprintf("
        FROM (SELECT a.*,COUNT(b.qId ) as Number FROM question_community a
        LEFT JOIN answer_community b ON a.qId = b.qId
        GROUP BY a.qId) as ls WHERE 1=1
        ");
        if(!empty($countryId))
        {
           $sql.=" AND ls.qCountryId = :qCountryId ";
                $this->setParam("qCountryId",$countryId);
        }
        if(!empty($cityId))
        {
            $sql.=" AND ls.qCityId = :qCityId ";
            $this->setParam("qCityId",$cityId);
        }
        if(!empty($search))
        {
            $sql.=" AND (ls.qTitle like :search OR ls.qContent like :search )";
            $this->setParam('search','%'.$search.'%');
        }
        if(!empty($tag)){
            $sql.=" AND ls.qId IN (";
            $sql.=$tag;
            $sql.=")";
        }
        $this->setSql($sql);
        return $this->find($page);
    }
}