<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/9/23
 * Time: 上午9:31
 */

namespace backend\services;


use backend\models\QaCommunityDb;
use common\components\Code;
use common\entity\AnswerCommunity;
use common\models\BaseDb;
use yii\base\Exception;

class QaService extends BaseDb{

    private $qaDb;

    public function __construct()
    {

    }
    public function getQaList($page,$countryId,$cityId,$tag,$search)
    {
        try {
            $conn = $this->getConnection();
            $this->qaDb = new QaCommunityDb($conn);
            $page=$this->qaDb->getQaList($page,$countryId,$cityId,$tag,$search);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $page;
    }

    public function deleteQuestion($id)
    {
        try {
            $conn = $this->getConnection();
            $this->qaDb = new QaCommunityDb($conn);
            return $this->qaDb->deleteById($id);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }

    public function getQuestionInfo($id)
    {
        try {
            $conn = $this->getConnection();
            $this->qaDb = new QaCommunityDb($conn);
            return $this->qaDb->getQuestionById($id);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }

    public function getAnswerListByQid($page,$type,$id)
    {
        try {
            $conn = $this->getConnection();
            $this->qaDb = new QaCommunityDb($conn);
            return $this->qaDb->getAnswerListByQid($page,$type,$id);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }
    public function getAnswerList($page,$search)
    {
        try {
            $conn = $this->getConnection();
            $this->qaDb = new QaCommunityDb($conn);
            $page=$this->qaDb->getAnswerList($page,$search);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $page;
    }

    public function addAnswer(AnswerCommunity $answerCommunity)
    {
        $conn=$this->getConnection();
        $tran = $conn->beginTransaction();
        try{
            $this->qaDb=new QaCommunityDb($conn);
            $info = $this->qaDb->getQuestionById($answerCommunity->qId);
            if(empty($info))
            {
                echo json_encode(Code::statusDataReturn(Code::FAIL,'未知问题内容'));
                exit;
            }
            $this->qaDb->addAnswer($answerCommunity);
            $this->commit($tran);
            $this->updateAnswerCount($answerCommunity->qId,true);
        }catch (Exception $e){
            $this->rollback($tran);
            throw $e;
        }finally{
            $this->closeLink();
        }
    }

    public function deleteAnswer($id,$qId)
    {
        try {
            $conn = $this->getConnection();
            $this->qaDb = new QaCommunityDb($conn);
            $this->updateAnswerCount($qId,false);
            return $this->qaDb->deleteAnswer($id);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }
    public function updateAnswerCount($id,$add)
    {
        $conn=$this->getConnection();
        try{
            $this->qaDb=new QaCommunityDb($conn);
            $info = $this->qaDb->getQuestionById($id);
            $count = $info['aNumber']?$info['aNumber']:0;
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
            $this->qaDb->updateAnswerNumber($id,$count);
            return $info;
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }

    public function getInvitationUser($userList)
    {
        try {
            $conn = $this->getConnection();
            $this->qaDb = new QaCommunityDb($conn);
            $rst = null;
            if(empty($userList))
            {
                return $rst;
            }
            $userSign="";
            $uArr=explode(',',$userList);
            foreach($uArr as $val )
            {
                $userRst= $this->qaDb->getUserInfo($val);

                if(isset($userRst["userSign"]))
                {
                    $rst[]=$userRst;
                }
            }
            return $rst;
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }
}