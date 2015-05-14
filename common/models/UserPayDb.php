<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/14
 * Time : 下午2:01
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\models;


class UserPayDb extends ProxyDb{

    public function addUserPay($orderNumber,$payNumber,$type,$status)
    {

        $connection=$this->getConnection();

        $command=$connection->createCommand('call pay_order(:orderNumber,:payNumber,:type,:status,@rst)');
        $command->bindParam(":orderNumber",$orderNumber,\PDO::PARAM_STR);
        $command->bindParam(":payNumber",$payNumber,\PDO::PARAM_STR);
        $command->bindParam(":type",$type,\PDO::PARAM_INT);
        $command->bindParam(":status",$status,\PDO::PARAM_INT);

        return $command->execute();
    }

}