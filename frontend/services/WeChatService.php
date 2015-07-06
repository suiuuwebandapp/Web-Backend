<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 15-3-13
 * Time: 下午3:50
 * To change this template use File | Settings | File Templates.
 */
namespace frontend\services;

use common\components\Code;
use common\components\Common;
use common\entity\UserAccess;
use common\entity\WeChat;
use common\entity\WeChatUserInfo;
use common\models\WeChatDb;
use yii;
use common\models\BaseDb;
use yii\base\Exception;

class WeChatService extends BaseDb{

    private $strItemRemain="";
    private $numbItemRemain=0;
    private $addStr= '<item>
                    <Title><![CDATA[查看更多请输入“更多” ]]></Title>
                    <Description><![CDATA[查看更多信息请输入“更多”]]></Description>
                    <PicUrl><![CDATA[]]></PicUrl>
                    </item>';
    public $baseUrl='';
    public $weChatDb;
    function __construct()
    {
        $this->baseUrl=Yii::$app->params['weChatUrl'];

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
                      "name":"随游服务",
                       "sub_button":[
                        {
                           "type":"view",
                           "name":"定制",
                           "url":"http://www.suiuu.com/we-chat-order-list/order-manage"
                        },
                       {
                          "type":"view",
                           "name":"随游",
                           "url":"http://www.suiuu.com/wechat-trip"
                        }]
                    },
                    {
                      "name":"精彩活动",
                       "sub_button":[
                       {
                           "type":"click",
                           "name":"活动",
                           "key":"EVENT_KEY_ACTIVE"
                        },
                        {
                           "type":"click",
                           "name":"随游全球",
                           "key":"EVENT_KEY_DESTINATION"
                        }
                       ]
                    },
                    {
                        "type":"view",
                        "name":"个人中心",
                        "url":"http://www.suiuu.com/wechat-user-center"

                    }
                    ]
                    }';
        /*$url_1 ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
        $app_id = WeChat::APP_ID;
        $url_test1_1  =  $this->baseUrl."/we-chat/get-code?actionType=11";//购课放在了第一位
        $url_test1_2 =  $this->baseUrl ."/we-chat/get-code?actionType=12";
        $url_test1_1_s=sprintf($url_1,$app_id,urlencode($url_test1_1));
        $url_test1_2_s=sprintf($url_1,$app_id,urlencode($url_test1_2));
        $data      = sprintf($string,$url_test1_1_s,$url_test1_2_s);*/
        $rstInfo   = $this->CurlHandel($url,$string);
        $json_data = json_decode($rstInfo['data']);
        return  $json_data;

    }




    public function getVsinsInfo($type,$imageHost)
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
                    <PicUrl><![CDATA[' . $imageHost . $value['picurl'] . ']]></PicUrl>
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
        $thisUser    = WeChat::ADMIN_ID_J;
        $thisContent = '通知:[' . $toUsername . ']发送的消息为[' . $keyword . ']';

        $str       = '{"touser": "%s", "msgtype": "%s", "text": {"content": "%s"}}';
        $data      = sprintf($str, $thisUser, WeChat::MSGTYPE_TEXT, $thisContent);
        $rstInfo   = $this->CurlHandel($url, $data);
        $json_data = json_decode($rstInfo['data']);

        return $json_data;
    }

    public function sendTemplateMessage($access_token,$toUser,$backUrl,$userName,$cmName,$date,$remark)
    {
        $url = WeChat::MESSAGE_SEN_TEMPLATE . $access_token;
        $templateId=WeChat::TEMPLATE_ID_FOR_RESERVE;
        $rst = $this->CurlHandel($url, $this->getTemplate($toUser,$templateId,$backUrl,$userName,$cmName,$date,$remark));
        return $rst;
    }
    /**
     * 获取内容链接
     * @param $wid
     * @return string
     */
    private function getBodyLink($id)
    {
        if (is_numeric($id)) {

            return  $this->baseUrl . "/we-chat/get-info-cont/id/" . $id;
        } else {

            return   $this->baseUrl;
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


    public function getUserInfo(WeChatUserInfo $weChatUserInfo)
    {
        try {
            $conn = $this->getConnection();
            $this->weChatDb=new WeChatDb($conn);
            return $this->weChatDb->findWeChatUserInfo($weChatUserInfo);
        } catch (Exception $e) {
            throw new Exception('得到用户信息异常', Code::FAIL, $e);
        } finally {
            $this->closeLink();
        }
    }

    /**
     * 插入用户
     * @param $arr
     * @return int
     * @throws Exception
     */
    public function insertWeChatInfo($arr)
    {
        try {
            $weChatUserInfo=$this->arr2WeChatUserInfo($arr);
            if($weChatUserInfo->openId=='')
            {
               throw new Exception('添加用户信息异常', Code::FAIL);
            }
            $userBaseSer=new UserBaseService();
            $userBase=$userBaseSer->findUserAccessByOpenIdAndType($weChatUserInfo->unionID,UserAccess::ACCESS_TYPE_WECHAT);
            if(!empty($userBase))
            {
                $weChatUserInfo->userSign=$userBase->userSign;
            }
            $conn = $this->getConnection();
            $this->weChatDb=new WeChatDb($conn);
            return $this->weChatDb->addWeChatUserInfo($weChatUserInfo);
        } catch (Exception $e) {
            throw new Exception('添加用户信息异常', Code::FAIL, $e);
        } finally {
            $this->closeLink();
        }
    }
    /**
     * 更新用户数据
     * @param $arr
     * @param $userSign
     * @return int
     * @throws Exception
     */
    public function upDateWeChatInfo($arr,$userSign)
    {
        try {
            $weChatUserInfoOld =new WeChatUserInfo();
            $weChatUserInfoOld->userSign=$userSign;
            $weChatUserInfo=$this->arr2WeChatUserInfo($arr,$weChatUserInfoOld);
            if($weChatUserInfo->openId=='')
            {
                throw new Exception('更新用户信息异常', Code::FAIL);
            }
            $conn = $this->getConnection();
            $this->weChatDb=new WeChatDb($conn);
            return $this->weChatDb->updateWeChatUserInfo($weChatUserInfo);
        } catch (Exception $e) {
            throw new Exception('更新用户信息异常', Code::FAIL, $e);
        } finally {
            $this->closeLink();
        }
    }

    public function bindingWeChatByUnionID($userSign,$unionID)
    {
        try {
            $weChatUserInfo =new WeChatUserInfo();
            $weChatUserInfo->userSign=$userSign;
            $weChatUserInfo->unionID=$unionID;
            $conn = $this->getConnection();
            $this->weChatDb=new WeChatDb($conn);
            return $this->weChatDb->bindingWeChatByUnionID($weChatUserInfo);
        } catch (Exception $e) {
            throw new Exception('更新用户信息异常', Code::FAIL, $e);
        } finally {
            $this->closeLink();
        }
    }

    private function arr2WeChatUserInfo($arr,WeChatUserInfo $weChatUserInfoOld=null)
    {

        if(empty($weChatUserInfoOld))
        {
            $weChatUserInfo =new WeChatUserInfo();
        }else
        {
            $weChatUserInfo =$weChatUserInfoOld;
        }
        $weChatUserInfo->openId=isset($arr['openid'])?$arr['openid']:'';
        $weChatUserInfo->v_nickname=isset($arr['nickname'])?$arr['nickname']:'';
        $weChatUserInfo->v_sex=isset($arr['sex'])?$arr['sex']:0;
        $weChatUserInfo->v_language=isset($arr['language'])?$arr['language']:'';
        $weChatUserInfo->v_city=isset($arr['city'])?$arr['city']:'';
        $weChatUserInfo->v_province=isset($arr['province'])?$arr['province']:'';
        $weChatUserInfo->v_country=isset($arr['country'])?$arr['country']:'';
        $weChatUserInfo->v_headimgurl=isset($arr['headimgurl'])?$arr['headimgurl']:'';
        $weChatUserInfo->v_subscribe_time=isset($arr['subscribe_time'])?$arr['subscribe_time']:0;
        $weChatUserInfo->unionID=isset($arr['unionid'])?$arr['unionid']:'';
        $weChatUserInfo->v_remark=isset($arr['remark'])?$arr['remark']:'';
        $weChatUserInfo->v_groupid=isset($arr['groupid'])?$arr['groupid']:0;
        return $weChatUserInfo;
    }
    private function curlHandel($url,$data)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return Code::statusDataReturn(Code::SUCCESS,$output);
    }

}