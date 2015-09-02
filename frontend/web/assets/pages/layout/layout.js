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



function initSocketConnection() {
    // 创建websocket
    ws = new WebSocket("ws://58.96.191.44:7272");
    // 当socket连接打开时，输入用户名
    ws.onopen = function () {
        if(reconnect == false) {
            // 登录
            var login_data = JSON.stringify({"type":"login","user_key":sessionId});
            console.log("socket connection success");
            ws.send(login_data);
            reconnect = true;
        }else{
            // 断线重连
            var relogin_data = JSON.stringify({"type":"re_login","user_key":sessionId});
            console.log("socket reconnection success");
            ws.send(relogin_data);
        }
    };
    // 当有消息时根据消息类型显示不同信息
    ws.onmessage = function (e) {
        console.log(e.data);
        var data = JSON.parse(e.data);
        switch(data['type']){
            // 服务端ping客户端
            case 'ping':
                ws.send(JSON.stringify({"type":"pong"}));
                break;;
            // 登录 更新用户列表
            case 'login':
                console.log(data['client_name']+"登录成功");
                break;
            // 断线重连，只更新用户列表
            case 're_login':
                console.log(data['client_name']+"重连成功");
                break;
            // 发言
            case 'say':
                //{"type":"say","from_client_id":xxx,"to_client_id":"all/client_id","content":"xxx","time":"xxx"}
                newMessageProcess(data);
                break;
            // 用户退出 更新用户列表
            case 'logout':
                console.log("用户退出了登录");

        }

    };
    ws.onclose = function () {
        console.log("连接关闭，定时重连");

    };
    ws.onerror = function () {
        console.log("出现错误");
    };
}



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
        initSocketConnection();
        setTopUnReadMessageCount(topNewMessageCount);

        //initUserMessageInfoList();
        //topMessageInterval=window.setInterval(function(){
        //    initUserMessageInfoList();
        //},10000);
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

var newMessageProcess=function (messageInfo)
{
    var userHtml="";

    var nickname=messageInfo.sender_name;
    if(nickname.length>5){
        nickname=nickname.substring(0,5);
    }
    userHtml+='<li class="message"><a style="width: 240px;height: 40px" href="/user-info?tab=myMessage"><img src="'+messageInfo.sender_HeadImg+'"><span>'+nickname+'</span>';
    userHtml+='<p>给您发了私信</p>';
    userHtml+='</a></li>';
    if(userHtml==""){
        userHtml='<li><p style="text-align: center;width: 240px">暂无私信消息</p></li>';
    }

    $("#unReadUserMessageList").html(userHtml+$("#unReadUserMessageList").html());
    var nowUserMessageCount=$("#unReadUserMessageList li[class='message']").size();
    if(nowUserMessageCount>maxMessageCount){
        $("#unReadUserMessageList li[class='message']").last().remove();
    }
    $("#moreUserMessage").show();
    setTopUnReadMessageCount(1);

    if(typeof(messageSessionList)!="undefined"){
        var tempMessage={};
        tempMessage.headImg=messageInfo.sender_HeadImg;
        tempMessage.isRead=0;
        tempMessage.lastConcatTime=messageInfo.time;
        tempMessage.lastContentInfo=messageInfo.content;
        tempMessage.nickname=messageInfo.sender_name;
        tempMessage.relateId=messageInfo.receive_id;
        tempMessage.sessionId=0;
        tempMessage.sessionKey=messageInfo.session_key;
        tempMessage.userId=messageInfo.sender_id;


        rebuildMessageSessionList(new Array(tempMessage),2);
    }
}

var setTopUnReadMessageCount=function(count) {
    if($("#topNewMessageCount").html()==""){
        count=parseInt(count)
    }else{
        count=parseInt(count)+parseInt($("#topNewMessageCount").html());
    }
    if(count>0){
        $("#topNewMessageCount").show();
        $("#topNewMessageCount").html(count);
        $("#noUserMessage").hide();
        if(count>maxMessageCount){
            $("#moreUserMessage").show();
        }else{
            $("#moreUserMessage").hide();
        }
    }else{
        $("#topNewMessageCount").hide();
        $("#topNewMessageCount").html("");
        $("#noUserMessage").show();
        $("#moreUserMessage").hide();


    }


};


function buildSysMessageListHtml(list){
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

function buildUserMessageListHtml(list){
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
        if(messageInfo.relateId!=SystemMessage.userId){
            nickname=messageInfo.nickname;
            if(nickname.length>5){
                nickname=nickname.substring(0,5);
            }
            userHtml+='<li><a style="width: 240px;height: 40px" href="/user-info?tab=myMessage"><img src="'+messageInfo.headImg+'"><span>'+nickname+'</span>';
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
                    window.location.href="/user-info?tab=myMessage";
                }
            }else{

            }
        }
    });
}

function initEmailTimer(){
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
        $("#emailRegister").removeAttr("disabled");
        $("#emailRegister").css("background","#858585");
        $("#emailRegister").unbind("click");
    }else{
        $("#emailTime input").val("");
        $("#emailTime").hide();
        $("#emailRegister").removeAttr("disabled");
        $("#emailRegister").css("background","#3dd9c3");
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
    var nickname = $("#regNickname").val();
    var email = $("#regEmail").val();
    var password = $("#regEmailPwd").val();


    if(nickname.length>20){
        Main.showTip("昵称不能大于20字符");
        return false;
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
    if(!$("#zhuce-check02").is(":checked")){
        Main.showTip("请同意《服务协议、退款政策、版权声明、免责声明》");
        return;
    }
    $.ajax({
        type: 'post',
        url: '/index/send-email',
        data: {
            nickname:nickname,
            email: email,
            password: password,
            _csrf: $('input[name="_csrf"]').val()
        },
        beforeSend: function () {
            $("#emailRegister").attr("disabled","disabled");
            $("#emailRegister").css("background","#858585");
            $("#emailTime").show();
            $("#emailTime input").val("正在发送验证邮件，请稍后...");
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
                $("#emailRegister").removeAttr("disabled");
                $("#emailRegister").css("background","#3dd9c3");
                $("#emailTime").hide();
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

function login(){
    var username = $("#username_bottom").val();
    var password = $("#userpassword_bottom").val();
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
                var obj= $.parseJSON(data);
                if(obj.status==1){
                    if(window.location.href.indexOf("result?result=")==-1){
                        window.location.reload();
                    }else{
                        window.location.href="/";
                    }
                }else{
                    Main.showTip(obj.data);
                    if(obj.message>=3){
                        $('.gt_refresh_button')[0].click();
                        $("#code9527").css('display','block');
                    }
                }
            }
        });
    }
}

function initPhoneRegister(){
    phoneTimer=window.setInterval(function(){
        if(phoneTime>0){
            phoneTime--;
            $("#getCodePhoneRegister").html(phoneTime+"秒后可发送");
            $("#getCodePhoneRegister").unbind("click");
        }else{
            window.clearInterval(phoneTimer);
            $("#getCodePhoneRegister").html("发送验证码");
            $("#getCodePhoneRegister").bind("click",function(){
                $("#getCodePhoneRegister").removeAttr("disabled");
                $("#getCodePhoneRegister").css("background","#FFAA00");
                getCodePhoneRegister();
            });
        }
    },1000);
}

function getCodePhoneRegister(){
    var nickname=$('#nickname_top').val();
    var phone=$('#phone_top').val();
    var password=$('#phone_password_top').val();
    var areaCode = $("#codeId_top").val();
    var valNum = $("#valNum").val();
    if(nickname==''){
        Main.showTip("昵称不能为空");
        return false;
    }else if(phone==''){
        Main.showTip("手机号不能为空");
        return false;
    }else if(password==''){
        Main.showTip("密码不能为空");
        return false;
    }else if(areaCode==''){
        Main.showTip("国家区号不能为空");
        return false;
    }else if(valNum==''){
        Main.showTip("请输入图形验证码");
        return false;
    }else{
        $.ajax({
            type: 'post',
            url: '/index/send-message',
            data: {
                nickname:nickname,
                phone:phone,
                password:password,
                areaCode:areaCode,
                valNum:valNum,
                _csrf: $('input[name="_csrf"]').val()
            },
            beforeSend: function () {
                //Main.showTip('正在提交，请稍后。。。');
                $("#getCodePhoneRegister").attr("disabled","disbaled");
                $("#getCodePhoneRegister").css("background","#858585");
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
                    $("#getCodePhoneRegister").removeAttr("disabled");
                    $("#getCodePhoneRegister").css("background","#FFAA00");
                    Main.showTip(obj.data);
                }
            }
        });
    }
}

function phoneRegister(){
    var code=$('#phoneCode_top').val();
    var password=$("#phone_password_top").val();
    var nickname=$('#nickname_top').val();

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
                nickname:nickname,
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

function initBottomTab(){
    var href=window.location.href;
    var tabId='';
    var tabArr=[]
    try{
        if(href.indexOf("/static")!=-1&&href.indexOf("?")!=-1){
            tabId=href.substring(href.indexOf("?")+1,href.length);
            tabArr=tabId.split("-");
            $("#"+tabArr[0]).click();
            $("#"+tabArr[1]).click();
        }
    }catch (e){}

}

function sendFeedback(){
    var content =$('#feedback_content').val();
    var username=$('#username_static').val();
    var phone=$('#phone_static').val();
    var email=$('#email_static').val();
    var chkType=$("input[type='radio'][name='rad']:checked").val();
    if(content=='')
    {
        Main.showTip('请输入反馈后提交');
        return;
    }
    if(chkType==1&&phone==''&&email==''){
        Main.showTip("手机或者邮箱必须填写一种");
        return;
    }
    $.ajax({
        url: "/user-feedback/web-create-feedback",
        type: "post",
        data:{
            content:content,
            username:username,
            email:email,
            phone:phone,
            _csrf: $('input[name="_csrf"]').val()
        },
        error:function(){
            Main.showTip('反馈异常');
        },
        success: function(data){
            var result=eval("("+data+")");
            if(result.status==1){
                Main.showTip('反馈成功，感谢您的反馈');
            }else{
                Main.showTip(result.data);
            }
        }
    });
}

function loadDes(search){
    search= $.trim(search);
    if(search==''){
        return '';
    }
    var allList=$.parseJSON(searchList);
    var rst=[];
    for(var i=0;i<allList.length;i++){
        var t=allList[i].cname+allList[i].ename;
        if(t.toLowerCase().indexOf(search.toLowerCase())!=-1){
            rst.push(allList[i]);
        }
    }
    return rst;
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

    initEmailTimer();
    initPhoneRegister();
    initTopMessage();
    initBreadcrumb();
    initBottomTab();

    $("#userpassword_bottom").keypress(function(e){
        if(e.keyCode==13){
            $("#login-check").click();
        }
    });
    $("#search-ipt").keypress(function(e){
        if(e.keyCode==13){
            $(".search-btn").click();
        }
    });

    $("#sendFeedback").bind("click",function(){
        sendFeedback();
    });

    $("#search").on('input',function(e){
        var search= $.trim($(this).val());
        if(search==''){
            $("#searchDrop").html("");
            $("#searchDrop").hide();
            return;
        }

        var rst=loadDes(search);

        if(rst!=null&&rst.length>0){
            var html='';
            for(var j=0;j<rst.length;j++){
                var temp=rst[j];
                var name=temp.cname+"\\"+temp.ename;
                var tripCountHtml='<b>'+temp.count+'条随游</b>';
                if(name.length>10){
                    name=name.substring(0,10)+"...";
                }

                html+='<li><a href="'+UrlManager.getTripSearchUrl(temp.cname)+'">';
                html+=name+tripCountHtml;
                html+='</a></li>'
                if(j==6){
                    break;
                }
            }
            $("#searchDrop").html(html);
            $("#searchDrop").show();
        }else{
            $("#searchDrop").html("");
            $("#searchDrop").hide();
        }
    });

});



