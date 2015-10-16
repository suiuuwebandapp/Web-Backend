<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/10/15
 * Time : 15:26
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\services;


use common\models\BaseDb;
use common\models\VolunteerDb;
use yii\base\Exception;

class VolunteerService extends  BaseDb{


    private $volunteerDb;

    public function findById($id)
    {
        if(empty($id)){
            throw new Exception("VolunteerId Is Not Allow Empty");
        }
        try{
            $conn=$this->getConnection();
            $this->volunteerDb=new VolunteerDb($conn);
            $rst=$this->volunteerDb->findById($id);
            return $rst;
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }
}