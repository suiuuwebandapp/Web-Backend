<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ma.Qiong
 * Date: 14-2-11
 * Time: 上午11:46
 * To change this template use File | Settings | File Templates.
 */
namespace common\entity;
class WeChat {

    /*------------------------配置---------------------------*/
    /**
     * 下班时间的时
     */
    const TIME_OUT = 17;
    /**
     * 下班时间的分
     */
    const TIME_OUT_I = 59;

    /*
     * 指令过期时间 多少秒
     * */
    const TIME_PAST = 300;
    /**
     * 下班后，对用户消息自动回复的语句。
     */
    const TIME_OUT_STRING = '对于我们没有及时回复您，表示歉意！对于您想要了解的问题，您也可以拨打我们的电话010-58483692';
    /**
     * click事件
     */
    const EVENT_CLICK_KEY_ACTIVE='EVENT_KEY_ACTIVE';
    const EVENT_CLICK_KEY_DESTINATION='EVENT_KEY_DESTINATION';

    const MSG_TXT_NO='暂无消息';
    const ATTENTION_REPLY_STR='[Rose]欢迎关注随游[Kiss]我们相信旅行从分享开始，我们相信旅行从来不需要等待，我们相信完美的旅行一直能够说走就走[Sun]你呢？准备好和我们一起出发了吗？欢迎随时吐槽，打字不爽就致电010-58483692，小游一直在这里等你哦[Beckon][Lips]';
    //const AUTO_RETURN_STR="您的消息我们已经收到";

    /*---------------------------------------------------*/

    /**
     * 请注意，该测试号将于2015-04-30失效
     */
    const APP_ID_b = 'wx4ca6715e7a4927f5';
    const APP_SECRET_b = '49f38104bae28fde91084cb1a415e8bb';
    //const EncodingAESKey_b='qriyBwOVeMt96BBQtfFqLl4wGvHEcD01pFmTNRUCDXW';

    /**
     * 正式号
     */
    const APP_ID = 'wxe7a1b295cc37081e';
    const APP_SECRET = '8eeae1bbbedbe6f9cbd7545ef3afea6e';
    const EncodingAESKey='mUtuVfdLTFxbDz0utc63GMuCKLifL72L0pSZVdu1OIj';
    /*
     * 模板id
     * */
    const TEMPLATE_ID_FOR_RESERVE="yWu0jQ8QB6HM-CQrVUYhu232TAMGi-uGQnTTnr4OjJc";
    //状态变更
    const TEMPLATE_ID_FOR_STATUS_CHANGE='526NMMRJEoXa1WtavjekxRjyMTR7jtlZWNme0FlgzNs';
    /**
     * 记录token信息
     */
    const TOKEN_FILE_NAME = 'tokenWeChat';

    /**
     * 测试调试信息
     */
    const LOG_XXX_NAME = 'logWeChat.txt';

    /*
     * 模板消息的备注
     *
     */

    const TEMPLATE_REMARK='!';



    /**
     * 管理员微信号--
     * //测试号的时候需要更换
     */
    const ADMIN_ID_J = 'o4lPkjqEAsPdaCIuOPKHePlyEDjU';

    /**
     * 信息类型 - text
     */
    const MSGTYPE_TEXT = 'text';

    /**
     * 信息类型 - event
     */
    const MSGTYPE_EVENT = 'event';
    /**
     * 信息类型 - event
     */
    const MSGTYPE_LOCATION = 'location';


    /**
     * 信息类型-多客服
     */
    const MSGTYPE_DKF='transfer_customer_service';

    /**
     * 信息类型 -image
     */
    const MSGTYPE_IMG = 'image';

    /**
     * 信息类型 - voice
     */
    const MSGTYPE_VOICE = 'voice';

    /**
     * 信息类型 - voide
     */
    const MSGTYPE_VOIDE = 'voide';

    /**
     * 信息类型 - music
     */
    const MSGTYPE_MUSIC = 'music';

    /**
     * 信息类型 - news
     */
    const MSGTYPE_NEWS = 'news';

    /**
     * 事件-订阅，扫描二维码 未关注
     */
    const EVENT_SUBSCRIBE = 'subscribe';
    /**
     * 事件-订阅 扫描二维码-已关注
     */
    const EVENT_SCAN = 'scan';

    /**
     * 事件-报告地理位置
     */
    const EVENT_LOCATION = 'LOCATION';

    /**
     * 事件-点击 菜单点击事件
     */
    const EVENT_CLICK = 'CLICK';

    //普通消息
    const TEXT_TPL= "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                        </xml>";
    //图文消息
    const MSG_TPL = "<xml>
                                    <ToUserName><![CDATA[%s]]></ToUserName>
                                    <FromUserName><![CDATA[%s]]></FromUserName>
                                    <CreateTime>%s</CreateTime>
                                    <MsgType><![CDATA[%s]]></MsgType>
                                    <ArticleCount>%d</ArticleCount>
                                    <Articles>
                                    %s
                                    </Articles>
                                    </xml> ";



    /**
     * 发送消息-微信链接接口
     */
    const MESSAGE_SEND_LINK = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=';

    /**
     * 创建菜单-微信链接接口
     */
    const MENU_CREATE_LINK = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=';

    /**
     * Token-链接
     */
    const TOKEN_LINK = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=';

    /**
     * 删除菜单
     */
    const MENU_DELETE_LINK = 'https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=';

    /**
     * 有道翻译api 链接
     */
    const YOUDAO_API_LINK = 'http://fanyi.youdao.com/openapi.do?keyfrom=babieyu&key=348190789&type=data&doctype=json&version=1.1&q=';

    /*
     *获取用户信息
     * */
    const GET_USER_INFO = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=';
    /*
     * 创建二维码ticket
     * */
    const GET_TICKET_URL = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=';
    /*
     * 临时二维码json数据
     * */
    const POST_JSON_TEMP = '{"expire_seconds": 1800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": %d}}}';
    /*
     * 临时二维码json数据
     * */
    const POST_JSON_EIKY = '{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": %d}}}';
    /*
     * 获取关注二维码
     * */
    const GET_ATTENTION_IMAGE = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=';
    /*
    * 获取code
    * */
    const GET_OAUTH2_CODE = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect';

    const GET_OAUTH2_OPENID='https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code';

    const MESSAGE_SEN_TEMPLATE='https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=';
}