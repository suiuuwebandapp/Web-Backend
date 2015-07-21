<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/7/10
 * Time: 下午5:29
 */

namespace frontend\services;


use common\components\Code;
use common\entity\AnswerCommunity;
use common\entity\QuestionCommunity;
use common\entity\UserAttention;
use common\models\BaseDb;
use common\models\QaCommunityDb;
use common\models\UserAttentionDb;
use yii\base\Exception;

class QaCommunityService  extends BaseDb {

    private $qaCommunityDb;
    public function addQuestion(QuestionCommunity $questionCommunity)
    {
        $conn=$this->getConnection();
        $tran = $conn->beginTransaction();
        try{
            $this->qaCommunityDb=new QaCommunityDb($conn);
            $qId = $this->qaCommunityDb->addQuestion($questionCommunity);
            //给邀请回答的人发送消息
            $this->commit($tran);
            $tagSer = new TagListService();
            $tagSer->updateQaTagValList($questionCommunity->qTag,$qId);
        }catch (Exception $e){
            $this->rollback($tran);
            throw $e;
        }finally{
            $this->closeLink();
        }
    }
    public function addAnswer(AnswerCommunity $answerCommunity)
    {
        $conn=$this->getConnection();
        $tran = $conn->beginTransaction();
        try{
            $this->qaCommunityDb=new QaCommunityDb($conn);
            $this->qaCommunityDb->addAnswer($answerCommunity);
            //给提问人发送消息
            $this->commit($tran);
        }catch (Exception $e){
            $this->rollback($tran);
            throw $e;
        }finally{
            $this->closeLink();
        }
    }

    public function getQaInfoById($id,$userSign)
    {
        $conn=$this->getConnection();
        try{
            $qaInfo = array();
            $this->qaCommunityDb=new QaCommunityDb($conn);
            $question = $this->qaCommunityDb->getQuestionById($id);
            if(isset($question['pvNumber']))
            {
                $pv =intval($question['pvNumber'])+1;
                $this->qaCommunityDb->updateQuestionPv($id,$pv);
            }
            $answer = $this->qaCommunityDb->getAnswerByQid($id);
            $attentionDb =new UserAttentionDb($conn);
            $attentionEntity = new UserAttention();
            $attentionEntity->relativeId=$id;
            $attentionEntity->relativeType=UserAttention::TYPE_FOR_QA;
            $attentionEntity->userSign = $userSign;
            $attention = $attentionDb->getAttentionResult($attentionEntity);
            $qaInfo['attention']=$attention==false?array():$attention;
            $qaInfo['question']=$question;
            $qaInfo['answer']=$answer;
            return $qaInfo;
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }

    public function getInviteUser($countryId,$cityId)
    {
        $conn=$this->getConnection();
        try{
            $this->qaCommunityDb=new QaCommunityDb($conn);
            $rst = $this->qaCommunityDb->getInviteUser($countryId,$cityId);
            $userBase = new UserBaseService();
            $sysUser = $userBase->findBaseInfoBySign(Code::RECOMMEND_ANSWER_USER);
            $rstArr = array('inviteUser'=>$rst,'sysUser'=>$sysUser);
            return $rstArr;
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }
    //得到问答列表
    public function getQaList($page,$countryId,$cityId,$tags,$search)
    {
        $conn=$this->getConnection();
        try{
            $this->qaCommunityDb=new QaCommunityDb($conn);
            $tagStr='';
            if(!empty($tags)&&$tags!='全部')
            {
             $intersection=array();
             $tagList=explode(',',$tags);
             foreach($tagList as $val){
                 $valArr=json_decode(\Yii::$app->redis->get(Code::QUESTION_COMMUNITY_TAG_PREFIX.md5($val)),true);
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
            $page = $this->qaCommunityDb->getQaList($page,$countryId,$cityId,$tagStr,$search);
            return array('data'=>$page->getList(),'msg'=>$page);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }

    public function updateQaAttentionCount($conn,$id,$add)
    {
        try{
            $this->qaCommunityDb=new QaCommunityDb($conn);
            $info = $this->qaCommunityDb->getQuestionById($id);
            $count = $info['attentionNumber']?$info['attentionNumber']:0;
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
            $this->qaCommunityDb->updateAttentionNumber($id,$count);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }
}