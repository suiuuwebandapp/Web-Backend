<?php
/**
 * Created by PhpStorm.
 * User: XiMing
 * Date: 15-1-12
 * Time: 下午10:26
 */
class FileUtil{
    static function SaveFile($filepath,$conteng){
        $file = fopen($filepath,"a");
        fwrite($file,$conteng);
        fclose($file);
    }

    static function GetFile($filepath){
        return file_get_contents($filepath);
    }
}