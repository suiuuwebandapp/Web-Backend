<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/14
 * Time : 上午10:52
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\controllers;


use common\components\Code;
use common\components\LogUtils;
use common\components\OssUpload;
use common\entity\UserPhoto;
use common\entity\UserPublisher;
use common\models\BaseDb;
use frontend\services\UploadService;
use frontend\services\UserBaseService;
use yii\base\Exception;
use yii\web\Controller;

class UploadController extends UnCController
{

    public $enableCsrfValidation = false;

    private $uploadService;

    public function __construct($id, $module)
    {
        $this->uploadService = new UploadService();
        parent::__construct($id, $module);
    }
    /******************************************************************************
     *
     * 参数说明:
     * $max_file_size  : 上传文件大小限制, 单位BYTE
     * $destination_folder : 上传文件路径
     * $watermark   : 是否附加水印(1为加水印,其他为不加水印);
     *
     * 使用说明:
     * 1. 将PHP.INI文件里面的"extension=php_gd2.so"一行前面的;号去掉,因为我们要用到GD库;
     * 2. 将extension_dir =改为你的php_gd2.so所在目录;
     ******************************************************************************/

    //上传文件类型列表
    const LOCAL_IMAGE_DIR = './uploads/image/';


    private $maxCardImgSize = 2048000;//上传文件大小限制, 单位BYTE
    private $cardImgTypes = [
        'jpg', 'png', 'jpeg'
    ];

    private $maxHeadImgSize = 1024000;
    private $headImgTypes = [
        'jpg', 'png', 'jpeg'
    ];

    private $maxTripImgSize = 2048000;
    private $tripImgTypes = [
        'jpg', 'png', 'jpeg'
    ];


    private $maxUserPhotoSize=2048000;
    private $userPhotoTypes = [
        'jpg', 'png', 'jpeg'
    ];

    /**
     * ./带代表根目录
     * @var string
     */
    private $localDir = './uploads/image/';

    /**
     * 上传用户身份证
     * @return array
     */
    public function actionUploadCardImg()
    {
        if (!array_key_exists('Filedata', $_FILES)) {
            return json_encode(Code::statusDataReturn(Code::FAIL));
        } else {
            $result = $this->uploadService->uploadOssFile($_FILES['Filedata'], $this->maxHeadImgSize, $this->headImgTypes, OssUpload::OSS_SUIUU_CARD_DIR);
            return json_encode($result);
        }
    }

    /**
     * 上传随游图片
     * @return string
     */
    public function actionUploadTripImg()
    {
        if (!array_key_exists('Filedata', $_FILES)) {
            return json_encode(Code::statusDataReturn(Code::FAIL));
        } else {
            $result = $this->uploadService->uploadLocalImg($_FILES['Filedata'], $this->maxTripImgSize, $this->tripImgTypes, $this->localDir);
            return json_encode($result);
        }
    }

    /**
     * 更新随友证件
     * @return string
     */
    public function actionUploadCardImgByUser()
    {
        if (!array_key_exists('Filedata', $_FILES)) {
            return json_encode(Code::statusDataReturn(Code::FAIL));
        } else {
            $result = $this->uploadService->uploadOssFile($_FILES['Filedata'], $this->maxHeadImgSize, $this->headImgTypes, OssUpload::OSS_SUIUU_CARD_DIR);
            if ($result['status'] == Code::SUCCESS) {
                try {
                    if ( $this->userObj!= null) {
                        $userBaseService = new UserBaseService();
                        $userBaseService->saveUserCard($this->userObj->userSign,$result['data']);
                        $result = Code::statusDataReturn(Code::SUCCESS);
                    } else {
                        $result = Code::statusDataReturn(Code::FAIL);
                    }
                } catch (Exception $e) {
                    LogUtils::log($e);
                    $result = Code::statusDataReturn(Code::FAIL);
                }
            }
            return json_encode($result);
        }
    }

    /**
     * 上传用户头像（上传到本地）
     * @return string
     */
    public function actionUploadHeadImg()
    {
        if (!array_key_exists('Filedata', $_FILES)) {
            return json_encode(Code::statusDataReturn(Code::FAIL));
        } else {
            $result = $this->uploadService->uploadLocalImg($_FILES['Filedata'], $this->maxCardImgSize, $this->cardImgTypes, $this->localDir);
            return json_encode($result);
        }
    }

    /**
     * 上传随游标题图像 压缩
     * @return array|string
     */
    public function actionUploadTripTitleImg()
    {
        if (!array_key_exists('Filedata', $_FILES)) {
            return json_encode(Code::statusDataReturn(Code::FAIL));
        } else {
            $img_info = getimagesize($_FILES['Filedata']['tmp_name']);
            $w=$img_info[0];
            $h=$img_info[1];
            $type=$img_info['mime'];
            //判断是否大于900 尺寸  如果大于 或者是PNG 的图片 进行压缩处理

            $result = $this->uploadService->uploadLocalImg($_FILES['Filedata'], $this->maxTripImgSize, $this->tripImgTypes, $this->localDir);
            if ($result['status'] == Code::SUCCESS) {
                $newWidth=$w;
                $newHeight=$h;
                if($w>900){
                    $newWidth=900;
                    $newHeight=(900/$w)*$h;
                }
                $rst=$this->uploadService->resetImg($result['data'],$newWidth,$newHeight,2);
                if($rst['status']==Code::SUCCESS){
                    $ext=explode("/",$type)[1];
                    $new_file_name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $ext;
                    $ossUpload=new OssUpload();
                    $rst=$ossUpload->putObject($rst['data'],OssUpload::OSS_SUIUU_TRIP_DIR,$new_file_name);
                    return json_encode($rst);
                }
                return json_encode($rst);
            }else{
                return json_encode($result);
            }

        }
    }

    /**
     * 截取随游并更新
     * @return string
     */
    public function actionCutTripImg()
    {
        $selectorX = \Yii::$app->request->post('x');
        $selectorY = \Yii::$app->request->post('y');
        $viewPortW = \Yii::$app->request->post('w');
        $viewPortH = \Yii::$app->request->post('h');
        $source = \Yii::$app->request->post('src');

        $new_file_name =null;
        $fileFlag=false;
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $source, $result)){
            $type = $result[2];
            $new_file_name= date("YmdHis") . '_' . rand(10000, 99999) . '.' . $type;
            $new_file = self::LOCAL_IMAGE_DIR.$new_file_name;
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $source)))){
                //echo '新文件保存成功：', $new_file;
                $fileFlag=true;
                $source=$new_file;
            }
        }
        if(!$fileFlag){
            return Code::statusDataReturn(Code::FAIL);
        }
        $extArr=explode(".", $source);
        $ext = end($extArr);
        $pWidth = \Yii::$app->request->post('pWidth');
        $pHeight = \Yii::$app->request->post('pHeight');
        try {
            if ($ext == "png") {
                $img = imagecreatefrompng($source);
            }
            if ($ext == "jpg"||$ext == "jpeg") {
                $img = imagecreatefromjpeg($source);
            }

            list($width, $height) = getimagesize($source);
            if($pWidth==0){
                $scale=$pHeight/$height;
            }else{
                $scale=$pWidth/$width;
            }

            $viewPortW=$viewPortW/$scale;
            $viewPortH=$viewPortH/$scale;
            $selectorX=$selectorX/$scale;
            $selectorY=$selectorY/$scale;

            $resultImg = imagecreatetruecolor($viewPortW, $viewPortH);

            //裁剪
            imagecopy($resultImg, $img, 0, 0, $selectorX, $selectorY, $viewPortW, $viewPortH);

//            if ($ext == "png") {
//                imagesavealpha($resultImg, true);
//            }
//
//            if ($ext == "png") {
//                $white = imagecolorallocatealpha($resultImg, 0, 0, 0, 127);
//                imagealphablending($resultImg, false);
//                imagefill($resultImg, 0, 0, $white);
//                imagefill($resultImg, $viewPortW, 0, $white);
//                imagefill($resultImg, 0, $viewPortH, $white);
//                imagefill($resultImg, $viewPortW, $viewPortH, $white);
//            }


            //缩放
            $showWidth=820;
            $showHeight=534;
            $image=imagecreatetruecolor($showWidth, $showHeight);

            //关键函数，参数（目标资源，源，目标资源的开始坐标x,y, 源资源的开始坐标x,y,目标资源的宽高w,h,源资源的宽高w,h）
            imagecopyresampled($image, $resultImg, 0, 0, 0, 0, $showWidth, $showHeight, $viewPortW, $viewPortH);
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
                imagepng($image, $picName);
            }
            if ($ext == "jpg"||$ext == "jpeg") {
                header('Content-type: image/jpg');
                imagejpeg($image, $picName);
            }

            imagedestroy($resultImg);

            $ossUpload=new OssUpload();
            $rst=$ossUpload->putObject($picName,OssUpload::OSS_SUIUU_HEAD_DIR,$new_file_name);

            if($rst['status']==Code::SUCCESS){
                unlink($picName);
                return json_encode(Code::statusDataReturn(Code::SUCCESS, $rst['data']));
            }else{
                return json_encode(Code::statusDataReturn(Code::FAIL));
            }

        } catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL, $e));
        }
    }

    /**
     * 上传用户相册
     */
    public function actionUploadUserPhoto()
    {
        if (!array_key_exists('Filedata', $_FILES)) {
            return json_encode(Code::statusDataReturn(Code::FAIL));
        } else {
            $result = $this->uploadService->uploadOssFile($_FILES['Filedata'], $this->maxUserPhotoSize, $this->userPhotoTypes, OssUpload::OSS_SUIUU_USER_PHOTO);
            if($result['status']==Code::SUCCESS){
                $userPhoto=new UserPhoto();
                $userPhoto->url=$result['data'];
                $userPhoto->userId=$this->userObj->userSign;
                $userPhoto->createTime=BaseDb::DB_PARAM_NOW;

                try{
                    $userBaseService=new UserBaseService();
                    $userPhoto=$userBaseService->addUserPhoto($userPhoto);
                    return json_encode(Code::statusDataReturn(Code::SUCCESS,$userPhoto));
                }catch (Exception $e){
                    return json_encode(Code::statusDataReturn(Code::FAIL));
                }
            }
            return json_encode($result);
        }
    }


}

