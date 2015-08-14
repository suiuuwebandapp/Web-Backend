<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/19
 * Time: 下午3:05
 */

namespace frontend\controllers;


use backend\components\Page;
use common\components\RequestValidate;
use frontend\services\TripService;
use yii\web\Controller;

class SController extends Controller {


    public $searchList;


    public function __construct($id, $module = null)
    {
        $rv=new RequestValidate();
        $rv->validate();

        $tripService=new TripService();
        $searchPage=new Page();
        $searchPage->showAll=true;
        $this->searchList=$tripService->getTripDesSearchList($searchPage,null)->getList();
        parent::__construct($id, $module);
    }
}