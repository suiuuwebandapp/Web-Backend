<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title>随游</title>
    <link rel="stylesheet" href="/assets/other/weixin/css/common.css">
    <link rel="stylesheet" href="/assets/other/weixin/css/jquery.mmenu.css">
    <link rel="stylesheet" href="/assets/other/weixin/css/weixin.css">
    <script type="text/javascript" src="/assets/other/weixin/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="/assets/other/weixin/js/jquery.mmenu.min.js"></script>
    <script type="text/javascript" src="/assets/other/weixin/js/weixin.js"></script>
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

<body onload="showHtml()">

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
        <p class="navTop">工作</p>
        <a href="javascript:;" class="sures" onclick="submitUserInfo()">确定</a>
    </div>
    <div class="con cshezhi_work clearfix">
        <ul class="list clearfix">
            <li  onclick="hiddenInput_('持证导游')"> <input type="radio" name="sex" id="rad01"><label for="rad01">持证导游</label></li>
            <li onclick="hiddenInput_('业余导游')"><input type="radio" name="sex" id="rad02"><label for="rad02" >业余导游</label></li>
            <li onclick="hiddenInput_('学生')" class="last"><input type="radio" name="sex" id="rad03"><label for="rad03" >学生</label></li>
            <li onclick="hiddenInput_('旅游爱好者')"> <input type="radio" name="sex" id="rad04"><label for="rad04" >旅游爱好者</label></li>
            <li  onclick="showInput_()" class="other"><input type="radio" name="sex" id="rad05"><label for="rad05">其他</label></li>
        </ul>
        <input id="val_work" type="text" placeholder="请输入其他职业" style="display: none">
    </div>
    <form style="display: none" action="/wechat-user-info/update-user-info" method="post" id="userInfo" >
        <input name="profession" id="val_sub">
    </form>
</div>
<script>
    var val="";
    function showInput_()
    {
        $("#val_work").show();
    }
    function hiddenInput_(str)
    {
        val=str;
        $("#val_work").hide();
    }
    function submitUserInfo()
    {
        if(val==""){
        val= $("#val_work").val();
        }
        if(val=="")
        {alert("请输入工作");return;}
        $("#val_sub").val(val);
        $("#userInfo").submit();
    }
</script>
</body>
</html>