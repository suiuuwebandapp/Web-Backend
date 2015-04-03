<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/3/31
 * Time : 下午2:21
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\models;


use yii\db\Transaction;
use yii\base\Exception;

class BaseDb {

    private $connection;
    public function getConnection(){
        $this->connection=\Yii::$app->getDb();
        return $this->connection;
    }

    public function closeLink(){
        $this->connection->close();
    }

    public function rollback(Transaction $transaction){
        $transaction->rollback();
    }

    public function commit(Transaction $transaction){
        $transaction->commit();
    }

    /**
     * 数组转换成自定义对象
     * @param $array
     * @param $className
     * @return mixed
     * @throws Exception
     */
    public static function arrayCastObject($array,$className)
    {
        if($array === false){
            return null;
        };

        if(class_exists($className)) {
            $newClass=new $className;
            foreach($newClass as $prop =>$val){
                $newClass->$prop=$array[$prop];
            }
            return $newClass;
        }else{
            throw new Exception('Undefined ClassName Exception');
        }
    }

}