
    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        <p class="navTop">邮箱认证</p>
        <a href="javascript:;" class="sures" onclick="submitUserInfo()">确定</a>
    </div>
    <div class="con cshezhi_ziliaoSet clearfix">
        <input id="email" type="text" placeholder="请输入邮箱" value="<?=$userInfo["email"]?>">
    </div>
    <form style="display: none" action="/wechat-user-info/update-user-info" method="post" id="userInfo" >
        <input name="email" id="val_sub">
        <input type="hidden" name="_csrf" value="<?php echo Yii::$app->request->getCsrfToken()?>">
    </form>
<script>
    function submitUserInfo()
    {
        var email = $('#email').val();
        if(email=="")
        {
            alert('email不能为空');
            return;
        }
        $.ajax({
            url :'/wechat-user-info/send-validate-mail',
            type:'post',
            data:{
                _csrf: $('input[name="_csrf"]').val(),
                mail:email
            },
            error:function(){
                alert("发送失败");
            },
            success:function(data){
                //hide load
                data=eval("("+data+")");
                if(data.status==1){
                    alert("验证邮件已发送，请登录邮箱完成验证修改");
                    window.location.href="/wechat-user-info/info";
                }else{
                    alert(data.data);
                }
            }
        });
    }
</script>