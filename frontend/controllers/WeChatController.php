<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/4/22
 * Time: 下午7:02
 */
namespace frontend\controllers;

use common\components\Code;
use common\components\wx\WXBizMsgCrypt;
use common\entity\UserBase;
use common\entity\WeChatUserInfo;
use common\pay\wxpay\NativeDynamicQrcode;
use frontend\services\CountryService;
use frontend\services\UserBaseService;
use frontend\services\WeChatService;
use yii\web\Controller;
use yii;
use common\entity\WeChat;
use common\components\Common;

class WeChatController extends SController
{




    public $enableCsrfValidation=false;
    public $layout=false;
    public $weChatSer;
    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        $this->weChatSer=new WeChatService();
    }

    public function actionValid()
    {
        if (empty($_GET["echostr"])) {

            $this->actionResponseMsg();
            exit;
        }

        $echoStr = $_GET["echostr"];

        if ($this->checkSignature()) {
            echo $echoStr;
            exit;
        }
    }

    //todo @test
    public function actionTest()
    {

        //var_dump( $this->getWechatUserInfo('oGfdst0AA7SAThQlEscjbHjbbzp8', true)); //关注的时候抓取用户信息
    }

    /**
     * 接收--文本
     */
    public function actionResponseMsg()
    {

		$postStr = isset($GLOBALS["HTTP_RAW_POST_DATA"])?$GLOBALS["HTTP_RAW_POST_DATA"]:file_get_contents("php://input");

        if (!empty($postStr)) {

            list($fromUsername, $toUsername, $keyword, $msgType, $objEvent, $objEventKey, $Label, $Location_X, $Location_Y, $Scale) = $this->getXmlMsg($postStr);

            //$logText = sprintf($this->HINT_LOGIN_TXT, $fromUsername);
            $time = time();
            $date_H = date("H",$time);
            $date_I = date("i",$time);

            $msgType_text = WeChat::MSGTYPE_TEXT;
            $msgType_news = WeChat::MSGTYPE_NEWS;
            $msgType_dkf = WeChat::MSGTYPE_DKF;

            //关注的
            if ($msgType == WeChat::MSGTYPE_EVENT && $objEvent == WeChat::EVENT_SUBSCRIBE) {


                if (!empty($objEventKey)) {
                  /*  $WeChatRst = $this->WeChatSer->getUserInfo($fromUsername, null);
                    if ($WeChatRst['status'] == Code::SUCCESS) {
                        $this->commonMsgTxt($this->textTpl, $fromUsername, $toUsername, $time, $msgType_text, "欢迎再次关注巴别鱼");
                    } else {
                        $ThisUId = intval(substr($objEventKey, 8));
                        $pointNumb = 0;
                        if($ThisUId>1000){
                            //好友推荐关注 改成了临时二维码需要重新测试
                            $this->pointInterface->addPoint(Point::POINT_EVENT_WECHAT_FRIEND_ATTENTION, intval($ThisUId), "推荐好友关注获得积分");
                            $this->commonMsgTxt($this->textTpl, $fromUsername, $toUsername, $time, $msgType_text, "欢迎关注巴别鱼,由于你的帮助好友已经获得积分");
                            $pointRet = $this->pointInterface->getPointEventById(Point::POINT_EVENT_WECHAT_FRIEND_ATTENTION);
                            if ($pointRet['status'] == Code::SUCCESS) {
                                $pointE = $pointRet['data'];
                                $pointNumb = $pointE->pointEventPoint;
                            }
                        }
                        //插入推荐数据
                        $this->WeChatSer->insertWeChatRecommend($ThisUId, $fromUsername, self::RECOMMEND_TYPE_ATTENTION, $pointNumb);

                    }*/

                }
                $this->getWechatUserInfo($fromUsername, true); //关注的时候抓取用户信息
                $this->commonMsgTxt(WeChat::TEXT_TPL, $fromUsername, $toUsername, $time, $msgType_text, WeChat::ATTENTION_REPLY_STR);
            } else if ($msgType == WeChat::MSGTYPE_EVENT && $objEvent == WeChat::EVENT_LOCATION) {
                //报告地理位置
                //$this->commonMsgTxt($this->textTpl, $fromUsername, $toUsername, $time, $msgType_text, "欢迎关注巴别鱼");
            } else if ($msgType == WeChat::MSGTYPE_EVENT && $objEvent == WeChat::EVENT_CLICK) {
                //click 事件
                switch ($objEventKey) {
                    case WeChat::EVENT_CLICK_KEY_ACTIVE: //21
                        $this->commonMsgTxt(WeChat::TEXT_TPL, $fromUsername, $toUsername, $time, $msgType_text, WeChat::MSG_TXT_NO);
                        break;
                    default :
                        $this->commonMsgTxt(WeChat::TEXT_TPL, $fromUsername, $toUsername, $time, $msgType_text, WeChat::MSG_TXT_NO);
                }
            } else if ($msgType == WeChat::MSGTYPE_LOCATION) {
                //发送位置签到
            } else {
                //关于用户发送消息的

                if (!empty($keyword)) {


                    if ($keyword == '0') {

                    }elseif($keyword==1){
                        $this->commonMsgTxt(WeChat::TEXT_TPL, $fromUsername, $toUsername, $time, $msgType_text, 1);
                    }
                    else {
                        if (
                            ($date_H == WeChat::TIME_OUT && $date_I >= WeChat::TIME_OUT_I) ||
                            ($date_H > WeChat::TIME_OUT)
                        ) {
                            $this->commonMsgTxt(WeChat::TEXT_TPL, $fromUsername, $toUsername, $time, $msgType_text, WeChat::TIME_OUT_STRING);
                        }else{
                            //$this->commonMsgTxt(WeChat::TEXT_TPL, $fromUsername, $toUsername, $time, $msgType_text, WeChat::ATTENTION_REPLY_STR);
                            //$this->commonMsgTxt(WeChat::TEXT_TPL, $fromUsername, $toUsername, $time, $msgType_dkf,'');
                            //$this->actionSendMsg($fromUsername, $keyword);
                        }

                    }
                }
            }

        } else {

            exit;
        }
    }
    /*
       * 获取code
       * */
    public function actionGetCode()
    {
        if (!$this->is_weixin()) {
            $this->showNoWx();
            exit;
        }
        //$actionType 菜单的选择如11表示第一列第一个 12 第一列第二个

        $openId = '';
        $actionType = '';
        $userSign = '';
        if (isset($_GET['code'])) {
            $code = $_GET['code'];
            $url = sprintf(WeChat::GET_OAUTH2_OPENID, WeChat::APP_ID, WeChat::APP_SECRET, $code);
            $rst = Common::CurlHandel($url);
            if ($rst['status'] == Code::SUCCESS) {
                $rstJson = json_decode($rst['data']);
                $openId = $rstJson->openid;
                $weChatUserInfo=new WeChatUserInfo();
                $weChatUserInfo->openId=$openId;
                $WeChatRst = $this->weChatSer->getUserInfo($weChatUserInfo);
                Yii::$app->session->set(Yii::$app->params['weChatSign'],json_encode($WeChatRst));
            } else {
                return   $this->renderPartial('errorHint', array('str1'=>'无法获取用户信息','str2'=>'请联系管理员','url'=>"javascript:WeixinJSBridge.call('closeWindow')"));
                exit;
            }
        } else {
            return $this->renderPartial('errorHint', array('str1'=>'无法获取CODE','str2'=>'请联系管理员 ','url'=>"javascript:WeixinJSBridge.call('closeWindow')"));
            exit;
        }
        if (isset($_GET['actionType'])) {
            $actionType = $_GET['actionType'];
        } else {
            return $this->renderPartial('errorHint', array('str1'=>'无法获取Type','str2'=>'请联系管理员 ','url'=>"javascript:WeixinJSBridge.call('closeWindow')"));
            exit;
        }
        switch ($actionType) {
            case "11":
                $url = Yii::$app->params['base_dir']. "/we-chat-order-list";
                header("location: " . $url);
                break;
            case "12":
                $url = Yii::$app->params['base_dir']. "/we-chat-order-list/order-manage";
                header("location: " . $url);
                break;
            default:
                $url = Yii::$app->params['base_dir'];
                header("location: " . $url);;
                break;
        }

        exit;
    }

    public function actionBindingMain()
    {
        return $this->renderPartial('bindingMain');
    }

    public function actionBinding()
    {
        if (!$this->is_weixin()) {
            $this->showNoWx();
            exit;
        }
        $userInfo=json_decode(Yii::$app->session->get(Yii::$app->params['weChatSign']));
        if(isset($userInfo->openId))
        {
            if($_POST)
            {

            }else
            {
                return $this->renderPartial('binding');
            }

        }else
        {
            return $this->renderPartial('errorHint', array('str1'=>'绑定错误,无法获取用户信息','str2'=>'请联系管理员','url'=>"javascript:WeixinJSBridge.call('closeWindow')"));
        }

    }

    public function actionRegister()
    {

        if (!$this->is_weixin()) {
            $this->showNoWx();
            exit;
        }
        $userInfo=json_decode(Yii::$app->session->get(Yii::$app->params['weChatSign']));
        if(isset($userInfo->openId))
        {
            if($_POST)
            {
                $phone=\Yii::$app->request->post('phone');
                $password=\Yii::$app->request->post('password');
                $cPassword=\Yii::$app->request->post('cPassword');
                $code=\Yii::$app->request->post('validateCode');//验证码
                if(empty($password))
                {
                    echo json_encode(Code::statusDataReturn(Code::FAIL,'密码不能为空'));
                }
                if($cPassword!=$password)
                {
                    echo json_encode(Code::statusDataReturn(Code::FAIL,'密码不一致'));
                }
                $rCode=\Yii::$app->redis->get(Code::USER_PHONE_VALIDATE_CODE_AND_PHONE . $phone);
                if(empty($rCode)||$rCode!=$code)
                {
                    echo json_encode(Code::statusDataReturn(Code::FAIL,'验证码不正确'));
                }
                $userBase=new UserBase();
                $userBase->nickname=$userInfo->v_nickname;
                $userBase->password=$password;
                $userBase->phone=$phone;
                $userBaseService = new UserBaseService();
                $user=$userBaseService->addUser($userBase);
                $this->weChatSer->bindingWeChatByUnionID($user->userSign,$userInfo->unionID);
                return $this->renderPartial('success',['title'=>'注册成功','str'=>'注册成功']);
            }else
            {
                $c=Yii::$app->request->get('c');
                $n=Yii::$app->request->get('n');
                return $this->renderPartial('register',['c'=>$c,'n'=>$n]);
            }
        }else
        {
            return $this->renderPartial('errorHint', array('str1'=>'绑定错误,无法获取用户信息','str2'=>'请联系管理员','url'=>"javascript:WeixinJSBridge.call('closeWindow')"));
        }

    }

    public function actionShowCountry()
    {
        $countrySer=new CountryService();
        $list = $countrySer->getCountryList();
        return $this->renderPartial('country',['list'=>$list]);
    }


    /**获取用户信息
     * @param $openId 用户id
     * @param $isSave 是否保存
     * @return array
     */
    private function getWeChatUserInfo($openId, $isSave)
    {

        $access_token = $this->readToken();

        $url = WeChat::GET_USER_INFO . $access_token . "&openid=" . $openId . "&lang=zh_CN";

        $rst =  Common::CurlHandel($url);
        if ($rst['status'] == Code::SUCCESS) {
            $rstJson = json_decode($rst['data'],true);
            if (isset($rstJson['nickname'])) {
                if ($isSave) {
                    $weChatUserInfo=new WeChatUserInfo();
                    $weChatUserInfo->openId=$openId;
                    $WeChatRst = $this->weChatSer->getUserInfo($weChatUserInfo);
                    if (!empty($WeChatRst)) {
                        $this->weChatSer->upDateWeChatInfo($rstJson,$WeChatRst['userSign']);
                     //可以改成更新信息。但是没法判断用户是否更新了
                    } else {

                        $this->weChatSer->insertWeChatInfo($rstJson);
                    }
                }
                return Code::statusDataReturn(Code::SUCCESS, $rstJson);
            } else {

                $file_str = '错误发生时间：[' . date('Y-m-d H:i:s') . ']错误内容:{获取用户信息' . $rst['data'] . '}';
                $this->write_to_log(WeChat::LOG_XXX_NAME, $file_str);
                exit;
            }
        }
        return Code::statusDataReturn(Code::FAIL);
    }

    /**
     * 发送普通文本消息
     * @param $textTpl
     * @param $fromUsername
     * @param $toUsername
     * @param $time
     * @param $msgType_text
     * @param $send_str
     */
    private function commonMsgTxt($textTpl, $fromUsername, $toUsername, $time, $msgType_text, $send_str)
    {

        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType_text, $send_str);
        $encrypt_type = (isset($_GET['encrypt_type']) && ($_GET['encrypt_type'] == 'aes')) ? "aes" : "raw";
        if($encrypt_type=='aes'){
            $timeStamp  =Yii::$app->request->get("timestamp");
            $nonce=Yii::$app->request->get("nonce");
            $pc=new WXBizMsgCrypt(Yii::$app->params['token_weChat'], WeChat::EncodingAESKey, WeChat::APP_ID);
            $encryptMsg = ''; //加密后的密文
            $errCode = $pc->encryptMsg($resultStr, $timeStamp, $nonce, $encryptMsg);
            if ($errCode == 0) {
                $resultStr= $encryptMsg;
            }else
            {
                $this->write_to_log(WeChat::LOG_XXX_NAME, $errCode);
            }
        }
        echo $resultStr;
    }

    /**
     * 发送图文消息
     * @param $msgTpl
     * @param $fromUsername
     * @param $toUsername
     * @param $time
     * @param $msgType_news
     * @param $rstData
     */
    private function mapMsgTxt($msgTpl, $fromUsername, $toUsername, $time, $msgType_news, $rstData)
    {
        $resultStr = sprintf($msgTpl, $fromUsername, $toUsername, $time, $msgType_news, $rstData['message'], $rstData['data']);
        $encrypt_type = (isset($_GET['encrypt_type']) && ($_GET['encrypt_type'] == 'aes')) ? "aes" : "raw";
        if($encrypt_type=='aes'){
            $timeStamp  =Yii::$app->request->post("timestamp");
            $nonce=Yii::$app->request->post("nonce");
            $pc=new WXBizMsgCrypt(Yii::$app->params['token_weChat'], WeChat::EncodingAESKey, WeChat::APP_ID);
            $encryptMsg = ''; //加密后的密文
            $errCode = $pc->encryptMsg($resultStr, $timeStamp, $nonce, $encryptMsg);
            if ($errCode == 0) {
                $resultStr= $encryptMsg;
            }else
            {
                $this->write_to_log(WeChat::LOG_XXX_NAME, $errCode);
            }
        }
        echo $resultStr;
    }
    /**
     * 获取用户发送的xml格式的消息
     * @param $postStr
     * @return array
     */
    public function getXmlMsg($postStr)
    {

        $encrypt_type = (isset($_GET['encrypt_type']) && ($_GET['encrypt_type'] == 'aes')) ? "aes" : "raw";
        if($encrypt_type=='aes'){
            $timeStamp  =Yii::$app->request->get("timestamp");
            $nonce=Yii::$app->request->get("nonce");
            $msg_signature =Yii::$app->request->get("msg_signature");
            // 第三方收到公众号平台发送的消息
            $pc=new WXBizMsgCrypt(Yii::$app->params['token_weChat'], WeChat::EncodingAESKey, WeChat::APP_ID);
            $msg = '';
            $errCode =  $pc->decryptMsg($msg_signature, $timeStamp, $nonce, $postStr, $msg);
            if ($errCode == 0) {
                return $this->xmlToArr($msg);
            } else {
                $this->write_to_log(WeChat::LOG_XXX_NAME, "@".$errCode."@");
            }
        }else
        {
            return $this->xmlToArr($postStr);
        }
        //print("解密后: " . $msg . "\n");

    }

    private function xmlToArr($xml)
    {

        $postObj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $fromUsername = isset($postObj->FromUserName) ? $postObj->FromUserName : '';
        $toUsername = isset($postObj->ToUserName) ? $postObj->ToUserName : '';
        $keyword = isset($postObj->Content) ? trim($postObj->Content) : '';
        $msgType = isset($postObj->MsgType) ? $postObj->MsgType : '';
        $objEvent = isset($postObj->Event) ? $postObj->Event : '';
        $objEventKey = isset($postObj->EventKey) ? $postObj->EventKey : '';
        $Label = isset($postObj->Label) ? $postObj->Label : '';
        $Location_X = isset($postObj->Location_X) ? $postObj->Location_X : '';
        $Location_Y = isset($postObj->Location_Y) ? $postObj->Location_Y : '';
        $Scale = isset($postObj->Scale) ? $postObj->Scale : '';
        return array($fromUsername, $toUsername, $keyword, $msgType, $objEvent, $objEventKey, $Label, $Location_X, $Location_Y, $Scale);
    }
    /**
     * 微信 校验信息
     * @return bool
     */
    private function checkSignature()
    {
        $signature = isset($_GET["signature"]) ? $_GET["signature"] : exit;
        $timestamp = isset($_GET["timestamp"]) ? $_GET["timestamp"] : exit;
        $nonce = isset($_GET["nonce"]) ? $_GET["nonce"] : exit;

        $token = Yii::$app->params['token_weChat'];
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }



    /**
     * 写入文件信息
     * @param $file
     * @param $str
     */
    private function write_to_log($file, $str)
    {
        if ($fd = @fopen($file, "a")) {
            fputs($fd, $str);
            fclose($fd);
        }
    }

    /**
     * 读取token
     * @return string
     */
    private function readToken()
    {
        //调用计划任务设置的Token
        $access_token = Yii::$app->redis->get(WeChat::TOKEN_FILE_NAME);
        if (empty($access_token)) {
            $this->actionGetToken();
            $access_token = Yii::$app->redis->get(WeChat::TOKEN_FILE_NAME);

            //创建菜单  只有当token 为空的时候,又重写创建的时候,再重写创建菜单
            $this->weChatSer->createMenuInfo($access_token);
        }
        return $access_token;
    }

    public function actionCreateMenu()
    {
        $access_token =$this->readToken();
        $obj=$this->weChatSer->createMenuInfo($access_token);
        var_dump($obj);
    }
    /**
     * 获取token校验信息
     * @return mixed
     */
    public function actionGetToken()
    {

        $app_id = WeChat::APP_ID;
        $app_secret = WeChat::APP_SECRET;
        $url = WeChat::TOKEN_LINK . 'client_credential&appid=' . $app_id . '&secret=' . $app_secret;

        $rst =  Common::CurlHandel($url);
        if ($rst['status'] == Code::SUCCESS) {
            $rstJson = json_decode($rst['data']);
            if (isset($rstJson->access_token)) {
                Yii::$app->redis->set(WeChat::TOKEN_FILE_NAME,$rstJson->access_token);
                \Yii::$app->redis->expire(WeChat::TOKEN_FILE_NAME,$rstJson->expires_in);
            } else {
                $file_str = '错误发生时间：[' . date('Y-m-d H:i:s') . ']错误内容:{' . $rst['data'] . '}';
                $this->write_to_log(WeChat::LOG_XXX_NAME, $file_str);
                exit;
            }
        }
    }


    private function is_weixin()
    {
        return true;
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return true;
        }
        return false;
    }

    public function showNoWx()
    {
        echo "请在微信浏览器打开";
    }
}