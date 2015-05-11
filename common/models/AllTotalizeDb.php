<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/11
 * Time: 下午5:03
 */
namespace common\models;

use common\entity\AllTotalize;
use yii\db\mssql\PDO;

class AllTotalizeDb extends ProxyDb
{
    public function addTotalize(AllTotalize $totalize)
    {
        $sql = sprintf("
            INSERT INTO all_totalize
            (
             totalize,tType,rId
            )
            VALUES
            (
              :totalize,:tType,:rId
            )
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":totalize", $totalize->totalize, PDO::PARAM_INT);
        $command->bindParam(":tType", $totalize->tType, PDO::PARAM_INT);
        $command->bindParam(":rId", $totalize->rId, PDO::PARAM_INT);
        return $command->execute();
    }

    public function findTotalize(AllTotalize $totalize){
        $sql = sprintf("
           SELECT * FROM all_totalize WHERE tType=:tType AND rId=:rId
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":tType", $totalize->tType, PDO::PARAM_INT);
        $command->bindParam(":rId", $totalize->rId, PDO::PARAM_INT);
        return $command->queryOne();
    }


    public function updateTotalize(AllTotalize $totalize){
        $sql = sprintf("
            UPDATE all_totalize SET totalize=:totalize WHERE tType=:tType AND rId=:rId
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":totalize", $totalize->totalize, PDO::PARAM_INT);
        $command->bindParam(":tType", $totalize->tType, PDO::PARAM_INT);
        $command->bindParam(":rId", $totalize->rId, PDO::PARAM_INT);
        return $command->execute();
    }

}