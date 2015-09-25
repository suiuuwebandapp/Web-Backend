<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title>注册</title>
    <link rel="stylesheet" href="/assets/other/weixin/css/common.css">
    <script type="text/javascript" src="/assets/other/weixin/js/jquery-1.11.1.min.js"></script>
    <link rel="stylesheet" href="/assets/other/weixin/css/weixin.css">
    <link rel="stylesheet" href="/assets/other/weixin/css/jquery.mmenu.css">
    <script type="text/javascript" src="/assets/other/weixin/js/jquery.mmenu.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $('div#menu').mmenu();
        });
    </script>
    <script>
        function showHtml()
        {
            $("#page").show();
            $("#loading").hide();
        }
    </script>
    <link rel="stylesheet" href="/assets/other/weixin/css/loading.css">
</head>

<body  class="bgwhite" onload="showHtml()">

<div id="loading" class="overlay">
    <div class="spinner" id="loading">
        <div class="rect1"></div>
        <div class="rect2"></div>
        <div class="rect3"></div>
        <div class="rect4"></div>
        <div class="rect5"></div>
    </div>
</div>
<div id="page" hidden="hidden" class="userCenter">
    <?php include "left.php"; ?>
    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        <p class="navTop">注册</p>
    </div>
<div class="con RegisteredPhone clearfix">
    <form action="/we-chat/register" method="post" id="wechat_register">
    </form>
    <ul class="lists clearfix">
        <li>
            <input id="wechat_country" type="text" placeholder="国家" areaCode="<?php echo $c;?>" class="country" value="<?php echo $n;?>"  onclick="toCountry()">
        </li>
        <li>
            <input type="text" id="wechat_phone" placeholder="手机号">
        </li>
        <li>
            <input type="password" id="wechat_password" placeholder="密码">
        </li>
        <li class="clearfix">
            <input type="text" class="w70" placeholder="手机验证码" id="wechat_code"><a href="javascript:;" class="code" onclick="getCode()">获取验证码</a>
        </li>
    </ul>
    <a href="javascript:;" class="btn" onclick="register()">注册</a>
    <p class="agr"><input type="checkbox" id="agreement"><label for="agreement">同意<a href="###">《网站注册协议》</a></label></p>
    <div class="down clearfix">
        <div class="line"></div>
        <span>快速登录</span>
        <div class="ddd clearfix"><a href="/access/connect-weibo?str=wap" class="icon sina"></a><a href="/access/connect-wechat-js" class="icon wei"></a><a href="#" class="icon qq"></a></div>

        <input id="r_url" hidden="hidden" value="<?php echo Yii::$app->session->get('r_url');?>">
    </div>
</div>
</div>
<script>
    function changeCode()
    {
        $('#codeImg').attr('src','/index/get-code')
    }

    var isClick=false;
    function register()
    {
        var code=$('#wechat_code').val();
        var phone = $('#wechat_phone').val();
        var areaCode = $('#wechat_country').attr('areaCode');
        //var valNum = $('#wechat_valNum').val();
        var password = $('#wechat_password').val();
        if(isClick)
        {
            alert("注册中...");
            return;
        }
        if(areaCode=="")
        {
            alert('国家不能为空');
            return;
        }
        if(phone=="")
        {
            alert('手机号不能为空');
            return;
        }
        /*if(valNum=="")
        {
            alert('图型验证码不能为空');
            return;
        }*/
        if(password=="")
        {
            alert('密码不能为空');
            return;
        }
        if(code=="")
        {
            alert('验证码不能为空');
            return;
        }
        if(!$('#agreement').is(':checked'))
        {
            alert('请同意网站注册协议');
            return;
        }
        isClick=true;

        $.ajax({
            url :'/we-chat/phone-register',
            type:'post',
            data:{
                code:code
            },
            error:function(){
                isClick=false;
                alert("注册失败");
            },
            success:function(data){
                isClick=false;
                //hide load
                data=eval("("+data+")");
                if(data.status==1){
                    if($('#r_url').val())
                    {
                        window.location.href=$('#r_url').val();
                        return;
                    }
                    window.location.href="/wechat-trip/index";
                }else{
                    alert(data.data);
                }
            }
        });
    }
    function toCountry()
    {
        window.location.href='/we-chat/show-country?rUrl=/we-chat/register';
    }
    var isSend=false;
    function getCode()
    {
        var phone = $('#wechat_phone').val();
        var areaCode = $('#wechat_country').attr('areaCode');
        //var valNum = $('#wechat_valNum').val();
        var password = $('#wechat_password').val();
        if(isSend)
        {
            alert("发送中");
            return;
        }
        if(areaCode=="")
        {
            alert('验证码不能为空');
            return;
        }
        if(phone=="")
        {
            alert('手机号不能为空');
            return;
        }
        /*if(valNum=="")
        {
            alert('图型验证码不能为空');
            return;
        }*/
        if(password=="")
        {
            alert('密码不能为空');
            return;
        }
        isSend=true;
        $.ajax({
            url :'/we-chat/send-message',
            type:'post',
            data:{
                areaCode:areaCode,
                phone:phone,
                password:password
            },
            error:function(){
                alert("验证码发送失败");
                isSend=false;
            },
            success:function(data){
                isSend=false;
                //hide load
                data=eval("("+data+")");
                if(data.status==1){
                    alert("发送成功，请注意查收");
                }else{
                    alert(data.data);
                }
            }
        });
    }
</script>
</body>

</html>
