<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/14
 * Time: 下午1:28
 */

namespace frontend\controllers;


use common\components\Code;
use common\entity\WeChatOrderList;
use frontend\components\Page;
use frontend\services\WeChatOrderListService;
use yii;
use yii\base\Exception;
class WeChatOrderListController extends WController {

    public $orderListSer;
    public $layout=false;
    public $enableCsrfValidation=false;
    public function __construct($id, $module = null)
    {
        $this->orderListSer = new WeChatOrderListService();
        parent::__construct($id, $module);
    }

    /**
     * 添加订购记录
     */
    public function actionAddOrder()
    {
        $this->loginValid();
        $site=Yii::$app->request->post('site');
        $content=Yii::$app->request->post('content');
        $timeList=Yii::$app->request->post('timeList');
        $userSign=$this->userObj->userSign;
        /*$site='北京';
        $content='随意';
        $timeList='2015-01-02,2015-15-25';
        $userSign='abb760a0ea093d829f7916b2a7a9f3ce';*/
        if(empty($site))
        {
            return json_encode(Code::statusDataReturn(Code::FAIL, "订购地点不能为空"));
        }
        if(empty($content))
        {
            return json_encode(Code::statusDataReturn(Code::FAIL, "订购内容不能为空"));

        }
        if(empty($timeList))
        {
            return json_encode(Code::statusDataReturn(Code::FAIL, "订购时间不能为空"));
        }
        $orderEntity=new WeChatOrderList();
        $orderEntity->wOrderSite=$site;
        $orderEntity->wOrderContent=$content;
        $orderEntity->wOrderTimeList=$timeList;
        $orderEntity->wUserSign=$userSign;
        $this->orderListSer->insertWeChatInfo($orderEntity);
    }

    //得到用户订购列表
    public function actionGetUserOrderList()
    {
        try {
            $this->loginValid();
            $userSign=$this->userObj->userSign;
            $page = new Page(Yii::$app->request);
            $data = $this->orderListSer->getOrderListByUserSign($userSign,$page);
            var_dump($data->getList());
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }

    //得到订购详情
    public function actionGetUserOrderInfo()
    {
        try {
            $this->loginValid();
            $id=Yii::$app->request->post('id');
            $userSign=$this->userObj->userSign;
            $data = $this->orderListSer->getOrderInfoById($id,$userSign);
        }catch (Exception $e)
        {
            $error=$e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$error));
        }
    }

    public function actionIndex()
    {
        return $this->renderPartial('index');
    }

    public function actionOrderManage()
    {
        return $this->renderPartial('index');
    }

}