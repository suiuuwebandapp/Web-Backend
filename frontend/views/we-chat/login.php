<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title>登录</title>
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
        <p class="navTop">登陆</p>
    </div>
<div class="con login clearfix">
    <ul class="lists clearfix">
        <li>
            <input type="text" placeholder="邮箱/手机号" id="username">
        </li>
        <li>
            <input type="password" placeholder="密码" id="password">
        </li>
        <li class="clearfix" id="codeLi" style="display: none">
            <input type="text" class="w70" placeholder="图形验证码" id="codeNumber"><a href="javascript:;" class="code" onclick="changeCode()"><img id="codeImg" style="height: 2.7rem" src="/index/get-code"></a>
        </li>
    </ul>
    <p class="agr"><input type="checkbox" id="agreement"><label for="agreement">自动登陆</label> </p>
    <div class="clearfix">
        <a href="javascript:;" class="btn bgOrange" id="loginBtn">登录</a>
        <a href="/we-chat/password-view" class="colOrange forgot fl">忘记密码？</a><a href="/we-chat/register" class="colBlue log fr">立即注册？</a>
    </div>
    <div class="down clearfix">
        <div class="line"></div>
        <span >快速登录</span>
        <div class="ddd clearfix"><a href="/access/connect-weibo?str=wap" class="icon sina"></a><a href="/access/connect-wechat-js" class="icon wei"></a><a href="#" class="icon qq"></a></div>


    </div>
    <input id="r_url" hidden="hidden" value="<?php echo Yii::$app->session->get('r_url');?>">
</div>
</div>
<script>

    var isLogin=false;
    function changeCode()
    {
        $('#codeImg').attr('src','/index/get-code')
    }
    $('#loginBtn').bind("click",function(){
        var username=$('#username').val();
        var password=$('#password').val();
        var code=$('#codeNumber').val();
        var remember=$('#agreement').is(':checked');
        if(username=="")
        {
            alert("用户名不能为空");
            return;
        }
        if(password=="")
        {
            alert("密码不能为空");
            return;
        }
        if(isLogin)
        {
            alert("登录中");
            return;
        }
        isLogin=true;

        $.ajax({
            url :'/we-chat/login',
            type:'post',
            data:{
                username:username,
                password:password,
                code:code,
                remember:remember
            },
            error:function(){
                isLogin=false;
                alert("登陆异常");
            },
            success:function(data){
                isLogin=false;
                //hide load
                data=eval("("+data+")");
                if(data.status==1){
                    if($('#r_url').val())
                    {
                        window.location.href=$('#r_url').val();
                        return;
                    }
                    window.location.href="/wechat-trip/index";
                }else if(data.status==-3){
                    window.location.href=data.data;
                }else{
                    alert(data.data);
                    if(data.message>2)
                    {
                        $("#codeLi").show();
                    }
                }
            }
        });
    });

</script>

</body>
</html>
