<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/8/19
 * Time: 下午4:51
 */

namespace app\modules\v1\models;

use app\modules\v1\entity\AppVersion;
use common\models\ProxyDb;
use yii\db\mssql\PDO;

class AppVersionDb  extends ProxyDb{

    public function addVersion(AppVersion $appVersion)
    {
        $sql=sprintf("
            INSERT INTO app_version
            (
              appId,clientType,versionId,versionMini,createTime,updateTime
            )
            VALUES
            (
            :appId,:clientType,:versionId,:versionMini,now(),now()
            )
        ");

        $command=$this->getConnection()->createCommand($sql);

        $command->bindParam(":appId",$appVersion->appId,PDO::PARAM_STR);
        $command->bindParam(":clientType",$appVersion->clientType,PDO::PARAM_STR);
        $command->bindParam(":versionId",$appVersion->versionId,PDO::PARAM_INT);
        $command->bindParam(":versionMini",$appVersion->versionMini,PDO::PARAM_INT);
        $command->execute();
        return $this->getConnection()->getLastInsertID();
    }

    public function getVersion(AppVersion $appVersion)
    {
        $sql=sprintf("SELECT  id,appId,clientType,versionId,versionMini,createTime,updateTime FROM app_version WHERE appId=:appId AND clientType=:clientType");
        $command = $this->getConnection()->createCommand($sql);
        $command->bindParam("appId",$appVersion->appId,PDO::PARAM_STR);
        $command->bindParam("clientType",$appVersion->clientType,PDO::PARAM_STR);
        return $command->queryOne();
    }

}