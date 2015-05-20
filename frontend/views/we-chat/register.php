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
</head>

<body>
<nav>注册</nav>
<div class="con Registered clearfix">
    <form action="/we-chat/register" method="post" id="wechat_register">
    <ul class="lists clearfix">
        <li>
            <label for="">国家</label>
            <input id="wechat_country" type="text" areaCode="<?php echo $c;?>" class="country" value="<?php echo $n;?>"  onclick="toCountry()">
        </li>
        <li>
            <label for="">手机号</label>
            <input name="phone" type="text" id="wechat_phone"><a href="javascript:;" class="code" onclick="getCode()">获取验证码</a>
        </li>
        <li>
            <label for="">验证码</label>
            <input name="validateCode" type="text">
        </li>
        <li>
            <label for="">密码</label>
            <input name="password" type="password">
        </li>
        <li>
            <label for="">确认密码</label>
            <input name="cPassword" type="password">
        </li>
    </ul>
    <a href="javascript:;" class="btn" onclick="submit()">立即注册</a>
    </form>
</div>
<script>
    function submit()
    {
        $('#wechat_register').submit();
    }
    function toCountry()
    {
        window.location.href='/we-chat/show-country';
    }
    function getCode()
    {
        var phone = $('#wechat_phone').val();
        var areaCode = $('#wechat_country').attr('areaCode');
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
        $.ajax({
            url :'/app-login/get-phone-code',
            type:'post',
            data:{
                areaCode:areaCode,
                phone:phone
            },
            error:function(){

                alert("验证码发送失败");
            },
            success:function(data){
                //hide load
                data=eval("("+data+")");
                if(data.status==1){
                    alert("发送成功，请注意查收");
                }else{
                    alert(data.data[0]);
                }
            }
        });
    }
</script>
</body>

</html>
