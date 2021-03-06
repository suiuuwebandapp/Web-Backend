/**
 * Created by suiuu on 15/5/7.
 */

//第一页消息列表
var currentPageMessageSessionKeyList=[];

var rotate;
var rotateCount=0;
var containerDivWidth=350;
var containerDivHeight=210;
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

        //我的账户页面
        $("#userAccount,#accountList").bind("click",function(){
            getUserAccountRecordList();
        });
        $("#accountHistory").bind("click",function(){
            getUserAccountHistoryList();
        });
        $("#accountSearch select").bind("change",function(){
            getUserAccountHistoryList();
        });
        $("#toAddUserAccount").bind("click",function(){
            $("#userInfo").click();
            $("#userAccountLink").click();
        });
        if(bindWechat){
            showWechatDiv();
        }

        getUnConfirmOrderByPublisher();

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


    $(".con-nav li a").bind("click",function(){
        resetUploadHeadImg();
    });

    $("#updateInfoBtn").bind("click",function(){
        updateUserInfo();
    });

    //绑定发送验证码事件
    $("#getCode").bind("click", function () {
        sendTravelCode();
    });

    //初始化上传身份证等功能
    $(".p_chose_card_front").bind("click", function () {
        $("#uploadAll").val("立即验证");
        $("#userCardFront").val("");
        var file = $("#uploadifive-fileCardFront input[type='file']").last();
        $(file).click();
    });
    $("#resetUploadFront").bind("click",function(){
        resetUploadFront()
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
    //绑定申请资质认证
    $("#applyUserAptitudeBtn").bind("click",function(){
        applyUserAptitude();
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
    //证件信息上传
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
                $(".upload_front_process").html("上传成功！");
                $(".upload_front_process").css("color","#3dd9c3");
            } else {
                if(datas.status==-2){
                    $(".upload_front_process").html(datas.data);
                }else{
                    $(".upload_front_process").html("上传失败，请稍后重试");
                }
                $(".upload_front_process").css("color","red");
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

    //用户照片上传
    $('#userPhotoFile').uploadifive({
        'auto': true,
        'queueID': 'frontQueue',
        'uploadScript': '/upload/upload-user-photo',
        'multi': false,
        'dnd': false,
        'onAddQueueItem': function (file) {
            var html = '<li><a href="javascript:;" class="imgs" pic="' + file.name + file.size + '"><span class="upload_show_info">正在上传...</span><span class="delet" onclick="removeUserPhoto(this)"></span><img /></a></li>';
            $("#user_photo_list").prepend(html);
        },
        'onUploadComplete': function (file, data) {
            var datas = eval('(' + data + ')');
            var pic = file.name + file.size;
            var a = $("#user_photo_list").find("a[pic='" + pic + "']");
            if (datas.status == 1) {
                var photo= datas.data;
                $(a).find("img").attr("src", photo.url);
                $(a).find("span").eq(0).hide();
                $(a).find("span[class='upload_show_info']").remove();
            } else {
                $(a).find("span").eq(0).html("上传失败");
                $(a).remove();

            }
        }
    });

    $('#userPhotoUpload').bind('click',function(){
        $("#uploadifive-userPhotoFile input[type='file']").last().click();
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
 * 更新用户基本资料
 */
function updateUserInfo(){
    var surname=$("#surname").val();
    var name=$("#name").val();
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
    var qq=$("#qq").val();
    var wx=$("#wx").val();
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
            surname:surname,
            name:name,
            qq:qq,
            wechat:wx,
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
        'width':'169px',
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
            //findCityInfo(search);
        }
    });
    $("#countryId").change();

    //初始化区号选择
    $(".areaCodeSelect").select2({
        'width':'150px',
        'placeholder':'请选择区号',
        formatNoMatches: function () {
            return "暂无匹配";
        }
    });
}

/**
 * 初始化日期选择器（生日）
 */
function initDatePicker(){
    $('#birthday').datepicker({
        language:'zh-CN',
        autoclose: true,
        endDate:new Date(Date.parse(nowDate.replace(/-/g,"/"))),
        format:'yyyy-mm-dd',
        orientation:'top'
    });
}

/**
 * 初始化用户基本信息
 */
function initUserInfo(){
    //init sex
    if(userSex==0){
        $("input:radio[name='sex'][value='0']").prop("checked",true);
        $("#rad02").next('label').css('background-position','0 -47px').siblings('label').css('background-position','0 10px')
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
    var tabId=Main.getRequestParam("tab");
    if(tabId!=""){
        $("#"+tabId).click();
    }else{
        var href=window.location.href;
        if(href.endWith("#")){
            href=href.substring(0,href.length-1);
        }
        if(href.indexOf("?")!=-1){
            tabId=href.substring(href.indexOf("?")+1,href.length);
            $("#"+tabId).click();
        }
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
    $(".upload_front_process").html("");
    $(".upload_front_process").css("color","rosybrown");
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
            Main.showTip("上传头像异常，请刷新重试！");
        },
        success: function(data){
            var result=eval("("+data+")");
            if(result.status==1){
                $(".userPic img").attr("src",result.data);
                $(".header img.user").attr("src",result.data);
                Main.showTip("上传头像成功!");
                resetUploadHeadImg();
            }else{
                Main.showTip("上传头像异常，请刷新重试！");
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
    var imgWidth = $("#img_origin").width();
    var imgHeight = $("#img_origin").height();
    $("#img_origin").css("margin", "0");
    $("#img_origin").attr("oldWidth", imgWidth);
    $("#img_origin").attr("oldHeight", imgHeight);

    if ((containerDivWidth / containerDivHeight) < (imgWidth / imgHeight)) {
        $("#img_origin").width(containerDivWidth);
    } else {
        $("#img_origin").height(containerDivHeight);
    }

    imgWidth = $("#img_origin").width();
    imgHeight = $("#img_origin").height();

    var padding=0;

    if (imgWidth == containerDivWidth) {
         padding = (containerDivHeight - imgHeight) / 2;
        $("#img_origin").css("margin-top", padding);
        //imgAreaSelectApi.setSelection((imgWidth/2)-(imgHeight/4), (imgHeight/2)-(imgHeight/4), (imgWidth/2)+(imgHeight/4), (imgHeight/2)+(imgHeight/4), true);

    }
    if (imgHeight ==containerDivHeight) {
        padding = (containerDivWidth - imgWidth) / 2;
        $("#img_origin").css("margin-left", padding);
        //imgAreaSelectApi.setSelection((imgHeight/2)-(imgWidth/4)-padding, (imgHeight/2)-(imgWidth/4), (imgHeight/2)+(imgWidth/4)-padding, (imgHeight/2)+(imgWidth/4), true);
    }

    if (imgHeight == imgWidth) {
        if (containerDivHeight > containerDivWidth) {
            $("#img_origin").css("margin-top", (containerDivHeight - imgHeight) / 2);
        } else {
            $("#img_origin").css("margin-left", (containerDivWidth - imgWidth) / 2);
        }
    }

    if (imgWidth > 100 && imgHeight > 100) {
        imgAreaSelectApi.setSelection(0, 0, 100, 100, true);
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
    var tripInfo,html='';
    if(tripList==''||tripList.length==0){
        $("#tripNothing p").html("您还没有发布过随游哦");
        $("#tripNothing").show();
        return html;
    }
    $("#tripNothing").hide();
    for(var i=0;i<tripList.length;i++){
        tripInfo=tripList[i];
        var count=tripInfo.count==null?'':tripInfo.count;
        var info=tripInfo.info;
        var title=tripInfo.title;
        var startTime=tripInfo.startTime;
        if(info.length>100){
            info=info.substring(0,100)+"...";
        }
        if(title.length>12){
            title=title.substring(0,12)+"...";
        }
        if(!Main.isNotEmpty(info)){
            info='&nbsp;';
        }

        if(!Main.isNotEmpty(startTime)){
            startTime='&nbsp;';
        }
        html+='<div class="orderList clearfix">';
        html+=' <img src="/assets/images/delete.fw.png" width="22" height="24" class="rubbish" onclick="deleteTravelTrip('+tripInfo.tripId+',this)">';
        html+=' <dl class="order clearfix">';
        if(tripInfo.status==TripStatus.TRAVEL_TRIP_STATUS_DRAFT||tripInfo.status==TripStatus.TRAVEL_TRIP_AUTO_SAVE){
            html+='   <dt class="title grey">';
        }else{
            html+='   <dt class="title">';
        }
        html+='       <span>'+Main.formatDate(tripInfo.createTime,'yyyy.MM.dd')+'发布</span><span>'+title+'</span><span>随游时间</span><span>附加服务</span>';
        html+='   </dt>';
        html+='   <dd>';
        html+='       <span class="pic"><img src="'+tripInfo.titleImg+'"></span>';
        html+='       <span>'+info+'</span>';
        html+='       <span>'+startTime+'</span>';
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
            html+=' <a href="'+UrlManager.getTripEditUrl(tripInfo.tripId)+'" class="sure">编辑发布</a>';
            html+=' <a href="'+UrlManager.getTripInfoUrl(tripInfo.tripId)+'" class="cancel">查看详情</a>';
        }else if(tripInfo.status==TripStatus.TRAVEL_TRIP_AUTO_SAVE){
            html+=' <a href="'+UrlManager.getTripEditUrl(tripInfo.tripId)+'" class="sure">继续编辑</a>';
        }else{
            html+=' <a href="'+UrlManager.getTripInfoUrl(tripInfo.tripId)+'" class="cancel">查看详情</a>';
            html+=' <a href="'+UrlManager.getTripEditUrl(tripInfo.tripId)+'" class="sure">重新编辑</a>';

        }
        if(count!=''){ html+='<a href="/trip/to-apply-list?trip='+tripInfo.tripId+'" class="sure">新申请</a>';};
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
    var tripInfo,html='';

    if(tripList==''||tripList.length==0){
        $("#tripNothing p").html("您还没有申请加入过随游哦");
        $("#tripNothing").show();
        return html;
    }
    $("#tripNothing").hide();
    for(var i=0;i<tripList.length;i++){
        tripInfo=tripList[i];
        var info=tripInfo.info;
        var title=tripInfo.title;
        if(info.length>100){
            info=info.substring(0,100)+"...";
        }
        if(title.length>12){
            title=title.substring(0,12)+"...";
        }
        //var count=tripInfo.count==null?'':tripInfo.count;
        //if(count!=''){ count='<a href="#" class="sure">新申请<b>'+count+'</b></a>'};
        html+='<div class="orderList clearfix">';
        html+=' <dl class="order clearfix">';
        html+='   <dt class="title">';
        html+='       <span>'+Main.formatDate(tripInfo.createTime,'yyyy.MM.dd')+'发布</span><span>'+tripInfo.title+'</span><span>随游时间</span><span>附加服务</span>';
        html+='   </dt>';
        html+='   <dd>';
        html+='       <span class="pic"><a href="/view-trip?trip='+tripInfo.tripId+'"><img src="'+title+'"></a></span>';
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
        html+='<p>';
        html+=' <a href="'+UrlManager.getTripInfoUrl(tripInfo.tripId)+'" class="cancel">查看详情</a>';
        html+=' <a href="javascript:;" onclick="quitTravelTrip('+tripInfo.tripId+',this)" class="sure">退出随游</a>';
        html+='</p>';
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
        $("#myOrderNothing").show();
        return html;
    }
    $("#myOrderNothing").hide();
    for(var i=0;i<list.length;i++){
        var orderInfo=list[i];
        var travelInfo=orderInfo.tripJsonInfo;
        travelInfo=eval("("+travelInfo+")");
        var serviceInfo=orderInfo.serviceInfo;
        serviceInfo=eval("("+serviceInfo+")");

        var  orderStatusHtml='',orderBtnHtml='';

        if(orderInfo.status==OrderStatus.USER_ORDER_STATUS_PAY_WAIT){
            orderBtnHtml+='<a href="javascript:cancelOrder('+orderInfo.orderId+');" class="cancel">取消订单</a><a href="/user-order/info?orderNumber='+orderInfo.orderNumber+'" class="sure">支付</a>';
            orderStatusHtml+='<span><b class="colOrange">待支付</b><br><b class="colGreen"></b></span>';
        }else if(orderInfo.status==OrderStatus.USER_ORDER_STATUS_PAY_SUCCESS) {
            //判断是否超过随游时长
            orderBtnHtml+='<a href="javascript:refundOrder('+orderInfo.orderId+');" class="cancel">申请退款</a>';
            orderStatusHtml+='<span><b class="colOrange">待接单</b><br><b class="colGreen">已支付</b></span>';
        }else if(orderInfo.status==OrderStatus.USER_ORDER_STATUS_CONFIRM){
            orderBtnHtml+='<a href="javascript:showRefundWindow('+orderInfo.orderId+');" class="cancel">申请退款</a><a href="javascript:userConfirmOrder('+orderInfo.orderId+')" class="sure">确认游玩</a>';
            orderStatusHtml+='<span><b class="colGreen">已支付</b><br><b class="colGreen">已确认</b></span>';
        }else if(orderInfo.status==OrderStatus.USER_ORDER_STATUS_CANCELED){
            orderBtnHtml+='<a href="#" class="cancel_1"></a><a href="#" class="sure_1"></a>';
            orderStatusHtml+='<span><b class="colOrange">订单关闭</b><br><b class="colGreen">已取消</b></span>';
        }else if(orderInfo.status==OrderStatus.USER_ORDER_STATUS_REFUND_WAIT){
            orderBtnHtml+='<a href="#" class="cancel_1"></a><a href="#" class="sure_1"></a>';
            orderStatusHtml+='<span><b class="colOrange">等待退款</b><br><b class="colGreen"></b></span>';
        }else if(orderInfo.status==OrderStatus.USER_ORDER_STATUS_REFUND_SUCCESS){
            orderBtnHtml+='<a href="#" class="cancel_1"></a><a href="#" class="sure_1"></a>';
            orderStatusHtml+='<span><b class="colOrange"></b><br><b class="colGreen">退款成功</b></span>';
        }else if(orderInfo.status==OrderStatus.USER_ORDER_STATUS_PLAY_SUCCESS||orderInfo.status==OrderStatus.USER_ORDER_STATUS_PLAY_FINISH){
            if(orderInfo.isComment==null||orderInfo.isComment=="null"){
                orderBtnHtml+='<a href="/user-order/to-comment?orderId='+orderInfo.orderId+'" class="cancel">去评价</a>';
            }
            orderBtnHtml+='<a href="javascript:;" class="sure">分享</a>';
            orderStatusHtml+='<span><b class="colOrange"></b><br><b class="colGreen">已完成</b></span>';
        }else if(orderInfo.status==OrderStatus.USER_ORDER_STATUS_REFUND_VERIFY){
            orderBtnHtml+='<a href="#" class="cancel_1"></a><a href="#" class="sure_1"></a>';
            orderStatusHtml+='<span><b class="colOrange">退款审核中</b><br><b class="colGreen"></b></span>';
        }else if(orderInfo.status==OrderStatus.USER_ORDER_STATUS_REFUND_SUCCESS){
            orderBtnHtml+='<a href="#" class="cancel_1"></a><a href="#" class="sure_1"></a>';
            orderStatusHtml+='<span><b class="colOrange"></b><br><b class="colGreen">已退款</b></span>';
        }

        html+='<div class="orderList clearfix">';

        html+='<dl class="order clearfix">';
        html+='<dt class="title">';
        html+='<span>订单状态</span><span>随游</span><span>开始日期</span><span>人数</span><span>联系人</span><span><a href="/user-order/view-order-info?orderNumber='+orderInfo.orderNumber+'" target="_blank">查看服务明细</a></span>';
        html+='</dt>';
        html+='<dd>';
        html+=orderStatusHtml;
        html+='<span><a href="'+UrlManager.getTripInfoUrl(travelInfo.info.tripId)+'" class="colGreen">'+travelInfo.info.title+'</a></span>';
        html+='<span><b>'+Main.formatDate(orderInfo.beginDate,'yyyy年MM月dd日')+'</b><br><b>'+Main.convertTimePicker(orderInfo.startTime,2)+'</b></span>';
        html+='<span>'+orderInfo.personCount+'</span>';
        if(orderInfo.phone==''||orderInfo.phone==null){
            html+='<span class="colOrange">未接单</span>';
        }else{
            html+='<span>';
            html+='<a href="'+UrlManager.getUserInfoUrl(orderInfo.userSign)+'" target="_blank" class="user"><img src="'+orderInfo.headImg+'"></a>';
            html+='<a href="javascript:;" class="message"><b>'+orderInfo.nickname+'</b><br>';
            html+='<img onclick="Main.showSendMessage(\''+orderInfo.userId+'\')"  src="/assets/images/xf.fw.png" width="18" height="12"></a>';
            if(Main.isNotEmpty(orderInfo.phone)){
                html+='<b>'+orderInfo.areaCode+' '+orderInfo.phone+'</b>';
            }
            html+='</span>';
        }
        html+='<span>';

        html+='</span>';
        html+='</dd>';
        html+='</dl>';

        html+='<p>';
        html+='<span class="data01"><b>订单创建时间：'+Main.formatDate(orderInfo.createTime,'yyyy年MM月dd日 hh:mm')+'</b><b>订单号 '+orderInfo.orderNumber+'</b></span>';
        html+=orderBtnHtml;
        html+='<span>总价：<b>￥'+orderInfo.totalPrice+'</b></span>';
        html+='</p>';

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
        $("#tripNothing p").html("您暂时还没有随游订单哦");
        $("#tripNothing").show();
        return html;
    }
    $("#tripNothing").hide();
    for(var i=0;i<list.length;i++){
        var orderInfo=list[i];
        var travelInfo=orderInfo.tripJsonInfo;
        travelInfo=eval("("+travelInfo+")");
        var serviceInfo=orderInfo.serviceInfo;
        serviceInfo=eval("("+serviceInfo+")");

        var  orderStatusHtml='',orderBtnHtml='';

        orderBtnHtml='<a href="#" class="cancel_1"></a><a href="#" class="sure_1"></a>';
        orderStatusHtml='<span><b class="colOrange"></b><br><b class="colGreen">已完成</b></span>';

        if(orderInfo.status==OrderStatus.USER_ORDER_STATUS_CONFIRM){
            orderStatusHtml='<span><b class="colGreen">已接单</b><br/><b class="colOrange">等待用户确认</b></span>';
            orderBtnHtml='<a href="javascript:showCancelWindow('+orderInfo.orderId+');" class="cancel">取消订单</a>';
        }else if(orderInfo.status==OrderStatus.USER_ORDER_STATUS_CANCELED){
            orderBtnHtml='<a href="#" class="cancel_1"></a><a href="#" class="sure_1"></a>';
            orderStatusHtml='<span><b class="colOrange">订单关闭</b><br><b class="colGreen">已取消</b></span>';
        }

        html+='<div class="orderList clearfix">';

        html+='<dl class="order clearfix">';
        html+='<dt class="title">';
        html+='<span>订单状态</span><span>随游</span><span>开始日期</span><span>人数</span><span>联系人</span><span><a href="/user-order/view-order-info?orderNumber='+orderInfo.orderNumber+'" target="_blank">查看服务明细</a></span>';
        html+='</dt>';
        html+='<dd>';
        html+=orderStatusHtml;
        html+='<span><a href="'+UrlManager.getTripInfoUrl(travelInfo.info.tripId)+'" class="colGreen">'+travelInfo.info.title+'</a></span>';
        html+='<span><b>'+Main.formatDate(orderInfo.beginDate,'yyyy年MM月dd日')+'</b><br><b>'+Main.convertTimePicker(orderInfo.startTime,2)+'</b></span>';
        html+='<span>'+orderInfo.personCount+'</span>';
        html+='<span>';
        html+='<a href="'+UrlManager.getUserInfoUrl(orderInfo.userId)+'" target="_blank" class="user"><img src="'+orderInfo.headImg+'"></a>';
        html+='<a href="javascript:;" class="message"><b>'+orderInfo.nickname+'</b><br>';
        html+='<img onclick="Main.showSendMessage(\''+orderInfo.userId+'\')"  src="/assets/images/xf.fw.png" width="18" height="12"></a>';
        if(Main.isNotEmpty(orderInfo.phone)){
            html+='<b>'+orderInfo.areaCode+' '+orderInfo.phone+'</b>';
        }
        html+='</span>';
        html+='<span>';
        html+='</span>';
        html+='</dd>';
        html+='</dl>';

        html+='<p>';
        html+='<span class="data01"><b>订单创建时间：'+Main.formatDate(orderInfo.createTime,'yyyy年MM月dd日 hh:mm')+'</b><b>订单号 '+orderInfo.orderNumber+'</b></span>';
        html+=orderBtnHtml;
        html+='<span>总价：<b>￥'+orderInfo.totalPrice+'</b></span>';
        html+='</p>';

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
        $("#tripNothing p").html("暂时没有可接的订单哦");
        $("#tripNothing").show();
        return html;
    }
    $("#tripNothing").hide();
    for(var i=0;i<list.length;i++){
        var orderInfo=list[i];
        var travelInfo=orderInfo.tripJsonInfo;
        travelInfo=eval("("+travelInfo+")");
        var serviceInfo=orderInfo.serviceInfo;
        serviceInfo=eval("("+serviceInfo+")");

        var  orderStatusHtml='',orderBtnHtml='';
        orderStatusHtml+='<span><b class="colOrange">待接单</b><br><b class="colGreen">已支付</b></span>';
        orderBtnHtml+='<a href="javascript:publisherIgnoreOrder('+orderInfo.orderId+');" class="cancel">忽略</a><a href="javascript:publisherConfirmOrder('+orderInfo.orderId+');" class="sure">接受</a>';

        html+='<div class="orderList clearfix">';

        html+='<dl class="order clearfix">';
        html+='<dt class="title">';
        html+='<span>订单状态</span><span>随游</span><span>开始日期</span><span>人数</span><span>联系人</span><span><a href="/user-order/view-order-info?orderNumber='+orderInfo.orderNumber+'" target="_blank">查看服务明细</a></span>';
        html+='</dt>';
        html+='<dd>';
        html+=orderStatusHtml;
        html+='<span><a href="'+UrlManager.getTripInfoUrl(travelInfo.info.tripId)+'" class="colGreen">'+travelInfo.info.title+'</a></span>';
        html+='<span><b>'+Main.formatDate(orderInfo.beginDate,'yyyy年MM月dd日')+'</b><br><b>'+Main.convertTimePicker(orderInfo.startTime,2)+'</b></span>';
        html+='<span>'+orderInfo.personCount+'</span>';
        html+='<span>';
        html+='<a href="'+UrlManager.getUserInfoUrl(orderInfo.userId)+'" target="_blank" class="user"><img src="'+orderInfo.headImg+'"></a>';
        html+='<a href="javascript:;" class="message"><b>'+orderInfo.nickname+'</b><br>';
        html+='<img onclick="Main.showSendMessage(\''+orderInfo.userId+'\')"  src="/assets/images/xf.fw.png" width="18" height="12"></a>';
        if(Main.isNotEmpty(orderInfo.phone)){
            html+='<b>'+orderInfo.areaCode+' '+orderInfo.phone+'</b>';
        }
        html+='</span>';        html+='<span>';
        html+='</span>';
        html+='</dd>';
        html+='</dl>';

        html+='<p>';
        html+='<span class="data01"><b>订单创建时间：'+Main.formatDate(orderInfo.createTime,'yyyy年MM月dd日 hh:mm')+'</b><b>订单号 '+orderInfo.orderNumber+'</b></span>';
        html+=orderBtnHtml;
        html+='<span>总价：<b>￥'+orderInfo.totalPrice+'</b></span>';
        html+='</p>';

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

function quitTravelTrip(tripId,obj)
{
    if(!confirm("确定要退出随游吗？")){
        return;
    }
    $.ajax({
        url :'/trip/quit-trip',
        type:'post',
        data:{
            tripId:tripId,
            _csrf: $('input[name="_csrf"]').val()

        },
        error:function(){
            Main.showTip("退出随游失败");
        },
        success:function(data){
            var datas=eval('('+data+')');
            if(datas.status==1){
                Main.showTip("退出随游成功");
                $(obj).parent().parent().remove();
            }else{
                Main.showTip("退出随游失败");
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
                    html+='<option value="'+city.id+'">'+city.cname+'</option>';
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
            $("#getCode").html("正在发送...");
        },
        error: function () {
            $("#getCode").html("发送失败...");
        },
        success: function (data) {
            var datas = eval('(' + data + ')');
            if (datas.status == 1) {
                $("#getCode").html("发送成功!");
                phoneTime = 60;
                initPhoneTimer();
            } else {
                $("#getCode").html("获取验证码");
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
                $("#phone_val").val(phone);
                $("#phone_show_btn").click();
                Main.showTip('验证成功');
                $("#phone").val('');
                $("#code_p").val('');
                $("#validatePhone").html('立即修改');
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
    $("#emailTip").html('');
    if (email == "") {
        $("#emailTip").html('邮箱不能为空');
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
                $("#emailTip").html(datas.data);
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
        $("#getCode").html(+phoneTime + "秒后重新发送");
        $("#getCode").attr("disabled", "disabled");

        $("#getCode").css("background", "gray");
        $("#getCode").unbind("click");
    } else {
        $("#getCode").html("获取验证码");
        $("#getCode").removeAttr("disabled");
        $("#getCode").css("background", "#FFAA00");
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
                    $("#collectNothing").show();
                    return;
                }else{
                    $("#collectNothing").hide();
                    $('#myCollectList').html('');
                }

                var str ='';
                for(var i=0;i<l;i++)
                {
                    var rst= result.data.data[i];
                    var commentCount=0;
                    var title=rst.title;
                    if(Main.isNotEmpty(rst.commentCount)){
                        commentCount=rst.commentCount;
                    }
                    if((i+1)%3==0){
                        str+='<div class="web-tuijian fl nomg">';
                    }else{
                        str+='<div class="web-tuijian fl">';
                    }
                    if(title.length>15){
                        title=title.substring(0,15)+"...";
                    }
                    str+='<a href="'+UrlManager.getTripInfoUrl(rst.tripId)+'" class="pic">';
                    str+='  <img src="'+rst.titleImg+'" width="410" height="267">';
                    str+='  <p class="p4"><span>￥'+rst.basePrice+'</span>每次</p>';
                    str+='</a>';
                    str+='<a href="'+UrlManager.getUserInfoUrl(rst.userSign)+'" target="_blank" class="user"><img src="'+rst.headImg+'"></a>';
                    str+='  <p class="title">'+rst.title+'</p>';
                    str+='<p class="xing">';
                    if(rst.score>=2){str+='<img src="/assets/images/start1.fw.png" alt="">';}else{str+='<img src="/assets/images/start2.fw.png" alt="">';}
                    if(rst.score>=4){str+='<img src="/assets/images/start1.fw.png" alt="">';}else{str+='<img src="/assets/images/start2.fw.png" alt="">';}
                    if(rst.score>=6){str+='<img src="/assets/images/start1.fw.png" alt="">';}else{str+='<img src="/assets/images/start2.fw.png" alt="">';}
                    if(rst.score>=8){str+='<img src="/assets/images/start1.fw.png" alt="">';}else{str+='<img src="/assets/images/start2.fw.png" alt="">';}
                    if(rst.score>=10){str+='<img src="/assets/images/start1.fw.png" alt="">';}else{str+='<img src="/assets/images/start2.fw.png" alt="">';}
                    str+='<span>'+rst.tripCount+'人去过</span><span>'+commentCount+'条评论</span>';
                    str+='</p>';
                    str+='</div>';
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
            Main.showTip('获取评论异常');
        },
        success: function(data){
            var result=eval("("+data+")");
            if(result.status==1){
                var l =  result.data.data.length;
                if(l==0){
                    $("#commentNothing").show();
                    return;
                }else{
                    $("#commentNothing").hide();
                    $('#commentList_51').html('');
                }
                var str ='';
                for(var i=0;i<l;i++)
                {
                    var rst= result.data.data[i];
                    str+='<li>';
                    str+='<div class="userPic">';
                    str+='<a href="'+UrlManager.getUserInfoUrl(rst.userSign)+'" target="_blank"><img alt="" src="'+rst.headImg+'"></a>';
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
                Main.showTip("添加屏蔽成功");
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
                getUnFinishList();
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
    var src=$("#imgFront").attr("src");
    //判断你是否从新选择了图片
    if ( src== ""||!src.startWith("blob:")) {
        Main.showTip("请选择证件图片");
        return;
    }
    $(".upload_front_process").html("正在上传，请稍后...");
    $('#fileCardFront').uploadifive('upload');
}

/**
 * 重置上传护照认证
 */
function resetUploadFront(){
    $("#imgFront").hide();
    $(".upload_front_process").html("");
    $(".p_chose_card_front").html("点击上传护照");
    $(".p_chose_card_front").show();
}


/**
 * 获取用户账户信息列表
 */
function getUserAccountRecordList(){
    $.ajax({
        url :'/user-account/list',
        type:'post',
        data:{
            _csrf: $('input[name="_csrf"]').val()
        },
        error:function(){
            Main.showTip("获取用户账户清单失败");
        },
        success:function(data){
            data= $.parseJSON(data);
            if(data.status==1){
                buildUserAccountListHtml(data.data,"accountDl");
            }else{
                Main.showTip("获取用户账户清单失败");
            }
        }
    });
}

/**
 * 构建清单列表
 * @param data
 */
function buildUserAccountListHtml(data,id) {
    var html='',type='',status='',flag=false;
    if(data.totalCount==0){
        html='<dd><div id="accountNothing" style="padding-top: 30px;" class="sycoNothing"><img src="/assets/images/N07.png" width="78" height="78"><p style="line-height: normal">您还没有收入/支出</p></div></dd>';
        $("#"+id+" dd").remove();
        $("#"+id).append(html);
        return;
    }
    for(var i=0;i<data.result.length;i++){
        var record=data.result[i];
        html+='<dd>';
        html+='<span>'+Main.formatDate(record.recordTime,'yyyy-MM-dd')+'</span>';
        if(record.type==UserAccountRecordType.USER_ACCOUNT_RECORD_TYPE_TRIP_SERVER){
            type='随游服务收入';
            status='已入账';
        }else if(record.type==UserAccountRecordType.USER_ACCOUNT_RECORD_TYPE_DRAW_MONEY){
            type='转出';
            flag=true;
            if(record.status==UserCashRecordType.USER_CASH_RECORD_STATUS_WAIT){
                status='正在处理';
            }else if(record.status==UserCashRecordType.USER_CASH_RECORD_STATUS_SUCCESS){
                status='转出成功';
            }else if(record.status==UserCashRecordType.USER_CASH_RECORD_STATUS_FAIL){
                status='转出失败';
            }else{
                status="转出异常";
            }
        }else if(record.type==UserAccountRecordType.USER_ACCOUNT_RECORD_TYPE_TRIP_DIVIDED_INTO){
            type='线路分成服务';
            status='已入账';
        }else if(record.type==UserAccountRecordType.USER_ACCOUNT_RECORD_TYPE_OTHER){
            type='其他收入';
            status='已入账';
        }
        html+='<span>'+type+'</span>';
        html+='<span>'+record.info+'</span>';
        html+='<span class="orange">￥'+parseInt(record.money)+'</span>';
        if(id=="historyDl"){
            html+='<span class="blueColor">'+status+'</span>';
        }
        html+='</dd>';

    }
    $("#"+id+" dd").remove();
    $("#"+id).append(html);
    if(data.pageHtml!=''){
        $("#"+id).append('<dd>'+data.pageHtml+'</dd>');
    }
}

/**
 * 获取用户账户历史
 */
function getUserAccountHistoryList(){
    $.ajax({
        url :'/user-account/history-list',
        type:'post',
        data:{
            year :$("#accountYear").val(),
            month:$("#accountMonth").val(),
            type :$("#accountType").val(),
            _csrf: $('input[name="_csrf"]').val()
        },
        error:function(){
            Main.showTip("获取用户历史交易失败");
        },
        success:function(data){
            data= $.parseJSON(data);
            if(data.status==1){
                buildUserAccountListHtml(data.data,"historyDl");
            }else{
                Main.showTip("获取用户历史交易失败");
            }
        }
    });
}

/**
 * 弹出绑定支付宝
 */
function showBindAlipay(){
    $("#showAlipayDiv").show();
    $(".mask").show();
}

/**
 * 绑定支付宝账号
 */
function bindAlipayAccount() {
    var account=$("#bindAlipayName").val();
    var name=$("#bindAlipayName").val();

    if(account==''){
        Main.showTip("请输入支付宝账户");
        return;
    }
    if(name==''){
        Main.showTip("请输入真实姓名");
        return;
    }
    $.ajax({
        url :'/user-account/bind-alipay',
        type:'post',
        data:{
            account :$("#bindAlipayAccount").val(),
            name : $("#bindAlipayName").val(),
            _csrf: $('input[name="_csrf"]').val()
        },
        error:function(){
            Main.showTip("绑定支付宝账户失败");
        },
        success:function(data){
            data= $.parseJSON(data);
            if(data.status==1){
                Main.showTip("绑定支付宝账户成功");
                window.location.href="/user-info?tab=userInfo&tabInfo=userAccountLink"
            }else{
                Main.showTip("绑定支付宝账户失败");
            }
        }
    });
}


/**
 * 绑定微信账号
 */
function bindWechatAccount(){

    var name=$("#bindWechatName").val();
    if(name==''){
        Main.showTip("请输入真实姓名");
        return;
    }
    $.ajax({
        url :'/user-account/bind-wechat',
        type:'post',
        data:{
            name : $("#bindWechatName").val(),
            _csrf: $('input[name="_csrf"]').val()
        },
        error:function(){
            Main.showTip("绑定微信账户失败");
        },
        success:function(data){
            data= $.parseJSON(data);
            if(data.status==1){
                Main.showTip("绑定微信账户成功");
                window.location.href="/user-info?tab=userInfo&tabInfo=userAccountLink"
            }else{
                Main.showTip("绑定微信账户失败");
            }
        }
    });
}
/**
 * 显示微信绑定扫码图片
 */
function showWechatImgDiv() {
    $("#showChangeWechatDiv").hide();
    $("#showWechatImgDiv").show();
    $(".mask").show();
}

/**
 * 显示微信绑定DIV
 */
function showWechatDiv(){
    $("#showWechatDiv").show();
    $(".mask").show();
}
/**
 * 显示更新微信绑定DIV
 */
function showChangeWechatDiv(){
    $("#showChangeWechatDiv").show();
    $(".mask").show();
}

/**
 * 提现
 */
function drawMoney(){
    var accountId=$("#accountId").val();
    var drawMoney=$("#drawMoney").val();
    if(accountId==""){
        Main.showTip("请选择有效的收款账户");
        return;
    }
    if(drawMoney==""){
        Main.showTip("请输入有效的转出金额");
        return;
    }
    if(isNaN(drawMoney)){
        Main.showTip("请输入有效的转出金额");
        return;
    }
    if(drawMoney<1){
        Main.showTip("请输入有效的转出金额");
        return;
    }
    $.ajax({
        url :'/user-account/draw-money',
        type:'post',
        data:{
            accountId : accountId,
            money : drawMoney,
            _csrf: $('input[name="_csrf"]').val()
        },
        error:function(){
            Main.showTip("转出余额失败");
        },
        success:function(data){
            data= $.parseJSON(data);
            if(data.status==1){
                Main.showTip("转出余额成功，我们会在5个工作日内将钱打入您的指定账户。");
                window.location.href="/user-info?tab=userAccount";
            }else{
                Main.showTip("转出余额失败");
            }
        }
    });
}

/**
 * 添加资质申请
 */
function applyUserAptitude(){
    $.ajax({
        url :'/user-info/apply-user-aptitude',
        type:'post',
        data:{
            _csrf: $('input[name="_csrf"]').val()
        },
        error:function(){
            Main.showTip("申请资质认证失败");
        },
        success:function(data){
            data= $.parseJSON(data);
            if(data.status==1){
                Main.showTip("申请资质认证失成功，我们的工作人员会尽快与您联系！");
                $("#applyUserAptitudeBtn").html("工作人员审核中");
                $("#applyUserAptitudeBtn").disable();
                $("#applyUserAptitudeBtn").unbind("click");
                $("#applyUserAptitudeBtn").css("background","gainsboro");
            }else{
                Main.showTip("申请资质认证失败");
            }
        }
    });
}

/**
 *删除用户照片
 * @param obj
 */
function removeUserPhoto(obj){
    var photoId=$(obj).parent().attr("photoId");
    if(photoId==''){
        Main.showTip("无效的照片");
        return;
    }
    $.ajax({
        url :'/user-info/remove-user-photo',
        type:'post',
        data:{
            photoId:photoId,
            _csrf: $('input[name="_csrf"]').val()
        },
        error:function(){
            Main.showTip("删除照片失败");
        },
        success:function(data){
            data= $.parseJSON(data);
            if(data.status==1){
                $(obj).parent().parent().remove();
            }else{
                Main.showTip("删除照片失败");
            }
        }
    });
}


function toValidateEmail(){
    $("#myUserInfo").click();
    $('html,body').animate({scrollTop:$('#validateEmail_info').offset().top-200}, 800);

}

function toValidatePhone() {
    $("#myUserInfo").click();
    $('html,body').animate({scrollTop:$('#phone_show_btn').offset().top-100}, 800);
}




/************************ User Message Begin ******************************/






/**
 * 初始化用户消息会话
 */
function initMessageSession(){
    $("#sendMessageBtn").bind("click",function(){
        sendUserMessage();
    });
    $("#userMessageSetting").bind("click",function(){
        initUserMessageSetting();
    });
    $("input:radio[name='user_message_setting_status']").bind("change",function(){
        updateUserMessageSetting();
    });
    $("#messageContent").keypress(function(e){
        if(e.keyCode==13){
            sendUserMessage();
        }
    });
    buildMessageSessionHtml(messageSessionList);
}


/**
 * 根据返回List构建私信会话HTML
 * @param list
 */
function buildMessageSessionHtml(list){
    $("#messageSessionDiv ul").html("");
    $("#messageInfoDiv ul").html("");//清空
    var html='',tempSession='',content='';
    if(list==null||list.length==0){
        $("#messageNothing").show();
        return;
    }
    $("#messageNothing").hide();

    for(var i=0;i<list.length;i++){
        tempSession=list[i];
        content=tempSession.lastContentInfo;
        currentPageMessageSessionKeyList.push(tempSession.sessionKey);
        var nickname=tempSession.nickname;
        var headImg=tempSession.headImg;
        if(tempSession.relateId==SystemMessage.userId){
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
        if(tempSession.relateId!=SystemMessage.userId){
            html+='<b class="shield_btn" onclick="addUserMessageShield(\''+tempSession.relateId+'\')">屏蔽</b>';
        }
        html+='<b class="datas">'+Main.formatDate(tempSession.lastConcatTime,'hh:mm')+'</b>';
        html+='</li>';
    }
    $("#messageSessionDiv ul").html(html);
    setTopUnReadMessageCount();
}

/**
 * 重新构建消息会话列表
 * type=1 发信人重新构建
 * type=2 收信人重新构建
 * @param list
 */
function rebuildMessageSessionList(sessionList,type){
    var list=new Array();
    list=sessionList;
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
        if(tempSession.relateId==SystemMessage.userId){
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
        if(tempSession.relateId!=SystemMessage.userId){
            html+='<b class="shield_btn" onclick="addUserMessageShield(\''+tempSession.relateId+'\')">屏蔽</b>';
        }
        html+='<b class="datas">'+Main.formatDate(tempSession.lastConcatTime,'hh:mm')+'</b>';
        html+='</li>';

        //如果是最后一个，判断这条消息是不是跟左侧的详情Key一致，如果一致，那么直接追加到消息详情页
        if(i==list.length-1&&type==2){
            var infoSessionKey=$("#messageInfoDiv").attr("sessionKey");
            var receiveHeadImg=$("#messageInfoDiv").attr("receiveHeadImg");
            if(infoSessionKey==tempSession.sessionKey){
                var infoHtml='';
                infoHtml+='<li class="zuo clearfix">';
                infoHtml+='<img src="'+receiveHeadImg+'">';
                infoHtml+=' <p>'+content+'</p>';
                infoHtml+='</li>';
                $("#messageInfoDiv ul").append(infoHtml);
            }
        }
        //将消息放置顶部
        $("#messageSessionDiv ul").prepend(html);
        $('#messageInfoDiv ul').scrollTop( $('#messageInfoDiv ul')[0].scrollHeight );
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
                $('#messageInfoDiv ul').scrollTop( $('#messageInfoDiv ul')[0].scrollHeight );

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
    var html='';

    if($.trim(receiveId)==''){
        Main.showTip("选择发送人有误");
        return;
    }
    if($.trim(content)==''){
        Main.showTip("请输入发送内容");
        return;
    }
    try{
        //发送消息
        ws.send(JSON.stringify({"type": "say","to_client_id": receiveId,"content":content}));
        //插入发送记录里面
        html+='<li class="you clearfix">';
        html+='<img src="'+userHeadImg+'">';
        html+=' <p>'+content+'</p>';
        html+='</li>';
        $("#messageInfoDiv ul").append(html);
        $("#messageContent").val("");
        $('#messageInfoDiv ul').scrollTop( $('#messageInfoDiv ul')[0].scrollHeight );
        var sessionKey=$("#messageInfoDiv").attr("sessionKey");

        var newMessageInfo=null;
        //创建UserMessageSession 并更新
        for(var i=0;i<messageSessionList.length;i++){
            var temp=messageSessionList[i];
            if(temp.sessionKey==sessionKey){
                newMessageInfo=temp;
                break;
            }
        }
        newMessageInfo.lastContentTime=new Date().format("yyyy-MM-dd HH:mm:ss");
        newMessageInfo.lastContentInfo=content;
        rebuildMessageSessionList(new Array(newMessageInfo),1);
    }catch (e){
        console.info('发送私信异常');
    }
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


/************************ User Message End ******************************/