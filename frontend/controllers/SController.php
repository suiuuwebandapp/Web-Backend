<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/19
 * Time: 下午3:05
 */

namespace frontend\controllers;


use backend\components\Page;
use common\components\Code;
use common\components\RequestValidate;
use frontend\services\TripService;
use frontend\services\UserMessageService;
use yii\web\Controller;

class SController extends Controller {


    public $searchList;
    public $unReadMessageList=null;


    public function __construct($id, $module = null)
    {
        $rv=new RequestValidate();
        $rv->validate();

        if(!\Yii::$app->request->getIsAjax()){

            $tripService=new TripService();
            $searchPage=new Page();
            $searchPage->showAll=true;
            $list=\Yii::$app->redis->get(Code::TRAVEL_TRIP_COUNTRY_CITY_TRIP_COUNT);
            if(empty($list)){
                $this->searchList=$tripService->getTripDesSearchList($searchPage,null)->getList();
                \Yii::$app->redis->set(Code::TRAVEL_TRIP_COUNTRY_CITY_TRIP_COUNT,json_encode($this->searchList));
            }else{
                $this->searchList=json_decode($list,true);
            }

            $currentUser=\Yii::$app->session->get(Code::USER_LOGIN_SESSION);
            if(isset($currentUser)&&(!\Yii::$app->request->getIsAjax())){
                self::initUserMessage();
            }
        }

        parent::__construct($id, $module);
    }



    public function initUserMessage()
    {
        if(isset($this->userObj->userSign)){
        $hasNewMessage=\Yii::$app->redis->get(Code::USER_LOGIN_SESSION_NEW_MESSAGE.$this->userObj->userSign);
        $unReadMessage=\Yii::$app->session->get(Code::USER_LOGIN_SESSION_UN_READ_MESSAGE);
        if(empty($hasNewMessage)||empty($unReadMessage)){
            $userMessageService=new UserMessageService();
            $unReadMessage=$userMessageService->getUnReadMessageList($this->userObj->userSign);
            \Yii::$app->redis->set(Code::USER_LOGIN_SESSION_NEW_MESSAGE.$this->userObj->userSign,"");
            \Yii::$app->session->set(Code::USER_LOGIN_SESSION_UN_READ_MESSAGE,$unReadMessage);
        }
        $this->unReadMessageList=$unReadMessage;
        }

    }
}