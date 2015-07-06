<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/6/1
 * Time : 下午5:56
 * Email: zhangxinmailvip@foxmail.com
 */

$userBase=Yii::$app->session->get("regUserBase");
$userAccess=Yii::$app->session->get("regUserAccess");

$bindFlag=1;
if($userBase==null||$userAccess==null){
    $bindFlag=0;
}
?>v
<script type="text/javascript">
    var bindFlag="<?=$bindFlag?>";
    if(bindFlag==0){
        alert("您的第三方信息已经超时，请重新登录");window.location.href="/"
    }
</script>
<style>
    .formTip{
        float: right;
        margin-right: 80px;
        font-size: 14px;
    }
    .accAreaCodeSelect{
        font-size: 16px;
    }
    .accAreaCodeSelect .select2-choice{
        border-radius:0px !important;
        background: #eee !important;
        color: #858585;
    }
    .accAreaCodeSelect #select2-chosen-1{
        margin-top: 5px;
    }
    .select2-hidden-accessible{
        display: none;
    }
</style>

<div class="regBDing clearfix">
    <h2 class="tit">只差一步，即可完成登录设置</h2>
    <div class="left">
        <p class="p1">快速完成随游账号创建<br>
            完成账号创建后，即可直接登录随游哦！</p>
        <ul>
            <li>
                <label for=""><span>*</span>昵称<span id="accNicknameTip" class="formTip"></span></label>
                <input type="text" id="accNickname" value="<?=$userBase->nickname?>" maxlength="30">

            </li>
            <li>
                <label for=""><span>*</span>设定密码<span id="accPasswordTip" class="formTip"></span></label>
                <input type="password" id="accPassword" maxlength="30">

            </li>
            <li>
                <label for=""><span>*</span>区号</label>
                <select id="accCodeId" name="countryIds" class="accAreaCodeSelect" required>
                    <option value=""></option>
                    <?php if($this->context->countryList!=null){ ?>
                        <?php foreach ($this->context->countryList as $c) { ?>
                            <?php if(empty($c['areaCode'])){continue;} ?>
                            <?php if ($c['areaCode'] == $this->context->areaCode) { ?>
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
                <label for=""><span>*</span>手机号<span id="accPhoneTip" class="formTip"></span></label>
                <input type="text" id="accPhone" maxlength="20">

            </li>
            <li class="liTip" style="display: none">
                <p class="tip">手机号已注册，请在页面右侧直接绑定</p>

            </li>
            <li class="liCode" style="display: none">
                <label for=""><span>*</span>验证码</label>
                <p class="row">
                    <input type="text" class="codeText" maxlength="6" id="accCode">
                    <a href="javascript:;" class="codeBtn" id="getAccCodePhoneRegister">获取短信验证码</a>
                </p>

            </li>
            <li class="server">
                <input type="checkbox" id="ch4" class="input1"><label for="ch4" class="fleft">同意</label>
                <a class="agreen" href="/static?about-services" target="_blank">服务协议</a>
                <a class="agreen" href="/static?help-refundPolicy" target="_blank">退款政策</a>
                <a class="agreen" href="/static?statement-copyright" target="_blank">版权声明</a>
                <a class="agreen" href="/static?statement-disclaimer" target="_blank">免责声明</a>
            </li>
            <li class="btns"> <a href="javascript:;" id="accessRegister" class="btn">注册</a></li>

        </ul>



    </div>
    <div class="right">
        <p class="rtit">已有随游账号，直接绑定</p>
        <ul>
            <li>
                <label for="">邮箱/已验证手机</label>
                <input type="text" id="bindUsername" maxlength="30">

            </li>
            <li>
                <label for="">密码</label>
                <input type="password" id="bindPassword" maxlength="30">

            </li>
            <li>
                <label for="">验证码</label>
                <p class="row">
                    <input type="text" class="codeText" id="bindCodeNum" maxlength="4">
                    <img id="bindCodeImg" src="/index/get-code" onclick="javascript:$(this).attr('src','/index/get-code')"  class="codePic">
                </p>

            </li>
            <li class="btns">
                <a href="javascript:;" class="rbtn01" id="btnBindUser">绑定账号</a>
                <a href="/index/password-send-code" class="rfoget">忘记密码？</a>

            </li>
        </ul>
    </div>
</div>

<script type="text/javascript">

    (function($){
        var phoneTimer;
        var phoneTime=0;

        $(document).ready(function(){
            initSelect();
            initBindValidate();
            initPhoneRegister();
            $("#btnBindUser").bind("click",function(){
                bindUser();
            });
        });



        function accessRegister()
        {
            var nickname=$("#accNickname").val();
            var password=$("#accPassword").val();
            var code=$("#accCode").val();
            if(code=='')
            {
                Main.showTip('验证码不能为空');
            }else{
                if(!$("#ch4").is(":checked")){
                    Main.showTip("请同意《服务协议、退款政策、版权声明、免责声明》");
                    return;
                }
                $.ajax({
                    type: 'post',
                    url: '/access/access-register',
                    data: {
                        code:code,
                        nickname:nickname,
                        password:password,
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
                            Main.showTip("恭喜您已经完成注册，即将跳转");
                            window.location.href="/";
                        }else
                        {
                            Main.showTip(obj.data);

                        }
                    }
                });
            }
        }

        /**
         * 初始化国家城市SELECT
         */
        function initSelect(){
            $("#accCodeId").select2({
                'width':'288px',
                'height':'40px',
                formatNoMatches: function () {
                    return "暂无匹配";
                }
            });
        }

        /**
         * 初始化左侧菜单验证
         * @returns {boolean}
         */
        function validateLeftForm()
        {
            var flag=true;
            var nickname=$("#accNickname").val();
            var password=$("#accPassword").val();
            if(nickname==''){
                $("#accNicknameTip").html("请输入用户昵称");
                flag=false;
            }
            if(password==''||password.length<6){
                $("#accPasswordTip").html("密码格式不正确");
                flag=false;
            }
            return flag;
        }

        /**
         * 初始化手机注册Timer
         */
        function initPhoneRegister(){
            phoneTimer=window.setInterval(function(){
                if(phoneTime>0){
                    phoneTime--;
                    $("#getAccCodePhoneRegister").html(phoneTime+"秒后可发送");
                    $("#getAccCodePhoneRegister").unbind("click");
                }else{
                    window.clearInterval(phoneTimer);
                    $("#getAccCodePhoneRegister").html("发送验证码");
                    $("#getAccCodePhoneRegister").bind("click",function(){
                        getCodePhoneRegister();
                    });
                }
            },1000);
        }

        /**
         *  获取手机验证码
         */
        function getCodePhoneRegister()
        {
            var phone=$('#accPhone').val();
            var areaCode = $("#accCodeId").val();
            if(!validateLeftForm()){
                return;
            }

            $.ajax({
                type: 'post',
                url: '/index/acc-send-message',
                data: {
                    phone:phone,
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
                        phoneTime=obj.data;
                        $("#accessRegister").bind("click",function(){
                            accessRegister();
                        });
                        initPhoneRegister();
                        Main.showTip("发送成功请注意查收。。。");
                    }else
                    {
                        Main.showTip(obj.data);

                    }
                }
            });
        }


        /**
         * 绑定Input 验证
         */
        function initBindValidate(){
            $("#accNickname").bind("blur",function(){
                var nickname=$(this).val();
                if(Main.isNotEmpty(nickname)){
                    $("#accNicknameTip").html("");
                }else{
                    $("#accNicknameTip").html("昵称格式不正确");
                }
            });
            $("#accPassword").bind("blur",function(){
                var password=$(this).val();
                if(!Main.isNotEmpty(password)){
                    $("#accPasswordTip").html("密码格式不正确");
                }else if(password.length<6){
                    $("#accPasswordTip").html("密码长度不能小于6位");
                }else {
                    $("#accPasswordTip").html("");

                }
            });
            $("#accPhone").bind("blur",function(){
                var phone=$(this).val();
                if(!Main.isNotEmpty(phone)){
                    $("#accPhoneTip").html("请输入有效的手机号码");
                    return;
                }else{
                    $("#accPhoneTip").html("");
                }

                $.ajax({
                    type: 'post',
                    url: '/index/val-phone-exist',
                    data: {
                        phone:phone,
                        _csrf: $('input[name="_csrf"]').val()
                    },
                    success: function (data) {
                        var obj=eval('('+data+')');
                        if(obj.status==1){
                            if(obj.data==0){
                                $(".liCode").show();
                                $(".liTip").hide();
                            }else{
                                $(".liCode").hide();
                                $(".liTip").show();
                            }
                        }else if(obj.status==-2){
                            $("#accPhoneTip").html(obj.data);
                        }
                    }
                });
            });
        }

        function bindUser()
        {
            var username=$("#bindUsername").val();
            var password=$("#bindPassword").val();
            var valNum=$("#bindCodeNum").val();
            if(username==''||username.length<6){
                Main.showTip("请输入正确的手机或邮箱");
                return;
            }
            if(password==''||password.length<6){
                Main.showTip("请输入正确的密码");
                return;
            }
            if(valNum==''||valNum.length!=4){
                Main.showTip("请输入正确验证码");
                return;
            }

            $.ajax({
                type: 'post',
                url: '/access/bind-user',
                data: {
                    username:username,
                    password:password,
                    valNum:valNum,
                    _csrf: $('input[name="_csrf"]').val()
                },
                error:function(){
                    Main.showTip("系统异常。。。");
                },
                success: function (data) {
                    var obj=eval('('+data+')');
                    if(obj.status==1){
                        Main.showTip("恭喜您已经完成绑定，即将跳转");
                        window.location.href="/";
                    }else{
                        Main.showTip(obj.data);

                    }
                }
            });

        }


    })(jQuery);


</script>