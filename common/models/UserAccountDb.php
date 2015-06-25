<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/6/3
 * Time : 下午3:28
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\models;


use common\entity\UserAccount;
use common\entity\UserAccountRecord;
use common\entity\UserCashRecord;
use yii\db\mssql\PDO;

class UserAccountDb extends ProxyDb{


    /**
     * 添加用户账户
     * @param UserAccount $userAccount
     * @throws \yii\db\Exception
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
              :userId,:type,:account,,:username,now(),now(),FALSE
            )
        ");

        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":userId",$userAccount->userId,PDO::PARAM_STR);
        $command->bindParam(":type",$userAccount->type,PDO::PARAM_INT);
        $command->bindParam(":account",$userAccount->account,PDO::PARAM_INT);
        $command->bindParam(":username",$userAccount->username,PDO::PARAM_INT);

        $command->execute();
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
        $command->bindParam(":info",$userAccountRecord->userId,PDO::PARAM_STR);

        $command->execute();
    }


    public function addUserCashRecord(UserCashRecord $userCashRecord)
    {
        $sql=sprintf("
            INSERT INTO user_cash_record
            (
              cashId,cashNumber,userId,money,balance,recordTime
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
        $command->bindParam(":info",$userAccountRecord->userId,PDO::PARAM_STR);

        $command->execute();
    }
}