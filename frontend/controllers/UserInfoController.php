<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/4
 * Time : 下午7:17
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\controllers;


use common\entity\UserBase;
use frontend\services\CountryService;
use common\components\Code;
use common\components\OssUpload;
use frontend\services\UserBaseService;
use yii\base\Exception;

class UserInfoController extends CController{

    public function __construct($id, $module = null)
    {
        $this->userBaseService = new UserBaseService();
        parent::__construct($id, $module);
    }


    public function actionIndex()
    {
        $countryService = new CountryService();
        $countryList = $countryService->getCountryList();

        return $this->render("info",[
            'countryList'=>$countryList
        ]);
    }


    public function actionUpdateUserInfo()
    {
        $userId = $this->userObj->userId;
        $sex = trim(\Yii::$app->request->post('sex',UserBase::USER_SEX_SECRET));
        $nickname = trim(\Yii::$app->request->post('nickname'));
        $birthday = trim(\Yii::$app->request->post('birthday'));
        $intro = trim(\Yii::$app->request->post('intro'));
        $info = trim(\Yii::$app->request->post('info'));
        $countryId = \Yii::$app->request->post('countryId');
        $cityId = \Yii::$app->request->post('cityId');
        $lon = \Yii::$app->request->post('lon');
        $lat = \Yii::$app->request->post('lat');
        $profession = trim(\Yii::$app->request->post('profession'));

        if(empty($nickname)||strlen($nickname)>30){
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"昵称格式不正确"));
            return;
        }
        if(empty($countryId)){
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"请选择居住地国家"));
            return;
        }
        if(empty($cityId)){
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"请选择居住地城市"));
            return;
        }
        try{
            $userInfo=$this->userBaseService->findUserById($userId);
            $userInfo->sex=$sex;
            $userInfo->nickname=$nickname;
            $userInfo->birthday=$birthday;
            $userInfo->intro=$intro;
            $userInfo->info=$info;
            $userInfo->countryId=$countryId;
            $userInfo->cityId=$cityId;
            $userInfo->lon=$lon;
            $userInfo->lat=$lat;
            $userInfo->profession=$profession;

            $this->userBaseService->updateUserBase($userInfo);
            $this->refreshUserInfo();

            echo json_encode(Code::statusDataReturn(Code::SUCCESS));

        }catch (Exception $e) {
            throw $e;
            echo json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }


    public function actionChangeUserHeadImg()
    {
        $userId = $this->userObj->userId;
        $selectorX = \Yii::$app->request->post('x');
        $selectorY = \Yii::$app->request->post('y');
        $viewPortW = \Yii::$app->request->post('w');
        $viewPortH = \Yii::$app->request->post('h');
        $rotate = \Yii::$app->request->post('rotate');
        $source = \Yii::$app->request->post('src');
        $extArr=explode(".", $source);
        $ext = end($extArr);
        $pWidth = \Yii::$app->request->post('pWidth');
        $pHeight = \Yii::$app->request->post('pHeight');
        $rotate = 360 - $rotate;
        try {
            if ($ext == "png") {
                $img = imagecreatefrompng($source);
            }
            if ($ext == "jpg") {
                $img = imagecreatefromjpeg($source);
            }

            list($width, $height) = getimagesize($source);
            $newImg = imagecreatetruecolor($pWidth, $pHeight);
            //把图片扩充到300*300
            imagecopyresampled($newImg, $img, 0, 0, 0, 0, $pWidth, $pHeight, $width, $height);

            $resultImg = imagecreatetruecolor($viewPortW, $viewPortH);

            //裁剪
            imagecopy($resultImg, $newImg, 0, 0, $selectorX, $selectorY, $viewPortW, $viewPortH);

            if ($ext == "png") {
                imagesavealpha($resultImg, true);
            }

            if ($ext == "png") {
                $white = imagecolorallocatealpha($resultImg, 0, 0, 0, 127);
                imagealphablending($resultImg, false);
                imagefill($resultImg, 0, 0, $white);
                imagefill($resultImg, $viewPortW, 0, $white);
                imagefill($resultImg, 0, $viewPortH, $white);
                imagefill($resultImg, $viewPortW, $viewPortH, $white);
            }

            //旋转
            if ($rotate != 0 && $rotate != 360) {
                $resultImg = imagerotate($resultImg, $rotate, 0);
            }

            //获得文件扩展名
            $fileFolder = UploadController::LOCAL_IMAGE_DIR; //图片目录路径

            $fileFolder .= date("Ymd");
            if (!file_exists($fileFolder)) { // 判断存放文件目录是否存在
                mkdir($fileFolder, 0777, true);
            }
            $new_file_name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $ext;
            $picName = $fileFolder . "/" . $new_file_name;
            if ($ext == "png") {
                header('Content-type: image/png');
                imagepng($resultImg, $picName);
            }
            if ($ext == "jpg") {
                header('Content-type: image/jpg');
                imagejpeg($resultImg, $picName);
            }

            imagedestroy($resultImg);

            $ossUpload=new OssUpload();
            $rst=$ossUpload->putObject($picName,OssUpload::OSS_SUIUU_HEAD_DIR,$new_file_name);

            if($rst['status']==Code::SUCCESS){
                unlink($picName);
                $this->userBaseService->updateUserHeadImg($userId, $rst['data']);
                $this->refreshUserInfo();
                echo json_encode(Code::statusDataReturn(Code::SUCCESS, $rst['data']));
            }else{
                echo json_encode(Code::statusDataReturn(Code::FAIL));
            }

        } catch (Exception $e) {
            echo json_encode(Code::statusDataReturn(Code::FAIL, $e));
        }
    }

}