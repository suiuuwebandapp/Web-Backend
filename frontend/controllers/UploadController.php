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
use yii\base\Exception;
use yii\web\Controller;

class UploadController extends Controller{

    public $enableCsrfValidation = false;

    public function __construct($id,$module)
    {
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



    private $maxCardImgSize=1024000;//上传文件大小限制, 单位BYTE
    private $cardImgTypes=[
        'jpg','png','jpeg'
    ];


    /**
     * 上传用户身份证
     * @return array
     */
    public function actionUploadCardImg()
    {
        if(!array_key_exists('Filedata',$_FILES)){
            echo json_encode(['status']);
        }else{
            $result=$this->uploadOssFile($_FILES['Filedata'],$this->maxCardImgSize,$this->cardImgTypes,OssUpload::OSS_SUIUU_CARD_DIR);
            return json_encode($result);
        }
    }


    /**
     * 上传文件
     * @param $file          $_FILES['Filedata']
     * @param $fileMaxSize   2048000（byte）
     * @param $fileTypes     array('jpg','png');
     * @param $fileFolder    ./uploads/image/
     * @return               array(status,data)
     */
    private function uploadOssFile($file,$fileMaxSize,$fileTypes,$fileFolder)
    {
        try{
            if(empty($file))
            {
                return Code::statusDataReturn(Code::FAIL,'file error!');
            }
            //判断上传文件是否存在
            if (!is_uploaded_file($file['tmp_name']))
            {
                return Code::statusDataReturn(Code::FAIL,'no file find');
            }
            //判断文件大小是否大于规定大小
            if (!empty($fileMaxSize)&&($file['size'] >= $fileMaxSize))
            {
                return Code::statusDataReturn(Code::FAIL,'image max size error');
            }
            if(empty($fileFolder))
            {
                return Code::statusDataReturn(Code::FAIL,'file folder is empty');
            }

            $imageSize = getimagesize($file['tmp_name']);
            $fileName     = $file['name'];

            //获得文件扩展名
            $temp_arr = explode(".", $fileName);
            $file_ext = array_pop($temp_arr);
            $file_ext = trim($file_ext);
            $file_ext = strtolower($file_ext);
            //判断图片文件的格式
            if (!in_array($file_ext, $fileTypes))
            {
                return Code::statusDataReturn(Code::FAIL,'image type error');
            }
            
            //新文件名
            $new_file_name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $file_ext;

            $ossUpload=new OssUpload();
            $rst=$ossUpload->putObject($file['tmp_name'],$fileFolder,$new_file_name);
            return $rst;
        }catch (Exception $e){
            return Code::statusDataReturn(Code::FAIL,$e->getMessage());
        }
    }
}