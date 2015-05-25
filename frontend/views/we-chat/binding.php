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
</head>

<body>
<div class="con bangding clearfix">
    <form action="/we-chat/binding" method="post" id="wechat_binding">
    <ul class="lists clearfix">
        <li>
            <label for="">邮箱/手机</label>
            <input name="username" type="text">
        </li>
        <li>
            <label for="">密码</label>
            <input name="password" type="password">
        </li>
    </ul>
    <a href="javascript:;" class="btn" onclick="binding()">立即绑定</a>
    </form>

</div>

<script>
    function binding()
    {
        if($('input[name=username]').val()=="")
        {
            alert("用户名不能为空");
            return ;
        }
       /* if($('input[name=password]').val()=="")
        {
            alert("密码不能为空");
            return ;
        }*/
        $('#wechat_binding').submit();
    }
</script>

</body>
</html>
