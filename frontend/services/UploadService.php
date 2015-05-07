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
}