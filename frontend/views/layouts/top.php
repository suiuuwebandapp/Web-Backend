<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/24
 * Time : 下午2:35
 * Email: zhangxinmailvip@foxmail.com
 */

?>
<link rel="stylesheet" type="text/css" href="/assets/plugins/select2/select2_metro.css">
<script type="text/javascript" src="/assets/plugins/select2/select2.min.js"></script>
<style type="text/css">

    #emailTime input{
        font-size: 14px !important;
    }
    /*input:-webkit-autofill {
        -webkit-box-shadow: 0 0 0px 1000px #858585 inset;
    }*/
</style>
<?php if (isset($this->context->userObj)) { ?>
    <!--header开始-->
    <div class="header">
        <div class="header-main clearfix w1200">
            <div class="header-nav fl">
                <ul>
                    <li class="active"><a href="<?=Yii::$app->params['base_dir']; ?>">首页</a></li>
                    <li><a href="/view-trip/list">随游</a></li>
                    <li><a href="/destination/list">目的地</a></li>
                    <li><a href="/article">专栏</a></li>
                    <li><a href="/index/create-travel">发布随游</a></li>
                </ul>
            </div>
            <div class="header-right fr">
                <p class="search"><input type="text" value="" class="text-xqy"></p>
                <ul>
                    <li><a href="#" class="search-btn"></a></li>
                    <li class="xitong" id="suiuu-btn1"><a href="javascript:;"></a>

                        <div class="xit-sz">
                            <span class="jiao"></span>
                            <ol>
                                <li class="sx">私信</li>
                                <li class="xtxx">系统消息</li>
                            </ol>
                            <ul>
                                <li><img src="/assets/images/1.png"><span>昵称</span>

                                    <p>给您发了私信</p></li>
                                <li><img src="/assets/images/1.png"><span>昵称</span>

                                    <p>给您发了私信</p></li>
                                <li><img src="/assets/images/1.png"><span>昵称</span>

                                    <p>给您发了私信</p></li>
                                <li><img src="/assets/images/1.png"><span>昵称</span>

                                    <p>给您发了私信</p></li>
                                <li><img src="/assets/images/1.png"><span>昵称</span>

                                    <p>给您发了私信</p></li>
                                <li><img src="/assets/images/1.png"><span>昵称</span>

                                    <p>给您发了私信</p></li>
                                <li><img src="/assets/images/1.png"><span>昵称</span>

                                    <p>给您发了私信</p></li>
                                <li><img src="/assets/images/1.png"><span>昵称</span>

                                    <p>给您发了私信</p></li>
                            </ul>
                        </div>
                    </li>
                    <li class="name" id="suiuu-btn2">
                        <img src="<?= $this->context->userObj->headImg; ?>" alt="">
                        <a href="javascript:;"><?= $this->context->userObj->nickname; ?></a>
                        <img src="/assets/images/header-icon.png" alt="" width="14" height="7" class="w20">

                        <div class="my-suiuu">
                            <span class="jiao"></span>
                            <ul>
                                <li class="bg1"><a href="javascript:;">我的随游</a></li>
                                <li class="bg2"><a href="javascript:;">我的订单</a></li>
                                <li class="bg3"><a href="zhanghao_shezhi.html">账号设置</a></li>
                                <li class="bg4"><a href="/login/logout">注销账号</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!--header结束-->
<?php } else { ?>
    <!--nav begin-->
    <div class="nav-out">
        <div class="nav-content w1200 clearfix">
            <h2 class="logo"><a href="#"><img src="/assets/images/nav-ico.png" width="120" height="42"></a></h2>

            <div class="nav">
                <ul>
                    <li class="active"><a href="<?=Yii::$app->params['base_dir']; ?>">首页</a></li>
                    <li><a href="/view-trip/list">随游</a></li>
                    <li><a href="/destination/list">目的地</a></li>
                    <li><a href="/article">专栏</a></li>
                    <li><a href="/index/create-travel">发布随游</a></li>
                </ul>
            </div>
            <div class="nav-right">
                <p class="search"><input type="text" value="" class="text-xqy"></p>
                <ol>
                    <li><a href="#" class="search-btn"></a></li>
                    <li><a href="javascript:;" id="zhuce">注册</a>

                        <div id="zhuce-main">
                            <a href="#" class="tab-title tab-title01">邮箱注册</a>
                            <p class="m-20"><label>国家</label>
                                <select id="codeId_top" name="countryIds" class="areaCodeSelect_top" required>
                                <option value=""></option>
                                <?php foreach ($this->context->countryList as $c) { ?>
                                    <?php if(empty($c['areaCode'])){continue;} ?>
                                    <?php if ($c['areaCode'] == $this->context->areaCode) { ?>
                                        <option selected
                                                value="<?= $c['areaCode'] ?>"><?= $c['cname'] . " " . $c['areaCode'] ?></option>
                                    <?php } else { ?>
                                        <option value="<?= $c['areaCode'] ?>"><?= $c['cname'] . " " . $c['areaCode'] ?></option>
                                    <?php } ?>

                                <?php } ?>
                            </select></p>
                            <p class="m-20"><label>手机号</label><input id="phone_top" type="text" value="" maxlength="30"></p>
                            <p><label>密码</label><input id="phone_password_top" type="text" value=""></p>
                            <p class="shuaxin"><input type="text" value="" id="phoneCode_top"><font><a href="javascript:;" id="getCodePhoneRegister">获取验证码</a></font></p>
                            <p class="zidong"><a href="javascript:;" class="xieyi">网站注册协议</a>
                                <input type="checkbox" id="zhuce-check01">
                                <label for="zhuce-check01" class="check">同意</label></p>
                            <a href="javascript:;" class="btn01" id="phoneRegister">注 册</a>
                            <div class="out-p clearfix">
                                <a href="/access/connect-weibo" class="logo-icon icon01"></a>
                                <a href="#" class="logo-icon icon02"></a>
                                <a href="/access/connect-qq" class="logo-icon icon03"></a>
                            </div>

                        </div>
                        <div id="zhuce-main02">
                            <a href="#" class="tab-title tab-title02">手机注册</a>

                            <p class="m-20"><label>邮箱</label><input type="text" value="" id="regEmail"></p>

                            <p><label>密码</label><input type="password" value="" id="regEmailPwd"></p>

                            <p class="shuaxin" id="emailTime"><input type="button" value="发送成功，"/></p>

                            <p class="zidong"><a href="javascript:;" class="xieyi">网站注册协议</a>
                                <input type="checkbox" value="同意" id="zhuce-check02">
                                <label for="zhuce-check02" class="check">同意</label>
                            </p>
                            <a href="javascript:;" class="btn01" id="emailRegister">注 册</a>
                            <div class="out-p clearfix"><a href="#" class="logo-icon icon01"></a>
                                <a href="#" href="#" class="logo-icon icon03"></a>
                            </div>
                        </div>
                    </li>
                    <li><a href="javascript:;" id="denglu">登录</a>

                        <div id="denglu-main">
                            <p><label>邮箱/手机号</label><input type="text" value="" id="username"> </p>

                            <p><label>密码</label><input type="password" value="" id="userpassword"></p>

                            <p class="fogot"><a href="/index/password-send-code">忘记密码</a></p>
                            <div id="code9527" class="form-group" style="padding-bottom: 20px;display: none">
                                <script async type="text/javascript" src="http://api.geetest.com/get.php?gt=b3a60a5dd8727fe814b43fce2ec7412a"></script>
                            </div>
                            <p class="zidong">
                                <input type="checkbox" id="logo-check" value="自动登录" />
                                <label for="logo-check" class="check">自动登录</label>
                            </p>

                            <a href="#" class="btn01" onclick="login()" id="login-check">立即登录</a>

                            <div class="out-p clearfix">
                                <a href="/access/connect-weibo" class="logo-icon icon01"></a>
                                <a href="#" class="logo-icon icon02"></a>
                                <a href="/access/connect-qq" class="logo-icon icon03"></a>
                            </div>
                        </div>
                    </li>
                </ol>
            </div>
        </div>
    </div>
    <!--nav end-->

<?php } ?>

<!--nav end-->


<script type="text/javascript">
    var emailTime="<?= array_key_exists('emailTime',$this->params)?$this->params['emailTime']:0;?>";
    var emailTimer;
    $(document).ready(function(){
        initEmailTimer();
        initPhoneRegister();
    });
    function initEmailTimer()
    {
        emailTimer=window.setInterval(function(){
            if(emailTime>0){
                emailTime--;
                initEmailTime();
            }else{
                window.clearInterval(emailTimer);
                initEmailTime();
            }
        },1000);
    }

    function initEmailTime(){
        //emailTime!=""&&emailTime>0
        if(emailTime!=""&&emailTime>0){
            $("#emailTime").show();
            $("#emailTime input").val("发送成功，"+emailTime+"秒后可重新发送");
            $("#emailRegister").attr("disabled","disabled");

            $("#emailRegister").css("color","gray");
            $("#emailRegister").unbind("click");
        }else{
            $("#emailTime input").val("");
            $("#emailRegister").removeAttr("disabled");
            $("#emailRegister").css("color","#ffeb81");
            $("#emailTime").hide();
            $("#emailRegister").unbind("click");
            $("#emailRegister").bind("click",function(){
                emailRegister();
            });
        }
    }

    /**
     * 邮箱注册
     * @returns {boolean}
     */
    function emailRegister() {
        var email = $("#regEmail").val();
        var password = $("#regEmailPwd").val();

        if(!$("#zhuce-check02").is(":checked")){
            Main.showTip("请同意《网站注册协议》");
            return;
        }
        if(email.length>30||email.length<6){
            Main.showTip("邮箱长度必须在6~30个字符之间");
            return false;
        }else{
            var regexp = /[\w!#$%&'*+/=?^_`{|}~-]+(?:\.[\w!#$%&'*+/=?^_`{|}~-]+)*@(?:[\w](?:[\w-]*[\w])?\.)+[\w](?:[\w-]*[\w])?/;
            var matches = regexp.exec(email);
            if(matches==null){
                Main.showTip("邮箱格式不正确");
                return false;
            }
        }
        if(password.length>30||password.length<6){
            Main.showTip("密码长度必须在6~30个字符之间");
            return false;
        }

        $.ajax({
            type: 'post',
            url: '/index/send-email',
            data: {
                email: email,
                password: password,
                _csrf: $('input[name="_csrf"]').val()
            },
            beforeSend: function () {
                //Main.showTip('正在提交，请稍后。。。');
            },
            error:function(){
                Main.showTip("系统异常。。。");
            },
            success: function (data) {
                var datas=eval('('+data+')');
                if(datas.status==1){
                    emailTime=datas.data;
                    initEmailTimer();
                }else{
                    //do something
                    Main.showTip(datas.data);
                }
            }
        });
    }

</script>
<script>
    var challenge='';
    var validate='';
    var seccode='';
    var _isCode=false;
    function gt_custom_ajax(result, selector, message) {
        if (result) {
            challenge = selector(".geetest_challenge").value;
             validate = selector(".geetest_validate").value;
             seccode = selector(".geetest_seccode").value;
            _isCode=false;
            //当验证成功时，获取相应input的值，并做ajax验证请求
        }else
        {
            _isCode=true;
        }
    }
    function login()
    {
        var username = $("#username").val();
        var password = $("#userpassword").val();
        var remember = $("#logo-check").is(":checked");

        if(username=='')
        {
            Main.showTip('用户名不能为空');
        }else if(password=='')
        {
            Main.showTip('密码不能为空');
        }else if(_isCode)
        {
            Main.showTip('验证失败');
        }else{
        $.ajax({
            type: 'post',
            url: '/index/login',
            data: {
                username: username,
                password: password,
                remember:remember,
                geetest_challenge:challenge,
                geetest_validate: validate,
                geetest_seccode:seccode,
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
                    window.location.reload();
                }else
                {$('.gt_refresh_button')[0].click();
                    Main.showTip(obj.data);
                    if(obj.message>=2)
                    {
                        $("#code9527").css('display','block');
                    }
                }
            }
        });
        }
    }
</script>

<script>
    var phoneTimer;
    var phoneTime=0;
    function initPhoneRegister()
    {
        phoneTimer=window.setInterval(function(){
            if(phoneTime>0){
                phoneTime--;
                $("#getCodePhoneRegister").html(phoneTime+"秒后可发送");

                $("#getCodePhoneRegister").unbind("click");
            }else{
                window.clearInterval(phoneTimer);
                $("#getCodePhoneRegister").html("发送验证码");
                $("#getCodePhoneRegister").bind("click",function(){
                    getCodePhoneRegister();
                });
            }
        },1000);


    }
    function getCodePhoneRegister()
    {
        var phone=$('#phone_top').val();
        var password=$('#phone_password_top').val();
        var areaCode = $("#codeId_top").val();

        if(phone=='')
        {
            Main.showTip("手机号不能为空");
        }else if(password=='')
        {
            Main.showTip("密码不能为空");
        }else if(areaCode=='')
        {
            Main.showTip("国家区号不能为空");
        }else
        {
            $.ajax({
                type: 'post',
                url: '/index/send-message',
                data: {
                    phone:phone,
                    password:password,
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
                        $("#phoneRegister").bind("click",function(){
                            phoneRegister();
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
    }
    function phoneRegister()
    {
        var code=$('#phoneCode_top').val();
        if(code=='')
        {
            Main.showTip('验证码不能为空');
        }else{
            if(!$("#zhuce-check01").is(":checked")){
                Main.showTip("请同意《网站注册协议》");
                return;
            }
            $.ajax({
                type: 'post',
                url: '/index/phone-register',
                data: {
                    code:code,
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
                        Main.showTip("注册成功即将跳转");
                        setTimeout(function(){ window.location.reload();},500)
                    }else
                    {
                        Main.showTip(obj.data);

                    }
                }
            });
        }
    }
</script>
<script>
    $(document).ready(function () {
        //初始化区号选择
        $(".areaCodeSelect_top").select2({
            'width':'174px',
            formatNoMatches: function () {
                return "暂无匹配";
            }
        });

    });
</script>