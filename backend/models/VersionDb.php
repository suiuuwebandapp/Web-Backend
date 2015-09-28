<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/9/28
 * Time: 上午9:52
 */

namespace backend\models;


use common\models\ProxyDb;

class VersionDb extends ProxyDb {

    public function getList($page,$search,$type)
    {
        $sql=sprintf("
        FROM app_version WHERE 1=1
        ");
        if(!empty($search))
        {
            $sql.=" AND id=:id";
            $this->setParam('id',$search);
        }
        if(!empty($type))
        {
            $sql.=" AND clientId=:clientId";
            $this->setParam('clientId',$type);
        }
        $this->setSql($sql);
        return $this->find($page);
    }

}