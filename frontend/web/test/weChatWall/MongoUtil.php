<?php
/**
 * Created by PhpStorm.
 * User: XiMing
 * Date: 15-1-13
 * Time: 下午6:03
 */

class MongoUtil{
    private $connection;
    private $db;
    function __construct($my_db){
        $this->connection = new MongoClient();// "mongodb://ip"
        $this->db = $this->connection->$my_db;
    }

    function getColloction($colloction){
        return $this->db->$colloction;
    }

    function getTop100($colloction){
        $coll = $this->getColloction($colloction);
        $cursor = $coll->find();
        foreach ( $cursor as $id => $value )
        {
            echo "$id: ";
            var_dump( $value );
        }
    }

    function finone($colloction,$doc){
        $res = $this->getColloction($colloction)->findOne($doc);
        var_dump( $res );
        return $res;
    }

    function insert($colloction,$doc){
        $coll = $this->getColloction($colloction);
        $coll->insert($doc);
    }

    function remove($colloction,$doc){
        return $this->getColloction($colloction)->remove($doc);
    }
}