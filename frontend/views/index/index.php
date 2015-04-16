<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/7
 * Time : 上午9:38
 * Email: zhangxinmailvip@foxmail.com
 */

phpinfo();exit;
?>
<script type="text/javascript" src="/assets/d74e916a/yii.js"></script>
<script type="text/javascript" src="/assets/d74e916a/yii.validation.js"></script>
<div>
    邮箱<input id="email" type="text"/>
    密码<input id="emailPwd" type="password"/>
    确认密码 <input id="emailConfirmPwd" type="password"/>

    <input id="btnEmail" type="button" value="邮箱注册"/>

    <br/><br/>

    手机<input id="phone" type="text"/>
    密码<input id="phonePwd" type="password"/>
    确认密码 <input id="phoneConfirmPassword" type="password"/>
    验证码<input id="phoneValidateCode" type="text"/>
    <input id="sendPhoneCode" type="button" value="获取验证码">
    <input id="btnPhone" type="button" value="手机注册"/>

    <br/><br/>
    <a href="<?= $weiboUrl?>">微博登陆</a>&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="https://api.weibo.com/oauth2/authorize?client_id=123050457758183&redirect_uri=http://www.example.com/response&response_type=code">微信登陆</a>&nbsp;&nbsp;&nbsp;&nbsp;

    <a href="https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=101206430&redirect_uri=http://local.suiuu.com/access/qq-login&state=asdfasd">QQ登陆</a>&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="/access/connect-qq">QQ登陆</a>&nbsp;&nbsp;&nbsp;&nbsp;

</div>


<script type="text/javascript">

    $(document).ready(function () {
        $("#btnEmail").bind("click", function () {
            emailRegister();
        });
    });


    function accessQQ(){

    }
    /**
     * 邮箱注册
     * @returns {boolean}
     */
    function emailRegister() {
        var email = $("#email").val();
        var password = $("#emailPwd").val();
        var passwordConfirm = $("#emailConfirmPwd").val();

        if(email.length>30||email.length<6){
            alert("邮箱长度必须在6~30个字符之间");
            return false;
        }else{
            var regexp = /[\w!#$%&'*+/=?^_`{|}~-]+(?:\.[\w!#$%&'*+/=?^_`{|}~-]+)*@(?:[\w](?:[\w-]*[\w])?\.)+[\w](?:[\w-]*[\w])?/;
            var matches = regexp.exec(email);
            if(matches==null){
                alert("邮箱格式不正确");
                return false;
            }
        }
        if(password.length>30||password.length<6){
            alert("密码长度必须在6~30个字符之间");
            return false;
        }
        if(password!=passwordConfirm){
            alert("两次输入密码不一致");
            return false;
        }


        $.ajax({
            type: 'post',
            url: '/index/email-register',
            data: {
                email: email,
                password: password,
                passwordConfirm: passwordConfirm,
                _csrf: $('input[name="_csrf"]').val()
            },
            beforeSend: function () {
                alert('正在提交，请稍后。。。');
            },
            error:function(){
                alert("系统异常。。。");
            },
            success: function (data) {
                var datas=eval('('+data+')');
                if(datas.status==1){
                    //do something
                    alert(datas.data);
                }else{
                    //do something
                    alert(datas.data);
                }
            }
        });
    }
    function sendPhoneCode() {

    }
    function printObject(obj){
        var temp = "";
        for(var i in obj){//用javascript的for/in循环遍历对象的属性
            temp += i+":"+obj[i]+"\n";
        }
        alert(temp);
    }

</script>