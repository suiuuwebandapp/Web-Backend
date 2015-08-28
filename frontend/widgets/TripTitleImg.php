<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/8/25
 * Time : 下午8:04
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\widgets;


use yii\base\Widget;

class TripTitleImg extends Widget{


    public $defaultImg;


    public function init(){
        parent::init();
    }


    public function run(){
        return $this->render("/widget/tripTitleImg",[
            'defaultImg'=>$this->defaultImg
        ]);
    }
}