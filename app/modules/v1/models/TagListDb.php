<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/7/10
 * Time: 下午3:01
 */

namespace app\modules\v1\models;


use app\modules\v1\entity\TagList;
use common\models\ProxyDb;
use yii\db\mssql\PDO;

class TagListDb extends ProxyDb {


    public function addTagList(TagList $tagList)
    {
        $sql = sprintf("
            INSERT INTO tag_list
            (
             tName,tType
            )
            VALUES
            (
            :tName,:tType
            )
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":tName", $tagList->tName, PDO::PARAM_STR);
        $command->bindParam(":tType", $tagList->tType, PDO::PARAM_INT);
        $command->execute();
        return $this->getConnection()->lastInsertID;
    }

    //根据类型获取对应的标签
    public function getAllTag($tType)
    {
        $sql = sprintf("
            SELECT tId,tName,tType  FROM tag_list WHERE 1=1
        ");
        if(!empty($tType))
        {
            $sql.=" AND tType=:tType";
        }
        $command=$this->getConnection()->createCommand($sql);

        if(!empty($tType))
        {
            $command->bindParam(":tType", $tType, PDO::PARAM_INT);
        }
        return $command->queryAll();
    }
    public function getTagById($id)
    {
        $sql = sprintf("
            SELECT tId,tName,tType   FROM tag_list WHERE tId=:tId;
        ");
        $command=$this->getConnection()->createCommand($sql);
            $command->bindParam(":tId", $id, PDO::PARAM_INT);
        return $command->queryOne();
    }

    public function getTagByName($tName)
    {
        $sql = sprintf("
            SELECT tId,tName,tType   FROM tag_list WHERE tName=:tName;
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":tName", $tName, PDO::PARAM_STR);
        return $command->queryOne();
    }

    public function deleteTag()
    {

    }
}