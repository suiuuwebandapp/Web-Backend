<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/14
 * Time : 下午2:07
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\services;


use common\components\Code;
use common\models\BaseDb;
use common\models\UserPayDb;
use yii\base\Exception;

class UserPayService extends BaseDb{

    private $userPayDb;

    public function __construct(){

    }


    /**
     * 添加用户支付记录，并且更新订单状态
     * @param $orderNumber
     * @param $payNumber
     * @param $type
     * @param $status
     * @return int
     * @throws Exception
     * @throws \Exception
     */
    public function addUserPay($orderNumber,$payNumber,$type,$status)
    {
        if(empty($orderNumber)){
            throw new Exception("OrderNumber Is Not Allow Empty");
        }
        if(empty($payNumber)){
            throw new Exception("PayNumber Is Not Allow Empty");
        }
        try{
            $conn=$this->getConnection();
            $this->userPayDb=new UserPayDb($conn);
            $rst=$this->userPayDb->addUserPay($orderNumber,$payNumber,$type,$status);
            return $rst;
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }

}