<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/4/28
 * Time: 下午8:29
 */
?>

<body>

<!----------重设密码------->
<div id="resetPassword">
    <span>新密码：</span>
    <input id="password" type="password" value="" class="csmm-text">
    <span>确认密码：</span>
    <input id="confirmPassword" type="password" value="" class="csmm-text">
    <span>验证码:</span>
    <p class="p1 clearfix">
        <input id="code_1" type="text" value="" class="code">
        <img src="/index/get-code" alt="" class="code-pic">
        <a href="#" class="change" onclick="getcode2()">换一个</a>
    </p>
    <p class="btns"><a href="#"  class="btn" onclick="resetPassword()">保存</a></p>
</div>
<!----------重设密码-----end--------->

</body>
<script>
    function getcode2(){
        $('#codeImg').attr('src','/index/get-code');
    }
    function resetPassword(){
    $.ajax({
        type: 'post',
        url: '/index/update-password',
        data: {
            password: $('#password').val(),
            confirmPassword:$('#confirmPassword').val(),
            code:$('#code_1').val(),
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
                Main.showTip(obj.data);
                window.location.href='/index'
            }else
            {
                Main.showTip(obj.data);

            }
        }
    });
    };
</script>