<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/3/31
 * Time : 下午2:21
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\models;


use yii\db\Command;
use yii\db\mssql\PDO;
use yii\db\Transaction;
use yii\base\Exception;

class BaseDb {

    //默认 当前时间  参数
    const DB_PARAM_NOW="db_param_now";

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
        if($array==null||$array === false){
            return null;
        };

        if(class_exists($className)) {
            $newClass=new $className;
            foreach($newClass as $prop =>$val){
                $val=array_key_exists($prop,$array)?$array[$prop]:null;
                $newClass->$prop=$val;
            }
            return $newClass;
        }else{
            throw new Exception('Undefined ClassName Exception');
        }
    }


    /**
     * 更新OBJECT 对象
     * 要求：设定PRIMARY KEY
     * @param $object
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public function updateObject($object)
    {
        $tableName=$this->object2TableName($object);
        try{
            $primaryKey=$object::PRIMARY_KEY;
        }catch(Exception $e){
            throw new Exception("Undefined PrimaryKey Exception");
        }
        $sqlBody='';
        $params=[];
        $paramArray=[];
        $replaceParam=[];

        if(class_exists(get_class($object))) {
            foreach($object as $prop =>$val){
                $params[]=$prop;
                if($val===self::DB_PARAM_NOW){
                    $replaceParam[$prop]="now()";
                    continue;
                }
                $paramArray[$prop]=$val;
            }
        }else{
            throw new Exception('Undefined ClassName Exception');
        }
        //循环生成SQL
        foreach($params as $p){
            if(array_key_exists($p,$replaceParam)){
                $sqlBody.=$p."=".$replaceParam[$p].",";
                continue;
            }
            $sqlBody.=$p."=:".$p.",";
        }
        if(empty($sqlBody)){
            throw new Exception("Invalid Sql Body");
        }
        $sqlWhere=" ".$primaryKey."=:".$primaryKey;
        $sqlBody=substr($sqlBody,0,strlen($sqlBody)-1);
        $sql=sprintf("
             UPDATE %s SET %s WHERE %s
          ",$tableName,$sqlBody,$sqlWhere);

        $conn=self::getConnection();
        $command=$conn->createCommand($sql);
        $this->commandSetParam($command,$paramArray);
        $command->execute();
    }


    /**
     * 根据主键删除数据
     * @param $className
     * @param $id
     * @return int
     * @throws \yii\db\Exception
     */
    public function deleteObjectById($className,$id)
    {
        $object=new $className;
        $tableName=$this->object2TableName($object);

        $sqlWhere=" ".$object::PRIMARY_KEY."=:".$object::PRIMARY_KEY;
        $sql=sprintf("
             DELETE FROM  %s  WHERE %s
          ",$tableName,$sqlWhere);

        $conn=self::getConnection();
        $command=$conn->createCommand($sql);
        $command->bindParam($object::PRIMARY_KEY,$id);
        return $command->execute();
    }


    /**
     * 保存OBJECT 对象到数据库
     * @param $object
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public function saveObject($object)
    {
        $tableName=$this->object2TableName($object);
        try{
            $primaryKey=$object::PRIMARY_KEY;
        }catch(Exception $e){
            throw new Exception("Undefined PrimaryKey Exception");
        }
        $values=[];
        $params=[];
        $paramArray=[];
        $replaceParam=[];

        if(class_exists(get_class($object))) {
            foreach($object as $prop =>$val){
                if($prop==$primaryKey){continue;}
                $params[]=$prop;

                if($val===self::DB_PARAM_NOW){
                    $replaceParam[$prop]="now()";
                    continue;
                }
                $paramArray[$prop]=$val;
            }
        }else{
            throw new Exception('Undefined ClassName Exception');
        }
        //循环生成SQL
        foreach($params as $p){
            if(array_key_exists($p,$replaceParam)){
                $values[]=$replaceParam[$p];
                continue;
            }
            $values[]=":".$p;
        }
        $sql=sprintf("
             INSERT INTO  %s (%s) VALUES (%s)
          ",$tableName,implode(",",$params),implode(",",$values)
        );
        $conn=self::getConnection();
        $command=$conn->createCommand($sql);
        $this->commandSetParam($command,$paramArray);
        $command->execute();
        return $object;
    }


    /**
     * 根据对象主键获取对象数组
     * @param $className
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function findObjectById($className,$id)
    {
        $object=new $className;
        $tableName=$this->object2TableName($object);

        $sqlWhere=" ".$object::PRIMARY_KEY."=:".$object::PRIMARY_KEY;
        $sql=sprintf("
             SELECT * FROM  %s  WHERE %s
          ",$tableName,$sqlWhere);

        $conn=self::getConnection();
        $command=$conn->createCommand($sql);
        $command->bindParam($object::PRIMARY_KEY,$id);
        return $command->queryOne();
    }


    /**
     * 根据对象 属性获取对象
     * @param $className
     * @param $queryKey
     * @param $value
     * @return array
     */
    public function findObjectByType($className,$queryKey,$value)
    {
        $object=new $className;
        $tableName=$this->object2TableName($object);

        $sqlWhere=" ".$queryKey."=:".$queryKey;
        $sql=sprintf("
             SELECT * FROM  %s  WHERE %s
          ",$tableName,$sqlWhere);
        $conn=self::getConnection();
        $command=$conn->createCommand($sql);
        $command->bindParam($queryKey,$value);
        return $command->queryAll();
    }


    /**
     * 获取OBJECT 对象 TableName
     * @param $object
     * @return string
     */
    private function object2TableName($object)
    {
        $tableName='';
        $className=get_class($object);
        $className=explode("\\",$className);
        $className=$className[count($className)-1];
        for($i=0;$i<strlen($className);$i++){
            if(preg_match('/^[A-Z]+$/', $className[$i])){
                $tableName.="_".strtolower($className[$i]);
            }else{
                $tableName.=$className[$i];
            }
        }
        if(strlen($tableName)>0){
            $tableName=substr($tableName,1,strlen($tableName));
        }
        return $tableName;
    }

    /**
     * Command 设置参数
     * @param Command $command
     * @param $paramArray
     */
    private function commandSetParam(Command $command,$paramArray)
    {
        foreach($paramArray as $key=>$value )
        {
            if(is_numeric($value)){
                $command->bindValue(":".$key,$value,PDO::PARAM_INT);
            }else{
                $command->bindValue(":".$key,$value,PDO::PARAM_STR);
            }
        }
    }


    /**
     * 获取最后插入ID
     * @return string
     */
    public function getLastInsertId()
    {
        return $this->getConnection()->lastInsertID;
    }

}