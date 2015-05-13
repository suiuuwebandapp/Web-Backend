<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 15-3-13
 * Time: 上午11:26
 * To change this template use File | Settings | File Templates.
 */

class WeChatController extends CController{


    const MAP_TXT_NO="今日暂无消息";
    const EVENT_KEY_TEST1="EVENT_KEY_TEST2-1";
    const EVENT_KEY_TEST2="EVENT_KEY_TEST3-1";
    const EVENT_KEY_TEST3="test3";
    //普通消息
    public $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                        </xml>";
    //图文消息
    public $maptextTpl = "<xml>
                                    <ToUserName><![CDATA[%s]]></ToUserName>
                                    <FromUserName><![CDATA[%s]]></FromUserName>
                                    <CreateTime>%s</CreateTime>
                                    <MsgType><![CDATA[%s]]></MsgType>
                                    <ArticleCount>%d</ArticleCount>
                                    <Articles>
                                    %s
                                    </Articles>
                                    </xml> ";



    public $WeChatSer=null;
    public function __construct($id, $module = null)
    {

        parent::__construct($id, $module);

        $this->WeChatSer = new WeChatService();
    }
    /**
     * 微信入口
     */
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

const  SAE_MYSQL_USER='519414839@qq.com';
const SAE_MYSQL_PASS='qwe123';
const  SAE_MYSQL_HOST_M='w.rdc.sae.sina.com.cn';
const SAE_MYSQL_HOST_S='w.rdc.sae.sina.com.cn';
const SAE_MYSQL_PORT=3307;
const SAE_MYSQL_DB='test';


    //
    public function actionTest()
    {


    }

    public function actionReadBug()
    {
        $mmc=memcache_init();
        if($mmc==false){
            return "";
        }
        else
        {
            return memcache_get($mmc,WeChatEntity::LOG_XXX_NAME);
        }
    }

    public function actionResponseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        $date_H = date("H");
        $date_I = date("i");
        $time = time();
        $msgType_text = WeChatEntity::MSGTYPE_TEXT;
        $msgType_news = WeChatEntity::MSGTYPE_NEWS;
        $msgType_dkf = WeChatEntity::MSGTYPE_DKF;
        if (!empty($postStr)) {

            list($fromUsername, $toUsername, $keyword, $msgType, $objEvent, $objEventKey, $Label, $Location_X, $Location_Y, $Scale) = $this->getXmlMsg($postStr);

            //关注的
            if ($msgType == WeChatEntity::MSGTYPE_EVENT && $objEvent == WeChatEntity::EVENT_SUBSCRIBE) {
                $this->commonMsgTxt($this->textTpl, $fromUsername, $toUsername, $time, $msgType_text, "您好欢迎关注52easy!");
            } else if ($msgType == WeChatEntity::MSGTYPE_EVENT && $objEvent == WeChatEntity::EVENT_LOCATION) {
            //报告地理位置
            //$this->commonMsgTxt($this->textTpl, $fromUsername, $toUsername, $time, $msgType_text, "欢迎关注巴别鱼");
        } else if ($msgType == WeChatEntity::MSGTYPE_EVENT && $objEvent == WeChatEntity::EVENT_CLICK) {
            //click 事件
            switch ($objEventKey) {
                case self::EVENT_KEY_TEST1:
                    $this->commonMsgTxt($this->textTpl, $fromUsername, $toUsername, $time, $msgType_text, "1");
                    break;
                case self::EVENT_KEY_TEST2:
                    $this->commonMsgTxt($this->textTpl, $fromUsername, $toUsername, $time, $msgType_text, "2");
                    break;
                default :
                    $this->commonMsgTxt($this->textTpl, $fromUsername, $toUsername, $time, $msgType_text, self::MAP_TXT_NO);
            }
        } else if ($msgType == WeChatEntity::MSGTYPE_LOCATION) {
                    $this->commonMsgTxt($this->textTpl, $fromUsername, $toUsername, $time, $msgType_text, "恭喜你签到成功");
        }else {
                //preg_match('/\d+/',$keyword)
                if(preg_match('/\d+/',$keyword)){
                   // $numb=intval(substr($keyword,strlen('双色球')));
                    $numb= intval($keyword);
                    if($numb<1)
                    {
                        $numb=1;
                    }
                    $str='';
                    for($j=0;$j<$numb;$j++)
                    {
                        $arr=$this-> randomBall();

                        for($i=0;$i<7;$i++)
                        {
                            if($i==6)
                            {
                                // $str.= '<a>'.$arr[$i].'</a>';
                                $str.= '<a href="">'.$arr[$i] .'</a>'."\n";
                            }else
                            {
                                $str.= $arr[$i].' ';
                            }
                        }
                    }
                    $this->commonMsgTxt($this->textTpl, $fromUsername, $toUsername, $time, $msgType_text, $str);
                }
                else{
                    //摇一摇
                $this->commonMsgTxt($this->textTpl, $fromUsername, $toUsername, $time, $msgType_text, "http://wechatapp01.sinaapp.com/52easy/index.php/test/YYY");
                }
            }
        }
    }


    private function randomBall()
    {
        $return_h=array();
        $arr_h=array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33);
        $arr_l=array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16);

            for($i=0;$i<6;$i++)
            {
                $j=intval(rand(0,count($arr_h)));
                $return_h[]=$arr_h[$j%count($arr_h)];
                array_splice($arr_h,$j%count($arr_h),1);
            }
            sort($return_h);
            $s=intval(rand(0,count($arr_l)));
            $return_h[]=$arr_l[$s%count($arr_l)];
            return $return_h;
    }

    //表情转换函数
     private  function utf8_bytes($cp)
        {

            if ($cp > 0x10000) {
                # 4 bytes
                return chr(0xF0 | (($cp & 0x1C0000) >> 18)) .
                chr(0x80 | (($cp & 0x3F000) >> 12)) .
                chr(0x80 | (($cp & 0xFC0) >> 6)) .
                chr(0x80 | ($cp & 0x3F));
            } else if ($cp > 0x800) {
                # 3 bytes
                return chr(0xE0 | (($cp & 0xF000) >> 12)) .
                chr(0x80 | (($cp & 0xFC0) >> 6)) .
                chr(0x80 | ($cp & 0x3F));
            } else if ($cp > 0x80) {
                # 2 bytes
                return chr(0xC0 | (($cp & 0x7C0) >> 6)) .
                chr(0x80 | ($cp & 0x3F));
            } else {
                # 1 byte
                return chr($cp);
            }
        }

    /**
     * 创建菜单项
     */
    public function actionCreateMenuMsg()
    {
        $pw = Yii::app()->request->getParam("pw");
        if ($pw != "qwe123") {
            echo "ddd";
            //防止别人使用最好改成私有然后删除这段话
            return;
        }
        $access_token = $this->readTokenLog();
        $json_data = $this->WeChatSer->createMenuInfo($access_token);
        echo $json_data->errmsg;
        exit;
    }

    /**
     * 删除菜单
     */
    public function actionDeleteMenu()
    {
        $pw = Yii::app()->request->getParam("pw");
        if ($pw != "qwe123") {
            //防止别人使用最好改成私有
            return;
        }
        $access_token = $this->readTokenLog();

        $json_data = $this->WeChatSer->deleteMenuInfo($access_token);

        echo $json_data->errmsg;
        exit;
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
        $rst = curlHandlePost($url);
        if ($rst['status'] == Code::SUCCESS) {
            $rstJson = json_decode($rst['data']);
            if (isset($rstJson->access_token)) {
                $this->setMMCToken($rstJson->access_token);
            } else {
                $file_str = '错误发生时间：[' . date('Y-m-d H:i:s') . ']错误内容:{' . $rst['data'] . '}';
                $this->write_to_log(WeChatEntity::LOG_XXX_NAME, $file_str);
                exit;
            }
        }
    }


    /**
     * 读取token
     * @return string
     */
    private function readTokenLog()
    {
        //调用计划任务设置的Token
        $access_token = $this->getMMCToken();

        if (empty($access_token)) {
            $this->actionGetToken();
            $access_token = $this->getMMCToken();

            //创建菜单  只有当token 为空的时候,又重写创建的时候,再重写创建菜单
            //$this->WeChatSer->createMenuInfo($access_token);
        }

        return $access_token;
    }


    private function write_to_log($key,$val)
    {
        $mmc=memcache_init();
        if($mmc==false)
            return "";
        else
        {
            $val_o= memcache_get($mmc,"$key");
            memcache_set($mmc,"$key",$val_o.'|'.$val);
        }
    }
    /**
     * 发送普通文本消息
     * @param $textTpl
     * @param $fromUsername
     * @param $toUsername
     * @param $time
     * @param $msgType_text
     * @param $subscribe_str
     */
    private function commonMsgTxt($textTpl, $fromUsername, $toUsername, $time, $msgType_text, $subscribe_str)
    {

        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType_text, $subscribe_str);
        $encrypt_type = (isset($_GET['encrypt_type']) && ($_GET['encrypt_type'] == 'aes')) ? "aes" : "raw";
        if($encrypt_type=='aes'){
            $timeStamp  =Yii::app()->request->getParam("timestamp");
            $nonce=Yii::app()->request->getParam("nonce");
            $pc=new WXBizMsgCrypt(Yii::app()->params['token_wechat'], WeChatEntity::EncodingAESKey, WeChatEntity::APP_ID);
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

    private function setMMCToken($val)
    {
        $mmc=memcache_init();
        if($mmc==false){
          return "";}
        else
        {
            memcache_set($mmc,"token","$val");

        }
    }
    private function getMMCToken()
    {
        $mmc=memcache_init();
        if($mmc==false){
            return "";}
        else
        {
            return memcache_get($mmc,"token");
        }
    }

    private function setMMCToken_b($val)
    {
        $mmc=memcache_init();
        if($mmc==false){
            return "";}
        else
        {
            memcache_set($mmc,"token_b","$val");

        }
    }
    private function getMMCToken_b()
    {
        $mmc=memcache_init();
        if($mmc==false){
            return "";}
        else
        {
            return memcache_get($mmc,"token_b");
        }
    }
    /**
     * 获取token校验信息
     * @return mixed
     */
    public function actionGetTokenB()
    {

        $app_id = WeChatEntity::APP_ID_b;
        $app_secret = WeChatEntity::APP_SECRET_b;
        $url = WeChatEntity::TOKEN_LINK . 'client_credential&appid=' . $app_id . '&secret=' . $app_secret;
        $rst = curlHandlePost($url);
        if ($rst['status'] == Code::SUCCESS) {
            $rstJson = json_decode($rst['data']);
            if (isset($rstJson->access_token)) {
                $this->setMMCToken_b($rstJson->access_token);
            } else {
                $file_str = '错误发生时间：[' . date('Y-m-d H:i:s') . ']错误内容:{' . $rst['data'] . '}';
                $this->write_to_log(WeChatEntity::LOG_XXX_NAME, $file_str);
                exit;
            }
        }
    }
    /**
     * 读取token
     * @return string
     */
    private function readTokenLogB()
    {
        //调用计划任务设置的Token
        $access_token = $this->getMMCToken_b();

        if (empty($access_token)) {
            $this->actionGetTokenB();
            $access_token = $this->getMMCToken_b();

            //创建菜单  只有当token 为空的时候,又重写创建的时候,再重写创建菜单
            //$this->WeChatSer->createMenuInfo($access_token);
        }

        return $access_token;
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
            $timeStamp  =Yii::app()->request->getParam("timestamp");
            $nonce=Yii::app()->request->getParam("nonce");
            $pc=new WXBizMsgCrypt(Yii::app()->params['token_wechat'], WeChatEntity::EncodingAESKey, WeChatEntity::APP_ID);
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
        $postObj      = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $fromUsername = $postObj->FromUserName;
        $toUsername   = $postObj->ToUserName;
        $keyword      = trim($postObj->Content);
        $msgType      = $postObj->MsgType;
        $objEvent     = $postObj->Event;
        $objEventKey  = $postObj->EventKey;

        return array( $fromUsername, $toUsername, $keyword, $msgType, $objEvent, $objEventKey);
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

        $token = Yii::app()->params['token_wechat'];
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