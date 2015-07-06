<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/7/3
 * Time : 下午2:21
 * Email: zhangxinmailvip@foxmail.com
 */

namespace backend\controllers;


use backend\components\Page;
use backend\components\TableResult;
use backend\services\UserAccountService;
use common\components\Code;
use common\components\LogUtils;
use yii\base\Exception;

class UserAccountController extends CController{


    private $userAccountService;


    public function __construct($id, $module = null)
    {
        $this->userAccountService=new UserAccountService();
        parent::__construct($id, $module);
    }



    public function actionToCashList()
    {
        return $this->render("cashList");
    }

    /**
     * 获取用户体现列表
     * @throws \Exception
     * @throws \yii\base\Exception
     */
    public function actionCashList()
    {
        $page=new Page(\Yii::$app->request);
        $search=trim(\Yii::$app->request->get('searchText',''));
        $type=trim(\Yii::$app->request->get('type',''));
        $status=trim(\Yii::$app->request->get('status',''));

        $page=$this->userAccountService->getUserCashRecordList($page,$search,$type,$status);
        $tableResult=new TableResult($page->draw,count($page->getList()),$page->totalCount,$page->getList());
        return json_encode($tableResult);
    }


    /**
     * 获取提现详情
     * @return string
     * @throws Exception
     * @throws \Exception
     */
    public function actionGetCashInfo()
    {
        $cashId=trim(\Yii::$app->request->get('cashId',''));
        $userCashInfo=$this->userAccountService->findUserCashInfoById($cashId);
        return $this->render("cashInfo",[
            'userCashInfo'=>$userCashInfo
        ]);
    }

    /**
     * 更新提现记录
     * @return string
     */
    public function actionUpdateCashInfo()
    {
        $cashId=trim(\Yii::$app->request->post('cashId',''));
        $cashNumber=trim(\Yii::$app->request->post('cashNumber',''));
        $status=trim(\Yii::$app->request->post('status',''));
        try{
            $this->userAccountService->updateUserCashRecord($cashId,$cashNumber,$status);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

}