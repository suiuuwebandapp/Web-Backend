<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/8
 * Time : 下午2:06
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\components;


use yii\web\Cookie;

class Mail {


    /**
     * 获取发送邮件内容
     * @param $link
     * @return string
     */
    private static function getRegisterHtml($link)
    {
        $html=sprintf('
            <p><img alt="随游欢迎您" src="http://suiuu.oss-cn-hongkong.aliyuncs.com/suiuu_email/header.png" style="width: 604px; height: 118px;" /></p>

            <div>
            <p style="font-family: microsoft yahei;">&nbsp;<strong>XXX</strong>, 您好，</p>

            <p style="width: 604px;font-family: microsoft yahei;">&nbsp; 欢迎你加入 随游，一个关于旅行以及分享的平台。 在这里你将结交来自全球的旅行爱好者，开启非同一般的旅行体验。</p>

            <p style="font-family: microsoft yahei;">&nbsp;请点击以下链接完成注册：</p>
            <a href="'.$link.'" style="font-family: microsoft yahei;">'.$link.'</a></div>

            <p style="display:inline">&nbsp;</p>

            <p style="display:inline">&nbsp;</p>

            <div style="float:left"><img alt="" src="http://suiuu.oss-cn-hongkong.aliyuncs.com/suiuu_email/QRcode.png" style="width: 100px; height: 100px; margin-left: 20px; margin-right: 20px;" />
            <p style="font-family: microsoft yahei;">扫码获取更多关于我们的信息</p>
            </div>

            <div style="margin-left:300px">
            <p style="display:inline;font-family: microsoft yahei;font-weight:bold;color:#0997F7;font-size: 15px;">随游运营团队</p>

            <p style="font-family: microsoft yahei;"><strong>Tel:</strong>+86(010)58483692</p>

            <p style="font-family: microsoft yahei;"><strong>Mail:</strong>info@suiuu.com</p>

            <p>系统发信，请勿回复</p>
            </div>

        ');

        return $html;
    }

    /**
     * SendCloud 发送邮件
     * @param $email
     * @param $link
     * @return array
     */
    public static function  sendRegisterMail($email,$link)
    {
        $url = 'http://sendcloud.sohu.com/webapi/mail.send.json';
        //不同于登录SendCloud站点的帐号，您需要登录后台创建发信子帐号，使用子帐号和密码才可以进行邮件的发送。
        $param = [
            'api_user' => 'suiuu_trigger',
            'api_key' => 'eqQ3SAr3dWFAbcIq',
            'from' => 'info@www.suiuu.com',
            'fromname' => '随游网',
            'to' => $email,
            'subject' => '感谢您注册随游网',
            'html' => self::getRegisterHtml($link)
        ];

        $options = [
            'http' => [
                'method' => 'POST',
                'header' => "Content-type: application/x-www-form-urlencoded ",
                'content' => http_build_query($param)
            ]
        ];
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        $result=json_decode($result);
        if($result->message=='success'){
            return Code::statusDataReturn(Code::SUCCESS);
        }else{
            return Code::statusDataReturn(Code::FAIL,$result->errors);
        }
    }


}