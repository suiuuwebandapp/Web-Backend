<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/7/23
 * Time: 下午5:09
 */

namespace app\modules\v1\controllers;


use common\components\Code;
use common\components\LogUtils;
use app\modules\v1\entity\UserAccount;
use app\modules\v1\entity\UserAccountRecord;
use app\components\Page;
use app\modules\v1\services\UserAccountService;
use yii\base\Exception;

class AppAccountController extends AController
{
    private $userAccountService;

    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        $this->userAccountService =new UserAccountService();
    }
    /**
     * 获取用户账户信息
     * @return string
     */
    public function actionAccountList()
    {
        $this->loginValid();
        $page=new Page(\Yii::$app->request);
        try{
            $typeList=[UserAccountRecord::USER_ACCOUNT_RECORD_TYPE_TRIP_DIVIDED_INTO,UserAccountRecord::USER_ACCOUNT_RECORD_TYPE_OTHER,UserAccountRecord::USER_ACCOUNT_RECORD_TYPE_TRIP_SERVER];
            $page=$this->userAccountService->getUserAccountRecordList($page,$this->userObj->userSign,null,null,implode(",",$typeList));
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,array('list'=>$page->getList(),'msg'=>$page)));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }
    }


    /**
     * 用户绑定支付宝账号
     * @return string
     */
    public function actionBindAlipay()
    {
        $this->loginValid();
        $account=\Yii::$app->request->post("account");
        $name=\Yii::$app->request->post("name");

        if(empty($account)){
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"Account Is Not Empty"));
            exit;
        }
        if(empty($name)){
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"Name Is Not Empty"));
            exit;
        }
        try{
            $userAccount=new UserAccount();
            $userAccount->account=$account;
            $userAccount->userId=$this->userObj->userSign;
            $userAccount->username=$name;
            $userAccount->type=UserAccount::USER_ACCOUNT_TYPE_ALIPAY;
            //判断是否绑定过Alipay
            $id = $this->userAccountService->addUserAccount($userAccount);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$id));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 绑定微信账号
     * @return mixed|string
     */
    public function actionBindWechat()
    {
        $this->loginValid();
        $name=\Yii::$app->request->post("name");
        if(empty($name)){
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"Name Is Not Empty"));
            exit;
        }
        $openId=\Yii::$app->request->post("openId");
        if(empty($openId)){
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"OpenId Is Not Empty"));
            exit;
        }
        try{
            $userAccount=new UserAccount();
            $userAccount->account=$openId;
            $userAccount->userId=$this->userObj->userSign;
            $userAccount->username=$name;
            $userAccount->type=UserAccount::USER_ACCOUNT_TYPE_WECHAT;

            //判断是否绑定过Alipay
            $id = $this->userAccountService->addUserAccount($userAccount);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$id));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }

    }

    /**
     * 得到关联账户列表
     * @return mixed|string
     */
    public function actionGetUserAccountList()
    {

        try{
            $this->loginValid();
            $userSign = $this->userObj->userSign;
            $rst  = $this->userAccountService->getUserAccountList($userSign);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$rst));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }

    }


    public function actionDrawMoney()
    {
        $this->loginValid();
        $accountId=\Yii::$app->request->post("accountId");
        $money=\Yii::$app->request->post("money");

        if(empty($accountId)){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"AccountId Is Not Empty"));
        }
        if(empty($money)){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"Money Is Not Empty"));
        }
        if(!is_numeric($money)){
            return  $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"Invalid Money"));
        }
        if($money<1){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"Invalid Money"));
        }

        $balance=$this->userObj->balance;
        if($money>$balance){
            return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"No Enough Balance"));
        }
        try{
            $this->userAccountService->addUserCashApply($money,$accountId,$this->userObj->userSign);
            $this->appRefreshUserInfo();
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$this->userObj));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }

    }


    public function actionDeleteUserAccount()
    {

        try{
            $this->loginValid();
            $accountId=\Yii::$app->request->post("accountId");
            if(empty($accountId)){
                return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR,"AccountId Is Not Empty"));
            }
            $userSign = $this->userObj->userSign;
             $this->userAccountService->deleteUserAccount($userSign,$accountId);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL));
        }

    }
}