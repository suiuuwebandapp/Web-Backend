<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/6/3
 * Time : 下午3:28
 * Email: zhangxinmailvip@foxmail.com
 */

namespace app\modules\v1\models;


use app\modules\v1\entity\UserAccount;
use app\modules\v1\entity\UserAccountRecord;
use app\modules\v1\entity\UserCashRecord;
use common\models\ProxyDb;
use yii\db\mssql\PDO;

class UserAccountDb extends ProxyDb{


    /**
     * 添加用户账户关联
     * @param UserAccount $userAccount
     * @throws \yii\db\Exception
     * @return int
     */
    public function addUserAccount(UserAccount $userAccount)
    {
        $sql=sprintf("
            INSERT INTO user_account
            (
              userId,type,account,username,createTime,updateTime,isDel
            )
            VALUES
            (
              :userId,:type,:account,:username,now(),now(),FALSE
            )
        ");

        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":userId",$userAccount->userId,PDO::PARAM_STR);
        $command->bindParam(":type",$userAccount->type,PDO::PARAM_INT);
        $command->bindParam(":account",$userAccount->account,PDO::PARAM_INT);
        $command->bindParam(":username",$userAccount->username,PDO::PARAM_INT);

        $command->execute();
        return $this->getConnection()->getLastInsertID();
    }



    /**
     * 添加用户账户操作记录
     * @param UserAccountRecord $userAccountRecord
     * @throws \yii\db\Exception
     */
    public function addUserAccountRecord(UserAccountRecord $userAccountRecord)
    {
        $sql=sprintf("
            INSERT INTO user_account_record
            (
              userId,type,relateId,money,info,recordTime
            )
            VALUES
            (
              :userId,:type,:relateId,:money,:info,now()
            )
        ");

        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":userId",$userAccountRecord->userId,PDO::PARAM_STR);
        $command->bindParam(":type",$userAccountRecord->type,PDO::PARAM_INT);
        $command->bindParam(":relateId",$userAccountRecord->relateId,PDO::PARAM_STR);
        $command->bindParam(":money",$userAccountRecord->money,PDO::PARAM_STR);
        $command->bindParam(":info",$userAccountRecord->info,PDO::PARAM_STR);

        $command->execute();
    }


    /**
     * 添加用户提现记录
     * @param UserCashRecord $userCashRecord
     * @throws \yii\db\Exception
     */

    public function addUserCashRecord(UserCashRecord $userCashRecord)
    {
        $sql=sprintf("
            INSERT INTO user_cash_record
            (
              userId,type,account,username,money,balance,createTime,status
            )
            VALUES
            (
              :userId,:type,:account,:username,:money,:balance,now(),:status
            )
        ");

        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":userId",$userCashRecord->userId,PDO::PARAM_INT);
        $command->bindParam(":type",$userCashRecord->type,PDO::PARAM_STR);
        $command->bindParam(":account",$userCashRecord->account,PDO::PARAM_STR);
        $command->bindParam(":username",$userCashRecord->username,PDO::PARAM_STR);
        $command->bindParam(":money",$userCashRecord->money,PDO::PARAM_STR);
        $command->bindParam(":balance",$userCashRecord->balance,PDO::PARAM_STR);
        $command->bindParam(":status",$userCashRecord->status,PDO::PARAM_INT);

        $command->execute();
    }


    /**
     * 获取用户账户记录列表
     * @param $page
     * @param $userId
     * @param $startTime
     * @param $endTime
     * @param $type
     * @return Page
     */
    public function getUserAccountRecordList( $page,$userId,$startTime,$endTime,$type)
    {
        $sql=sprintf("
            FROM user_account_record uar
            LEFT JOIN user_cash_record ucr ON uar.relateId=ucr.cashId
            WHERE 1=1 AND uar.userId=:userId
        ");
        $this->setParam("userId",$userId);
        if(!empty($type)){
            $sql.=" AND uar.type in (".$type.")";
        }
        if(isset($startTime)){
            $sql.=" AND date_format(uar.recordTime,'%Y-%m')>=:startTime";
            $this->setParam("startTime",$startTime);
        }
        if(isset($endTime)){
            $sql.=" AND date_format(uar.recordTime,'%Y-%m')<=:endTime";
            $this->setParam("endTime",$endTime);
        }
        $this->setSql($sql);
        $this->setSelectInfo("uar.accountRecordId,uar.userId,uar.type,uar.relateId,uar.money,uar.info,uar.recordTime,ucr.status");
        return  $this->find($page);
    }


    /**
     * 获取用户账户列表
     * @param $userSign
     * @return array
     */
    public function getUserAccountList($userSign)
    {
        $sql=sprintf("
            SELECT accountId,userId,type,account,username,createTime FROM user_account
            WHERE userId=:userSign AND isDel=FALSE
        ");

        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":userSign",$userSign,PDO::PARAM_STR);

        return $command->queryAll();
    }

    /**
     * 更新其他账户信息为删除状态
     * @param $userSign
     * @param $type
     * @return array
     */
    public function updateUserAccountDelete($userSign,$type)
    {
        $sql=sprintf("
            UPDATE user_account SET isDel=TRUE
            WHERE userId=:userSign AND type=:type
        ");

        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":userSign",$userSign,PDO::PARAM_STR);
        $command->bindParam(":type",$type,PDO::PARAM_INT);

        $command->execute();
    }

    /**
     * 更新其他账户信息为删除状态
     * @param $userSign
     * @param $accountId
     * @return array
     */
    public function updateUserAccountDeleteById($userSign,$accountId)
    {
        $sql=sprintf("
            UPDATE user_account SET isDel=TRUE
            WHERE userId=:userSign AND accountId=:accountId
        ");

        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":userSign",$userSign,PDO::PARAM_STR);
        $command->bindParam(":accountId",$accountId,PDO::PARAM_INT);

        $command->execute();
    }

    /**
     * 获取用户账户类型
     * @param $userSign
     * @param $type
     * @return array|bool
     */
    public function findUserAccountByType($userSign,$type)
    {
        $sql=sprintf("
            SELECT accountId,userId,type,account,username,createTime FROM user_account
            WHERE userSign=:userSign  AND type=:type
        ");
        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":userSign",$userSign,PDO::PARAM_STR);
        $command->bindParam(":type",$type,PDO::PARAM_INT);

        return $command->queryOne();
    }

    /**
     * 根据Id获取账户详情
     * @param $accountId
     * @return array|bool
     */
    public function findUserAccountByAccountId($accountId)
    {
        $sql=sprintf("
            SELECT accountId,userId,type,account,username,createTime FROM user_account
            WHERE accountId=:accountId
        ");
        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":accountId",$accountId,PDO::PARAM_INT);

        return $command->queryOne();
    }


    /**
     * 获取所有用户体现记录
     * @param $page
     * @param $search
     * @param $type
     * @param $status
     * @return Page|null
     */
    public function getAllUserCashList( $page,$search,$type,$status)
    {
        $sql=sprintf("
            FROM user_cash_record ucr
            LEFT JOIN user_base ub ON ub.userSign=ucr.userId
            WHERE 1=1
        ");
        if(!empty($search)){
            $sql.=' AND  ( ucr.cashNumber=:search OR ub.phone like :search OR ub.email like :search OR ub.nickname like :search  )';
            $this->setParam('search',$search.'%');
        }
        if(!empty($type)){
            $sql.=" AND ucr.type =:type";
            $this->setParam("type",$type);
        }
        if(!empty($status)){
            $sql.=" AND ucr.status =:status";
            $this->setParam("status",$status);
        }
        $this->setSql($sql);
        $this->setSelectInfo("ucr.cashId,ucr.cashNumber,ucr.userId,ucr.money,ucr.balance,ucr.account,ucr.username,ucr.type,ucr.createTime,ucr.finishTime,ucr.status,ub.nickname,ub.phone,ub.areaCode,ub.email");
        return  $this->find($page);
    }
    /**
     * 获取提现详情
     * @param $cashId
     * @return array|bool
     */
    public function findUserCashById($cashId)
    {
        $sql=sprintf("
            SELECT cashId,cashNumber,userId,money,balance,account,username,type,createTime,finishTime,status FROM user_cash_record
            WHERE cashId=:cashId
        ");
        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":cashId",$cashId,PDO::PARAM_INT);

        return $command->queryOne();
    }





}