<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/8/19
 * Time: 下午4:51
 */

namespace app\modules\v1\services;


use app\modules\v1\entity\AppVersion;
use app\modules\v1\models\AppVersionDb;
use common\models\BaseDb;
use yii\base\Exception;

class AppVersionService extends BaseDb{
    public function __construct()
    {

    }
    public function addVersion(AppVersion $appVersion)
    {
        try {
            $conn = $this->getConnection();
            $appLogDb = new AppVersionDb($conn);
            return $appLogDb->addVersion($appVersion);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }
    public function getVersion(AppVersion $appVersion)
    {
        try {
            $conn = $this->getConnection();
            $appLogDb = new AppVersionDb($conn);
            return $appLogDb->getVersion($appVersion);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }
}