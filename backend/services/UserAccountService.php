<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/7/2
 * Time : 下午6:47
 * Email: zhangxinmailvip@foxmail.com
 */

namespace backend\services;


use backend\components\Page;
use backend\models\UserBaseDb;
use common\components\DateUtils;
use common\entity\UserCashRecord;
use common\models\BaseDb;
use common\models\UserAccountDb;
use yii\base\Exception;

class UserAccountService extends BaseDb{

    private $userAccountDb;


    /**
     * 获取用户体现记录
     * @param Page $page
     * @param $search
     * @param $type
     * @param $status
     * @return Page|null
     * @throws Exception
     * @throws \Exception
     */
    public function getUserCashRecordList(Page $page,$search,$type,$status)
    {
        try{
            $conn=$this->getConnection();
            $this->userAccountDb=new UserAccountDb($conn);
            $page=$this->userAccountDb->getAllUserCashList($page,$search,$type,$status);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
        return $page;
    }


    /**
     * 获取用户体现详情
     * @param $cashId
     * @return array
     * @throws Exception
     * @throws \Exception
     */
    public function findUserCashInfoById($cashId)
    {
        if(empty($cashId)){
            throw new Exception("CashId Is Not Allow Empty");
        }
        $userCashInfo=[];
        try{
            $conn=$this->getConnection();
            $this->userAccountDb=new UserAccountDb($conn);
            $userBaseDb=new UserBaseDb($conn);
            $userCashInfo['info']=$this->findObjectById(UserCashRecord::class,$cashId);
            $userCashInfo['user']=$userBaseDb->findByUserSign($userCashInfo['info']['userId']);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
        return $userCashInfo;
    }


    /**
     * 更新用户提现记录
     * @param $cashId
     * @param $cashNumber
     * @param $status
     * @throws Exception
     * @throws \Exception
     */
    public function updateUserCashRecord($cashId,$cashNumber,$status)
    {
        if(empty($cashId)){
            throw new Exception("CashId Is Not Allow Empty");
        }
        if($status==UserCashRecord::USER_CASH_RECORD_STATUS_SUCCESS){
            if(empty($cashNumber)){
                throw new Exception("CashNumber Is Not Allow Empty");
            }
        }
        try{
            $conn=$this->getConnection();
            $this->userAccountDb=new UserAccountDb($conn);
            $userCashInfo=$this->userAccountDb->findUserCashById($cashId);
            $userCashInfo=$this->arrayCastObject($userCashInfo,UserCashRecord::class);
            if(empty($userCashInfo)){
                throw new Exception("Invalid CashId");
            }
            $userCashInfo->status=$status;
            $userCashInfo->cashNumber=$cashNumber;
            $userCashInfo->finishTime=DateUtils::getNowTime();
            $this->updateObject($userCashInfo,UserCashRecord::class);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }

}