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
</head>

<body class="bgwhite">
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

</div>
<script>
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
                alert("登陆异常");
            },
            success:function(data){
                //hide load
                data=eval("("+data+")");
                if(data.status==1){
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
