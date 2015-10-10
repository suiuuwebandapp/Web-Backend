
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
<script>
    function changeCode()
    {
        $('#codeImg').attr('src','/index/get-code')
    }
    function toRegister()
    {
        window.location.href='/we-chat/access-reg';
    }
    var isBinding=false;
    function binding()
    {
        if(isBinding)
        {
            alert("绑定中...");
            return;
        }
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
        isBinding=true;
        $.ajax({
            url :'/we-chat/binding',
            type:'post',
            data:{
                username:phone,
                _csrf: $('input[name="_csrf"]').val(),
                password:password
            },
            error:function(){
                isBinding=false;
                alert("验证码发送失败");
            },
            success:function(data){
                isBinding=false;
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