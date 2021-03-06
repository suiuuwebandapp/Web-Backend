/**
 * Created by suiuu on 15/4/24.
 */
var Main = function() {

    // private functions & variables
    var basePath = "";

    var initMenuOpen=function(currentLi){

    };


    // public functions
    return {

        // main function
        init : function(options) {
        },
        showTip:function(tipInfo){
            alert(tipInfo);
        },
        // 打印Object 所有属性值
        printObject : function (obj){
            var temp = "";
            for(var i in obj){// 用javascript的for/in循环遍历对象的属性
                temp += i+":"+obj[i]+"\n";
            }
            alert(temp);
        },
        isNotEmpty:function(obj){
            if(obj==null||obj=="null"||obj==""||obj==undefined){
                return false;
            }else{
                return true;
            }
        },
        getRequestParam:function (name) {
            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
            var r = window.location.search.substr(1).match(reg);
            if (r != null) return unescape(r[2]); return null;
        },
        setInputOnlyNumber:function(obj){
            $(obj).bind("keyup",function(){
                var value=$(this).val()
                $(this).val(value.replace(/[^\d]/g,''));
            });
            $(obj).bind("beforepaste",function(){
                clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))
            });
        },
        // 改变页面地址
        changeLocationUrl : function(location) {
            window.location.href = location;
        },
        parseDate:function(strDate){
            return eval('new Date(' + strDate.replace(/\d+(?=-[^-]+$)/,
                function (a) { return parseInt(a, 10) - 1; }).match(/\d+/g) + ')');
        },
        /**
         * yyyy-MM-dd hh:mm:ss
         * @param strDate
         * @param format
         * @returns {*}
         */
        formatDate :function(strDate,format){
            if(strDate==''||strDate==undefined){
                return '';
            }
            var date = eval('new Date(' + strDate.replace(/\d+(?=-[^-]+$)/,
            function (a) { return parseInt(a, 10) - 1; }).match(/\d+/g) + ')');
            var o = {
                "M+" : date.getMonth()+1, //month
                "d+" : date.getDate(), //day
                "h+" : date.getHours(), //hour
                "m+" : date.getMinutes(), //minute
                "s+" : date.getSeconds(), //second
                "q+" : Math.floor((date.getMonth()+3)/3), //quarter
                "S" : date.getMilliseconds() //millisecond
            }

            if(/(y+)/.test(format)) {
                format = format.replace(RegExp.$1, (date.getFullYear()+"").substr(4 - RegExp.$1.length));
            }

            for(var k in o) {
                if(new RegExp("("+ k +")").test(format)) {
                    format = format.replace(RegExp.$1, RegExp.$1.length==1 ? o[k] : ("00"+ o[k]).substr((""+ o[k]).length));
                }
            }
            return format;
        },
        convertTimePicker:function(time,type){
            if(time==''||time==null){
                return '';
            }
            var rst='';
            var timeArr='';
            var timeInfo='';
            if(type==1){
                timeArr=time.split(" ");
                timeInfo=timeArr[0].split(":");

                if(timeArr[1]=="AM"){
                    rst=timeArr[0];
                }else{
                    rst=(timeInfo[0]+12)+":"+timeInfo[1];
                }
            }else{
                timeInfo=time.split(":");
                if(timeInfo[0]>12){
                    rst=(timeInfo[0]-12)+":"+timeInfo[1]+" PM";
                }else{
                    rst=timeInfo[0]+":"+timeInfo[1]+" AM";
                }
            }
            return rst;
        },
        convertOrderDateToShow:function(stringDate)
        {
            var time=Math.round(Date.parse(stringDate));
            var now=Math.round(new Date().getTime());
            var diffValue=now-time;
            var result=this.formatDate(stringDate,'yyyy-MM-dd hh:mm');

            var minute = 1000 * 60;
            var hour = minute * 60;
            var day = hour * 24;
            var halfamonth = day * 15;
            var month = day * 30;

            if(diffValue < 0){
                //若日期不符则弹出窗口告之
                //alert("结束日期不能小于开始日期！");
            }
            var monthC =diffValue/month;
            var weekC =diffValue/(7*day);
            var dayC =diffValue/day;
            var hourC =diffValue/hour;
            var minC =diffValue/minute;
            if(monthC>=1){
                //result="发表于" + parseInt(monthC) + "个月前";
            }
            else if(weekC>=1){
                //result="发表于" + parseInt(weekC) + "周前";
            }
            else if(dayC>=1){
                result=""+ parseInt(dayC) +"天前";
            }
            else if(hourC>=1){
                result=""+ parseInt(hourC) +"小时前";
            }
            else if(minC>=1){
                result=""+ parseInt(minC) +"分钟前";
            }else
                result="刚刚";

            return result;
        },
        showSendMessage:function(userSign){
            if(isLogin==0||isLogin=="0"){
                $("#denglu").click();
                return;
            }
            $.ajax({
                url :'/user-info/find-user-info',
                type:'post',
                data:{
                    userSign:$.trim(userSign)
                },
                error:function(){
                    Main.showTip("获取用户基本信息失败");
                },
                success:function(data){
                    data=eval("("+data+")");
                    if(data.status==1){
                        var userInfo=data.data;
                        $("#show_message_sex").html(userInfo.sex);
                        $("#show_message_receiverId").val(userSign);
                        $("#show_message_headImg").attr("src",userInfo.headImg);
                        if(Main.isNotEmpty(userInfo.countryCname)){
                            $("#show_message_city").html(userInfo.countryCname+" "+userInfo.cityCname);
                        }else{
                            $("#show_message_city").html("");
                        }

                        $("#show_message_nickname").html(userInfo.nickname);
                        $("#show_message_age").html(userInfo.birthday);

                        $("#showMessageDiv").show();
                        $("#myMask").show();

                    }else{
                        Main.showTip("获取用户基本信息失败");
                    }
                }
            });
        },
        showScreenSendMessage:function(){
            var receiveId=$("#show_message_receiverId").val();
            var content=$("#sendMessageContent").val();
            if($.trim(receiveId)==''){
                Main.showTip("选择发送人有误");
                return;
            }
            if($.trim(content)==''){
                Main.showTip("请输入发送内容");
                return;
            }
            try{
                ws.send(JSON.stringify({"type": "say","to_client_id": receiveId,"content":content}));
                Main.showTip("发送消息成功");
                $("#showMessageDiv").hide();
                $("#myMask").hide();
                $("#sendMessageForm")[0].reset();
            }catch (e){}
        }
    };

}();

var TripStatus={
    'TRAVEL_TRIP_STATUS_NORMAL':1,
    'TRAVEL_TRIP_STATUS_DRAFT':2,
    'TRAVEL_TRIP_STATUS_DELETE':3,
    'TRAVEL_TRIP_AUTO_SAVE':4
};

var TripBasePriceType={
    'TRIP_BASE_PRICE_TYPE_PERSON':1,
    'TRIP_BASE_PRICE_TYPE_COUNT':2
};

var OrderStatus= {
    'USER_ORDER_STATUS_PAY_WAIT': 0,
    'USER_ORDER_STATUS_PAY_SUCCESS': 1,
    'USER_ORDER_STATUS_CONFIRM': 2,
    'USER_ORDER_STATUS_CANCELED': 3,
    'USER_ORDER_STATUS_REFUND_WAIT': 4,
    'USER_ORDER_STATUS_REFUND_SUCCESS': 5,
    'USER_ORDER_STATUS_PLAY_SUCCESS': 6,
    'USER_ORDER_STATUS_PLAY_FINISH': 7,
    'USER_ORDER_STATUS_REFUND_VERIFY':8,
    'USER_ORDER_STATUS_REFUND_FAIL':9
};

var SystemMessage={
    'userId':'SYSTEM_MESSAGE',
    'nickname':'系统消息',
    'headImg':'/assets/images/user_default.png'
};

var UserAccountRecordType={
    'USER_ACCOUNT_RECORD_TYPE_TRIP_SERVER':1,//随游服务
    'USER_ACCOUNT_RECORD_TYPE_TRIP_DIVIDED_INTO':2,//随游分成
    'USER_ACCOUNT_RECORD_TYPE_DRAW_MONEY':3,//提现
    'USER_ACCOUNT_RECORD_TYPE_OTHER':4,//其他
    'USER_ACCOUNT_RECORD_TYPE_REFUND':5//退款
};

var UserCashRecordType={
    'USER_CASH_RECORD_STATUS_WAIT':1,//等待转出
    'USER_CASH_RECORD_STATUS_SUCCESS':2,//转出成功
    'USER_CASH_RECORD_STATUS_FAIL':3//转出失败
};

/**
 * 页面地址管理
 */
var UrlManager=function(){
    return{
        getTripSearchUrl:function(keywords){
            return "/view-trip/list?s="+encodeURIComponent(keywords);
        },
        getTripInfoUrl:function(tripId){
            return "/view-trip/info/"+tripId+".html";
        },
        getTripEditUrl:function(tripId){
            return "/trip/edit-trip?trip="+tripId;
        },
        getUserInfoUrl:function(userId){
            return "/view-user/info/"+userId+".html";
        }
    }
}();

/*******************************************************************************
 * Usage
 ******************************************************************************/
// Custom.init();
// Custom.doSomeStuff();


/**
 * 构造上传预览插件
 */
jQuery.fn.extend({
    uploadPreview: function (opts) {
        var _self = this,
            _this = $(this);
        opts = jQuery.extend({
            Img: "ImgPr",
            Width: 100,
            Height: 100,
            ImgType: ["gif", "jpeg", "jpg", "bmp", "png"],
            Callback: function () {
            }
        }, opts || {});
        _self.getObjectURL = function (file) {
            var url = null;
            if (window.createObjectURL != undefined) {
                url = window.createObjectURL(file)
            } else if (window.URL != undefined) {
                url = window.URL.createObjectURL(file)
            } else if (window.webkitURL != undefined) {
                url = window.webkitURL.createObjectURL(file)
            }
            return url
        };
        _this.change(function () {
            if (this.value) {
                if (!RegExp("\.(" + opts.ImgType.join("|") + ")$", "i").test(this.value.toLowerCase())) {
                    alert("选择文件错误,图片类型必须是" + opts.ImgType.join("，") + "中的一种");
                    this.value = "";
                    return false
                }
                if (navigator.userAgent.indexOf("MSIE") > -1) {
                    try {
                        $("#" + opts.Img).attr('src', _self.getObjectURL(this.files[0]))
                    } catch (e) {
                        var src = "";
                        var obj = $("#" + opts.Img);
                        var div = obj.parent("div")[0];
                        _self.select();
                        if (top != self) {
                            window.parent.document.body.focus()
                        } else {
                            _self.blur()
                        }
                        src = document.selection.createRange().text;
                        document.selection.empty();
                        obj.hide();
                        obj.parent("div").css({
                            'filter': 'progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale)',
                            'width': opts.Width + 'px',
                            'height': opts.Height + 'px'
                        });
                        div.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = src
                    }
                } else {
                    $("#" + opts.Img).attr('src', _self.getObjectURL(this.files[0]))
                }
                opts.Callback()
            }
        })
    }
});


/**
 * 构造 Replace All 方法
 * @param reallyDo
 * @param replaceWith
 * @param ignoreCase
 * @returns {string|*}
 */
String.prototype.replaceAll = function(reallyDo, replaceWith, ignoreCase) {
    if (!RegExp.prototype.isPrototypeOf(reallyDo)) {
        return this.replace(new RegExp(reallyDo, (ignoreCase ? "gi": "g")), replaceWith);
    } else {
        return this.replace(reallyDo, replaceWith);
    }
}

String.prototype.endWith=function(s){
    if(s==null||s==""||this.length==0||s.length>this.length)
        return false;
    if(this.substring(this.length-s.length)==s)
        return true;
    else
        return false;
    return true;
}

String.prototype.startWith=function(s){
    if(s==null||s==""||this.length==0||s.length>this.length)
        return false;
    if(this.substr(0,s.length)==s)
        return true;
    else
        return false;
    return true;
}

/**
 * yyyy-MM-dd hh:mm:ss
 * @param fmt
 * @returns {*}
 */
Date.prototype.format = function (fmt) { //author: meizz
    var o = {
        "M+": this.getMonth() + 1, //月份
        "d+": this.getDate(), //日
        "h+": this.getHours(), //小时
        "m+": this.getMinutes(), //分
        "s+": this.getSeconds(), //秒
        "q+": Math.floor((this.getMonth() + 3) / 3), //季度
        "S": this.getMilliseconds() //毫秒
    };
    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o)
        if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
    return fmt;
}
