<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/29
 * Time : 下午5:46
 * Email: zhangxinmailvip@foxmail.com
 */

namespace backend\services;


use backend\components\Page;
use backend\models\UserBaseDb;
use common\models\BaseDb;
use yii\base\Exception;

class UserBaseService extends BaseDb{


    /**
     * @param Page $page
     * @param $search
     * @return Page|null
     * @throws Exception
     * @throws \Exception
     */
    public function getUserBaseListByPage(Page $page,$search)
    {
        try{
            $conn=$this->getConnection();
            $userBaseDb=new UserBaseDb($conn);
            $page=$userBaseDb->getUserBaseListByPage($page,$search);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
        return $page;
    }
}