<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/7/29
 * Time: 下午3:05
 */

namespace frontend\services;


use common\components\Code;
use common\entity\UserMessageRemind;
use common\models\BaseDb;
use common\models\UserMessageRemindDb;
use yii\base\Exception;

class UserMessageRemindService extends BaseDb
{
    private $remindDb;
    public function __construct()
    {

    }
    public function getOrderMessage($userSign,$page,$type)
    {
        try {
            $conn = $this->getConnection();
            $this->remindDb=new UserMessageRemindDb($conn);
            $data=$this->remindDb->getOrderRemind($userSign,$page,$type);
            return array('data'=>$data->getList(),'msg'=>$data);
        } catch (Exception $e) {
            throw new Exception('获取订单消息异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }
    public function getTripMessage($userSign,$page,$type)
    {
        try {
            $conn = $this->getConnection();
            $this->remindDb=new UserMessageRemindDb($conn);
            $data=$this->remindDb->getTripRemind($userSign,$page,$type);
            return array('data'=>$data->getList(),'msg'=>$data);
        } catch (Exception $e) {
            throw new Exception('获取旅途消息异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }

    public function getTpMessage($userSign,$page,$type)
    {
        try {
            $conn = $this->getConnection();
            $this->remindDb=new UserMessageRemindDb($conn);
            $data=$this->remindDb->getTpRemind($userSign,$page,$type);
            return array('data'=>$data->getList(),'msg'=>$data);
        } catch (Exception $e) {
            throw new Exception('获取旅图消息异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }

    public function getQaMessage($userSign,$page,$type)
    {
        try {
            $conn = $this->getConnection();
            $this->remindDb=new UserMessageRemindDb($conn);
            $data=$this->remindDb->getQaRemind($userSign,$page,$type);
            return array('data'=>$data->getList(),'msg'=>$data);
        } catch (Exception $e) {
            throw new Exception('获取问答消息异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }
    public function getSysMessage($userSign,$page,$type)
    {
        try {
            $conn = $this->getConnection();
            $this->remindDb=new UserMessageRemindDb($conn);
            $data=$this->remindDb->getSysRemind($userSign,$page,$type);
            return array('data'=>$data->getList(),'msg'=>$data);
        } catch (Exception $e) {
            throw new Exception('获取问答消息异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }
    public function deleteUserMessageRemind($rid,$userSign)
    {
        try {
            $conn = $this->getConnection();
            $this->remindDb=new UserMessageRemindDb($conn);

            $this->remindDb->deleteUserMessageRemind($rid,$userSign);
        } catch (Exception $e) {
            throw new Exception('删除用户消息异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }

    public function addMessageRemind($relativeId,$relativeType,$userSign,$relativeUserSign,$rType=0,$content="",$url="")
    {
        try {
            $conn = $this->getConnection();
            $this->remindDb=new UserMessageRemindDb($conn);
            $this->remindDb->addUserMessageRemind($relativeId,$relativeType,$userSign,$relativeUserSign,$rType,$content,$url);
        } catch (Exception $e) {
            throw new Exception('添加消息异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }

    }
}