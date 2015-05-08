<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/8
 * Time: 上午11:00
 */
namespace frontend\services;

use common\components\Code;
use common\entity\UserFeedback;
use common\models\BaseDb;
use common\models\UserFeedbackDb;
use yii\base\Exception;

class UserFeedbackService extends BaseDb
{
    private $UserFeedbackDb;


    public function __construct()
    {

    }

    /**添加反馈
     * @param UserFeedback $feedback
     * @throws Exception
     */
    public function createFeedback(UserFeedback $feedback)
    {
        try {
            $conn = $this->getConnection();
            $this->UserFeedbackDb=new UserFeedbackDb($conn);
            $this->UserFeedbackDb->addUserAttention($feedback);
        } catch (Exception $e) {
            throw new Exception('添加反馈异常',Code::FAIL,$e);
        } finally {
            $this->closeLink();
        }
    }

}