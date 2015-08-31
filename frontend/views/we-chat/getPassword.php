<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title>密码找回</title>
    <link rel="stylesheet" href="/assets/other/weixin/css/common.css">
    <link rel="stylesheet" href="/assets/other/weixin/css/weixin.css">
    <script type="text/javascript" src="/assets/other/weixin/js/jquery-1.11.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/assets/plugins/select2/select2_metro.css">
    <script type="text/javascript" src="/assets/plugins/select2/select2.min.js"></script>
    <style>
        .formTip{
            float: right;
            margin-right: 80px;
            font-size: 14px;
            height: 2.7rem;
        }
        .accAreaCodeSelect{
            font-size: 16px;
        }
        .accAreaCodeSelect .select2-choice{
            border-radius:0px !important;
            background: #eee !important;
            color: #858585;
            text-align: center;
            font-size: 0.85rem;
            padding: 10px;
            height: 2.7rem;
        }

        .select2-hidden-accessible{
            display: none;
        }
    </style>
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
        <p class="navTop">找回密码</p>
    </div>
<div class="con findPassword02 clearfix">
    <ul class="lists clearfix">
        <li>
            <label>区号</label>
            <select id="accCodeId" name="countryIds" class="accAreaCodeSelect"  >
                <option value=""></option>
                <?php if($countryList!=null){ ?>
                    <?php foreach ($countryList as $c) { ?>
                        <?php if(empty($c['areaCode'])){continue;} ?>
                        <?php if ($c['areaCode'] == $areaCode) { ?>
                            <option selected
                                    value="<?= $c['areaCode'] ?>"><?= $c['cname'] . " " . $c['areaCode'] ?></option>
                        <?php } else { ?>
                            <option value="<?= $c['areaCode'] ?>"><?= $c['cname'] . " " . $c['areaCode'] ?></option>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            </select>
        </li>
        <li>
            <input  id="phone" type="text" placeholder="手机号">
            <a href="javascript:;" class="code colOrange" onclick="getCode()">获取验证码</a>
        </li>
        <li>
            <label>验证码</label>
            <input id="code" type="text" >
        </li>
        <li>
            <label>输入新密码</label>
            <input id="password" type="password" >
        </li>
    </ul>
    <a href="javascript:;" class="btn" onclick="updatePassword()">确定</a>

</div>
</div>
<script>
    $(document).ready(function () {
        //初始化区号选择
        $(".accAreaCodeSelect").select2({
            'width':'100%',
            'height':'100%',
            formatNoMatches: function () {
                return "暂无匹配";
            }
        });

    });
    function getCode()
    {
        var phone = $('#phone').val();
        var areaCode = $("#accCodeId").val();
        if(areaCode=="")
        {
            alert('区号不能为空');
            return;
        }
        if(phone=="")
        {
            alert('手机号不能为空');
            return;
        }
        $.ajax({
            url :'/we-chat/password-code',
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
                    alert(data.data);
                }
            }
        });
    }
    function updatePassword()
    {
        var phone = $('#phone').val();
        var code = $("#code").val();
        var password = $("#password").val();
        if(code=="")
        {
            alert('验证码不能为空');
            return;
        }
        if(phone=="")
        {
            alert('手机号不能为空');
            return;
        }
        if(password=="")
        {
            alert('密码不能为空');
            return;
        }
        $.ajax({
            url :'/we-chat/update-password',
            type:'post',
            data:{
                code:code,
                password:password,
                phone:phone
            },
            error:function(){
                alert("验证码发送失败");
            },
            success:function(data){
                //hide load
                data=eval("("+data+")");
                if(data.status==1){
                    alert("修改成功即将跳转");
                    window.location.href="/wechat-user-center";
                }else{
                    alert(data.data);
                }
            }
        });
    }
</script>
</body>
</html>
