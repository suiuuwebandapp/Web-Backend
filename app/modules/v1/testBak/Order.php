<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/8/14
 * Time: 下午2:08
 */

namespace app\modules\v1\models;

use yii\db\ActiveRecord;

class Order extends ActiveRecord
{
    // 获取订单所属用户
    public function getCustomer()
    {
        //同样第一个参数指定关联的子表模型类名
        //
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }
    // 获取订单中所有图书
    public function getBooks()
    {
        //同样第一个参数指定关联的子表模型类名
        //
        return $this->hasMany(Book::className(), ['id' => 'book_id']);
    }
}