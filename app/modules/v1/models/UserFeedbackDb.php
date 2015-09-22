<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/8
 * Time: 上午10:35
 */

namespace app\modules\v1\models;

use app\modules\v1\entity\UserFeedback;
use common\models\ProxyDb;
use yii\db\mssql\PDO;

class UserFeedbackDb extends ProxyDb
{


    /**
     * 添加反馈
     * @param UserFeedback $feedback
     */
    public function addUserAttention(UserFeedback $feedback)
    {
        $sql=sprintf("
            INSERT INTO user_feedback (userSign,content,imgList,createTime,fType,fLevel,fResult,fName,contact,fAddr)
                                VALUES(:userSign,:content,:imgList,now(),:fType,:fLevel,:fResult,:fName,:contact,:fAddr)
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":userSign", $feedback->userSign, PDO::PARAM_STR);
        $command->bindParam(":content", $feedback->content, PDO::PARAM_STR);
        $command->bindParam(":imgList", $feedback->imgList, PDO::PARAM_STR);
        $command->bindParam(":fType", $feedback->fType, PDO::PARAM_INT);
        $command->bindParam(":fLevel", $feedback->fLevel, PDO::PARAM_INT);
        $command->bindValue(":fResult", $feedback::RESULT_UN_DISPOSE, PDO::PARAM_INT);
        $command->bindParam(":fName", $feedback->fName, PDO::PARAM_STR);
        $command->bindParam(":contact", $feedback->contact, PDO::PARAM_STR);
        $command->bindParam(":fAddr", $feedback->fAddr, PDO::PARAM_STR);
        $command->execute();
        //return $this->getConnection()->lastInsertID;
    }

}