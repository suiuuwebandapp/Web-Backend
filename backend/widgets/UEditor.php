<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/8/25
 * Time : 下午8:04
 * Email: zhangxinmailvip@foxmail.com
 */

namespace backend\widgets;


use yii\base\Widget;

class UEditor extends Widget{


    public $defaultImg;


    public function init(){
        parent::init();
    }


    public function run(){
        return $this->render("/widget/ueditor",[
        ]);
    }
}