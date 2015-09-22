<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/8/14
 * Time: 下午2:08
 */

namespace app\modules\v1\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    // 获取订单所属用户
    public function getCustomer()
    {
        //同样第一个参数指定关联的子表模型类名
        //
        return $this->hasOne(Customer::className(), ['id' => 'id']);
    }
    // 获取订单中所有图书
    public function getBooks()
    {
        //同样第一个参数指定关联的子表模型类名
        //
        return $this->hasMany(Book::className(), ['id' => 'book_id']);
    }

    public function getUser()
    {
        return $this->find();
    }

    public function fields()
    {
        $fields = parent::fields();

        // remove fields that contain sensitive information
        unset($fields['id']);

        return $fields;
    }
    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }



    public static function findIdentityByAccessToken($token, $type = null)
    {

    }
}