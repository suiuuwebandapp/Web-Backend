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

class ProxyDb extends Connection {

    private $db;

    private $paramArray=[];

    private $sql="";

    public function __construct(Connection $db){
        $this->db=$db;
    }

    protected function getConnection(){
        $this->db->open();
        return $this->db;
    }

    protected function appendPageSql($sql,$PageEntity){

    }


    public function setParam($key,$value){
        $this->paramArray[$key]=$value;
    }


    public function find(Page $page=null)
    {
        if($page!=null){
            $page=new Page();
            $page->showAll=true;
        }


        $command=$this->db->createCommand($this->sql);
        foreach($this->paramArray as $key=>$value )
        {
            $command->bindParam(":".$key,$value);
        }

        if($page){

        }

        return $command->queryAll();
    }

    public function findAllCount()
    {
        $command=$this->db->createCommand($this->sql);
        foreach($this->paramArray as $key=>$value )
        {
            $command->bindParam(":".$key,$value);
        }
        return $command->queryAll();
    }


    public function findList(Page $page,$sql)
    {
        if ($page == null) {
            $page = new Page();
            $page->showAll(true);
        }
    }

}