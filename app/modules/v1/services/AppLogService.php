<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/8/19
 * Time: 下午4:33
 */

namespace app\modules\v1\services;


use app\modules\v1\entity\AppLog;
use app\modules\v1\models\AppLogDb;
use common\models\BaseDb;
use yii\base\Exception;

class AppLogService extends BaseDb{
    public function __construct()
    {

    }
    public function addLog(AppLog $log)
    {
        try {
            $conn = $this->getConnection();
            $appLogDb = new AppLogDb($conn);
            $appLogDb->addLog($log);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }
}