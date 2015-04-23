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
use yii\web\Controller;
use yii;
use frontend\entity\WeChatEntity;
use common\components\Common;

class WeChatController extends Controller
{

    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);

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


    /**
     * 接收--文本
     */
    public function actionResponseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        if (!empty($postStr)) {

            list($fromUsername, $toUsername, $keyword, $msgType, $objEvent, $objEventKey, $Label, $Location_X, $Location_Y, $Scale) = $this->getXmlMsg($postStr);

            $logText = sprintf($this->HINT_LOGIN_TXT, $fromUsername);

            $date_H = date("H");
            $date_I = date("i");
            $time = time();
            $msgType_text = WeChatEntity::MSGTYPE_TEXT;
            $msgType_news = WeChatEntity::MSGTYPE_NEWS;
            $msgType_dkf = WeChatEntity::MSGTYPE_DKF;
            //关注的
            if ($msgType == WeChatEntity::MSGTYPE_EVENT && $objEvent == WeChatEntity::EVENT_SUBSCRIBE) {


                if (!empty($objEventKey)) {
                  /*  $WeChatRst = $this->WeChatSer->getUserInfo($fromUsername, null);
                    if ($WeChatRst['status'] == Code::SUCCESS) {
                        $this->commonMsgTxt($this->textTpl, $fromUsername, $toUsername, $time, $msgType_text, "欢迎再次关注巴别鱼");
                    } else {
                        $ThisUId = intval(substr($objEventKey, 8));
                        $pointNumb = 0;
                        if($ThisUId>1000){
                            //好友推荐关注 改成了临时二维码需要重新测试
                            $this->pointInterface->addPoint(PointEntity::POINT_EVENT_WECHAT_FRIEND_ATTENTION, intval($ThisUId), "推荐好友关注获得积分");
                            $this->commonMsgTxt($this->textTpl, $fromUsername, $toUsername, $time, $msgType_text, "欢迎关注巴别鱼,由于你的帮助好友已经获得积分");
                            $pointRet = $this->pointInterface->getPointEventById(PointEntity::POINT_EVENT_WECHAT_FRIEND_ATTENTION);
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
                $this->commonMsgTxt($this->textTpl, $fromUsername, $toUsername, $time, $msgType_text, 'test');
            } else if ($msgType == WeChatEntity::MSGTYPE_EVENT && $objEvent == WeChatEntity::EVENT_LOCATION) {
                //报告地理位置
                //$this->commonMsgTxt($this->textTpl, $fromUsername, $toUsername, $time, $msgType_text, "欢迎关注巴别鱼");
            } else if ($msgType == WeChatEntity::MSGTYPE_EVENT && $objEvent == WeChatEntity::EVENT_CLICK) {

            } else if ($msgType == WeChatEntity::MSGTYPE_LOCATION) {

                //发送位置签到



            } else {
                //关于用户发送消息的

                if (!empty($keyword)) {
                    $rst = $this->WeChatSer->getUserType($fromUsername);
                    $type_v = 0;
                    $time_v = 0;
                    if ($rst['status'] == Code::SUCCESS) {
                        $rstData = $rst['data'];
                        $type_v = $rstData['vType'];
                        $time_v = $rstData['vTime'];
                    }
                    if ($time - $time_v > WeChatEntity::TIME_PAST) {
                        $timePast = false;
                    } else {
                        $timePast = true;
                    }

                    if ($keyword == '0') {
                        //$this->commonMsgTxt($this->textTpl, $fromUsername, $toUsername, $time, $msgType_text, self::MAP_TXT_MORE);
                    }elseif($keyword=1){
                        $this->commonMsgTxt($this->textTpl, $fromUsername, $toUsername, $time, $msgType_text, 1);
                    }
                    else {
                        if (
                            ($date_H == WeChatEntity::TIME_OUT && $date_I >= WeChatEntity::TIME_OUT_I) ||
                            ($date_H > WeChatEntity::TIME_OUT)
                        ) {
                            $this->commonMsgTxt($this->textTpl, $fromUsername, $toUsername, $time, $msgType_text, WeChatEntity::TIME_OUT_STRING);
                        }else{
                            $this->commonMsgTxt($this->textTpl, $fromUsername, $toUsername, $time, $msgType_dkf,'1');
                            //$this->actionSendMsg($fromUsername, $keyword);
                        }

                    }
                }
            }

        } else {

            exit;
        }
    }



    private  function curlHandlePost($url,$data=null, $header = null, $type = 'POST')
    {
        $c=new Common();
        return $c->CurlHandel($url,$data=null, $header = null, $type = 'POST');
    }

    /**获取用户信息
     * @param $openId 用户id
     * @param $isSave 是否保存
     * @return array
     */
    private function getWeChatUserInfo($openId, $isSave)
    {

        $access_token = $this->readToken();

        $url = WeChatEntity::GET_USER_INFO . $access_token . "&openid=" . $openId . "&lang=zh_CN";

        $rst = $this->curlHandlePost($url);

        if ($rst['status'] == Code::SUCCESS) {
            $rstJson = json_decode($rst['data']);

            if (isset($rstJson->nickname)) {
                if ($isSave) {
                    $WeChatRst = $this->vSer->getUserInfo($openId, null);
                    if ($WeChatRst['status'] == Code::SUCCESS) {
                     //可以改成更新信息。但是没法判断用户是否更新了
                    } else {
                        $this->WeChatSer->insertWeChatInfo($rstJson->openid, $rstJson->nickname, $rstJson->sex, $rstJson->city, $rstJson->country, $rstJson->province, $rstJson->language, $rstJson->headimgurl, $rstJson->subscribe_time);
                    }
                }
                return Code::statusDataReturn(Code::SUCCESS, $rstJson);
            } else {

                $file_str = '错误发生时间：[' . date('Y-m-d H:i:s') . ']错误内容:{获取用户信息' . $rst['data'] . '}';
                $this->write_to_log(WeChatEntity::LOG_XXX_NAME, $file_str);
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
            $pc=new WXBizMsgCrypt(Yii::$app->params['token_weChat'], WeChatEntity::EncodingAESKey, WeChatEntity::APP_ID);
            $encryptMsg = ''; //加密后的密文
            $errCode = $pc->encryptMsg($resultStr, $timeStamp, $nonce, $encryptMsg);
            if ($errCode == 0) {
                $resultStr= $encryptMsg;
            }else
            {
                $this->write_to_log(WeChatEntity::LOG_XXX_NAME, $errCode);
            }
        }
        echo $resultStr;
    }

    /**
     * 发送图文消息
     * @param $maptextTpl
     * @param $fromUsername
     * @param $toUsername
     * @param $time
     * @param $msgType_news
     * @param $rstData
     */
    private function mapMsgTxt($maptextTpl, $fromUsername, $toUsername, $time, $msgType_news, $rstData)
    {
        $resultStr = sprintf($maptextTpl, $fromUsername, $toUsername, $time, $msgType_news, $rstData['message'], $rstData['data']);
        $encrypt_type = (isset($_GET['encrypt_type']) && ($_GET['encrypt_type'] == 'aes')) ? "aes" : "raw";
        if($encrypt_type=='aes'){
            $timeStamp  =Yii::$app->request->get("timestamp");
            $nonce=Yii::$app->request->get("nonce");
            $pc=new WXBizMsgCrypt(Yii::$app->params['token_weChat'], WeChatEntity::EncodingAESKey, WeChatEntity::APP_ID);
            $encryptMsg = ''; //加密后的密文
            $errCode = $pc->encryptMsg($resultStr, $timeStamp, $nonce, $encryptMsg);
            if ($errCode == 0) {
                $resultStr= $encryptMsg;
            }else
            {
                $this->write_to_log(WeChatEntity::LOG_XXX_NAME, $errCode);
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
            $pc=new WXBizMsgCrypt(Yii::$app->params['token_weChat'], WeChatEntity::EncodingAESKey, WeChatEntity::APP_ID);
            $msg = '';
            $errCode =  $pc->decryptMsg($msg_signature, $timeStamp, $nonce, $postStr, $msg);
            if ($errCode == 0) {
                return $this->xmlToArr($msg);
            } else {
                $this->write_to_log(WeChatEntity::LOG_XXX_NAME, "@".$errCode."@");
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
        $access_token = Yii::$app->redis->get(WeChatEntity::TOKEN_FILE_NAME);

        if (empty($access_token)) {
            $this->actionGetToken();
            $access_token = Yii::$app->redis->get(WeChatEntity::TOKEN_FILE_NAME);

            //创建菜单  只有当token 为空的时候,又重写创建的时候,再重写创建菜单
            $this->WeChatSer->createMenuInfo($access_token);
        }

        return $access_token;

    }

    /**
     * 获取token校验信息
     * @return mixed
     */
    public function actionGetToken()
    {

        $app_id = WeChatEntity::APP_ID;
        $app_secret = WeChatEntity::APP_SECRET;
        $url = WeChatEntity::TOKEN_LINK . 'client_credential&appid=' . $app_id . '&secret=' . $app_secret;

        $rst = $this->curlHandlePost($url);
        if ($rst['status'] == Code::SUCCESS) {
            $rstJson = json_decode($rst['data']);

            if (isset($rstJson->access_token)) {
                Yii::$app->redis->set(WeChatEntity::TOKEN_FILE_NAME,$rstJson->access_token);
            } else {

                $file_str = '错误发生时间：[' . date('Y-m-d H:i:s') . ']错误内容:{' . $rst['data'] . '}';
                $this->write_to_log(WeChatEntity::LOG_XXX_NAME, $file_str);
                exit;
            }
        }
    }


    private function is_weixin()
    {
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