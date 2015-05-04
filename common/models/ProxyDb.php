<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/3/31
 * Time : 下午1:59
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\models;


use backend\components\Page;
use yii\db\Connection;
use yii\db\mssql\PDO;

class ProxyDb extends Connection {

    const TABLE_NAME="{{TABLE_NAME}}";

    private $db;

    private $paramArray=array();

    private $sql="";

    private $tableName;

    private $selectInfo;


    public function __construct(Connection $db,$tableName=null){
        $this->db=$db;
        $this->tableName=$tableName;
    }

    protected function getConnection(){
        $this->db->open();
        return $this->db;
    }


    public function setSql($sql){
        $this->sql=$sql;
    }

    public function setParam($key,$value){
        $this->paramArray[$key]=$value;
    }


    public function getTableName(){
        return $this->tableName;
    }


    public function setSelectInfo($selectInfo)
    {
        $this->selectInfo=$selectInfo;
    }



    public function find(Page $page=null)
    {

        if($page==null){
            $page=new Page();
            $page->showAll=true;
        }
        //替换Table名称
        $this->sql=str_replace(self::TABLE_NAME,$this->tableName,$this->sql);
        if(!empty($this->selectInfo)){
            $searchSql="SELECT ".$this->selectInfo." ".$this->sql;

        }else{
            $searchSql="SELECT * ".$this->sql;
        }

        $command=$this->db->createCommand($searchSql);

        if(!empty($page->sortName)){
            $searchSql=$searchSql." order by ".$page->sortName." ".$page->sortType;
        }

        if(!$page->showAll){
            $searchSql=$searchSql." limit ".$page->startRow.",".$page->pageSize;
            $page->totalCount=$this->findAllCount();
        }
        $command->setSql($searchSql);

        foreach($this->paramArray as $key=>$value )
        {
            if(is_numeric($value)){
                $command->bindValue(":".$key,$value,PDO::PARAM_INT);
            }else{
                $command->bindValue(":".$key,$value,PDO::PARAM_STR);
            }
        }
        $page->setList($command->queryAll());
        return $page;

    }

    public function findList(Page $page)
    {
        if($page==null){
            $page=new Page();
            $page->showAll=true;
        }
        $searchSql=$this->sql;
        $command=$this->db->createCommand($searchSql);

        $command->setSql($searchSql);

        foreach($this->paramArray as $key=>$value )
        {
            if(is_numeric($value)){
                $command->bindValue(":".$key,$value,PDO::PARAM_INT);
            }else{
                $command->bindValue(":".$key,$value,PDO::PARAM_STR);
            }
        }
        $page->setList($command->queryAll());
        return $page;
    }


    public function findAllCount()
    {
        $allCountSql=$this->sql;

        $allCountSql=" SELECT COUNT(*) AS num ".$allCountSql;

        $command=$this->db->createCommand($allCountSql);
        foreach($this->paramArray as $key=>$value )
        {
            if(is_numeric($value)){
                $command->bindValue(":".$key,$value,PDO::PARAM_INT);
            }else{
                $command->bindValue(":".$key,$value,PDO::PARAM_STR);
            }
        }
        $rst= $command->queryOne();
        return $rst['num'];


    }


    public function findListBySql()
    {
        //替换Table名称
        $this->sql=str_replace(self::TABLE_NAME,$this->tableName,$this->sql);

        if(!empty($this->selectInfo)){
            $searchSql="SELECT ".$this->selectInfo." ".$this->sql;

        }else{
            $searchSql="SELECT * ".$this->sql;
        }

        $command=$this->db->createCommand($searchSql);

        $command->setSql($searchSql);

        foreach($this->paramArray as $key=>$value )
        {
            if(is_numeric($value)){
                $command->bindValue(":".$key,$value,PDO::PARAM_INT);
            }else{
                $command->bindValue(":".$key,$value,PDO::PARAM_STR);
            }
        }
        return $command->queryAll();
    }


}