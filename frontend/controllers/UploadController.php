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
use common\components\OssUpload;
use frontend\services\UploadService;
use yii\base\Exception;
use yii\web\Controller;

class UploadController extends Controller{

    public $enableCsrfValidation = false;

    private $uploadService;

    public function __construct($id,$module)
    {
        $this->uploadService=new UploadService();
        parent::__construct($id, $module);
    }
    /******************************************************************************

    参数说明:
    $max_file_size  : 上传文件大小限制, 单位BYTE
    $destination_folder : 上传文件路径
    $watermark   : 是否附加水印(1为加水印,其他为不加水印);

    使用说明:
    1. 将PHP.INI文件里面的"extension=php_gd2.so"一行前面的;号去掉,因为我们要用到GD库;
    2. 将extension_dir =改为你的php_gd2.so所在目录;
     ******************************************************************************/

    //上传文件类型列表
    const LOCAL_IMAGE_DIR='./uploads/image/';


    private $maxCardImgSize=2048000;//上传文件大小限制, 单位BYTE
    private $cardImgTypes=[
        'jpg','png','jpeg'
    ];

    private $maxHeadImgSize=1024000;
    private $headImgTypes=[
        'jpg','png','jpeg'
    ];

    private $localDir='./uploads/image/';

    /**
     * 上传用户身份证
     * @return array
     */
    public function actionUploadCardImg()
    {
        if(!array_key_exists('Filedata',$_FILES)){
            echo json_encode(['status']);
        }else{
            $result=$this->uploadService->uploadOssFile($_FILES['Filedata'],$this->maxHeadImgSize,$this->headImgTypes,OssUpload::OSS_SUIUU_CARD_DIR);
            return json_encode($result);
        }
    }

    public function actionUploadHeadImg()
    {
        if(!array_key_exists('Filedata',$_FILES)){
            echo json_encode(['status']);
        }else{
            $result=$this->uploadService->uploadLocalImg($_FILES['Filedata'],$this->maxCardImgSize,$this->cardImgTypes,$this->localDir);
            return json_encode($result);
        }
    }



}