<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/9/29
 * Time : 11:15
 * Email: zhangxinmailvip@foxmail.com
 */

namespace backend\services;


use backend\components\Page;
use common\models\BaseDb;
use common\models\UserMessageDb;
use yii\base\Exception;

class UserMessageService extends BaseDb{

    public function getSysMessageSessionList(Page $page,$keywords)
    {
        try{
            $conn=$this->getConnection();
            $userMessageDb=new UserMessageDb($conn);
            $page=$userMessageDb->getSysUserMessageSessionList($page,$keywords);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
        return $page;

    }


    public function getUserMessageList($userId,$sessionKey,$read)
    {

        $conn=$this->getConnection();
        $tran=$conn->beginTransaction();
        try{
            $this->userMessageDb=new UserMessageDb($conn);
            $senderMessageSession=$this->userMessageDb->findUserMessageSessionByKey($userId,$sessionKey);
            if($read==1){
                $this->userMessageDb->updateUserMessageRead($sessionKey,$userId);
                $this->userMessageDb->updateUserMessageSessionRead($senderMessageSession['userId'],$sessionKey);
            }
            $list=$this->userMessageDb->getUserMessageListByKey($userId,$sessionKey);

            $this->commit($tran);
            return $list;
        }catch (Exception $e){
            $this->rollback($tran);
            throw $e;
        }finally{
            $this->closeLink();
        }
    }

    /**
     * 获取所有未读消息数量
     * @return array|bool
     * @throws Exception
     * @throws \Exception
     */
    public function getUnReadMessageCount()
    {
        $count=0;
        try{
            $conn=$this->getConnection();
            $userMessageDb=new UserMessageDb($conn);
            $rst=$userMessageDb->getUnReadSysUserMessageCount();
            $count=$rst['unReadCount'];
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
        return $count;
    }

}