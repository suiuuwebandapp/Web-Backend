<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/6/23
 * Time: 下午4:31
 */

namespace frontend\controllers;


use common\components\Code;
use common\components\LogUtils;
use common\entity\TravelTripService;
use common\entity\WeChatUserInfo;
use frontend\services\PublisherService;
use frontend\services\TripService;
use frontend\services\UserBaseService;
use frontend\services\UserOrderService;
use frontend\services\WeChatService;
use yii\base\Exception;

class WechatUserCenterController extends WController {
    public $layout=false;
    public $enableCsrfValidation=false;
    public $userOrderService ;
    private $tripSer;
    public function __construct($id, $module = null)
    {
        $this->userOrderService = new UserOrderService();
        $this->tripSer=new TripService();
        parent::__construct($id, $module);
    }

    public function actionUserCenter()
    {

        $this->loginValid();
        $userSign=$this->userObj->userSign;
        if(empty($userSign))
        {
            return 'userSign is null';
        }
        try{
            if($this->userObj->isPublisher){
                $this->redirect('/wechat-user-center/my-trip');
            }else
            {

            }
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->redirect('/we-chat/error?str="系统异常"');
        }


    }

    public function actionMyTrip()
    {
        $this->loginValid();
        $userSign=$this->userObj->userSign;
        $publisherService=new PublisherService();
        $createPublisherInfo = $publisherService->findUserPublisherByUserSign($userSign);
        $myList=array();
        if(!empty($createPublisherInfo)){
            $userPublisherId=$createPublisherInfo->userPublisherId;
            $myList=$this->tripSer->getMyTripList($userPublisherId);
        }
        return $this->renderPartial('myTrip',['list'=>$myList,'userObj'=>$this->userObj,'active'=>1,'newMsg'=>0]);
    }

    /**
     *随游详情
     */
    public function actionTripInfo()
    {
        $tripId=\Yii::$app->request->get('id');

    }

    /**
     * 我的订单
     */
    public function actionMyOrder()
    {
        $this->loginValid();
        $userSign=$this->userObj->userSign;

    }

    public function actionTripOrder()
    {
        $this->loginValid();
        $userSign=$this->userObj->userSign;
        $userBaseService = new UserBaseService();
        $userPublisherObj=$userBaseService->findUserPublisherByUserSign($userSign);
        $publisherId=$userPublisherObj->userPublisherId;
        if(empty($publisherId)){
            return $this->redirect('/we-chat/error?str=无效的随友信息');
        }
        $list=$this->userOrderService->getPublisherOrderList($publisherId);
        $newList=$this->userOrderService->getUnConfirmOrderByPublisher($publisherId);
        return $this->renderPartial('tripOrder',['list'=>$list,'newList'=>$newList]);
    }

    public function actionOrderInfo()
    {
        try{
        $orderId=\Yii::$app->request->get('id');
        if(empty($orderId)){
           return $this->redirect('/we-chat/error?str=未知的订单&url=javascript:history.go(-1);');
          }
            $info = $this->userOrderService->findOrderByOrderNumber($orderId);
            $userSer =new UserBaseService();
            $userInfo = $userSer->findBaseInfoBySign($info->userId);
            return $this->renderPartial('orderInfo',['info'=>$info,'userInfo'=>$userInfo]);
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->redirect('/we-chat/error?str="系统异常"');
        }
    }

    /**
     * 确认订单
     */
    public function actionPublisherConfirmOrder()
    {
        $this->loginValid();
        $orderId=trim(\Yii::$app->request->post("orderId", ""));
        try{
            $userSign=$this->userObj->userSign;
            $userBaseService = new UserBaseService();
            $userPublisherObj=$userBaseService->findUserPublisherByUserSign($userSign);
            $publisherId=$userPublisherObj->userPublisherId;
        if(empty($orderId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
        }
        if(empty($publisherId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的随友信息"));
        }

            $this->userOrderService->publisherConfirmOrder($orderId,$publisherId);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 忽略订单
     */
    public function actionPublisherIgnoreOrder()
    {
        $this->loginValid();
        $orderId=trim(\Yii::$app->request->post("orderId", ""));
        $userSign=$this->userObj->userSign;
        $userBaseService = new UserBaseService();
        $userPublisherObj=$userBaseService->findUserPublisherByUserSign($userSign);
        $publisherId=$userPublisherObj->userPublisherId;
        if(empty($orderId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的订单号"));
        }
        if(empty($publisherId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的随友信息"));
        }
        try{
            $this->userOrderService->publisherIgnoreOrder($orderId,$publisherId);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }
}