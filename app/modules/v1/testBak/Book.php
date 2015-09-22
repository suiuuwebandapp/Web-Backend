<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/8/14
 * Time: 下午2:09
 */

namespace app\modules\v1\models;

use yii\db\ActiveRecord;

class Book extends ActiveRecord
{
    // 获取图书的作者
    public function getAuthor()
    {
        //同样第一个参数指定关联的子表模型类名
        return $this->hasOne(Author::className(), ['id' => 'author_id']);
    }
}
