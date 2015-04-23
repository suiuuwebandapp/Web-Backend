<?php
return;
include_once "wxBizMsgCrypt.php";

$arr=array(1,2,3);
$a=$arr[0];
$arr=array(5,6,7);
$b=$arr[0];
echo $a;
echo $b;

// 第三方发送消息给公众平台
$encodingAesKey = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFG";
$token = "pamtest";
$timeStamp = "1409304348";
$nonce = "xxxxxx";
$appId = "wxb11529c136998cb6";
$text = "<xml> <ToUserName><![CDATA[]]></ToUserName>
                        <FromUserName><![CDATA[gh_64b227281cb1]]></FromUserName>
                        <CreateTime>1418126472</CreateTime>
                        <MsgType><![CDATA[text]]></MsgType>
                        <Content><![CDATA[欢迎关注巴别鱼]]></Content>
                        <FuncFlag>0</FuncFlag>
                        </xml>";


$pc = new WXBizMsgCrypt($token, $encodingAesKey, $appId);
$encryptMsg = '';
$errCode = $pc->encryptMsg($text, $timeStamp, $nonce, $encryptMsg);
if ($errCode == 0) {
	print("加密后: " . $encryptMsg . "\n");
} else {
	print($errCode . "\n");
}

$xml_tree = new DOMDocument();
$xml_tree->loadXML($encryptMsg);
$array_e = $xml_tree->getElementsByTagName('Encrypt');
$array_s = $xml_tree->getElementsByTagName('MsgSignature');
$encrypt = $array_e->item(0)->nodeValue;
$msg_sign = $array_s->item(0)->nodeValue;

$format = "<xml><ToUserName><![CDATA[toUser]]></ToUserName><Encrypt><![CDATA[%s]]></Encrypt></xml>";
$from_xml = sprintf($format, $encrypt);

// 第三方收到公众号平台发送的消息
$msg = '';
$errCode = $pc->decryptMsg($msg_sign, $timeStamp, $nonce, $from_xml, $msg);
if ($errCode == 0) {
	print("解密后: " . $msg . "\n");
} else {
	print($errCode . "\n");
}
