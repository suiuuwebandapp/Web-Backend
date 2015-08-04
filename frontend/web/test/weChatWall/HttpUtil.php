<?php
/**
 * Created by PhpStorm.
 * User: XiMing
 * Date: 15-1-12
 * Time: 下午10:24
 */
require_once('FileUtil.php');
class HttpUtil{
    public $userAgent = 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36';
    public $getHeader ="1";
    public  $referer;//引用地址
    public $origin = 'https://mp.weixin.qq.com';
    function  __construct(){
        $this->cookie =\FileUtil::GetFile('cookie.txt');
    }
    function GetContent($url){
        $this->url = $url;
        $header = array(
            'Accept:*/*',
            'Accept-Encoding:gzip,deflate,sdch',
            'Accept-Language:zh-CN,zh;q=0.8',
            'Connection:keep-alive',
            'Host:mp.weixin.qq.com',
            'Cookie:noticeLoginFlag=1; ts_refer=www.baidu.com/link; ts_uid=2795363840; noticeLoginFlag=1; 3g_guest_id=-9129512921284435968; pt_clientip=05297f0000010693; pt_serverip=a2ad0aab402cbea1; ptui_loginuin=519414839; pgv_info=ssid=s1015664861; pgv_pvid=2857987892; o_cookie=519414839; pt2gguin=o0519414839; uin=o0519414839; skey=@wMLhHgZe0; ptcz=e28aa9c4e39598e3489890f607de71f1978e0591ebc1e5659d440340870ca20d; qm_username=519414839; qm_sid=78876dd55696110be450dd3b6cf35485,cPC3BxvqaF1Y.; ptisp=cm; data_bizuin=3013299910; data_ticket=AgXIR4DeKjiI8U7iT2MbsnCxAwEmRM0PPFAjRE3oNC8=; wx_csrf_cookie=9319b60bc247c7e125a4f8d0d22b22d3; slave_user=gh_ddba3bfc5646; slave_sid=ZklvMHY3THdvZ3Y5TXN3U2Jnc2FZRnl4VVRCcXFHOFdVMGh6cVRnWXJCeExCMV9pVm1BSlBCdXdXNG8xZ0dDczZYWWZRUzc3N29PblBIWWpmM1kwTjJ0YXJCMk5ud2NZU0txOGVCcHNla0lmcDJXVFNqUlA4dkM4UzBabXBJeUw=; bizuin=3077371673',
            'Referer:'. "https://mp.weixin.qq.com/cgi-bin/message?t=message/list&count=20&day=7&token=821765461&lang=zh_CN",
            'X-Requested-With:XMLHttpRequest'
        );
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header); //设置HTTP头字段的数组
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, $this->userAgent); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_HTTPGET, 1); // 发送一个常规的GET请求
        curl_setopt($curl, CURLOPT_COOKIEFILE,  $this->cookie); // 读取上面所储存的Cookie信息
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, $this->getHeader); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        curl_setopt($curl, CURLOPT_ENCODING ,'gzip');
        $tmpInfo = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            // echo 'Errno'.curl_error($curl);
        }
        curl_close($curl); // 关闭CURL会话
        return mb_convert_encoding($tmpInfo, 'utf-8', 'GBK,UTF-8,ASCII');; // 返回数据
    }

    function PostContent($url,$send_data){
        if(isset($this->referer)){
            $this->referer = 'https://mp.weixin.qq.com/';
        }
        $header = array(
            'Accept:*/*',
            'Accept-Charset:GBK,utf-8;q=0.7,*;q=0.3',
            'Accept-Encoding:gzip,deflate,sdch',
            'Accept-Language:zh-CN,zh;q=0.8',
            'Connection:keep-alive',
            'Host:mp.weixin.qq.com',
            'Referer:'.$this->referer,
            'X-Requested-With:XMLHttpRequest',
            'Origin:'.$this->origin
        );
        $curl = curl_init(); //启动一个curl会话
        curl_setopt($curl, CURLOPT_URL, $url); //要访问的地址
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header); //设置HTTP头字段的数组
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); //对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); //从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, $this->userAgent); //模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); //使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); //自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); //发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $send_data); //Post提交的数据包
        curl_setopt($curl, CURLOPT_COOKIEJAR,  $this->cookie); //读取储存的Cookie信息
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); //设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, $this->getHeader); //显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); //获取的信息以文件流的形式返回
        $result = curl_exec($curl); //执行一个curl会话
        curl_close($curl); //关闭curl
        return $result;
    }
}