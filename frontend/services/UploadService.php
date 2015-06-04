<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/6
 * Time : 下午9:11
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\services;


use common\components\Code;
use common\components\OssUpload;
use yii\base\Exception;

class UploadService {


    /**
     * 上传文件
     * @param $file          $_FILES['Filedata']
     * @param $fileMaxSize   2048000（byte）
     * @param $fileTypes     array('jpg','png');
     * @param $fileFolder    ./uploads/image/
     * @return               array(status,data)
     */
    public function uploadOssFile($file,$fileMaxSize,$fileTypes,$fileFolder)
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


    /**
     * 上传文件到本地
     * @param $file
     * @param $fileMaxSize
     * @param $fileTypes
     * @param $fileFolder
     * @return array
     */
    public function uploadLocalImg($file,$fileMaxSize,$fileTypes,$fileFolder)
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
            if (!file_exists($fileFolder))
            { // 判断存放文件目录是否存在
                mkdir($fileFolder, 0777, true);
            }
            //判断图片文件的格式
            if (!in_array($file_ext, $fileTypes))
            {
                return Code::statusDataReturn(Code::FAIL,'image type error');
            }

            //新文件名
            $new_file_name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $file_ext;

            $picName   = $fileFolder . "/" . $new_file_name;
            if (!move_uploaded_file($file['tmp_name'], $picName))
            {
                return Code::statusDataReturn(Code::FAIL,"存储图片错误");
            } else
            {
                //图片处理完毕存库
                @chmod($picName, 0777);
                return Code::statusDataReturn(Code::SUCCESS,$picName);
            }
        }catch (Exception $e){
            return Code::statusDataReturn(Code::FAIL,$e->getMessage());
        }
    }




    /**
     *
     * @param $Image    //需要调整的图片(含路径)
     * @param int $Dw   //调整时最大宽度;缩略图时的绝对宽度
     * @param int $Dh   //调整时最大高度;缩略图时的绝对高度
     * @param int $Type //1,调整尺寸; 2,生成缩略图
     * @return array
     */
    public function resetImg($Image, $Dw = 550, $Dh = 500, $Type = 1)
    {
        IF (!file_exists($Image)) {
            return Code::statusDataReturn(Code::FAIL);
        }
        //如果需要生成缩略图,则将原图拷贝一下重新给$Image赋值
        IF ($Type != 1) {
            $tempArr=explode('.',$Image);
            $tempArr[count($tempArr)-2].='_reset';
            $newImg=implode('.',$tempArr);
            Copy($Image, $newImg);
            $Image = $newImg;
        }
        //取得文件的类型,根据不同的类型建立不同的对象
        $ImgInfo = getimagesize($Image);
        switch ($ImgInfo[2]) {
            case 1:
                $Img = @imagecreatefromgif($Image);
                break;
            case 2:
                $Img = @imagecreatefromjpeg($Image);
                break;
            case 3:
                $Img = @imagecreatefrompng($Image);
                break;
        }
        //如果对象没有创建成功,则说明非图片文件
        IF (empty($Img)) {
            //如果是生成缩略图的时候出错,则需要删掉已经复制的文件
            IF ($Type != 1) {
                unlink($Image);
            }
            return Code::statusDataReturn(Code::FAIL);
        }
        //如果是执行调整尺寸操作则
        if ($Type == 1) {
            $w = imagesx($Img);
            $h = imagesy($Img);
            $width = $w;
            $height = $h;
            if ($width > $Dw) {
                $Par = $Dw / $width;
                $width = $Dw;
                $height = $height * $Par;
                if ($height > $Dh) {
                    $Par = $Dh / $height;
                    $height = $Dh;
                    $width = $width * $Par;
                }
            } else if ($height > $Dh) {
                $Par = $Dh / $height;
                $height = $Dh;
                $width = $width * $Par;
                if ($width > $Dw) {
                    $Par = $Dw / $width;
                    $width = $Dw;
                    $height = $height * $Par;
                }
            }
            $nImg = imagecreatetruecolor($width, $height);   //新建一个真彩色画布
            imagecopyresampled($nImg, $Img, 0, 0, 0, 0, $width, $height, $w, $h);//重采样拷贝部分图像并调整大小
            imagejpeg($nImg, $Image);     //以JPEG格式将图像输出到浏览器或文件
            return Code::statusDataReturn(Code::SUCCESS,$Image);
            //如果是执行生成缩略图操作则
        } else {
            $w = imagesx($Img);
            $h = imagesy($Img);
            $width = $w;
            $height = $h;
            $nImg = imagecreatetruecolor($Dw, $Dh);
            if ($h / $w > $Dh / $Dw) { //高比较大
                $width = $Dw;
                $height = $h * $Dw / $w;
                $IntNH = $height - $Dh;
                imagecopyresampled($nImg, $Img, 0, -$IntNH / 1.8, 0, 0, $Dw, $height, $w, $h);
            } else {   //宽比较大
                $height = $Dh;
                $width = $w * $Dh / $h;
                $IntNW = $width - $Dw;
                imagecopyresampled($nImg, $Img, -$IntNW / 1.8, 0, 0, 0, $width, $Dh, $w, $h);
            }
            imagejpeg($nImg, $Image);
            return Code::statusDataReturn(Code::SUCCESS,$Image);
        }
    }


}