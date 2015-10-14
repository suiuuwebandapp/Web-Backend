<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/9/23
 * Time: 上午10:23
 */

namespace backend\models;


use common\entity\AnswerCommunity;
use common\models\ProxyDb;
use yii\db\mssql\PDO;

class QaCommunityDb extends ProxyDb {

    public function getQaList($page,$countryId,$cityId,$tag,$search)
    {
        $sql=sprintf("
        FROM (SELECT a.qId,qTitle,qContent,qAddr,qCountryId,qCityId,qTag,qUserSign,qCreateTime,qInviteAskUser,pvNumber,attentionNumber,aNumber,c.headImg,c.nickname  FROM question_community a
        LEFT JOIN user_base c ON a.qUserSign = c.userSign) as ls WHERE 1=1
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
            $sql.=" AND (ls.qTitle like :search OR ls.qContent like :search OR ls.qId = :qid )";
            $this->setParam('search','%'.$search.'%');
            $this->setParam('qid',$search);
        }
        if(!empty($tag)){
            $sql.=" AND ls.qId IN (";
            $sql.=$tag;
            $sql.=")";
        }
        $this->setSql($sql);
        return $this->find($page);
    }

    public function deleteById($id)
    {
        $sql = sprintf("
              DELETE FROM question_community WHERE qId=:qId
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":qId", $id, PDO::PARAM_INT);
        return $command->execute();
    }

    public function deleteAnswer($id)
    {
        $sql = sprintf("
              DELETE FROM answer_community WHERE aId=:aId
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":aId", $id, PDO::PARAM_INT);
        return $command->execute();
    }

    /**得到问题详情
     * @param $id
     * @return array|bool
     */
    public function  getQuestionById($id)
    {
        $sql = sprintf("
            SELECT qId,qTitle,qContent,qAddr,qCountryId,qCityId,qTag,qUserSign,qCreateTime,qInviteAskUser,pvNumber,attentionNumber,aNumber,c.nickname,c.headImg FROM question_community a
            LEFT JOIN user_base c ON a.qUserSign = c.userSign
             WHERE qId=:qId;
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":qId", $id, PDO::PARAM_INT);
        return $command->queryOne();
    }

    public function getUserInfo($userSign)
    {
        $sql = sprintf("
            SELECT headImg,nickname,userSign FROM user_base
             WHERE userSign=:userSign AND phone  is NULL AND email  is NULL;
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":userSign", $userSign, PDO::PARAM_STR);
        return $command->queryOne();
    }

    public function getAnswerListByQid($page,$type,$id)
    {
        $sql=sprintf("
        FROM (SELECT * FROM answer_community WHERE qId=:qId) as a
        LEFT JOIN user_base b ON a.aUserSign=b.userSign
        ");
        $this->setParam('qId',$id);
        if(!empty($type))
        {
            $sql.=" AND a.type=:type";
            $this->setParam('type',$type);
        }
        $this->setSelectInfo("a.*,b.nickname");
        $this->setSql($sql);
        return $this->find($page);
    }

    public function getAnswerList($page,$search)
    {
        //
        $sql=sprintf("
        FROM (SELECT a.*,c.headImg,c.nickname FROM (SELECT * FROM answer_community LIMIT 0,100) as a
        LEFT JOIN user_base c ON a.aUserSign = c.userSign) as ls WHERE 1=1
        ");
        if(!empty($search))
        {
            $sql.=" AND (ls.aContent like :search OR ls.nickname like :search OR ls.qId=:qId)";
            $this->setParam('search','%'.$search.'%');
            $this->setParam('qId',$search);
        }
        $this->setSql($sql);
        return $this->find($page);
    }

    public function updateAnswerNumber($id,$number)
    {
        $sql = sprintf("
            UPDATE question_community a SET a.aNumber = :aNumber WHERE a.qId=:qId
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":qId", $id, PDO::PARAM_INT);
        $command->bindParam(":aNumber", $number, PDO::PARAM_INT);
        $command->execute();
    }

    public function addAnswer(AnswerCommunity $answerCommunity)
    {
        $sql = sprintf("
            INSERT INTO answer_community
            (
             qId,aContent,aUserSign,aCreateTime,type
            )
            VALUES
            (
             :qId,:aContent,:aUserSign,now(),:type
            )
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":qId", $answerCommunity->qId, PDO::PARAM_INT);
        $command->bindParam(":aContent", $answerCommunity->aContent, PDO::PARAM_STR);
        $command->bindParam(":aUserSign", $answerCommunity->aUserSign, PDO::PARAM_STR);
        $command->bindParam(":type", $answerCommunity->type, PDO::PARAM_INT);
        $command->execute();
    }
}