/**
 * Created by suiuu on 15/6/1.
 */
var emailTimer;
var topMessageInterval;
var user_message_count=0;
var sys_message_count=0;
var challenge='';
var validate='';
var seccode='';
var _isCode=false;
var phoneTimer;
var phoneTime=0;

$(document).ready(function(){
    initEmailTimer();
    initPhoneRegister();
    initTopMessage();
    initBreadcrumb();
    $("#userpassword").keypress(function(e){
        if(e.keyCode==13){
            $("#login-check").click();
        }
    });
    $("#search-ipt").keypress(function(e){
        if(e.keyCode==13){
            $(".search-btn").click();
        }
    });

});

function initBreadcrumb(){
    var href=window.location.href;
    if(href.indexOf("?")!=-1){
        href=href.substring(0,href.indexOf("?"));
    }
    $(".menu_ul li").each(function(){
        var a=$(this).find("a");
        if(href.indexOf($(a).attr("href"))!=-1){
            $(".menu_ul li").removeClass("active");
            $(this).addClass("active");
        }
    });
}
function initTopMessage(){
    //如果用户登录了，查询是否有新私信
    if(isLogin==1){
        initUserMessageInfoList();
        topMessageInterval=window.setInterval(function(){
            initUserMessageInfoList();
        },10000);
    }

    $(".search-btn").bind("click",function(){
        var search=$("#search-ipt").val();
        if(search!=""){
            window.location.href="/search?s="+search;
        }
    });


    $("#userMessageLiBtn").bind("click",function(){
        $("#userMessageLiBtn").addClass("active");
        $("#sysMessageLiBtn").removeClass("active");
        $("#unReadUserMessageList").show();
        $("#unReadSystemMessageList").hide();
    });

    $("#sysMessageLiBtn").bind("click",function(){
        $("#userMessageLiBtn").removeClass("active");
        $("#sysMessageLiBtn").addClass("active");
        $("#unReadUserMessageList").hide();
        $("#unReadSystemMessageList").show();
    });
}

function initUserMessageInfoList()
{
    $.ajax({
        type: 'post',
        url: '/user-message/un-read-message-info-list',
        data: {
            _csrf: $('input[name="_csrf"]').val()
        },
        error:function(){
            window.clearInterval(topMessageInterval);
        },
        success: function (data) {
            var datas=eval('('+data+')');
            if(datas.status==1){
                sys_message_count=0;
                user_message_count=0;
                buildUserMessageListHtml(datas.data['userList']);
                buildSysMessageListHtml(datas.data['sysList']);
                initTopMessageSelect();
            }else{

            }
        }
    });
}
function buildSysMessageListHtml(list)
{
    if(list==""||list.length==0){
        $("#unReadSystemMessageList").html('<li><p style="text-align: center">暂无系统消息</p></li>');
        return;
    }
    var sysHtml="",messageInfo="",nickname="";
    for(var i=0;i<list.length;i++){
        if(i==7){
            break;
        }
        messageInfo=list[i];
        var content=messageInfo.content;
        if(content.length>12){
            content=content.substring(0,12)+"...";
        }
        sysHtml+='<li onclick="changeSystemMessageRead('+messageInfo.messageId+',\''+messageInfo.url+'\')">';
        sysHtml+='<p>'+content+'</p>';
        sysHtml+='</li>';
        sys_message_count++;
    }
    $("#unReadSystemMessageList").html(sysHtml);
}
function initTopMessageSelect(){

    if(sys_message_count>0){
        $("#suiuu-btn1").addClass("active");
        $("#sysMessageLiBtn").click();
    }
    if(user_message_count>0){
        $("#suiuu-btn1").addClass("active");
        $("#userMessageLiBtn").click();

    }
}

function buildUserMessageListHtml(list)
{
    if(list==""||list.length==0){
        $("#unReadUserMessageList").html('<li><p style="text-align: center;width: 240px">暂无私信消息</p></li>');
        return;
    }
    var userHtml="",messageInfo="",nickname="";
    for(var i=0;i<list.length;i++){
        if(i==7){
            break;
        }
        messageInfo=list[i];
        if(messageInfo.userId!=SystemMessage.userId){
            nickname=messageInfo.nickname;
            if(nickname.length>5){
                nickname=nickname.substring(0,5);
            }
            userHtml+='<li><a style="width: 240px;height: 40px" href="/user-info?myMessage"><img src="'+messageInfo.headImg+'"><span>'+nickname+'</span>';
            userHtml+='<p>给您发了私信</p>';
            userHtml+='</a></li>';
            user_message_count++;

        }
    }
    if(userHtml==""){
        userHtml='<li><p style="text-align: center;width: 240px">暂无私信消息</p></li>';
    }
    $("#unReadUserMessageList").html(userHtml);
}

function changeSystemMessageRead(messageId,url){
    $.ajax({
        type: 'post',
        url: '/user-message/change-system-message-read',
        data: {
            messageId:messageId,
            _csrf: $('input[name="_csrf"]').val()
        },
        error:function(){
            window.clearInterval(topMessageInterval);
        },
        success: function (data) {
            var datas=eval('('+data+')');
            if(datas.status==1){
                if(url!=""){
                    window.location.href=url;
                }else{
                    window.location.href="/user-info?myMessage";
                }
            }else{

            }
        }
    });
}

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
        $("#emailRegister").css("color","white");
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
    if(!$("#zhuce-check02").is(":checked")){
        Main.showTip("请同意《服务协议、退款政策、版权声明、免责声明》");
        return;
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
                    if(window.location.href.indexOf("result?result=")==-1){
                        window.location.reload();
                    }else{
                        window.location.href="/";
                    }

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
    var valNum = $("#valNum").val();

    if(phone=='')
    {
        Main.showTip("手机号不能为空");
    }else if(password=='')
    {
        Main.showTip("密码不能为空");
    }else if(areaCode=='')
    {
        Main.showTip("国家区号不能为空");
    }else if(valNum==''){
        Main.showTip("请输入图形验证码");
    }else
    {
        $.ajax({
            type: 'post',
            url: '/index/send-message',
            data: {
                phone:phone,
                password:password,
                areaCode:areaCode,
                valNum:valNum,
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
    var password=$("#phone_password_top").val();
    if(code=='')
    {
        Main.showTip('验证码不能为空');
    }else{
        if(!$("#zhuce-check01").is(":checked")){
            Main.showTip("请同意《服务协议、退款政策、版权声明、免责声明》");
            return;
        }
        $.ajax({
            type: 'post',
            url: '/index/phone-register',
            data: {
                code:code,
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
                    Main.showTip("注册成功即将跳转");
                    if(window.location.href.indexOf("result?result=")==-1){
                        window.location.reload();
                    }else{
                        window.location.href="/";
                    }
                }else
                {
                    Main.showTip(obj.data);

                }
            }
        });
    }
}
$(document).ready(function () {
    //初始化区号选择
    $(".areaCodeSelect_top").select2({
        'width':'288px',
        'height':'40px',
        formatNoMatches: function () {
            return "暂无匹配";
        }
    });

});

