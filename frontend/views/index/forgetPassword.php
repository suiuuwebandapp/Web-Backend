<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/4/29
 * Time: 上午10:04
 */
?>

<link rel="stylesheet" type="text/css" href="/assets/plugins/select2/select2_metro.css">
<script type="text/javascript" src="/assets/plugins/select2/select2.min.js"></script>

<style type="text/css">
    .select2-container .select2-choice {
        background-color: #eee;
        border-radius: 0px;
        font-size: 13px;
        color: dimgray;
        padding-top:0 ;
        margin: 0 auto;
        border: none;

    }

    .select2-drop {
        font-size: 14px;
    }

    .select2-highlighted {
        background-color: #0088e4;
    }
    .select2-no-results {
        font-size: 13px;
        color: dimgray;
        text-align: center;
    }

</style>
<div class="forgotPaw con-nav" id="tab">
    <ul>
        <li><a href="javascrip:;"  class="active">邮箱找回</a></li>
        <li><a href="javascrip:;">手机找回</a></li>
    </ul>
    <div style="display:block;" class="TabCon">
        <span>绑定邮箱:</span>
        <input id="username_1" type="text" value="" class="wjmm-text">
        <span>验证码:</span>
        <p>

            <input id="sendCode_1" type="text" value="" class="zhsz-text1">
            <img id="codeImg" src="/index/get-code" alt="" class="csmm-img">
            <input type="button" value="换一个" class="zhsz-btn" onclick="getcode1()">
        </p>
        <input type="button" value="发送验证" class="tijiao" onclick="sendMail()">
    </div>

    <div class="TabCon">
        <span>手机号:</span>
        <p  class="p1">
        <span class="sect">
            <select id="codeId" name="countryIds" class="areaCodeSelect1" required>
                <option value=""></option>
                <?php foreach ($countryList as $c) { ?>
                    <?php if(empty($c['areaCode'])){continue;} ?>
                    <?php if ($c['areaCode'] == $areaCode) { ?>
                        <option selected
                                value="<?= $c['areaCode'] ?>"><?= $c['cname'] . " " . $c['areaCode'] ?></option>
                    <?php } else { ?>
                        <option value="<?= $c['areaCode'] ?>"><?= $c['cname'] . " " . $c['areaCode'] ?></option>
                    <?php } ?>

                <?php } ?>
            </select>
         </span>
            <input  id="username_2" type="text" value="" class="zhsz-text1 phone">
            <input type="button" value="获取验证码" class="zhsz-btn phone" onclick="sendPhone1()">
        </p>
        <span>输入验证码:</span>
        <input id="sendCode_2" type="text" value="" class="wjmm-text">
        <input type="button" value="确&nbsp;定" class="tijiao" onclick="ResetPassword()" >
    </div>

</div>
<script>
    function getcode1(){
        $('#codeImg').attr('src','/index/get-code');
    }
    function sendMail(){
        var username= $('#username_1').val()
        if(username=='')
        {
            Main.showTip("邮箱不能为空");
            return;
        }
        　var Regex = /^(?:\w+\.?)*\w+@(?:\w+\.)*\w+$/;
　　      if (!Regex.test(username)){
            Main.showTip("邮箱格式不正确");
            return;
        }
            $.ajax({
            type: 'post',
            url: '/index/password-send-code',
            data: {
                username: username,
                code:$('#sendCode_1').val(),
                _csrf: $('input[name="_csrf"]').val()
            },
            beforeSend: function () {
                //Main.showTip('正在提交，请稍后。。。');
            },
            error:function(){
                Main.showTip("系统异常。。。");
            },
            success: function (data) {
                var obj=eval('('+data+')');
                if(obj.status==1)
                {

                    Main.showTip('发送成功，请注意查收!');
                }else
                {
                    Main.showTip(obj.data);

                }
            }
        });
    };
    function sendPhone1(){
        var username= $('#username_2').val();
        var areaCode = $("#codeId").val();
        if(username=='')
        {
            Main.showTip("手机号不能为空");
            return;
        }
        $.ajax({
            type: 'post',
            url: '/index/password-send-code',
            data: {
                username: $('#username_2').val(),
                areaCode:areaCode,
                _csrf: $('input[name="_csrf"]').val()
            },
            beforeSend: function () {
                //Main.showTip('正在提交，请稍后。。。');
            },
            error:function(){
                Main.showTip("系统异常。。。");
            },
            success: function (data) {
                var obj=eval('('+data+')');
                if(obj.status==1)
                {

                    Main.showTip('发送成功，请注意查收!');
                }else
                {
                    Main.showTip(obj.data);

                }
            }
        });
    };
    function ResetPassword(){
        var username= $('#username_2').val()
        if(username=='')
        {
            Main.showTip("手机号不能为空");
            return;
        }
        $.ajax({
            type: 'post',
            url: '/index/reset-password',
            data: {
                u: $('#username_2').val(),
                code: $('#sendCode_2').val(),
                _csrf: $('input[name="_csrf"]').val()
            },
            beforeSend: function () {
                //Main.showTip('正在提交，请稍后。。。');
            },
            error:function(){
                Main.showTip("系统异常。。。");
            },
            success: function (data) {
                var obj=eval('('+data+')');
                if(obj.status==1)
                {
                    window.location.href='/index/reset-password-view'
                }else
                {
                    Main.showTip(obj.data);

                }
            }
        });

    };
</script>
<script>
    $(document).ready(function () {

        //初始化区号选择
        $(".areaCodeSelect1").select2({
            'width':'100px',
            'height':'20px',
            formatNoMatches: function () {
                return "暂无匹配";
            }
        });

    });
</script>