<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/6/23
 * Time : 下午5:11
 * Email: zhangxinmailvip@foxmail.com
 */

namespace app\modules\v1\services;


use app\modules\v1\entity\UserAccount;
use app\modules\v1\entity\UserAccountRecord;
use app\modules\v1\entity\UserCashRecord;
use common\models\BaseDb;
use app\modules\v1\models\UserAccountDb;
use app\modules\v1\models\UserOrderDb;
use yii\base\Exception;

class UserAccountService extends BaseDb{

    private $userAccountDb;


    public function __construct()
    {

    }


    /**
     * 获取用户账户记录列表
     * @param  $page
     * @param $userId
     * @param $startTime
     * @param $endTime
     * @param $type
     * @return Page
     * @throws Exception
     * @throws \Exception
     */
    public function getUserAccountRecordList( $page,$userId,$startTime,$endTime,$type)
    {
        try {
            $conn = $this->getConnection();
            $this->userAccountDb = new UserAccountDb($conn);
            $page=$this->userAccountDb->getUserAccountRecordList($page,$userId,$startTime,$endTime,$type);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $page;
    }


    /**
     * 添加用户账户关联
     * @param UserAccount $userAccount
     * @throws Exception
     * @throws \Exception
     * @return int
     */
    public function addUserAccount(UserAccount $userAccount)
    {
        $conn = $this->getConnection();
        $tran=$conn->beginTransaction();
        try {
            $this->userAccountDb = new UserAccountDb($conn);
            $this->userAccountDb->updateUserAccountDelete($userAccount->userId,$userAccount->type);
            $id = $this->userAccountDb->addUserAccount($userAccount);
            $this->commit($tran);
            return $id;
        } catch (Exception $e) {
            $this->rollback($tran);
            throw $e;
        } finally {
            $this->closeLink();
        }

    }


    /**
     * 获取用户账户关联列表
     * @param $userSign
     * @return array|null
     * @throws Exception
     * @throws \Exception
     */
    public function getUserAccountList($userSign)
    {
        $accountList=null;
        try {
            $conn = $this->getConnection();
            $this->userAccountDb = new UserAccountDb($conn);
            $accountList=$this->userAccountDb->getUserAccountList($userSign);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $accountList;
    }

    /**删除
     * @param $userSign
     * @param $accountId
     * @throws Exception
     * @throws \Exception
     */
    public function deleteUserAccount($userSign,$accountId)
    {
        $accountList=null;
        try {
            $conn = $this->getConnection();
            $this->userAccountDb = new UserAccountDb($conn);
            $this->userAccountDb->updateUserAccountDeleteById($userSign,$accountId);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }

    public function addUserCashApply($money,$accountId,$userSign)
    {
        $conn = $this->getConnection();
        $tran=$conn->beginTransaction();
        try {
            $userOrderDb=new UserOrderDb($conn);
            $this->userAccountDb = new UserAccountDb($conn);
            $userAccount=$this->userAccountDb->findUserAccountByAccountId($accountId);
            //判断accountId 和   userSign 的有效性
            if(empty($userAccount)||$userAccount['userId']!=$userSign){
                throw new Exception("Invalid AccountId");
            }
            $userMoney=$userOrderDb->getUserMoney($userSign);
            $balance=$userMoney['balance']-$money;
            if($balance<0){
                throw new Exception("No Enough Balance");
            }
            $version=$userMoney['version'];
            //1.更新用户余额
            $rstCount=$userOrderDb->updateUserBaseMoney($userSign,$balance,$version);
            if($rstCount==0){
                throw new Exception("Invalid UserBase Version");
            }
            //2.添加用户提现记录
            $userCashRecord=new UserCashRecord();
            $userCashRecord->userId=$userSign;
            $userCashRecord->account=$userAccount['account'];
            $userCashRecord->username=$userAccount['username'];
            $userCashRecord->type=$userAccount['type'];
            $userCashRecord->money=$money;
            $userCashRecord->balance=$balance;
            $userCashRecord->status=UserCashRecord::USER_CASH_RECORD_STATUS_WAIT;

            $this->userAccountDb->addUserCashRecord($userCashRecord);

            //3.添加随友账户记录
            $accountType=$userAccount['type']==UserAccount::USER_ACCOUNT_TYPE_ALIPAY?"支付宝:".$userAccount['username']." ".$userAccount['account']:"微信:".$userAccount['username'];
            $userAccountRecord=new UserAccountRecord();
            $userAccountRecord->userId=$userSign;
            $userAccountRecord->money=$money;
            $userAccountRecord->balance=$balance;
            $userAccountRecord->info="提现至".$accountType;
            $userAccountRecord->type=UserAccountRecord::USER_ACCOUNT_RECORD_TYPE_DRAW_MONEY;
            $userAccountRecord->relateId=$this->getLastInsertId();

            $this->userAccountDb->addUserAccountRecord($userAccountRecord);

            $this->commit($tran);
        } catch (Exception $e) {
            $this->rollback($tran);
            throw $e;
        } finally {
            $this->closeLink();
        }
    }
}