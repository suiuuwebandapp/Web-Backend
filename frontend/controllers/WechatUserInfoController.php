<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/8/27
 * Time: 上午11:14
 */

namespace frontend\controllers;

use common\components\LogUtils;
use frontend\components\Page;
use frontend\services\PublisherService;
use frontend\services\TripService;
use frontend\services\UserAttentionService;
use frontend\services\UserBaseService;
use Yii;
use yii\base\Exception;

class WechatUserInfoController extends WController {
    public $layout=false;
    public $enableCsrfValidation=false;
    public $userOrderService =null ;
    public $userBaseSer=null;
    public $tripSer=null;
    public function __construct($id, $module = null)
    {
        $this->userBaseSer=new UserBaseService();
        $this->tripSer=new TripService();
        parent::__construct($id, $module);
    }

    public function actionSetting()
    {
        $login = $this->loginValid();
        if(!$login){
            return $this->redirect(['/we-chat/login']);
        }
        $userSign=$this->userObj->userSign;
        $userInfo = $this->userBaseSer->findUserByUserSignArray($userSign);
        return $this->renderPartial('setting',["userInfo"=>$userInfo,'userObj'=>$this->userObj,'active'=>8,'newMsg'=>0]);
    }

    public function actionSupply()
    {
        $this->loginValid();
        return $this->renderPartial('supply',['userObj'=>$this->userObj,'active'=>8,'newMsg'=>0]);
    }
    public function actionContact()
    {
        $this->loginValid();
        return $this->renderPartial('contact',['userObj'=>$this->userObj,'active'=>8,'newMsg'=>0]);
    }
    public function actionNotice()
    {
        $this->loginValid();
        return $this->renderPartial('notice',['userObj'=>$this->userObj,'active'=>8,'newMsg'=>0]);
    }

    public function actionUserInfo()
    {
        $login = $this->loginValid();
        /*if(!$login){
            return $this->redirect(['/we-chat/login']);
        }*/
        try{
            $userSign = Yii::$app->request->get("userSign");
            $page=new Page();
            $page->sortType="desc";
            $page->sortName="attentionId";
            $AttentionService = new UserAttentionService();
            $data = $AttentionService->getUserCollectionTravel($userSign, $page);
            $userInfo = $this->userBaseSer->findUserByUserSignArray($userSign);
            if(empty($userInfo))
            {
                return $this->redirect('/we-chat/error?str="未知用户"');
            }
            $publisherService=new PublisherService();
            $createPublisherInfo = $publisherService->findUserPublisherByUserSign($userSign);
            $myList=array();
            if(!empty($createPublisherInfo)){
                $userPublisherId=$createPublisherInfo->userPublisherId;
                $myList=$this->tripSer->getMyTripList($userPublisherId);
            }
            return $this->renderPartial("userInfo",['attention'=>$data,'userInfo'=>$userInfo,'tripList'=>$myList,'userObj'=>$this->userObj,'active'=>8,'newMsg'=>0]);
         }catch (Exception $e){
            LogUtils::log($e);
            return $this->redirect('/we-chat/error?str="系统异常"');
            }

    }

    public function actionTripList()
    {
        $this->loginValid();
        $userSign = Yii::$app->request->get("userSign");
        $publisherService=new PublisherService();
        $createPublisherInfo = $publisherService->findUserPublisherByUserSign($userSign);
        $myList=array();
        if(!empty($createPublisherInfo)){
            $userPublisherId=$createPublisherInfo->userPublisherId;
            $myList=$this->tripSer->getMyTripList($userPublisherId);
        }
        return $this->renderPartial("tripList",['tripList'=>$myList,'userObj'=>$this->userObj,'active'=>1,'newMsg'=>0]);
    }

    public function actionAttentionList()
    {
        $this->loginValid();
        $userSign = Yii::$app->request->get("userSign");
        $page=new Page();
        $page->sortType="desc";
        $page->sortName="attentionId";
        $page->showAll=true;
        $AttentionService = new UserAttentionService();
        $data = $AttentionService->getUserCollectionTravel($userSign, $page);
        return $this->renderPartial("attentionList",['list'=>$data,'userObj'=>$this->userObj,'active'=>1,'newMsg'=>0]);
    }

}