<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 15-3-13
 * Time: 下午3:50
 * To change this template use File | Settings | File Templates.
 */
namespace frontend\services;

use common\entity\WeChat;
class WeChatService{

    private $strItemRemain="";
    private $numbItemRemain=0;
    private $addStr= '<item>
                    <Title><![CDATA[查看更多请输入“更多” ]]></Title>
                    <Description><![CDATA[查看更多信息请输入“更多”]]></Description>
                    <PicUrl><![CDATA[]]></PicUrl>
                    </item>';
    function __construct()
    {

    }


    public function sendTemplateMessage($access_token,$toUser,$backUrl,$userName,$cmName,$date,$remark)
    {

        $url = WeChat::MESSAGE_SEN_TEMPLATE . $access_token;
        $templateId=WeChat::TEMPLATE_ID_FOR_RESERVE;
        $rst = curlHandlePost($url, $this->getTemplate($toUser,$templateId,$backUrl,$userName,$cmName,$date,$remark));
        return $rst;
    }

    /**
     * 创建菜单
     */
    public function createMenuInfo($access_token)
    {
        $url = WeChat::MENU_CREATE_LINK . $access_token;

        $string = '{
                    "button":[
                    {
                      "name":"test1",
                       "sub_button":[
                        {
                           "type":"view",
                           "name":"test1-1",
                           "url":"%s"
                        },
                       {
                          "type":"view",
                           "name":"test1-2",
                           "url":"%s"
                        }]
                    },
                    {
                      "name":"test2",
                       "sub_button":[
                       {
                           "type":"click",
                           "name":"test2-1",
                           "key":"EVENT_KEY_TEST2-1"
                        },
                        {
                           "type":"view",
                           "name":"我是学霸",
                           "url":"http://mp.weixin.qq.com/s?__biz=MjM5NzMyODM1NA==&mid=203174585&idx=4&sn=4ae8afa2b78c9be98dd2a58930923f9f#rd"
                        }
                       ]
                    },
                    {
                      "name":"鱼部落",
                       "sub_button":[
                        {
                           "type":"click",
                           "name":"最新活动",
                           "key":"EVENT_KEY_TEST3-1"
                        },
                        {
                            "type":"view",
                           "name":"鱼社区",
                           "url":"http://m.wsq.qq.com/262926839"
                        }]
                    }
                    ]
                    }';
        $url_1 ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
        $app_id = WeChat::APP_ID;
        $url_test1_1  ="http://".Yii::app()->params['vsinsHost'] ."/vsins/getCode/actionType/11";//购课放在了第一位
        $url_test1_2 ="http://".Yii::app()->params['vsinsHost'] ."/vsins/getCode/actionType/12";
        $url_test1_1_s=sprintf($url_1,$app_id,urlencode($url_test1_1));
        $url_test1_2_s=sprintf($url_1,$app_id,urlencode($url_test1_2));
        $data      = sprintf($string,$url_test1_1_s,$url_test1_2_s);
        $rstInfo   = curlHandlePost($url, $data);

        $json_data = json_decode($rstInfo['data']);

        return  $json_data;

    }



    public function getVsinsInfo($type)
    {
        $strItem   = '';
        $countItem = 0;
        $rstData   = $this->_objVsinsDB->getVsinsInfo($type);

        if ($rstData['status'] == Code::SUCCESS) {
            if (empty($rstData['data'][0])) {

            } else {


                foreach ($rstData['data'] as $value) {
                    $rstBodyUrl = $this->getBodyLink($value['id']);

                    $strItem .= '<item>
                    <Title><![CDATA[' . $value['title'] . ']]></Title>
                    <Description><![CDATA[' . $value['abstract'] . ']]></Description>
                    <PicUrl><![CDATA[' . Yii::app()->params['image_dir_host'] . $value['picurl'] . ']]></PicUrl>
                    <Url><![CDATA[' . $rstBodyUrl . ']]></Url>
                    </item>|';
                }
                $countItem = count($rstData['data']);

                //5显示条数
                //之类的数字应该提出来，暂时先这样写
                if($countItem>5)
                {
                    $strItem1="";
                    $this->strItemRemain="";
                    $ar=preg_split("/\|/",$strItem);
                    array_pop($ar);
                    $arr1=array_slice($ar,0,5);
                    $arr2=array_slice($ar,5,5);
                    foreach ($arr1 as $key => $value) {
                        $strItem1.=$value;
                    }
                    foreach ($arr2 as $key => $value) {
                        $this->strItemRemain.=$value;
                    }
                    $strItem1.=$this->addStr;
                    $this->numbItemRemain=$countItem-5;
                    $strItem1 =str_replace("|","",$strItem1);
                    return Code::statusDataReturn(Code::SUCCESS, $strItem1, 6);
                }
            }
            $strItem =str_replace("|","",$strItem);
            return Code::statusDataReturn(Code::SUCCESS, $strItem, $countItem);

        } else {
            return Code::statusDataReturn(Code::FAIL);
        }
    }
    /**
     * 发送消息
     * @param $toUsername
     * @param $keyword
     * @param $access_token
     * @return array
     */
    public function sendMessage($toUsername, $keyword, $access_token)
    {
        $url         = WeChat::MESSAGE_SEND_LINK . $access_token;
        $thisUser    = WeChat::ADMIN_ID_W;
        $thisContent = '通知:[' . $toUsername . ']发送的消息为[' . $keyword . ']';

        $str       = '{"touser": "%s", "msgtype": "%s", "text": {"content": "%s"}}';
        $data      = sprintf($str, $thisUser, WeChat::MSGTYPE_TEXT, $thisContent);
        $rstInfo   = curlHandlePost($url, $data);
        $json_data = json_decode($rstInfo['data']);

        return $json_data;
    }
    /**
     * 获取内容链接
     * @param $wid
     * @return string
     */
    private function getBodyLink($id)
    {
        if (is_numeric($id)) {

            return Yii::app()->params['url'] . "/Vsins/GetInfoCont/id/" . $id;
        } else {

            return 'http://wechatapp01.sinaapp.com/52easy/index.php';
        }
    }
    /**
     * 获取二维码连接
     * @param $wid
     * @return string
     */
    private function getCodeLink($uId)
    {
        if (!empty($uId)) {
            return Yii::app()->params['url'] . "/Vsins/AttionZ/uId/".$uId;
        } else {
            return 'http://wechatapp01.sinaapp.com/52easy/index.php';
        }
    }

    private function getTemplate($toUser,$templateId,$backUrl,$userName,$cmName,$date,$remark)
    {
        $json='{
                        "touser":"%s",
                        "template_id":"%s",
                        "url":"%s",
                        "topcolor":"#FF0000",
                        "data":{
                            "userName": {
                            "value":"%s",
                            "color":"#173177"
                            },
                            "courseName":{
                            "value":"%s",
                            "color":"#173177"
                            },
                            "date":{
                            "value":"%s",
                            "color":"#173177"
                            },
                            "remark":{
                            "value":"%s",
                            "color":"#173177"
                            }
                        }
                    }';
        return sprintf($json,$toUser,$templateId,$backUrl,$userName,$cmName,$date,$remark);
    }

}