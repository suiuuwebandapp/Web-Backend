<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/8/19
 * Time: 下午4:21
 */

namespace app\modules\v1\models;


use app\modules\v1\entity\AppLog;
use common\models\ProxyDb;
use yii\db\mssql\PDO;

class AppLogDb  extends ProxyDb{

    public function addLog(AppLog $appLog)
    {
        $sql=sprintf("
            INSERT INTO app_log
            (
              appVersionId,userSign,createTime,log
            )
            VALUES
            (
             :appVersionId,:userSign,now(),:log
            )
        ");

        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":appVersionId",$appLog->appVersionId,PDO::PARAM_INT);
        $command->bindParam(":userSign",$appLog->userSign,PDO::PARAM_STR);
        $command->bindParam(":log",$appLog->log,PDO::PARAM_STR);
        $command->execute();
    }

}