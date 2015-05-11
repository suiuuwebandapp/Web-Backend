/**
 * Created by suiuu on 15/5/7.
 */

//第一页消息列表
var currentPageMessageSessionKeyList=[];
var messageSessionTimer;
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

    getUnFinishList();
    initUploadImg();
    initTab();
    initUserInfo();
    initDatePicker();
    initSelect();

    initMessageSession();
});

/**
 *  初始化修改密码
 */
function initUpdatePassword()
{
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
    messageSessionTimer=window.setInterval(function(){
        getUserUnReadMessageSession();
    },5000);
}

/**
 * 根据返回List构建私信会话HTML
 * @param list
 */
function buildMessageSessionHtml(list){
    if(list==''||list.length==0){
        return;
    }
    $("#messageInfoDiv ul").html("");//清空
    var html='',tempSession='',content='';
    for(var i=0;i<list.length;i++){
        tempSession=list[i];
        content=tempSession.lastContentInfo;
        currentPageMessageSessionKeyList.push(tempSession.sessionKey);
        if(content.length>50){
            content=content.substring(0,50)+"...";
        }
        var isNew='';
        if(tempSession.isRead!=1){
            isNew='class="new"';
        }
        html+='<li '+isNew+' sessionKey="'+tempSession.sessionKey+'" onclick=showMessageSessionInfo("'+tempSession.sessionKey+'","'+tempSession.headImg+'",this)>';
        html+='<div class="people"><img src="'+tempSession.headImg+'"><span>'+tempSession.nickname+'</span></div>';
        html+='<p class="words">'+content+'</p>';
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
        error:function(){
            Main.showTip("获取私信列表失败");
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
        var html=''
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
        content=tempSession.lastContentInfo;
        currentPageMessageSessionKeyList.push(tempSession.sessionKey);
        if(content.length>50){
            content=content.substring(0,50)+"...";
        }
        var isNew='';
        if(tempSession.isRead!=1){
            isNew='class="new"';
        }
        html+='<li '+isNew+' sessionKey="'+tempSession.sessionKey+'" onclick=showMessageSessionInfo("'+tempSession.sessionKey+'","'+tempSession.headImg+'",this)>';
        html+='<div class="people"><img src="'+tempSession.headImg+'"><span>'+tempSession.nickname+'</span></div>';
        html+='<p class="words">'+content+'</p>';
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
        //如果自己是发送人
        if(tempMessage.senderId==userSign){
            receiveId=tempMessage.receiveId;
            html+='<li class="you clearfix" mid="'+tempMessage.messageId+'">';
            html+='<img src="'+userHeadImg+'">';
            html+=' <p>'+tempMessage.content+'</p>';
            html+='</li>';
        }else{
            receiveId=tempMessage.senderId;
            html+='<li class="zuo clearfix" mid="'+tempMessage.messageId+'">';
            html+='<img src="'+receiveHeadImg+'">';
            html+=' <p>'+tempMessage.content+'</p>';
            html+='</li>';
        }

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
    if(nickname==''){

    }
    if($.trim(nickname)==''||$.trim(nickname)>30){
        $("#nicknameTip").html("昵称格式不正确");
        return;
    }
    if($.trim(countryId)==''){
        $("#cityTip").html("请选择居住地国家");
        return;
    }
    if($.trim(cityId)==''||$.trim(nickname)>30){
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
                Main.showTip("跟新用户信息成功");
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
function findCityInfo(obj) {
    var name=$(obj).val();
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
        $("input:radio[name='sex'][value='0']").attr("checked",true);
        $("#rado2").next('label').css('background-position','0 -47px').siblings('label').css('background-position','0 10px')
    }else if(userSex==1){
        $("input:radio[name='sex'][value='1']").attr("checked",true);
        $("#rad01").next('label').css('background-position','0 -47px').siblings('label').css('background-position','0 10px')
    }else{
        $("input:radio[name='sex'][value='2']").attr("checked",true);
        $("#rad03").next('label').css('background-position','0 -47px').siblings('label').css('background-position','0 10px')
    }

    if(userProfession=='持证导游'){
        $("input:radio[name='profession'][value='持证导游']").attr("checked",true);
        $("#shenfen01").next('label').css('background-position','0 -47px').siblings('label').css('background-position','0 10px')
    }else if(userProfession=='业余导游'){
        $("input:radio[name='profession'][value='业余导游']").attr("checked",true);
        $("#shenfen02").next('label').css('background-position','0 -47px').siblings('label').css('background-position','0 10px')
    }else if(userProfession=='学生'){
        $("input:radio[name='profession'][value='学生']").attr("checked",true);
        $("#shenfen03").next('label').css('background-position','0 -47px').siblings('label').css('background-position','0 10px')
    }else if(userProfession=='旅游爱好者'){
        $("input:radio[name='profession'][value='旅游爱好者']").attr("checked",true);
        $("#shenfen04").next('label').css('background-position','0 -47px').siblings('label').css('background-position','0 10px')
    }else{
        $("input:radio[name='profession'][value='其他']").attr("checked",true);
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
    imgAreaSelectApi.cancelSelection();
}

/**
 * 初始化头像截取插件
 * @param imgObj
 */
function initImgAreaSelect(imgObj){
    alert(imgAreaSelectApi);
    imgAreaSelectApi = $(imgObj).imgAreaSelect({
        instance : true,	// true，返回一个imgAreaSelect绑定到的图像的实例，可以使用api方法
        onSelectChange : preview,	// 改变选区时的回调函数
        handles : true,	// true，调整手柄则会显示在选择区域内
        fadeSpeed:200,
        resizable : true,
        aspectRatio:"1:1"

    });
    imgAreaSelectApi.setRotate(0);
    resetRotate();
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
        return '';
    }
    var tripInfo,html='';
    for(var i=0;i<tripList.length;i++){
        tripInfo=tripList[i];
        var count=tripInfo.count==null?'':tripInfo.count;
        var info=tripInfo.info;
        if(info.length>100){
            info=info.substring(0,100)+"...";
        }
        if(count!=''){ count='<a href="/trip/to-apply-list?trip='+tripInfo.tripId+'" class="sure">新申请</a><b>'+count+'</b>'};
        html+='<div class="orderList clearfix">';
        html+=' <img src="/assets/images/delete.fw.png" width="22" height="24" class="rubbish" onclick="deleteTravelTrip('+tripInfo.tripId+',this)">';
        html+=' <dl class="order clearfix">';
        html+='   <dt class="title">';
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
        html+=' <p><a href="/view-trip/info?trip='+tripInfo.tripId+'" class="cancel">查看详情</a>'+count+'</p>';
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
        return '';
    }
    var tripInfo,html='';
    for(var i=0;i<tripList.length;i++){
        tripInfo=tripList[i];
        var count=tripInfo.count==null?'':tripInfo.count;
        if(count!=''){ count='<a href="#" class="sure">新申请</a><b>'+count+'</b>'};
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
            html+='<img src="/assets/images/delete.fw.png" width="22" height="24" class="rubbish">'
        }
        html+='<dl class="order clearfix">';
        html+='<dt class="title">';
        html+='<span>'+Main.convertOrderDateToShow(orderInfo.createTime)+'</span><span>随游</span><span>开始时间</span><span>随友</span><span>随友电话</span><span>出行日期</span><span>人数</span><span>单项服务</span>';
        html+='</dt>';
        html+='<dd>';
        html+='<span class="pic"><img src="'+travelInfo.info.titleImg+'"></span>';
        html+='<span>'+travelInfo.info.title+'</span>';
        html+='<span>'+Main.convertTimePicker(orderInfo.startTime,2)+'</span>';
        if(orderInfo.phone==''||orderInfo.phone==null){
            html+='<span>未接单</span>';
            html+='<span>未接单</span>';
        }else{
            html+='<span><a href="#" class="user"><img src="'+orderInfo.headImg+'"  width="40" height="40"></a><a href="#" class="message"><b>'+orderInfo.nickname+'</b><br><img src="/assets/images/xf.fw.png" width="18" height="12"></a></span>';
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
        if(orderInfo.status==OrderStatus.USER_ORDER_STATUS_PAY_WAIT){
            html+='<p><a href="#" class="cancel">取消订单</a><a href="#" class="sure">支付</a><span>总价：<b>'+orderInfo.totalPrice+'</b></span>';
            html+='<span class="blue">待支付</span><span class="orange"></span></p>';
        }else if(orderInfo.status==OrderStatus.USER_ORDER_STATUS_PAY_SUCCESS) {
            html+='<p><a href="#" class="cancel">取消订单</a><span>总价：<b>'+orderInfo.totalPrice+'</b></span>';
            html+='<span class="blue">已支付</span><span class="orange">待接单</span></p>';
        }else if(orderInfo.status==OrderStatus.USER_ORDER_STATUS_CONFIRM){
            html+='<p><a href="#" class="cancel">申请退款</a><a href="#" class="sure">确认游玩</a><span>总价：<b>'+orderInfo.totalPrice+'</b></span>';
            html+='<span class="blue">已支付</span><span class="orange">已确认</span></p>';
        }else if(orderInfo.status==OrderStatus.USER_ORDER_STATUS_CANCELED){
            html+='<p><a href="#" class="cancel"></a><a href="#" class="sure"></a><span>总价：<b>'+orderInfo.totalPrice+'</b></span>';
            html+='<span class="blue">已取消</span><span class="orange"></span></p>';
        }else if(orderInfo.status==OrderStatus.USER_ORDER_STATUS_REFUND_WAIT){
            html+='<p><a href="#" class="cancel"></a><a href="#" class="sure"></a><span>总价：<b>'+orderInfo.totalPrice+'</b></span>';
            html+='<span class="blue">等待退款</span><span class="orange"></span></p>';
        }else if(orderInfo.status==OrderStatus.USER_ORDER_STATUS_REFUND_SUCCESS){
            html+='<p><a href="#" class="cancel"></a><a href="#" class="sure"></a><span>总价：<b>'+orderInfo.totalPrice+'</b></span>';
            html+='<span class="blue">退款成功</span><span class="orange"></span></p>';
        }else if(orderInfo.status==OrderStatus.USER_ORDER_STATUS_PLAY_SUCCESS||orderInfo.status==OrderStatus.USER_ORDER_STATUS_PLAY_FINISH){
            html+='<p><a href="#" class="cancel">去评价</a><a href="#" class="sure">分享</a><span>总价：<b>'+orderInfo.totalPrice+'</b></span>';
            html+='<span class="blue">已完成</span><span class="orange"></span></p>';
        }
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
        html+='<span><a href="#" class="user"><img src="'+orderInfo.headImg+'" width="40" height="40"></a><a href="#" class="message"><b>'+orderInfo.nickname+'</b><br><img src="/assets/images/xf.fw.png" width="18" height="12"></a></span>';
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

function initValidatePhone()
{
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
//验证邮箱
function initValidateEmail_info()
{
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
                $('#myCollectList').html('');

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
                $('#commentList_51').html('');

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
                Main.showTip('得到收藏异常');
            }
        }
    });

}
