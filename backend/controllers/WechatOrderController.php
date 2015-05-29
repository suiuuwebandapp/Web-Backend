<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/27
 * Time: 下午3:00
 */

namespace backend\controllers;


use backend\components\Page;
use backend\components\TableResult;
use backend\services\SysUserService;
use backend\services\WechatOrderService;
use common\components\Code;
use common\entity\WeChatOrderList;
use Yii;

class WechatOrderController extends CController {


    private $wechatOrderSer;


    public function __construct($id, $module = null)
    {
        $this->wechatOrderSer=new WechatOrderService();
        parent::__construct($id, $module);
    }

    public function actionList()
    {
        return $this->render('list');
    }

    public function actionGetList()
    {
        $page=new Page(Yii::$app->request);
        $page->sortName="wStatus";
        $search=\Yii::$app->request->get("searchText","");
        $status=Yii::$app->request->get('status');
        $isDel=Yii::$app->request->post('del',false);
        $page = $this->wechatOrderSer->getList($page,$search,$status,$isDel);
        $tableResult=new TableResult($page->draw,count($page->getList()),$page->totalCount,$page->getList());
        echo json_encode($tableResult);
    }

    public function actionDeleteOrder()
    {
        $orderNumber=Yii::$app->request->post('orderNumber');
        $this->wechatOrderSer->deleteOrder($orderNumber);
        echo json_encode(Code::statusDataReturn(Code::SUCCESS,"删除成功"));
    }
    public function actionOverOrder()
    {
        $orderNumber=Yii::$app->request->post('orderNumber');
        $this->wechatOrderSer->overOrder($orderNumber);
        echo json_encode(Code::statusDataReturn(Code::SUCCESS,"更新成功"));
    }
    public function actionEdit()
    {
        $orderNumber=Yii::$app->request->get('o');
        $data = $this->wechatOrderSer->getOrderInfo($orderNumber);
        return $this->render("edit",['info'=>$data]);
    }

    public function actionEditOrder()
    {
        $orderNumber=Yii::$app->request->post('orderNumber');
        $money=Yii::$app->request->post('money');
        $rPhone=Yii::$app->request->post('rPhone');
        $wDetails=Yii::$app->request->post('wDetails');

        if(empty($orderNumber)){return json_encode(Code::statusDataReturn(Code::FAIL,"未知订单号"));}
        if(empty($money)){return json_encode(Code::statusDataReturn(Code::FAIL,"未知金额"));}
        if(empty($rPhone)){return json_encode(Code::statusDataReturn(Code::FAIL,"未知用户"));}
        if(empty($wDetails)){return json_encode(Code::statusDataReturn(Code::FAIL,"未知详细信息"));}

        $data = $this->wechatOrderSer->getOrderInfo($orderNumber);
        if(empty($data))
        {
            return json_encode(Code::statusDataReturn(Code::FAIL,"未知订单"));
        }
        if($data['wStatus']>WeChatOrderList::STATUS_PROCESSED)
        {
            return json_encode(Code::statusDataReturn(Code::FAIL,"订单已经处理过"));
        }
        $userSer = new SysUserService();
        $user = $userSer->findUserByPhone($rPhone);
        if(empty($user)||empty($user->userSign))
        {
            return json_encode(Code::statusDataReturn(Code::FAIL,"未知用户"));
        }
        $this->wechatOrderSer->updateOrderInfo($wDetails,$user->userSign,$money,$orderNumber);
        return json_encode(Code::statusDataReturn(Code::SUCCESS,"更新成功"));
    }


}