<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/6/4
 * Time: 下午3:42
 */

namespace backend\services;


use common\models\BaseDb;
use common\models\WeChatNewsListDb;
use yii\base\Exception;

class WechatNewsService extends BaseDb{

    private $wechatNewDb;

    public function __construct()
    {

    }
    public function getList($page,$search,$status,$type)
    {
        try {
            $conn = $this->getConnection();
            $this->wechatNewDb = new WeChatNewsListDb($conn);
            $page=$this->wechatNewDb->getNewsList($page,$search,$status,$type);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $page;
    }
    public function deleteNews($id)
    {
        try {
            $conn = $this->getConnection();
            $this->wechatNewDb = new WeChatNewsListDb($conn);
            $this->wechatNewDb->deleteNews($id);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }

    public function addNews($chatNewsList)
    {
        try {
            $conn = $this->getConnection();
            $this->wechatNewDb = new WeChatNewsListDb($conn);
            $this->wechatNewDb->addNews($chatNewsList);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }

    public function change($id,$status)
    {
        try {
            $conn = $this->getConnection();
            $this->wechatNewDb = new WeChatNewsListDb($conn);
            if($status==1)
            {
                $status=2;
            }else
            {
                $status=1;
            }
            $this->wechatNewDb->change($id,$status);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }

}