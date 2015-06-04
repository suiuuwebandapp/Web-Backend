/**
 * Created by suiuu on 15/5/7.
 */

//第一页消息列表
var currentPageMessageSessionKeyList=[];
var messageSessionTimer;

var rotate;
var rotateCount=0;
var containerDivWidth=300;
var imgAreaSelectApi;

$(document).ready(function(){
    if(isPublisher){
        $("#myTripManager").bind("click",function(){
            getMyTripList();
        });
        $("#myJoinTripManager").bind("click",function(){
            getMyJoinTripList();
        });
        $("#unConfirmOrderManager").bind("click",function(){
            getUnConfirmOrderByPublisher();
        });
        $("#myPublisherOrder").bind("click",function(){
            getPublisherOrderList();
        });

        getUnConfirmOrderByPublisher();

    }else{
        $("#tripManager").parent("li").hide();
    }


    $("#validatePhone").bind("click",function(){
        initValidatePhone();
    });
    $("#validateEmail_info").bind("click",function(){
        initValidateEmail_info();
    });

    $("#password_update_info").bind("click",function(){
        initUpdatePassword();
    });
    $("#myComment").bind("click",function(){
        initMyComment(1);
    });
    $("#myCollect").bind("click",function(){
        initCollect();
    });

    $("#unFinishOrderManager").bind("click",function(){
        getUnFinishList();
    });
    $("#finishOrderManager").bind("click",function(){
        getFinishList();
    });

    $(".con-nav li").bind("click",function(){
        resetUploadHeadImg();
    });

    $("#updateInfoBtn").bind("click",function(){
        updateUserInfo();
    });

    //绑定发送验证码事件
    $("#getCode").bind("click", function () {
        sendTravelCode();
    });
    $("#sendMessageBtn").bind("click",function(){
        sendUserMessage();
    });
    $("#userMessageSetting").bind("click",function(){
        initUserMessageSetting();
    });
    $("input:radio[name='user_message_setting_status']").bind("change",function(){
       updateUserMessageSetting();
    });

    //初始化上传身份证等功能
    $(".p_chose_card_front").bind("click", function () {
        $("#uploadAll").val("上 传");
        $("#userCardFront").val("");
        var file = $("#uploadifive-fileCardFront input[type='file']").last();
        $(file).click();
    });
    if($("#imgFront").attr("src")!=''){
        $(".p_chose_card_front").hide();
        $("#imgFront").show();
        $("#imgFront").bind("click",function(){
            $(".p_chose_card_front").click();
        });
    }

    //绑定上传事件
    $("#uploadAll").bind("click", function () {
        uploadAll();
    });

    getUnFinishList();
    initUploadImg();
    initTab();
    initDatePicker();
    initSelect();
    initMessageSession();
    initUserInfo();
    initUploadfive();


});

function initUploadfive(){
    $('#fileCardFront').uploadifive({
        'auto': false,
        'queueID': 'frontQueue',
        'uploadScript': '/upload/upload-card-img-by-user',
        'multi': false,
        'dnd': false,
        'onUploadComplete': function (file, data) {
            var datas = eval('(' + data + ')');
            if (datas.status == 1) {
                $("#userCardFront").val(datas.data);
                $("#cardTip").html("");
                $("#uploadAll").val("上传成功！");
            } else {
                $("#uploadAll").val("上传失败，请稍后重试。。。");
            }

        },
        onSelect:function(){
            $("#uploadifive-fileCardFront input[type='file']").uploadPreview({
                Img: "imgFront",
                Width: 120,
                Height: 120,
                ImgType: [
                    "jpeg", "jpg", "png"
                ], Callback: function () {
                    $("#imgFront").show();
                    $(".p_chose_card_front").hide();
                    $("#imgFront").unbind("click");
                    $("#imgFront").bind("click", function () {
                        $(".p_chose_card_front").click();
                    });
                }
            });
        },
        onInit: function () {
            //初始化预览图片
            $("#uploadifive-fileCardFront input[type='file']").uploadPreview({
                Img: "imgFront",
                Width: 120,
                Height: 120,
                ImgType: [
                    "jpeg", "jpg", "png"
                ], Callback: function () {
                    $("#imgFront").show();
                    $(".p_chose_card_front").hide();
                    $("#imgFront").unbind("click");
                    $("#imgFront").bind("click", function () {
                        $(".p_chose_card_front").click();
                    });
                }
            });
        }
    });
}

/**
 *  初始化修改密码
 */
function initUpdatePassword(){
    var password = $('#password_user_info').val();
    var qPassword = $('#qPassword_user_info').val();
    var oPassword = $('#oPassword_user_info').val();
    if(oPassword=='')
    {
        Main.showTip("请输入旧密码");
        return;
    }
    if(password=='')
    {
        Main.showTip("请输入新密码");
        return;
    }
    if(qPassword=='')
    {
        Main.showTip("请输入确认密码");
        return;
    }
    $.ajax({
        url :'/user-info/update-password',
        type:'post',
        data:{
            oPassword:oPassword,
            password:password,
            qPassword:qPassword,
            _csrf: $('input[name="_csrf"]').val()
        },
        beforeSend:function(){
        },
        error:function(){
            Main.showTip("更新密码异常");
        },
        success:function(data){

            data=eval("("+data+")");
            if(data.status==1){
                Main.showTip(data.data);
            }else{
                Main.showTip(data.data);
            }
        }
    });
}

/**
 * 初始化用户私信会话列表
 */

/**
 * 初始化用户消息会话
 */
function initMessageSession(){
    $.ajax({
        url :'/user-message/message-session-list',
        type:'post',
        data:{},
        beforeSend:function(){
        },
        error:function(){
            Main.showTip("获取私信列表失败");
        },
        success:function(data){
            data=eval("("+data+")");
            if(data.status==1){
                buildMessageSessionHtml(data.data);
            }else{
                Main.showTip("获取私信列表失败");
            }
            //初始化完成之后，调用定时器
            initUserMessageSessionTimer();
        }
    });
}

/**
 * 初始化会话列表定时器
 */
function initUserMessageSessionTimer(){
    try{
        window.clearInterval(messageSessionTimer);
    }catch(e){
    }
    messageSessionTimer=window.setInterval(function(){
        getUserUnReadMessageSession();
    },10000);
}

/**
 * 根据返回List构建私信会话HTML
 * @param list
 */
function buildMessageSessionHtml(list){
    $("#messageSessionDiv ul").html("");
    $("#messageInfoDiv ul").html("");//清空

    if(list==''||list.length==0){
        return;
    }
    var html='',tempSession='',content='';
    for(var i=0;i<list.length;i++){
        tempSession=list[i];
        content=tempSession.lastContentInfo;
        currentPageMessageSessionKeyList.push(tempSession.sessionKey);
        var nickname=tempSession.nickname;
        var headImg=tempSession.headImg;
        if(tempSession.userId==SystemMessage.userId){
            nickname=SystemMessage.nickname;
            headImg=SystemMessage.headImg;
        }
        if(content.length>50){
            content=content.substring(0,50)+"...";
        }
        var isNew='';
        if(tempSession.isRead!=1){
            isNew='class="new"';
        }
        html+='<li '+isNew+' sessionKey="'+tempSession.sessionKey+'" onclick=showMessageSessionInfo("'+tempSession.sessionKey+'","'+headImg+'",this)>';
        html+='<div class="people"><img src="'+headImg+'"><span>'+nickname+'</span></div>';
        html+='<p class="words">'+content+'</p>';
        if(tempSession.userId!=SystemMessage.userId){
            html+='<b class="shield_btn" onclick="addUserMessageShield(\''+tempSession.userId+'\')">屏蔽</b>';
        }
        html+='<b class="datas">'+Main.formatDate(tempSession.lastConcatTime,'hh:mm')+'</b>';
        html+='</li>';
    }
    $("#messageSessionDiv ul").html(html);
}

/**
 * 获取用户未读私信会话
 */
function getUserUnReadMessageSession(){
    $.ajax({
        url :'/user-message/un-read-message-session-list',
        type:'post',
        data:{},
        beforeSend:function(){
        },
        success:function(data){
            data=eval("("+data+")");
            if(data.status==1){
                rebuildMessageSessionList(data.data);
               // $("#messageInfoDiv").attr("receiveHeadImg",receiveHeadImg);
                //$(obj).removeClass("new");
                //buildMessageSessionInfo(data.data)
            }else{
                Main.showTip("获取私信列表失败");
            }
        }
    });
}

/**
 * 重新构建消息会话列表
 * @param list
 */
function rebuildMessageSessionList(list){
    if(list==''||list.length==0){
        return;
    }
    //将查出数据翻转，循环时候，末尾的先放置头部，依次循环
    list=list.reverse();
    var content='',tempSession='';
    for(var i=0;i<list.length;i++){
        var html='';
        tempSession=list[i];
        //如果未读会话已经存在，那么置顶 ，移除当前  如果不存在，那么置顶 移除最后一个
        var index=currentPageMessageSessionKeyList.indexOf(tempSession.sessionKey);
        if(index!=-1){
            currentPageMessageSessionKeyList.splice(index,1);
            currentPageMessageSessionKeyList.push(tempSession.sessionKey);
            $("#messageSessionDiv ul li[sessionKey='"+tempSession.sessionKey+"']").remove();
        }else{
            var last=currentPageMessageSessionKeyList.pop();
            $("#messageSessionDiv ul li[sessionKey='"+last+"']").remove();
            currentPageMessageSessionKeyList.push(tempSession.sessionKey);
        }
        var nickname=tempSession.nickname;
        var headImg=tempSession.headImg;
        if(tempSession.userId==SystemMessage.userId){
            nickname=SystemMessage.nickname;
            headImg=SystemMessage.headImg;
        }
        content=tempSession.lastContentInfo;
        currentPageMessageSessionKeyList.push(tempSession.sessionKey);
        if(content.length>50){
            content=content.substring(0,50)+"...";
        }
        var isNew='';
        if(tempSession.isRead!=1){
            isNew='class="new"';
        }
        html+='<li '+isNew+' sessionKey="'+tempSession.sessionKey+'" onclick=showMessageSessionInfo("'+tempSession.sessionKey+'","'+headImg+'",this)>';
        html+='<div class="people"><img src="'+headImg+'"><span>'+nickname+'</span></div>';
        html+='<p class="words">'+content+'</p>';
        if(tempSession.userId!=SystemMessage.userId){
            html+='<b class="shield_btn" onclick="addUserMessageShield(\''+tempSession.userId+'\')">屏蔽</b>';
        }
        html+='<b class="datas">'+Main.formatDate(tempSession.lastConcatTime,'hh:mm')+'</b>';
        html+='</li>';

        //如果是最后一个，判断这条消息是不是跟左侧的详情Key一致，如果一致，那么直接追加到消息详情页
        if(i==list.length-1){
            var infoSessionKey=$("#messageInfoDiv").attr("sessionKey");
            var receiveHeadImg=$("#messageInfoDiv").attr("receiveHeadImg");
            if(infoSessionKey==tempSession.sessionKey){
                if($("#messageInfoDiv ul li").last().attr("autoFlag")!="true"){
                    var infoHtml='';
                    infoHtml+='<li class="you clearfix" autoFlag="true">';
                    infoHtml+='<img src="'+receiveHeadImg+'">';
                    infoHtml+=' <p>'+content+'</p>';
                    infoHtml+='</li>';
                    $("#messageInfoDiv ul").append(infoHtml);
                }
            }
        }
        //将消息放置顶部
        $("#messageSessionDiv ul").prepend(html);
    }


}

/**
 * 获取私信会话详情
 * @param sessionKey
 * @param receiveHeadImg
 * @param obj
 */
function showMessageSessionInfo(sessionKey,receiveHeadImg,obj){
    $.ajax({
        url :'/user-message/message-session-info',
        type:'post',
        data:{
            sessionKey:sessionKey
        },
        beforeSend:function(){
        },
        error:function(){
            Main.showTip("获取私信列表失败");
        },
        success:function(data){
            data=eval("("+data+")");
            if(data.status==1){
                $("#messageInfoDiv").attr("receiveHeadImg",receiveHeadImg);
                $(obj).removeClass("new");
                buildMessageSessionInfo(data.data,sessionKey)
            }else{
                Main.showTip("获取私信列表失败");
            }
        }
    });
}

/**
 * 发送私信消息
 */
function sendUserMessage(){
    var receiveId=$("#messageInfoDiv").attr("receiveId");
    var content=$("#messageContent").val();

    if($.trim(receiveId)==''){
        Main.showTip("选择发送人有误");
        return;
    }
    if($.trim(content)==''){
        Main.showTip("请输入发送内容");
        return;
    }

    $.ajax({
        url :'/user-message/add-user-message',
        type:'post',
        data:{
            receiveId:$.trim(receiveId),
            content:$.trim(content)
        },
        error:function(){
            Main.showTip("发送消息失败");
        },
        success:function(data){
            data=eval("("+data+")");
            if(data.status==1){
                var html='';
                html+='<li class="you clearfix">';
                html+='<img src="'+userHeadImg+'">';
                html+=' <p>'+content+'</p>';
                html+='</li>';
                $("#messageInfoDiv ul").append(html);
                $("#messageContent").val("");
            }else{
                Main.showTip("发送消息失败");
            }
        }
    });
}

/**
 * 构建私信会话详情HTML
 * @param list
 */
function buildMessageSessionInfo(list,sessionKey){
    if(list==''||list.length==0){
        return;
    }
    var receiveHeadImg=$("#messageInfoDiv").attr("receiveHeadImg");
    $("#messageInfoDiv ul").html("");//清空
    var html='',tempMessage='',receiveId='';
    for(var i=0;i<list.length;i++){
        tempMessage=list[i];
        var content=tempMessage.content;
        if(Main.isNotEmpty(tempMessage.url)){
            content='<a href="'+tempMessage.url+'">'+content+'</a>';
        }
        //如果自己是发送人
        if(tempMessage.senderId==userSign){
            receiveId=tempMessage.receiveId;
            html+='<li class="you clearfix" mid="'+tempMessage.messageId+'">';
            html+='<img src="'+userHeadImg+'">';
            html+=' <p>'+content+'</p>';
            html+='</li>';
        }else{
            receiveId=tempMessage.senderId;
            html+='<li class="zuo clearfix" mid="'+tempMessage.messageId+'">';
            html+='<img src="'+receiveHeadImg+'">';
            html+=' <p>'+content+'</p>';
            html+='</li>';
        }

    }
    if(tempMessage.senderId==SystemMessage.userId||tempMessage.receiveId==SystemMessage.userId){
        $("#write_div").hide();
    }else{
        $("#write_div").show();
    }
    $("#messageInfoDiv").attr("receiveId",receiveId);
    $("#messageInfoDiv").attr("sessionKey",sessionKey);
    $("#messageInfoDiv").show();
    $("#messageInfoDiv ul").html(html);
}

/**
 * 更新用户基本资料
 */
function updateUserInfo(){
    var sex=$('input:radio[name="sex"]:checked').val();
    var nickname=$("#nickname").val();
    var birthday=$("#birthday").val();
    var intro=$("#intro").val();
    var info=$("#info").val();
    var countryId=$("#countryId").val();
    var cityId=$("#cityId").val();
    var lon=$("#lon").val();
    var lat=$("#lat").val();
    var profession=$("input:radio[name='profession']:checked").val();
    if(profession=='其他'){
        profession=$("#other").val();
    }
    if($.trim(nickname)==''||($.trim(nickname)).length>30){
        $("#nicknameTip").html("昵称格式不正确");
        return;
    }
    if($.trim(countryId)==''){
        $("#cityTip").html("请选择居住地国家");
        return;
    }
    if($.trim(cityId)==''){
        $("#cityTip").html("请选择居住地城市");
        return;
    }

    $.ajax({
        url :'/user-info/update-user-info',
        type:'post',
        data:{
            sex:sex,
            nickname:nickname,
            birthday:birthday,
            intro:intro,
            info:info,
            countryId:countryId,
            cityId:cityId,
            lon:lon,
            lat:lat,
            profession:profession
        },
        beforeSend:function(){
        },
        error:function(){
            Main.showTip("更新用户信息失败");
        },
        success:function(data){
            data=eval("("+data+")");
            if(data.status==1){
                Main.showTip("更新用户信息成功");
                window.location.href=window.location.href;
            }else{
                Main.showTip("更新用户信息失败");
            }
        }
    });



}

/**
 * 获取地区详情
 * @param obj
 */
function findCityInfo(name) {
    if(name==""){
        return;
    }
    $.ajax({
        url :'/google-map/search-map-info?search='+name,
        type:'get',
        data:{},
        beforeSend:function(){
        },
        error:function(){
            Main.showTip("获取地区坐标失败,未知系统异常");
        },
        success:function(data){
            data=eval("("+data+")");
            if(data.status==1){
                $("#lon").attr("lon",data.data.lng);
                $("#lat").attr("lat",data.data.lat);

                window.frames['mapFrame'].setMapSite(data.data.lng,data.data.lat);
            }else{
                Main.showTip("获取地区坐标失败,未知系统异常");
            }
        }
    });
}

/**
 * 初始化Select2 (国家，城市)
 */
function initSelect(){
    //初始化国家，城市
    $(".select2").select2({
        'width':'350px',
        containerCss: {
            'margin-bottom':'20px'
        },
        formatNoMatches: function () {
            return "暂无匹配数据";
        }
    });


    //绑定获取城市列表
    $("#countryId").on("change", function () {
        $("#countryTip").html("");
        getCityList();
    });
    $("#cityId").on("change", function () {
        if($("#cityId").val()!=""){
            $("#cityTip").html("");
        }
        var search=$("#cityId").find("option:selected").text();
        if(search!=''){
            if(search.indexOf("/")!=-1){
                search=search.split("/")[0];
            }
            findCityInfo(search);
        }
    });
    $("#countryId").change();

    //初始化区号选择
    $(".areaCodeSelect").select2({
        'width':'130px',
        formatNoMatches: function () {
            return "暂无匹配";
        }
    });
}

/**
 * 初始化日期选择器（生日）
 */
function initDatePicker(){
    $('#birthday').datetimepicker({
        language:  'zh-CN',
        autoclose:1,
        startView: 2,
        minView: 2,
        forceParse: 0,
        format:'yyyy-mm-dd',
        weekStart: 1
    });
    $(".datetimepicker").hide();


    $('#birthday').unbind("focus");

    $("#birthday").bind("focus",function(){
        var top=$("#birthday").offset().top;
        var left=$("#birthday").offset().left;
        $(".datetimepicker").css({
            'top':top+40,
            'left':left,
            'position':'absolute',
            'background-color':'white',
            'border':'1px solid gray',
            'font-size':'14px'
        });
        $(".datetimepicker").show();
    });

    $(".table-condensed tbody").bind("click",function(){
        $(".datetimepicker").hide();
    });
}

/**
 * 初始化用户基本信息
 */
function initUserInfo(){
    //init sex
    if(userSex==0){
        $("input:radio[name='sex'][value='0']").prop("checked",true);
        $("#rado2").next('label').css('background-position','0 -47px').siblings('label').css('background-position','0 10px')
    }else if(userSex==1){
        $("input:radio[name='sex'][value='1']").prop("checked",true);
        $("#rad01").next('label').css('background-position','0 -47px').siblings('label').css('background-position','0 10px')
    }else{
        $("input:radio[name='sex'][value='2']").prop("checked",true);
        $("#rad03").next('label').css('background-position','0 -47px').siblings('label').css('background-position','0 10px')
    }

    if(userProfession=='持证导游'){
        $("input:radio[name='profession'][value='持证导游']").prop("checked",true);
        $("#shenfen01").next('label').css('background-position','0 -47px').siblings('label').css('background-position','0 10px')
    }else if(userProfession=='业余导游'){
        $("input:radio[name='profession'][value='业余导游']").prop("checked",true);
        $("#shenfen02").next('label').css('background-position','0 -47px').siblings('label').css('background-position','0 10px')
    }else if(userProfession=='学生'){
        $("input:radio[name='profession'][value='学生']").prop("checked",true);
        $("#shenfen03").next('label').css('background-position','0 -47px').siblings('label').css('background-position','0 10px')
    }else if(userProfession=='旅游爱好者'){
        $("input:radio[name='profession'][value='旅游爱好者']").prop("checked",true);
        $("#shenfen04").next('label').css('background-position','0 -47px').siblings('label').css('background-position','0 10px')
    }else{
        $("input:radio[name='profession'][value='其他']").prop("checked",true);
        $("#shenfen05").next('label').css('background-position','0 -47px').siblings('label').css('background-position','0 10px')
        $("#other").val(userProfession);
        $("#other").show();
    }

}

/**
 * 初始化TAB选择触发事件
 */
function initTab(){
    var href=window.location.href;
    if(href.endWith("#")){
        href=href.substring(0,href.length-1);
    }
    var tabId='';
    if(href.indexOf("?")!=-1){
        tabId=href.substring(href.indexOf("?")+1,href.length);
        $("#"+tabId).click();
    }
}

/**
 * 初始化上传插件
 */
function initUploadImg(){

    $('#reImg').uploadifive({
        'auto': true,
        'queueID': 'reQueue',
        'uploadScript': '/upload/upload-head-img',
        'multi': false,
        'dnd': false,
        'onUpload':function(){
            $("#uploadBtn").val("正在上传，请稍后");
        },
        'onUploadComplete': function (file, data) {
            var datas = eval('(' + data + ')');
            if (datas.status == 1) {
                $("#uploadBtn").val("上传成功！");
                $("#img_src").val(datas.data);
                $("#img_origin").attr("src",datas.data);
                $(".p_photo1 img").attr("src",datas.data);
                $(".p_photo2 img").attr("src",datas.data);
                $(".p_photo3 img").attr("src",datas.data);

                $("#img_origin").show();
                $("#uploadBtn").hide();
                initImgAreaSelect("#img_origin");
            } else {
                $("#uploadBtn").val("上传失败，请重试");
            }
        }
    });
    $("#uploadBtn").bind("click",function(){
        $("#uploadifive-reImg input[type='file'][id!='titleImgFile']").last().click();
    });
    $("#uploadImgConfirm").bind("click",function(){
        selectImg();
    });
    $("#uploadImgCancle").bind("click",function(){
        resetUploadHeadImg();
    });

}

/**
 * 重置上传头像插件
 */
function resetUploadHeadImg(){
    removeImgAreaSelect();
    $("#uploadBtn").val("点击上传图片");
    $("#uploadBtn").show();
    $("#img_origin").hide();
    $("#img_origin").attr("src","");
    $("#img_src").val();
}

/**
 * 上传头像选择IMG（截头像）
 */
function selectImg(){
    var x=$("#img_x").val();
    var y=$("#img_y").val();
    var w=$("#img_w").val();
    var h=$("#img_h").val();
    var rotate=$("#img_rotate").val();
    var imgSrc=$("#img_src").val();
    if(imgSrc==""){
        Main.showTip("您还没有选择图片哦！");
        return;
    }
    if(w==0||h==0){
        Main.showTip("请正确选择图片！");
        return;
    }
    if(isNaN(w)||isNaN(h)){
        Main.showTip("请正确选择图片！");
        return;
    }
    $.ajax({
        url: "/user-info/change-user-head-img",
        type: "post",
        data:{
            "x":x,
            "y":y,
            "w":w,
            "h":h,
            "rotate":rotate,
            "src":imgSrc,
            "pWidth":$("#img_origin").width(),
            "pHeight":$("#img_origin").height()
        },
        error:function(){
            alert("上传头像异常，请刷新重试！");
        },
        success: function(data){
            var result=eval("("+data+")");
            if(result.status==1){
                $(".userPic img").attr("src",result.data);
                resetUploadHeadImg();
            }else{
                alert("上传头像异常，请刷新重试！");
            }
        }
    });
}

/**
 * 重置截头像插件
 */
function resetImg(){
    imgAreaSelectApi.update();
}

/**
 * 移除截图选择器
 */
function removeImgAreaSelect(){
    if(Main.isNotEmpty(imgAreaSelectApi)){
        imgAreaSelectApi.cancelSelection();
    }
}

/**
 * 初始化头像截取插件
 * @param imgObj
 */
function initImgAreaSelect(imgObj){
    imgAreaSelectApi = $(imgObj).imgAreaSelect({
        instance : true,	// true，返回一个imgAreaSelect绑定到的图像的实例，可以使用api方法
        onSelectChange : preview,	// 改变选区时的回调函数
        handles : true,	// true，调整手柄则会显示在选择区域内
        fadeSpeed:200,
        resizable : true,
        aspectRatio:"1:1"

    });
    imgAreaSelectApi.setRotate(0);
    //resetRotate();
}

/**
 * 图片加载完成触发事件
 */
$('#img_origin').load(function(){
    var form = $('#coordinates_form');

    //获取 x、y、w、h的值
    var left = parseInt(form.children('.x').val());
    var top = parseInt(form.children('.y').val());
    var width = parseInt(form.children('.w').val());
    var height = parseInt(form.children('.h').val());

    //imgAreaSelectApi 就是图像img_origin的实例 上边instance已解释
    //setSelection(),设置选区的坐标
    //update(),更新
    imgAreaSelectApi.setSelection(left, top, left+width, top+height);
    imgAreaSelectApi.update();

    //图片居中
    var imgWidth=$("#img_origin").width();
    var imgHeight=$("#img_origin").height();
    $("#img_origin").css("margin","0")
    if(imgWidth<containerDivWidth&&imgHeight<containerDivWidth){
        if(imgWidth>imgHeight){
            $("#img_origin").width(containerDivWidth);
        }else{
            $("#img_origin").height(containerDivWidth);
        }
    }
    imgWidth=$("#img_origin").width();
    imgHeight=$("#img_origin").height();

    if(imgWidth>=imgHeight){
        var padding=(imgWidth-imgHeight)/2;
        $("#img_origin").css("margin-top",padding);
        imgAreaSelectApi.setSelection((imgWidth/2)-(imgHeight/4), (imgHeight/2)-(imgHeight/4), (imgWidth/2)+(imgHeight/4), (imgHeight/2)+(imgHeight/4), true);
    }
    if(imgHeight>imgWidth){
        var padding=(imgHeight-imgWidth)/2;
        $("#img_origin").css("margin-left",padding);
        imgAreaSelectApi.setSelection((imgHeight/2)-(imgWidth/4)-padding, (imgHeight/2)-(imgWidth/4), (imgHeight/2)+(imgWidth/4)-padding, (imgHeight/2)+(imgWidth/4), true);
    }


    imgAreaSelectApi.setOptions({ show: true });

    imgAreaSelectApi.update();
    preview($("#img_origin"),imgAreaSelectApi.getSelection());

});

/**
 * 上传完成预览事件
 * @param img
 * @param selection
 */
function preview(img, selection){

    var form = $('#coordinates_form');
    //重新设置x、y、w、h的值
    form.children('.x').val(selection.x1);
    form.children('.y').val(selection.y1);
    form.children('.w').val(selection.x2-selection.x1);
    form.children('.h').val(selection.y2-selection.y1);
    form.children('.rotate').val(imgAreaSelectApi.getRotate());
    preview_photo('p_photo1', selection);
    preview_photo('p_photo2', selection);
    preview_photo('p_photo3', selection);


}

/**
 * 用户拖动头像内容，生成预览图
 * @param div_class
 * @param selection
 */
function preview_photo(div_class, selection){
    var div = $('div.'+div_class);

    //获取div的宽度与高度
    var width = div.outerWidth();
    var height = div.outerHeight();
    var scaleX = width/selection.width;
    var scaleY = height/selection.height;

    div.find('img').css({
        width : Math.round(scaleX * $('#img_origin').outerWidth())+'px',
        height : Math.round(scaleY * $('#img_origin').outerHeight())+'px',
        marginLeft : '-'+Math.round(scaleX * selection.x1)+'px',
        marginTop : '-'+Math.round(scaleY * selection.y1)+'px'
    });
}

/**
 * 重置旋转（暂时没有旋转功能）
 */
function resetRotate(){
    rotateCount=0;
    var du=0;
    rotate(document.getElementById("crop_container"), du);
    rotate(document.getElementById("p_photo"),du);
    imgAreaSelectApi.setRotate(du);
    imgAreaSelectApi.update();
}

/**
 * 获取我的随游
 */
function getMyTripList(){
    $.ajax({
        url :'/trip/my-trip-list',
        type:'post',
        data:{
            _csrf: $('input[name="_csrf"]').val()
        },
        error:function(){
            Main.showTip("获取我的随游失败");
        },
        success:function(data){
            //hide load
            data=eval("("+data+")");
            if(data.status==1){
                $("#myTripList").html(buildMyTripHtml(data.data));
            }else{
                Main.showTip("获取我的随游失败");
            }
        }
    });
}

/**
 * 构建我的随游HTML
 * @param tripList
 * @returns {string}
 */
function buildMyTripHtml(tripList){
    if(tripList==''||tripList.length==0){
        html="<p class='no_result'><a>您还没有发布过随游哦~</a></p>";
        return html;
    }
    var tripInfo,html='';
    for(var i=0;i<tripList.length;i++){
        tripInfo=tripList[i];
        var count=tripInfo.count==null?'':tripInfo.count;
        var info=tripInfo.info;
        if(info.length>100){
            info=info.substring(0,100)+"...";
        }
        html+='<div class="orderList clearfix">';
        html+=' <img src="/assets/images/delete.fw.png" width="22" height="24" class="rubbish" onclick="deleteTravelTrip('+tripInfo.tripId+',this)">';
        html+=' <dl class="order clearfix">';
        if(tripInfo.status==TripStatus.TRAVEL_TRIP_STATUS_DRAFT){
            html+='   <dt class="title grey">';
        }else{
            html+='   <dt class="title">';
        }
        html+='       <span>'+Main.formatDate(tripInfo.createTime,'yyyy.MM.dd')+'发布</span><span>'+tripInfo.title+'</span><span>随游时间</span><span>附加服务</span>';
        html+='   </dt>';
        html+='   <dd>';
        html+='       <span class="pic"><img src="'+tripInfo.titleImg+'"></span>';
        html+='       <span>'+info+'</span>';
        html+='       <span>'+tripInfo.startTime+'</span>';
        html+='       <span>';
        if(tripInfo.names!=''&&tripInfo.names!=null){
            var names=tripInfo.names.split(",");
            for(var j=0;j<names.length;j++){
                html+=names[j]+'<b></b><br>';
            }
        }
        html+='        </span>';
        html+='   </dd>';
        html+=' </dl>';
        html+=' <p>';
        if(tripInfo.status==TripStatus.TRAVEL_TRIP_STATUS_DRAFT){
            html+=' <a href="/trip/edit-trip?trip='+tripInfo.tripId+'" class="sure">编辑发布</a>';
        }else{
            if(count!=''){ html+='<a href="/trip/to-apply-list?trip='+tripInfo.tripId+'" class="sure">新申请<b>'+count+'</b></a>';};
        }
        html+=' <a href="/view-trip/info?trip='+tripInfo.tripId+'" class="cancel">查看详情</a>';
        html+=' </p>';
        html+='</div>';
    }
    return html;
}

/**
 * 获取我加入的随游
 */
function getMyJoinTripList(){
    $.ajax({
        url :'/trip/my-join-trip-list',
        type:'post',
        data:{
            _csrf: $('input[name="_csrf"]').val()
        },
        error:function(){
            Main.showTip("获取我加入的随游失败");
        },
        success:function(data){
            //hide load
            data=eval("("+data+")");
            if(data.status==1){
                $("#myJoinTripList").html(buildMyJoinTripHtml(data.data));
            }else{
                Main.showTip("获取我加入的随游失败");
            }
        }
    });
}

/**
 * 构建我加入的随游HTML
 * @param tripList
 * @returns {string}
 */
function buildMyJoinTripHtml(tripList){
    if(tripList==''||tripList.length==0){
        html="<p class='no_result'><a>您还没有申请加入过随游哦~</a></p>";
        return html;
    }
    var tripInfo,html='';
    for(var i=0;i<tripList.length;i++){
        tripInfo=tripList[i];
        var count=tripInfo.count==null?'':tripInfo.count;
        if(count!=''){ count='<a href="#" class="sure">新申请<b>'+count+'</b></a>'};
        html+='<div class="orderList clearfix">';
        html+=' <dl class="order clearfix">';
        html+='   <dt class="title">';
        html+='       <span>'+Main.formatDate(tripInfo.createTime,'yyyy.MM.dd')+'发布</span><span>'+tripInfo.title+'</span><span>随游时间</span><span>附加服务</span>';
        html+='   </dt>';
        html+='   <dd>';
        html+='       <span class="pic"><img src="'+tripInfo.titleImg+'"></span>';
        html+='       <span>'+tripInfo.info+'</span>';
        html+='       <span>'+tripInfo.startTime+'</span>';
        html+='       <span>';
        if(tripInfo.names!=''&&tripInfo.names!=null){
            var names=tripInfo.names.split(",");
            for(var j=0;j<names.length;j++){
                html+=names[j]+'<b></b><br>';
            }
        }
        html+='        </span>';
        html+='   </dd>';
        html+=' </dl>';
        html+=' <p><a href="/view-trip/info?trip='+tripInfo.tripId+'" class="cancel">查看详情</a>'+count+'</p>';
        html+='</div>';
    }
    return html;
}

/**
 * 获取用户未完成的订单
 */
function getUnFinishList(){
    $.ajax({
        url :'/user-order/get-un-finish-order',
        type:'post',
        data:{
            _csrf: $('input[name="_csrf"]').val()
        },
        error:function(){
            Main.showTip("获取我的未完成订单失败");
        },
        success:function(data){
            //hide load
            data=eval("("+data+")");
            if(data.status==1){
                $("#unFinishList").html(buildOrderList(data.data,0));
            }else{
                Main.showTip("获取我的未完成订单失败");
            }
        }
    });
}

/**
 * 获取用户已完成的订单
 */
function getFinishList(){
    $.ajax({
        url :'/user-order/get-finish-order',
        type:'post',
        data:{
            _csrf: $('input[name="_csrf"]').val()
        },
        error:function(){
            Main.showTip("获取我的完成订单失败");
        },
        success:function(data){
            //hide load
            data=eval("("+data+")");
            if(data.status==1){
                $("#finishList").html(buildOrderList(data.data,1));
            }else{
                Main.showTip("获取完成订单失败");
            }
        }
    });
}

/**
 * 构建用户订单页面
 * @param list
 * @param type
 * @returns {string}
 */
function buildOrderList(list,type){
    var html="";
    if(list==""||list.length==0){
        html="<p class='no_result'><a>暂时没有找到您的订单哦~</a></p>";
        return html;
    }
    for(var i=0;i<list.length;i++){
        var orderInfo=list[i];
        var travelInfo=orderInfo.tripJsonInfo;
        travelInfo=eval("("+travelInfo+")");
        var serviceInfo=orderInfo.serviceInfo;
        serviceInfo=eval("("+serviceInfo+")");
        html+='<div class="orderList clearfix">';
        if(type==1){
            html+='<img src="/assets/images/delete.fw.png" width="22" height="24" class="rubbish" onclick="deleteOrderInfo('+orderInfo.orderId+')">';
        }
        html+='<dl class="order clearfix">';
        html+='<dt class="title">';
        html+='<span>'+Main.convertOrderDateToShow(orderInfo.createTime)+'</span><span>随游</span><span>开始时间</span><span>随友</span><span>随友电话</span><span>出行日期</span><span>人数</span><span>单项服务</span>';
        html+='</dt>';
        html+='<dd>';
        html+='<span class="pic"><a href="/view-trip/info?trip='+travelInfo.info.tripId+'"><img src="'+travelInfo.info.titleImg+'"></a></span>';
        html+='<a href="/view-trip/info?trip='+travelInfo.info.tripId+'"><span>'+travelInfo.info.title+'</span></a>';
        html+='<span>'+Main.convertTimePicker(orderInfo.startTime,2)+'</span>';
        if(orderInfo.phone==''||orderInfo.phone==null){
            html+='<span>未接单</span>';
            html+='<span>未接单</span>';
        }else{
            html+='<span><a href="javascript:;" class="user"><img src="'+orderInfo.headImg+'" ></a><a href="javascript:;" onclick="Main.showSendMessage(\''+orderInfo.userSign+'\')" class="message"><b>'+orderInfo.nickname+'</b><br><img src="/assets/images/xf.fw.png" width="18" height="12"></a></span>';
            html+='<span>'+orderInfo.phone+'</span>';
        }

        html+='<span>'+orderInfo.beginDate+'</span>';
        html+='<span>'+orderInfo.personCount+'</span>';
        html+='<span>';
        if(serviceInfo!=''&&serviceInfo.length>0){
            for(var j=0;j<serviceInfo.length;j++){
                var service=serviceInfo[j];
                html+=service.title+'<b>'+service.money+'</b>';
                if(service.type==tripServiceTypePeople){
                    html+='/人';
                }else{
                    html+='/次';
                }
                html+='<br>';
            }
        }
        html+='</span>';
        html+='</dd>';
        html+='</dl>';
        html+='<p class="order_list_number">订单号：'+orderInfo.orderNumber+'</p>';
        if(orderInfo.status==OrderStatus.USER_ORDER_STATUS_PAY_WAIT){
            html+='<p><a href="javascript:cancelOrder('+orderInfo.orderId+');" class="cancel">取消订单</a><a href="/user-order/info?orderNumber='+orderInfo.orderNumber+'" class="sure">支付</a><span>总价：<b>'+orderInfo.totalPrice+'</b></span>';
            html+='<span class="blue">待支付</span><span class="orange"></span></p>';
        }else if(orderInfo.status==OrderStatus.USER_ORDER_STATUS_PAY_SUCCESS) {
            //判断是否超过随游时长
            html+='<p><a href="javascript:refundOrder('+orderInfo.orderId+');" class="cancel">申请退款</a><span>总价：<b>'+orderInfo.totalPrice+'</b></span>';
            html+='<span class="blue">已支付</span><span class="orange">待接单</span></p>';
        }else if(orderInfo.status==OrderStatus.USER_ORDER_STATUS_CONFIRM){
            html+='<p><a href="javascript:showRefundWindow('+orderInfo.orderId+');" class="cancel">申请退款</a><a href="javascript:userConfirmOrder('+orderInfo.orderId+')" class="sure">确认游玩</a><span>总价：<b>'+orderInfo.totalPrice+'</b></span>';
            html+='<span class="blue">已支付</span><span class="orange">已确认</span></p>';
        }else if(orderInfo.status==OrderStatus.USER_ORDER_STATUS_CANCELED){
            html+='<p><a href="#" class="cancel_1"></a><a href="#" class="sure_1"></a><span>总价：<b>'+orderInfo.totalPrice+'</b></span>';
            html+='<span class="blue">已取消</span><span class="orange">订单关闭</span></p>';
        }else if(orderInfo.status==OrderStatus.USER_ORDER_STATUS_REFUND_WAIT){
            html+='<p><a href="#" class="cancel_1"></a><a href="#" class="sure_1"></a><span>总价：<b>'+orderInfo.totalPrice+'</b></span>';
            html+='<span class="blue">等待退款</span><span class="orange"></span></p>';
        }else if(orderInfo.status==OrderStatus.USER_ORDER_STATUS_REFUND_SUCCESS){
            html+='<p><a href="#" class="cancel_1"></a><a href="#" class="sure_1"></a><span>总价：<b>'+orderInfo.totalPrice+'</b></span>';
            html+='<span class="blue">退款成功</span><span class="orange"></span></p>';
        }else if(orderInfo.status==OrderStatus.USER_ORDER_STATUS_PLAY_SUCCESS||orderInfo.status==OrderStatus.USER_ORDER_STATUS_PLAY_FINISH){
            html+='<p>';
            if(orderInfo.isComment==null||orderInfo.isComment=="null"){
                html+='<a href="/user-order/to-comment?orderId='+orderInfo.orderId+'" class="cancel">去评价</a>';
            }
            html+='<a href="#" class="sure">分享</a>';
            html+='<span>总价：<b>'+orderInfo.totalPrice+'</b></span>';
            html+='<span class="blue">已完成</span><span class="orange"></span></p>';
        }else if(orderInfo.status==OrderStatus.USER_ORDER_STATUS_REFUND_VERIFY){
            html+='<p><a href="#" class="cancel_1"></a><a href="#" class="sure_1"></a><span>总价：<b>'+orderInfo.totalPrice+'</b></span>';
            html+='<span class="blue">退款审核中</span><span class="orange"></span></p>';
        }else if(orderInfo.status==OrderStatus.USER_ORDER_STATUS_REFUND_SUCCESS){
            html+='<p><a href="#" class="cancel_1"></a><a href="#" class="sure_1"></a><span>总价：<b>'+orderInfo.totalPrice+'</b></span>';
            html+='<span class="blue">已退款</span><span class="orange"></span></p>';
        }
        html+='</div>';
    }
    return html;
}

/**
 * 获取随友订单列表
 */
function getPublisherOrderList(){
    $.ajax({
        url :'/user-order/get-publisher-order-list',
        type:'post',
        data:{
            _csrf: $('input[name="_csrf"]').val()
        },
        error:function(){
            Main.showTip("获取随友订单失败");
        },
        success:function(data){
            //hide load
            data=eval("("+data+")");
            if(data.status==1){
                $("#myPublisherOrderList").html(buildPublisherOrderList(data.data));
            }else{
                Main.showTip("获取随友订单失败");
            }
        }
    });
}

/**
 * 构建随友订单列表
 * @param list
 * @returns {string}
 */
function buildPublisherOrderList(list){
    var html="";
    if(list==""||list.length==0){
        html="<p class='no_result'><a>您暂时还没有随游订单哦~</a></p>";
        return html;
    }
    for(var i=0;i<list.length;i++){
        var orderInfo=list[i];
        var travelInfo=orderInfo.tripJsonInfo;
        travelInfo=eval("("+travelInfo+")");
        var serviceInfo=orderInfo.serviceInfo;
        serviceInfo=eval("("+serviceInfo+")");

        html+='<div class="orderList clearfix">';
        html+='<dl class="order clearfix">';
        html+='<dt class="title">';
        html+='<span>'+Main.convertOrderDateToShow(orderInfo.createTime)+'</span><span>申请随游</span><span>开始时间</span><span>申请游客</span><span>出行时间</span><span>人数</span><span>附加服务</span>';
        html+='</dt>';
        html+='<dd>';
        html+='<span class="pic"><img src="'+travelInfo.info.titleImg+'"/></span>';
        html+='<span>'+travelInfo.info.title+'</span>';
        html+='<span>'+Main.convertTimePicker(orderInfo.startTime,2)+'</span>';
        html+='<span><a href="javascript:;" class="user"><img src="'+orderInfo.headImg+'" width="50" height="50"></a><a href="javascript:;" class="message"><b>'+orderInfo.nickname+'</b><br><img onclick="Main.showSendMessage(\''+orderInfo.userId+'\')" src="/assets/images/xf.fw.png" width="18" height="12"></a></span>';
        html+='<span>'+orderInfo.beginDate+'</span>';
        html+='<span>'+orderInfo.personCount+'</span>';
        html+='<span>';
        if(serviceInfo!=''&&serviceInfo.length>0){
            for(var j=0;j<serviceInfo.length;j++){
                var service=serviceInfo[j];
                html+=service.title+'<b>'+service.money+'</b>';
                if(service.type==tripServiceTypePeople){
                    html+='/人';
                }else{
                    html+='/次';
                }
                html+='<br>';
            }
        }
        html+='</span>';
        html+='</dd>';
        html+='</dl>';
        html+='<p><a href="javascript:showCancelWindow('+orderInfo.orderId+');" class="cancel">取消订单</a></p>';
        html+='</div>';
    }
    return html;

}

/**
 * 获取随友可接收的订单
 */
function getUnConfirmOrderByPublisher(){
    $.ajax({
        url :'/user-order/get-un-confirm-order',
        type:'post',
        data:{
            _csrf: $('input[name="_csrf"]').val()
        },
        error:function(){
            Main.showTip("获取可接受随游订单失败");
        },
        success:function(data){
            //hide load
            data=eval("("+data+")");
            if(data.status==1){
                $("#unConfirmList").html(buildUnConfirmList(data.data,1));
            }else{
                Main.showTip("获取可接受随游订单失败");
            }
        }
    });
}

/**
 * 构建可接受订单
 * @param list
 * @returns {string}
 */
function buildUnConfirmList(list){
    var html="";
    if(list==""||list.length==0){
        html="<p class='no_result'><a>暂时没有可接的订单哦~</a></p>";
        return html;
    }
    for(var i=0;i<list.length;i++){
        var orderInfo=list[i];
        var travelInfo=orderInfo.tripJsonInfo;
        travelInfo=eval("("+travelInfo+")");
        var serviceInfo=orderInfo.serviceInfo;
        serviceInfo=eval("("+serviceInfo+")");

        html+='<div class="orderList clearfix">';
        html+='<dl class="order clearfix">';
        html+='<dt class="title">';
        html+='<span>'+Main.convertOrderDateToShow(orderInfo.createTime)+'</span><span>申请随游</span><span>开始时间</span><span>申请游客</span><span>出行时间</span><span>人数</span><span>附加服务</span>';
        html+='</dt>';
        html+='<dd>';
        html+='<span class="pic"><img src="'+travelInfo.info.titleImg+'"></span>';
        html+='<span>'+travelInfo.info.title+'</span>';
        html+='<span>'+Main.convertTimePicker(orderInfo.startTime,2)+'</span>';
        html+='<span><a href="javascript:;" class="user"><img src="'+orderInfo.headImg+'"></a><a href="javascript:;" class="message"><b>'+orderInfo.nickname+'</b><br><img onclick="Main.showSendMessage(\''+orderInfo.userId+'\')"  src="/assets/images/xf.fw.png" width="18" height="12"></a></span>';
        html+='<span>'+orderInfo.beginDate+'</span>';
        html+='<span>'+orderInfo.personCount+'</span>';
        html+='<span>';
        if(serviceInfo!=''&&serviceInfo.length>0){
            for(var j=0;j<serviceInfo.length;j++){
                var service=serviceInfo[j];
                html+=service.title+'<b>'+service.money+'</b>';
                if(service.type==tripServiceTypePeople){
                    html+='/人';
                }else{
                    html+='/次';
                }
                html+='<br>';
            }
        }
        html+='</span>';
        html+='</dd>';
        html+='</dl>';
        html+='<p><a href="javascript:publisherIgnoreOrder('+orderInfo.orderId+');" class="cancel">忽略</a><a href="javascript:publisherConfirmOrder('+orderInfo.orderId+');" class="sure">接受</a></p>';
        html+='</div>';
    }
    return html;

}

/**
 * 确认用户订单
 * @param orderId
 */
function publisherConfirmOrder(orderId){
    if(orderId==''){
        return;
    }
    $.ajax({
        url :'/user-order/publisher-confirm-order',
        type:'post',
        data:{
            orderId:orderId,
            _csrf: $('input[name="_csrf"]').val()
        },
        error:function(){
            Main.showTip("抢单失败");
        },
        success:function(data){
            data=eval("("+data+")");
            if(data.status==1){
                Main.showTip("抢单成功");
                getUnConfirmOrderByPublisher();
            }else{
                Main.showTip("抢单失败");
            }
        }
    });
}

/**
 * 忽略用户订单
 * @param orderId
 */
function publisherIgnoreOrder(orderId){
    if(orderId==''){
        return;
    }
    $.ajax({
        url :'/user-order/publisher-ignore-order',
        type:'post',
        data:{
            orderId:orderId,
            _csrf: $('input[name="_csrf"]').val()
        },
        error:function(){
            Main.showTip("忽略订单失败");
        },
        success:function(data){
            data=eval("("+data+")");
            if(data.status==1){
                getUnConfirmOrderByPublisher();
            }else{
                Main.showTip("忽略订单失败");
            }
        }
    });
}

/**
 * 删除随游
 * @param tripId
 */
function deleteTravelTrip(tripId,obj){
    if(!confirm("确定要删除随游吗？")){
        return;
    }
    $.ajax({
        url :'/trip/delete-trip',
        type:'post',
        data:{
            tripId:tripId,
            _csrf: $('input[name="_csrf"]').val()

        },
        error:function(){
            Main.showTip("删除随游失败");
        },
        success:function(data){
            var datas=eval('('+data+')');
            if(datas.status==1){
                Main.showTip("删除随游成功");
                $(obj).parent("div").remove();
            }else{
                Main.showTip("删除随游失败");
            }
        }
    });
}

/**
 * 级联获取城市列表
 */
function  getCityList(){
    var countryId=$("#countryId").val();
    if(countryId==""){
        return;
    }
    $("#countryTip").html("");
    $("#cityId").empty();

    $("#cityId").append("<option value=''></option>");
    $("#cityId").val("").trigger("change");
    $.ajax({
        url :'/country/find-city-list',
        type:'post',
        data:{
            countryId:countryId,
            _csrf: $('input[name="_csrf"]').val()

        },
        error:function(){
            $("#cityTip").html("获取城市列表失败");
        },
        success:function(data){
            var datas=eval('('+data+')');
            if(datas.status==1){
                var html = "";
                for(var i=0;i<datas.data.length;i++){
                    var city=datas.data[i];
                    html+='<option value="'+city.id+'">'+city.cname+"/"+city.ename+'</option>';
                }
                $("#cityId").append(html);
                if(cityId!=""){
                    $("#cityId").val(cityId).trigger("change");
                }
            }else{
                $("#cityTip").html("获取城市列表失败");
            }
        }
    });
}

/**
 * 发送手机验证码
 */
function sendTravelCode() {
    //TODO 验证手机有效性
    var phone = $("#phone").val();
    var areaCode = $("#codeId").val();

    if (phone == "") {
        $("#phoneTip").html("请输入有效的手机号");
        return;
    } else {
        $("#phoneTip").html("");
    }
    $.ajax({
        url: '/index/send-travel-code',
        type: 'post',
        data: {
            phone: phone,
            areaCode: areaCode,
            _csrf: $('input[name="_csrf"]').val()

        },
        beforeSend: function () {
            $("#getCode").val("正在发送...");
        },
        error: function () {
            $("#getCode").val("发送失败...");
        },
        success: function (data) {
            var datas = eval('(' + data + ')');
            if (datas.status == 1) {
                $("#getCode").val("发送成功");
                phoneTime = 60;
                initPhoneTimer();
            } else {
                $("#getCode").val("发送失败...");
                $("#phoneTip").html(datas.data);
            }
        }
    });
}

/**
 * 初始化验证手机
 */
function initValidatePhone(){
    //TODO 验证手机
    var phone = $("#phone").val();
    var areaCode = $("#codeId").val();
    var code_p = $("#code_p").val();
    if (phone == "") {
        $("#phoneTip").html("请输入有效的手机号");
        return;
    } else {
        $("#phoneTip").html("");
    }
    $.ajax({
        url: '/index/validate-phone',
        type: 'post',
        data: {
            phone: phone,
            areaCode: areaCode,
            code:code_p,
            _csrf: $('input[name="_csrf"]').val()

        },
        beforeSend: function () {

        },
        error: function () {
            Main.showTip('验证失败');
        },
        success: function (data) {
            var datas = eval('(' + data + ')');
            if (datas.status == 1) {
                Main.showTip('验证成功');
                $("#phone").val('');
                $("#code_p").val('');
                $("#validatePhone").val('立即修改');

            } else {
                Main.showTip(datas.data);
            }
        }
    });
}

/**
 * 验证邮箱
 */
function initValidateEmail_info(){
    var email = $("#email_info").val();

    if (email == "") {
        Main.showTip('邮箱不能为空');
        return;
    }
    $.ajax({
        url: '/index/send-validate-mail',
        type: 'post',
        data: {
            mail: email,
            _csrf: $('input[name="_csrf"]').val()

        },
        beforeSend: function () {

        },
        error: function () {
            Main.showTip('验证失败');
        },
        success: function (data) {
            var datas = eval('(' + data + ')');
            if (datas.status == 1) {
                Main.showTip(datas.data);
            } else {
                Main.showTip(datas.data);
            }
        }
    });
}

/**
 * 初始化手机验证计时器
 */
function initPhoneTimer() {
    phoneTimer = window.setInterval(function () {
        if (phoneTime > 0) {
            phoneTime--;
            initPhoneTime();
        } else {
            window.clearInterval(phoneTimer);
            initPhoneTime();
        }
    }, 1000);
}

/**
 * 初始化手机计时
 */
function initPhoneTime() {

    if (phoneTime != "" && phoneTime > 0) {
        $("#getCode").val(+phoneTime + "秒后重新发送");
        $("#getCode").attr("disabled", "disabled");

        $("#getCode").css("background", "gray");
        $("#getCode").unbind("click");
    } else {
        $("#getCode").val("获取验证码");
        $("#getCode").removeAttr("disabled");
        $("#getCode").css("background", "#ff7a4d");
        $("#getCode").unbind("click");
        $("#getCode").bind("click", function () {
            sendTravelCode();
        });
    }
}

/**
 * 初始化用户收藏
 */
function initCollect(){
    $.ajax({
        url: "/user-info/get-collection-travel",
        type: "post",
        data:{
            _csrf: $('input[name="_csrf"]').val()
        },
        error:function(){
            Main.showTip('得到收藏异常');
        },
        success: function(data){
            var result=eval("("+data+")");
            if(result.status==1){
                var l =  result.data.data.length;
                if(l==0){
                    $('#myCollectList').html('<p class="no_result" style="padding-bottom: 20px">您还没有收藏过随游哦~</p>');
                    return;
                }else{
                    $('#myCollectList').html('');
                }

                var str ='';
                for(var i=0;i<l;i++)
                {
                    var rst= result.data.data[i];
                    str+=' <li>';
                    str+='  <a href="/view-trip/info?trip='+rst.tripId+'"><img src="'+rst.titleImg+'" alt=""></a>';
                    str+='<div class="userPic">';
                    str+='<a href="#"><img src="'+rst.headImg+'" alt=""></a>';
                    str+='<span>'+rst.nickname+'</span>';
                    str+='</div>';
                    str+='<p>'+rst.title+'</p>';
                    str+='</li>';

                }
                $('#myCollectList').append(str);
            }else{
                Main.showTip('得到收藏异常');
            }
        }
    });
}

/**
 * 初始化用户评论
 * @param page
 */
function initMyComment(page){
    $.ajax({
        url: "/user-info/get-comment",
        type: "post",
        data:{
            cPage:page,
            _csrf: $('input[name="_csrf"]').val()
        },
        error:function(){
            Main.showTip('得到收藏异常');
        },
        success: function(data){
            var result=eval("("+data+")");
            if(result.status==1){
                var l =  result.data.data.length;
                if(l==0){
                    $('#commentList_51').html('<p>您还没有发言哦~</p>');
                    return;
                }else{
                    $('#commentList_51').html('');
                }
                var str ='';
                for(var i=0;i<l;i++)
                {
                    var rst= result.data.data[i];
                    str+='<li>';
                    str+='<div class="userPic">';
                    str+='<a href="#"><img alt="" src="'+rst.headImg+'"></a>';
                    str+='<span>'+rst.nickname+'</span>';
                    str+='</div>';
                    if(rst.rnickname!=null){
                        str+='<span>回复</span>';
                        str+='<div class="userPic">';
                        str+=' <a href="#"><img alt="" src="'+rst.rheadImg+'"></a>';
                        str+=' <span>'+rst.rnickname+'</span>';
                        str+='</div>';
                    }
                    str+='<p>'+rst.content+'</p>';
                    str+='<b>'+rst.cTime+'</b>';
                    str+='<p class="detail">关&nbsp;于&nbsp;:&nbsp;<a href="">'+rst.title+'</a></p>';
                    str+='</li>';

                }
                $('#commentList_51').append(str);
                $('#spage').html('');
                $('#spage').append(result.message);

                $("#spage li").click(function() {
                    var page=$(this).find('a').attr('page');
                    initMyComment(page);
                });
            }else{
                Main.showTip('获取我的发言异常');
            }
        }
    });
}

/**
 * 用户取消订单
 * @param orderId
 */
function cancelOrder(orderId){
    if(!confirm("确定取消订单吗？")){
        return;
    }
    if(orderId==''){
        Main.showTip("无效的订单");
        return;
    }
    $.ajax({
        url: "/user-order/cancel-order",
        type: "post",
        data:{
            orderId:orderId,
            _csrf: $('input[name="_csrf"]').val()
        },
        error:function(){
            Main.showTip('取消订单异常');
        },
        success: function(data){
            var result=eval("("+data+")");
            if(result.status==1){
                Main.showTip('取消订单成功');
                getUnFinishList();
            }else{
                Main.showTip('取消订单异常');
            }
        }
    });
}

/**
 * 随友取消订单
 * @param orderId
 */
function publisherCancelOrder(){
    if(!confirm("确定取消订单吗？")){
        return;
    }
    var orderId=$("#show_message_cancel_order_id").val();
    var message=$("#show_order_message").val();
    if(orderId==''){
        Main.showTip("无效的订单");
        return;
    }
    if(message==''){
        Main.showTip("请输入退款原因");
        return;
    }
    $.ajax({
        url: "/user-order/publisher-cancel-order",
        type: "post",
        data:{
            orderId:orderId,
            message:message,
            _csrf: $('input[name="_csrf"]').val()
        },
        error:function(){
            Main.showTip('取消订单异常');
        },
        success: function(data){
            var result=eval("("+data+")");
            if(result.status==1){
                Main.showTip('取消订单成功');
                getPublisherOrderList();
                $("#showOrderDiv").hide();
                $("#myMask").hide();
            }else{
                Main.showTip('取消订单异常');
            }
        }
    });
}

/**
 * 未接单情况下直接申请退款
 * @param orderId
 */
function refundOrder(orderId){
    if(!confirm("确定申请退款吗？")){
        return;
    }
    if(orderId==''){
        Main.showTip("无效的订单");
        return;
    }
    $.ajax({
        url: "/user-order/refund-order",
        type: "post",
        data:{
            orderId:orderId,
            _csrf: $('input[name="_csrf"]').val()
        },
        error:function(){
            Main.showTip('申请退款异常');
        },
        success: function(data){
            var result=eval("("+data+")");
            if(result.status==1){
                Main.showTip('申请退款成功，请耐心等待审核');
                getUnFinishList();
            }else{
                Main.showTip('申请退款异常');
            }
        }
    });
}

/**
 * 弹出退款申请窗口
 * @param orderId
 */
function showRefundWindow(orderId){
    if(orderId==''){
        Main.showTip("无效的订单");
        return;
    }
    $("#show_message_refund_order_id").val(orderId);
    $("#show_refund_message").html("");
    $("#showRefundDiv").show();
    $("#myMask").show();
}

/**
 * 已接单情况下填写退款申请退款
 */
function refundOrderByMessage(){
    var message=$("#show_refund_message").val();
    var orderId=$("#show_message_refund_order_id").val();
    if(message==''){
        Main.showTip("请填写退款申请");
        return;
    }
    if(orderId==''){
        Main.showTip("无效的订单");
        return;
    }

    $.ajax({
        url: "/user-order/refund-order-by-message",
        type: "post",
        data:{
            orderId:orderId,
            message:message,
            _csrf: $('input[name="_csrf"]').val()
        },
        error:function(){
            Main.showTip('取消订单异常');
        },
        success: function(data){
            var result=eval("("+data+")");
            if(result.status==1){
                Main.showTip('取消订单成功');
                $("#showRefundDiv").hide();
                $("#myMask").hide();
                getUnFinishList();
            }else{
                Main.showTip('取消订单异常');
            }
        }
    });
}

/**
 * 删除订单
 * @param orderId
 */
function deleteOrderInfo(orderId){
    if(!confirm("确定要删除订单吗？")){
        return;
    }
    $.ajax({
        url :'/user-order/delete-order',
        type:'post',
        data:{
            orderId:orderId,
            _csrf: $('input[name="_csrf"]').val()

        },
        error:function(){
            Main.showTip("删除订单失败");
        },
        success:function(data){
            var datas=eval('('+data+')');
            if(datas.status==1){
                Main.showTip("删除订单成功");
                getFinishList();
            }else{
                Main.showTip("删除订单失败");
            }
        }
    });
}

/**
 * 初始化用户私信消息设置
 */
function initUserMessageSetting(){
    $.ajax({
        url :'/user-message/find-user-message-setting',
        type:'post',
        data:{
            _csrf: $('input[name="_csrf"]').val()
        },
        error:function(){
            Main.showTip("获取用户私信设置失败");
        },
        success:function(data){
            var datas=eval('('+data+')');
            if(datas.status==1){
                buildUserMessageSetting(datas.data);
            }else{
                Main.showTip("获取用户私信设置失败");
            }
        }
    });
}

/**
 * 构建用户消息设置
 * @param userMessageSetting
 */
function buildUserMessageSetting(userMessageSetting){
    if(userMessageSetting.status==1){
        $("input:radio[name='user_message_setting_status'][value='1']").prop("checked",true);
        $("#user_message_setting_all").next('label').css('background-position','0 -47px').siblings('label').css('background-position','0 10px')
    }else if(userMessageSetting.status==2){
        $("input:radio[name='sex'][value='2']").prop("checked",true);
        $("#user_message_setting_none").next('label').css('background-position','0 -47px').siblings('label').css('background-position','0 10px')
    }
    if(userMessageSetting.shieldIds!=''){
        var html='',userBase='';
        for(var i=0;i<userMessageSetting.userBaseList.length;i++){
            userBase=userMessageSetting.userBaseList[i];
            html+='<div class="setd">';
            html+='<div class="people"><img src="'+userBase.headImg+'"><span>'+userBase.nickname+'</span></div>';
            html+='<input type="button" value="取消屏蔽" class="btn" onclick="deleteUserMessageShield(\''+userBase.userSign+'\')">';
            html+='</div>';
        }
        $("#messageShieldList").html(html);
        $("#user_message_setting_title").show();
        $("#messageShieldList").show();
    }else{
        $("#user_message_setting_title").hide();
        $("#messageShieldList").hide();
    }
}

/**
 * 更新用户私信设置
 */
function updateUserMessageSetting(){
    var status=$("input:radio[name='user_message_setting_status']:checked").val();
    $.ajax({
        url :'/user-message/update-message-setting-status',
        type:'post',
        data:{
            status:status,
            _csrf: $('input[name="_csrf"]').val()
        },
        error:function(){
            Main.showTip("更新私信设置失败");
        },
        success:function(data){
            var datas=eval('('+data+')');
            if(datas.status==1){
                initMessageSession();
            }else{
                Main.showTip("更新私信设置失败");
            }
        }
    });
}

/**
 * 添加用户屏蔽
 * @param userId
 */
function addUserMessageShield(userId){
    $.ajax({
        url :'/user-message/add-user-message-shield',
        type:'post',
        data:{
            shieldId:userId,
            _csrf: $('input[name="_csrf"]').val()
        },
        error:function(){
            Main.showTip("添加屏蔽失败");
        },
        success:function(data){
            var datas=eval('('+data+')');
            if(datas.status==1){
                initMessageSession();
            }else{
                Main.showTip("添加屏蔽失败");
            }
        }
    });
}

/**
 * 删除用户屏蔽
 */
function deleteUserMessageShield(userId){
    $.ajax({
        url :'/user-message/delete-user-message-shield',
        type:'post',
        data:{
            shieldId:userId,
            _csrf: $('input[name="_csrf"]').val()
        },
        error:function(){
            Main.showTip("取消屏蔽失败");
        },
        success:function(data){
            var datas=eval('('+data+')');
            if(datas.status==1){
                initUserMessageSetting();
                initMessageSession();
            }else{
                Main.showTip("取消屏蔽失败");
            }
        }
    });
}

/**
 * 用户确认游玩
 * @param orderId
 */
function userConfirmOrder(orderId){
    if(orderId==''){
        return;
    }
    if(!confirm("提示：\n    确认游玩后系统将会给随友打款\n    之后订单将无法取消或申请退款\n    请谨慎操作!")){
        return;
    }
    $.ajax({
        url :'/user-order/user-confirm-play',
        type:'post',
        data:{
            orderId:orderId,
            _csrf: $('input[name="_csrf"]').val()
        },
        error:function(){
            Main.showTip("确认游玩失败");
        },
        success:function(data){
            data=eval("("+data+")");
            if(data.status==1){
                Main.showTip("确认游玩成功");
                getFinishList();
            }else{
                Main.showTip("确认游玩失败");
            }
        }
    });
}


/**
 * 填出取消订单窗口
 * @param orderId
 */
function showCancelWindow(orderId){
    if(orderId==''){
        Main.showTip("无效的订单");
        return;
    }
    $("#show_message_cancel_order_id").val(orderId);
    $("#show_order_message").html("");
    $("#showOrderDiv").show();
    $("#myMask").show();
}

/**
 * 个人中心，上传护照
 */
function uploadAll() {
    if ($("#imgFront").attr("src") == "") {
        Main.showTip("请选择护照图片");
        return;
    }
    $('#fileCardFront').uploadifive('upload');
    $("#uploadAll").val("正在上传，请稍后...");
}
