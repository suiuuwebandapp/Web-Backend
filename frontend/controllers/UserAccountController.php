<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/6/23
 * Time : 下午5:14
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\controllers;


use backend\components\Page;
use common\components\Code;
use common\components\Common;
use common\components\LogUtils;
use common\components\PageResult;
use common\entity\UserAccount;
use common\entity\UserAccountRecord;
use frontend\interfaces\WechatInterface;
use frontend\services\UserAccountService;
use frontend\services\UserBaseService;
use frontend\services\UserOrderService;
use yii\base\Exception;

class UserAccountController extends CController {


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
    public function actionList()
    {
        $page=new Page(\Yii::$app->request);
        try{
            $typeList=[UserAccountRecord::USER_ACCOUNT_RECORD_TYPE_TRIP_DIVIDED_INTO,UserAccountRecord::USER_ACCOUNT_RECORD_TYPE_OTHER,UserAccountRecord::USER_ACCOUNT_RECORD_TYPE_TRIP_SERVER];
            $page=$this->userAccountService->getUserAccountRecordList($page,$this->userObj->userSign,null,null,implode(",",$typeList));
            $pageResult=new PageResult($page);
            $pageHtml=Common::pageHtml($pageResult->currentPage,$pageResult->pageSize,$pageResult->totalCount);
            $pageResult->pageHtml=$pageHtml;
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$pageResult));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }


    /**
     * 获取用户账户历史
     * @return string
     */
    public function actionHistoryList()
    {
        $year=\Yii::$app->request->post("year");
        $month=\Yii::$app->request->post("month");
        $type=\Yii::$app->request->post("type","");

        $page=new Page(\Yii::$app->request);
        try{
            if($month<10){
                $month="0".$month;
            }
            $recordTime=$year."-".$month;
            $page=$this->userAccountService->getUserAccountRecordList($page,$this->userObj->userSign,$recordTime,$recordTime,$type);
            $pageResult=new PageResult($page);
            $pageHtml=Common::pageHtml($pageResult->currentPage,$pageResult->pageSize,$pageResult->totalCount);
            $pageResult->pageHtml=$pageHtml;
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$pageResult));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }


    /**
     * 用户绑定支付宝账号
     * @return string
     */
    public function actionBindAlipay()
    {
        $account=\Yii::$app->request->post("account");
        $name=\Yii::$app->request->post("name");

        if(empty($account)){
            return json_decode(Code::statusDataReturn(Code::PARAMS_ERROR,"Account Is Not Empty"));
        }
        if(empty($name)){
            return json_decode(Code::statusDataReturn(Code::PARAMS_ERROR,"Name Is Not Empty"));
        }
        try{
            $userAccount=new UserAccount();
            $userAccount->account=$account;
            $userAccount->userId=$this->userObj->userSign;
            $userAccount->username=$name;
            $userAccount->type=UserAccount::USER_ACCOUNT_TYPE_ALIPAY;

            //判断是否绑定过Alipay
            $this->userAccountService->addUserAccount($userAccount);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    /**
     * 绑定微信账号
     * @return mixed|string
     */
    public function actionBindWechat()
    {
        $name=\Yii::$app->request->post("name");
        if(empty($name)){
            return json_decode(Code::statusDataReturn(Code::PARAMS_ERROR,"Name Is Not Empty"));
        }
        $weiXinAccount=\Yii::$app->getSession()->get(Code::USER_WECHAT_ACCOUNT);
        $openId=$weiXinAccount['openId'];
        if(empty($openId)){
            return json_decode(Code::statusDataReturn(Code::PARAMS_ERROR,"OpenId Is Not Empty"));
        }
        try{
            $userAccount=new UserAccount();
            $userAccount->account=$openId;
            $userAccount->userId=$this->userObj->userSign;
            $userAccount->username=$name;
            $userAccount->type=UserAccount::USER_ACCOUNT_TYPE_WECHAT;

            //判断是否绑定过Alipay
            $this->userAccountService->addUserAccount($userAccount);
            //清除Session
            \Yii::$app->getSession()->remove(Code::USER_WECHAT_ACCOUNT);

            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }


    /**
     * 获取微信账户信息
     * @throws Exception
     */
    public function actionGetWechatInfo()
    {
        $code=\Yii::$app->request->get("code");
        $wechatInterface=new WechatInterface();
        $tokenRst=$wechatInterface->callBackGetTokenInfo("",$code);
        if($tokenRst['status']!=Code::SUCCESS){
            throw new Exception('微信认证失败');
        }
        $tokenInfo=$tokenRst['data'];
        $openId=$tokenInfo['openid'];
        $accessToken=$tokenInfo['access_token'];

        $userInfoRst=$wechatInterface->getUserInfo($accessToken,$openId);
        if($userInfoRst['status']!=Code::SUCCESS){
            throw new Exception('获取微信用户信息失败');
        }
        $userInfo=$userInfoRst['data'];
        $nickname=$userInfo['nickname'];

        $weiXinAccount=[];
        $weiXinAccount['nickname']=$nickname;
        $weiXinAccount['openId']=$openId;

        \Yii::$app->getSession()->set(Code::USER_WECHAT_ACCOUNT,$weiXinAccount);

        return $this->redirect("/user-info?tab=userInfo&tabInfo=userAccountLink&bindWechat=1");
    }

    public function actionDrawMoney()
    {
        $accountId=\Yii::$app->request->post("accountId");
        $money=\Yii::$app->request->post("money");

        if(empty($accountId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"AccountId Is Not Empty"));
        }
        if(empty($money)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"Money Is Not Empty"));
        }
        if(!is_numeric($money)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"Invalid Money"));
        }
        if($money<1){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"Invalid Money"));
        }

        try{
            $balance=$this->userBaseService->findUserMoneyByUserSign($this->userObj->userSign);
            if($money>$balance){
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"No Enough Balance"));
            }
            $this->userAccountService->addUserCashApply($money,$accountId,$this->userObj->userSign);
            $this->refreshUserInfo();
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }

}