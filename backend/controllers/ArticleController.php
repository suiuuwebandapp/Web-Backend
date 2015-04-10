<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/10
 * Time : 下午1:52
 * Email: zhangxinmailvip@foxmail.com
 */

namespace backend\controllers;


class ArticleController extends CController{


    public function actionList(){

        return $this->render('list');
    }


    public function actionAdd(){
        return $this->render('add');
    }

}