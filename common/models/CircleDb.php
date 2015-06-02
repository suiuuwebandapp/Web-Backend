<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/6/2
 * Time: 上午10:53
 */

namespace common\models;


class CircleDb extends ProxyDb{

    public function getCircleList($page,$search,$type)
    {
        $sql=sprintf("
        FROM sys_circle_sort
            WHERE 1=1
        ");
        if(!empty($type))
        {
            $sql.=" AND cType = :cType ";
            $this->setParam("cType",$type);
        }
        if(!empty($search))
        {
            $sql.=" AND cName like :search ";
            $this->setParam("search","%".$search."%");
        }
        $this->setSql($sql);
        return $this->find($page);
    }

}