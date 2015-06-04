<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/6/4
 * Time : 下午2:17
 * Email: zhangxinmailvip@foxmail.com
 */

namespace console\controllers;

use backend\components\Page;
use common\components\Code;
use common\components\OssUpload;
use common\entity\TravelTrip;
use frontend\services\TripService;
use frontend\services\UploadService;
use yii\base\Exception;
use yii\console\Controller;

class TripController extends Controller
{


    public function actionTest()
    {
        echo 1;
    }


    public function actionUploadTitle()
    {

        $tripService = new TripService();
        $uploadService = new UploadService();
        $page = new Page();
        $page->showAll = true;
        $allTrip = $tripService->getList($page, null, null, null, null, null, null, null);
        foreach ($allTrip->getList() as $trip) {
            if ($trip['status'] == TravelTrip::TRAVEL_TRIP_STATUS_DELETE) {
                continue;
            }
            $ext = explode(".", $trip['titleImg']);
            $img = $this->grabImg($trip['titleImg'], "/tmp/" . $trip['tripId'] . "." . $ext[count($ext) - 1]);
            if (!$img) {
                echo "******************" . $trip['tripId'] . "FAIL" . "\n";
                continue;
            }
            $rst = $uploadService->resetImg($img, 550, 500, 2);
            if ($rst['status'] != Code::SUCCESS) {
                return $rst;
            }
            $ossUpload = new OssUpload();
            $rst = $ossUpload->putObject($rst['data'], OssUpload::OSS_SUIUU_TRIP_DIR, basename($rst['data']));
            if ($rst['status'] == Code::SUCCESS) {
                try{
                    $tripService->updateTravelTripTitleImg($trip['tripId'],$rst['data']);
                    echo "--------------" . $trip['tripId'] . "SUCCESS" . "\n";
                    continue;

                }catch (Exception $e){
                    echo "******************" . $trip['tripId'] . "FAIL" . "\n";
                    continue;
                }
            } else {
                echo "******************" . $trip['tripId'] . "FAIL" . "\n";
                continue;
            }
            break;
        }
    }

    public function grabImg($url, $filename = "")
    {
        if ($url == "") return false;

        if ($filename == "") {
            $ext = strrchr($url, ".");
            if ($ext != ".gif" && $ext != ".jpg" && $ext != ".png") return false;
            $filename = date("YmdHis") . $ext;
        }

        ob_start();
        readfile($url);
        $img = ob_get_contents();
        ob_end_clean();
        $size = strlen($img);

        $fp2 = @fopen($filename, "a");
        fwrite($fp2, $img);
        fclose($fp2);

        return $filename;
    }
}