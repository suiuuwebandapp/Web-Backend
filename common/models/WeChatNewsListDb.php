<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/21
 * Time: 下午6:33
 */

namespace common\models;


use yii\db\mssql\PDO;

class WeChatNewsListDb  extends ProxyDb {

    public function findNewsById($newsId)
    {
        $sql = sprintf("
           SELECT * FROM wechat_news_list WHERE newsId=:newsId
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":newsId", $newsId, PDO::PARAM_INT);
        return $command->queryOne();
    }

}