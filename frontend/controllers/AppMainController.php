<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/12
 * Time: 上午11:42
 */
namespace frontend\controllers;


use common\components\Code;
use common\components\LogUtils;
use common\entity\UserBase;
use frontend\components\Page;
use frontend\services\CircleService;
use frontend\services\UserAttentionService;
use frontend\services\UserBaseService;
use frontend\services\UserOrderService;
use Yii;
use yii\base\Exception;
use yii\debug\models\search\Log;

class AppMainController extends AController
{
    private $AttentionService;
    private $CircleService;

    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        $this->CircleService = new CircleService();
        $this->AttentionService = new UserAttentionService();
    }


    public function actionUpdateUserInfo()
    {
        $this->loginValid();
        $userSign =$this->userObj->userSign;
        $userService=new UserBaseService();
        $sex = trim(\Yii::$app->request->post('sex',UserBase::USER_SEX_SECRET));
        $nickname = trim(\Yii::$app->request->post('nickname'));
        $headImg = trim(\Yii::$app->request->post('headImg'));
        $birthday = trim(\Yii::$app->request->post('birthday'));
        $intro = trim(\Yii::$app->request->post('intro'));
        $info = trim(\Yii::$app->request->post('info'));
        $countryId = \Yii::$app->request->post('countryId');
        $cityId = \Yii::$app->request->post('cityId');
        $lon = \Yii::$app->request->post('lon');
        $lat = \Yii::$app->request->post('lat');
        $profession = trim(\Yii::$app->request->post('profession'));

        if(empty($nickname)||strlen($nickname)>30){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"昵称格式不正确"));
        }
        if(empty($countryId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"请选择居住地国家"));
        }
        if(empty($cityId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"请选择居住地城市"));
        }
        try{
            $userInfo=$userService->findUserByUserSign($userSign);
            $userInfo->sex=$sex;
            $userInfo->nickname=$nickname;
            $userInfo->headImg=$headImg;
            $userInfo->birthday=$birthday;
            $userInfo->intro=$intro;
            $userInfo->info=$info;
            $userInfo->countryId=$countryId;
            $userInfo->cityId=$cityId;
            $userInfo->lon=$lon;
            $userInfo->lat=$lat;
            $userInfo->profession=$profession;

            $userService->updateUserBase($userInfo);
            $this->appRefreshUserInfo();
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$userInfo));
        }catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    //得到搜索结果
    public function actionGetSeek()
    {
        $this->loginValid(false);
        if(empty($str))
        {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'无法搜索未知标题'));
        }
        try{
            $str=\Yii::$app->request->post('str');
            $page = new Page(\Yii::$app->request);
            $this->CircleService->getSeekResult($str,$page);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }
    //得到用户主页
    public function actionGetHomepageInfo()
    {
        //确实当前用户是否关注
        //需要验证 true
        $this->loginValid(false);
        try{
            $userSign=\Yii::$app->request->post('userSign');
            //$userSign='085963dc0af031709b032725e3ef18f5';
            $page = new Page(\Yii::$app->request);
            $mySign=$this->userObj->userSign;
            //$mySign='5787a571910e3352a76c753776e1b8f4';
            if(empty($userSign))
            {
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'无法得到未知用户主页'));
            }
            $data=$this->CircleService->getHomepageInfo($userSign,$page,$mySign);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    //得到参与随游列表
    public function actionGetTravelListByUserSign()
    {
        $this->loginValid();
        try{
            $userSign = $this->userObj->userSign;
            $page = new Page(\Yii::$app->request);
            $data =$this->CircleService->getTravelListByUserSign($userSign,$page);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }
    //得到用户的帖子
    public function actionGetArticleListByUserSign()
    {
        $this->loginValid();
        try{
            $userSign = $this->userObj->userSign;
            $page = new Page(\Yii::$app->request);

            $data =$this->CircleService->getArticleListByUserSign($userSign,$page);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    //得到首页列表
    public function actionGetIndexList()
    {

        $this->loginValid(false);
        try{
            $page1 = new Page();//得到所有的  个数在里面定义
            $page1->pageSize=6;
            $page2 = new Page();//得到所有的  个数在里面定义
            $page2->pageSize=2;
            $page3 = new Page();//得到所有的  个数在里面定义
            $page3->pageSize=2;
            $page4 = new Page();//得到所有的  个数在里面定义
            $number = \Yii::$app->request->post('n');
            if(empty($number))
            {
                $number=2;
            }
            $page4->pageSize=$number;
            $userSign = $this->userObj->userSign;
            $data= array();
            $data['circleDynamic'] = $this->AttentionService->getAttentionCircleDynamic($userSign,$page1);
            $data['userDynamic'] = $this->AttentionService->getAttentionUserDynamic($userSign,$page2);
            $data['recommendUser'] =$this->AttentionService->getRecommendUser($page3);
            $data['recommendTravel'] =$this->AttentionService->getRecommendTravel($page4);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }

    public function actionPay()
    {
        //$this->loginValid();
        require_once(dirname(__FILE__) . '/../../common/pay/pingpp/init.php');
        $channel = Yii::$app->request->post("channel");
        $number = Yii::$app->request->post("orderNumber");
        $orderService=new UserOrderService();
        if(empty(trim($number))){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'无效的订单号'));
        }
        $orderInfo=$orderService->findOrderByOrderNumber(trim($number));
        if(empty($orderInfo)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'无效的订单号'));
        }
        /*if($orderInfo->userId!=$this->userObj->userSign){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'订单用户不匹配'));
        }*/
        $amount = intval($orderInfo->totalPrice*100);//价格
//$extra 在使用某些渠道的时候，需要填入相应的参数，其它渠道则是 array() .具体见以下代码或者官网中的文档。其他渠道时可以传空值也可以不传。
        $extra = array();
        switch ($channel) {
            //这里值列举了其中部分渠道的，具体的extra所需参数请参见官网中的 API 文档
            case 'alipay_wap':
                $extra = array(
                    'success_url' => 'http://www.suiuu.com/success',
                    'cancel_url' => 'http://www.suiuu.com/cancel'
                );
                break;
            case 'upmp_wap':
                $extra = array(
                    'result_url' => 'http://www.suiuu.com/result?code='
                );
                break;
            case 'bfb_wap':
                $extra = array(
                    'result_url' => 'http://www.suiuu.com/result?code='
                );
                break;
            case 'upacp_wap':
                $extra = array(
                    'result_url' => 'http://www.suiuu.com/result?code='
                );
                break;
            case 'wx_pub':
                $extra = array(
                    'open_id' => 'open_id'
                );
                break;
            case 'wx_pub_qr':
                $extra = array(
                    'product_id' => 'Productid'
                );
                break;
            case 'jdpay_wap':
                $extra = array(
                    'success_url'=>'http://www.suiuu.com',
                    'fail_url'=>'http://www.suiuu.com'
                );
                break;
        }

        \Pingpp\Pingpp::setApiKey('sk_live_dMQIp9BoYtDRtbYIfylNUxnT');
        try {
            $travelTripInfo=json_decode($orderInfo->tripJsonInfo,true);
            $tripInfo=$travelTripInfo['info'];
            $ch = \Pingpp\Charge::create(
                array(
                    "subject"   => htmlspecialchars($tripInfo['title']),
                    "body"      =>htmlspecialchars($tripInfo['intro']),
                    "amount"    => 10,
                    "order_no"  => $number,
                    "currency"  => "cny",
                    "extra"     => $extra,
                    "channel"   => $channel,
                    "client_ip" => $_SERVER["REMOTE_ADDR"],
                    "app"       => array("id" => "app_04Ky94P0Wf1Km1uz")
                )
            );
            return $ch;
        } catch (\Pingpp\Error\Base $e) {
            header('Status: ' . $e->getHttpStatus());
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,$e->getHttpBody()));
        }
    }
}