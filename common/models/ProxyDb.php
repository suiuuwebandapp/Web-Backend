<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/3/31
 * Time : 下午1:59
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\models;


use yii\db\Connection;

class ProxyDb extends Connection {

    private $db;

    public function __construct(Connection $db){
        $this->db=$db;
    }

    protected function getConnection(){
        $this->db->open();
        return $this->db;
    }

    protected function appendPageSql($sql,$PageEntity){

    }

}