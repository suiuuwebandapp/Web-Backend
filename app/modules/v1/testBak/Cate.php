<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/8/14
 * Time: 下午2:08
 */

namespace app\modules\v1\models;

use yii\db\ActiveRecord;

class Cate extends ActiveRecord
{
// 这是获取客户的订单，由上面我们知道这个是一对多的关联，一个客户有多个订单
    public function getCate()
    {
        // 第一个参数为要关联的子表模型类名，
        // 第二个参数指定 通过子表的customer_id，关联主表的id字段
        return $this->find();
    }

}