<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title>绑定</title>
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
</head>

<body class="bgwhite">
<div id="page" class="userCenter">
    <?php include "left.php"; ?>
    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        <p class="navTop">绑定账号</p>
    </div>
<div class="con bangding02 clearfix">
    <ul class="lists clearfix">
        <li>
            <label for="">随游账号</label>
            <input id="phone" type="text" placeholder="输入已注册的邮箱/手机号">
        </li>
        <li>
            <label for="">密码</label>
            <input id="password" type="password" placeholder="密码">
        </li>
        <!--<li><label for="">验证码</label></li>
        <li class="clearfix">
            <input type="text" class="w70" placeholder="图形验证码" id="valNum"><a href="" class="code"><img style="height:2.7rem;" onclick="changeCode()" src="/index/get-code"></a>
        </li>-->
        <li class="btns clearfix"><a href="javascript:;" class="btn btn01" onclick="binding()">绑定账号</a><a href="/we-chat/password-view" class="fr forget">忘记密码？</a></li>
    </ul>
    <div class="down clearfix">
        <ul class="lists clearfix">
            <li>
                <label for="">没有随游账号</label>
                <input type="text" class="country" value="注册账号" onclick="toRegister()">
            </li>
        </ul>

    </div>
    <input id="r_url" hidden="hidden" value="<?php echo Yii::$app->session->get('r_url');?>">
</div>
</div>
<script>
    function changeCode()
    {
        $('#codeImg').attr('src','/index/get-code')
    }
    function toRegister()
    {
        window.location.href='/we-chat/access-reg';
    }
    function binding()
    {
        var phone = $('#phone').val();
        //var valNum = $('#valNum').val();
        var password = $('#password').val();
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
        $.ajax({
            url :'/we-chat/binding',
            type:'post',
            data:{
                username:phone,
                password:password
            },
            error:function(){
                alert("验证码发送失败");
            },
            success:function(data){
                //hide load
                data=eval("("+data+")");
                if(data.status==1){
                    if($('#r_url').val())
                    {
                        window.location.href=$('#r_url').val();
                        return;
                    }
                    window.location.href="/wechat-trip";
                }else{
                    alert(data.data);
                }
            }
        });
    }
</script>
</body>
</html>
