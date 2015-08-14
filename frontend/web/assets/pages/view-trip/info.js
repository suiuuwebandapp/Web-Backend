/**
 * Created by suiuu on 15/8/11.
 */

$(document).ready(function () {
    initScroll();
    initBtnClick();
    initDatePicker();
    getComment(1);




});


/**
 * 鼠标滚动事件处理
 */
function initScroll(){
    $(document).scroll(function () {
        var scrollTop = $(document).scrollTop();
        resetDatePicker();
        var documentHeight = $(document).height();//浏览器时下窗口可视区域高度
        var fixHeight = $(".web-right").offset().top + $(".web-right").height();
        var footHeight = $("#footer-out").height();

        var maxHeight = documentHeight - footHeight;
        //console.info($(".web-right").offset().top+"-"+fixHeight+"-"+maxHeight);

        if (scrollTop > 400) {
            $('.sydetail .web-right').addClass('fixed')
            $('.sydetailNav').addClass('fixed')
        } else {
            $('.sydetailNav').removeClass('fixed')
            $('.sydetail .web-right').removeClass('fixed')
        }

        if (fixHeight > maxHeight) {
            $(".sydetail .web-right").hide();
            $(".datetimepicker").hide();
        } else {
            $(".sydetail .web-right").show();
        }


        if (scrollTop + fixHeight > documentHeight - maxHeight) {
            $(".sylx-xiangxi").css("position", "absolute");
        } else {
            $(".sylx-xiangxi").css("position", "fixed");
        }
    });
}

/**
 *
 */
function initBtnClick(){



    $(".removeBtn").bind("click",function(){
        var applyId=$(this).attr("applyId");
        opposeApply(applyId);
    });

    $(".sure").bind("click",function(){
        var applyId=$(this).attr("applyId");
        var publisherId=$(this).attr("publisherId");
        agreeApply(applyId,publisherId);
    });
    /**
     * 时间选择器控件处理
     */
    $('#startTime').timepicki({
        step_size_minutes: 15,
        format_output: function (tim, mini, meri) {
            return tim + ":" + mini + " " + meri;
        }
    });

    $("#collection_trip").bind("click", function () {
        submitCollection();
    });

    $("#peopleCount").bind("change", function () {
        showPrice();
    });
    $("#serviceLi input[type='checkbox']").bind("click", function () {
        showPrice();
    });
    $("#showServiceDiv").bind("click", function () {
        $(".serverSelct").show();
        $(".mask").show();
    });
    $("#closeServiceDiv,#confirmServiceDiv").bind("click", function () {
        $(".serverSelct").hide();
        $(".mask").hide();
    });
    $("#calendar").bind("click", function () {
        $("#beginTime").focus();
    });
    $("#toApply").bind("click", function () {
        if (userId == '') {
            $("#denglu").click();
            //Main.showTip("登录后才能购买哦~！");
            return;
        }
        if (userPublisherId == '') {
            Main.showTip("成为随友后才能加入线路！");
            window.location.href = '/index/create-travel';
            return;
        }
        $(".syxqPro02").show();
        $(".mask").show();
        //window.location.href = '/trip/to-apply-trip?trip=' + $("#tripId").val();
    });
    $("#cancelApply").bind("click",function(){
        $(".syxqPro02").hide();
        $(".mask").hide();
    });
    $("#applyBtn").bind("click",function(){
        var info=$("#applyInfo").val()
        if(info==''){
            return;
        }
        $("#applyForm").submit();
    });

    /**
     *显示更多评论
     */
    $("#showMoreComment").bind("click", function () {
        var showPage = $(this).attr("showPage");
        getComment(showPage);
    });

    /**
     * 点击购买
     */
    $("#toBuy").bind("click", function () {
        if (userId == '') {
            $("#denglu").click();
            //Main.showTip("登录后才能购买哦~！");
            return;
        }
        $("html,body").animate({scrollTop: $("#buyTrip").offset().top - 30}, 500);
    });
    /**
     * 添加订单
     */
    $("#addOrder").bind("click", function () {
        if (isOwner == 1) {
            Main.showTip("您无法购买自己的随游哦~");
            return;
        }
        var peopleCount = $("#peopleCount").val();
        var tripId = $("#tripId").val();
        var beginDate = $("#beginTime").val();
        var startTime = $("#startTime").val();
        var serviceArr = [];
        var serviceIds = '';


        if (userId == '') {
            $("#denglu").click();
            //Main.showTip("登录后才能购买哦~！");
            return;
        }
        if (beginDate == '') {
            Main.showTip("请选择您的出行日期");
            return;
        }
        if (peopleCount == '' || peopleCount == 0) {
            Main.showTip("请输入你的出行人数");
            return;
        }
        peopleCount = parseInt(peopleCount);
        maxPeopleCount = parseInt(maxPeopleCount);

        if (peopleCount > maxPeopleCount) {
            Main.showTip("这个随友最多之能接待" + maxPeopleCount + "个小伙伴呦~");
            return;
        }
        if (startTime == '') {
            Main.showTip("请输入您的起始时间");
            return;
        }

        $("#serviceLi input[type='checkbox']").each(function () {
            if ($(this).is(":checked")) {
                serviceArr.push($(this).attr("serviceId"));
            }
        });
        if (serviceArr != '' && serviceArr.length > 0) {
            serviceIds = serviceArr.join(",");
        }

        $("#serviceIds").val(serviceIds);
        $("#orderForm").submit();

    });

    /**
     * 绑定随友移除
     */
    $("#publisherList div[type='publisherList'] a").bind("click", function () {
        var tripPublisherId = $(this).attr("tripPublisherId");
        var tripId = $("#tripId").val();
        if (!confirm("确认要移除随友吗？")) {
            return;
        }
        $.ajax({
            url: '/trip/remove-publisher',
            type: 'post',
            data: {
                tripId: tripId,
                tripPublisherId: tripPublisherId,
                _csrf: $('input[name="_csrf"]').val()
            },
            error: function () {
                //hide load
                Main.showTip("移除随友失败");
            },
            success: function (data) {
                //hide load
                data = eval("(" + data + ")");
                if (data.status == 1) {
                    window.location.href=window.location.href;
                    //$("#div_trip_publisher_" + tripPublisherId).remove();
                } else {
                    Main.showTip("移除随友失败");
                }
            }
        });

    });
}


/**
 * 初始化日期控件
 */
function initDatePicker() {
    $('#beginTime').datetimepicker({
        language: 'zh-CN',
        autoclose: 1,
        startView: 2,
        minView: 2,
        forceParse: 0,
        format: 'yyyy-mm-dd',
        weekStart: 1,
        startDate: nowDate
    });
    $(".datetimepicker").hide();


    $('#beginTime').unbind("focus");

    $("#beginTime").bind("focus", function () {
        resetDatePicker();
        $(".datetimepicker").show();
    });

    $(".table-condensed tbody").bind("click", function () {
        $(".datetimepicker").hide();
    });

    /**
     * 隐藏日期选择
     */
    $(document).bind("click", function (e) {
        var target = $(e.target);
        if (target.closest("#beginTime").length != 1) {
            if (target.closest(".datetimepicker").length == 0) {
                $(".datetimepicker").hide();
            }
        }
    });
}

/**
 * 重置日期控件
 */
function resetDatePicker() {
    var top = $("#beginTime").offset().top;
    var left = $("#beginTime").offset().left;

    if ($(".web-right").hasClass("fixed")) {
        $(".datetimepicker").css({
            'top': '120px',
            'left': '50%',
            'margin-left': '283px',
            'position': 'fixed',
            'background-color': 'white',
            'border': '1px solid gray',
            'font-size': '14px'
        });
    } else {
        $(".datetimepicker").css({
            'top': top + 40,
            'left': left,
            'margin-left': '0px',
            'position': 'absolute',
            'background-color': 'white',
            'border': '1px solid gray',
            'font-size': '14px'
        });
    }
}
/**
 * 显示价格
 */
function showPrice() {
    var peopleCount = $("#peopleCount").val();
    var tripTime = $("#tripTime").val();
    var allPrice = 0;
    var stepFlag = false;

    if (peopleCount == '' || peopleCount == 0) {
        return;
    }

    peopleCount = parseInt(peopleCount);
    maxPeopleCount = parseInt(maxPeopleCount);

    if (peopleCount > maxPeopleCount) {
        Main.showTip("这个随友最多之能接待" + maxPeopleCount + "个小伙伴呦~");
        return;
    }
    //判断有没有阶梯价格
    var stepPriceList = [];
    if (stepPriceJson != '') {
        stepPriceList = eval("(" + stepPriceJson + ")");
    }
    if (basePriceType == TripBasePriceType.TRIP_BASE_PRICE_TYPE_COUNT) {
        allPrice = basePrice;
    } else {
        if (stepPriceList.length > 0) {
            for (var i = 0; i < stepPriceList.length; i++) {
                var stepPrice = stepPriceList[i];
                if (peopleCount >= stepPrice['minCount'] && peopleCount <= stepPrice['maxCount']) {
                    allPrice = parseInt(stepPrice['price']) * peopleCount;
                    stepFlag = true;
                    break;
                }
            }
        } else {
            allPrice = parseInt(basePrice) * peopleCount;
        }
        if (!stepFlag) {
            allPrice = parseInt(basePrice) * peopleCount;
        }
    }

    //判断有没有附加服务
    $("#serviceLi input[type='checkbox']").each(function () {
        var tempPrice = parseInt($(this).attr("servicePrice"));
        if ($(this).is(":checked")) {
            //如果TRUE每次 FALSE 每人
            if ($(this).attr("serviceType") == serviceTypeCount) {
                allPrice = parseInt(allPrice)+parseInt(tempPrice);
            } else {
                allPrice = parseInt(allPrice)+(parseInt(tempPrice) * peopleCount);
            }
        }
    });
    allPrice = parseInt(allPrice);
    $("#allPrice").html("总价：￥" + allPrice);
}

/**
 * 添加收藏
 */
function submitCollection() {
    var tripId = $("#tripId").val();
    if (tripId == '' || tripId == undefined || tripId == 0) {
        Main.showTip('未知的随游');
        return;
    }
    var isCollection = false;
    if ($('#collection_trip').attr('class') == 'addIicon') {
        $('#collection_trip').addClass('active');
        isCollection = true;
    } else {
        $('#collection_trip').removeClass('active');
        isCollection = false;
    }

    if (isCollection) {
        //添加收藏
        $.ajax({
            url: '/view-trip/add-collection-travel',
            type: 'post',
            data: {
                travelId: tripId,
                _csrf: $('input[name="_csrf"]').val()
            },
            error: function () {
                //hide load
                Main.showTip("收藏随游失败");
                $('#collection_trip').removeClass('active');
                isCollection = false;
            },
            success: function (data) {
                //hide load
                data = eval("(" + data + ")");
                if (data.status == 1) {
                    Main.showTip("收藏成功");
                    $('#collection_trip').attr('attentionIdTrip', data.data);
                } else {
                    Main.showTip(data.data);
                    $('#collection_trip').removeClass('active');
                    isCollection = false;
                }
            }
        });
    } else {
        //取消收藏
        $.ajax({
            url: '/view-trip/delete-attention',
            type: 'post',
            data: {
                attentionId: $('#collection_trip').attr('attentionIdTrip'),
                _csrf: $('input[name="_csrf"]').val()
            },
            error: function () {
                //hide load
                $('#collection_trip').addClass('active');
                isCollection = true;
                Main.showTip("收藏随游失败");
            },
            success: function (data) {
                //hide load
                data = eval("(" + data + ")");
                if (data.status == 1) {
                    Main.showTip("取消成功");
                } else {
                    $('#collection_trip').addClass('active');
                    isCollection = true;
                    Main.showTip(data.data);
                }
            }
        });
    }
}

/**
 * 获取评论
 * @param page
 */
function getComment(page) {
    rid = 0;
    rSign = '';
    $.ajax({
        type: 'post',
        url: '/view-trip/get-comment-list',
        data: {
            tripId: tripId,
            cPage: page,
            _csrf: $('input[name="_csrf"]').val()
        },
        beforeSend: function () {
            //Main.showTip('正在提交，请稍后。。。');
        },
        error: function () {
            //Main.showTip("系统异常。。。");
        },
        success: function (data) {
            var obj = eval('(' + data + ')');
            if (obj.status == 1) {
                //$('#tanchu_pl').html('');
                var str = '';
                for (var i = 0; i < obj.data.length; i++) {
                    var r = "@";
                    if (obj.data[i].rTitle == null) {
                        r = '';
                    } else {
                        r += obj.data[i].rTitle;
                    }
                    var c = '';
                    var status = obj.data[i].status;
                    if (status == 1) {
                        c = 'active'
                    }
                    str += '<li>';
                    str += '<div class="user-pic fl">';
                    str += '<img src=\"' + obj.data[i].headImg + '\" alt=\"\">';
                    str += '<span class=\"user-name\">';
                    str += obj.data[i].nickname;
                    if (obj.data[i].travelCount > 0) {
                        str += '<b>玩过该路线</b>';
                    }
                    str += "</span></div><p class='fl'><b>";
                    str += r;
                    str += "</b>";
                    str += ' ' + obj.data[i].content;
                    str += "</p><a href='#pllist' rSign='" + obj.data[i].userSign + "' id='" + obj.data[i].commentId + "' class='hf' onclick='reply(this)'></a>";
                    str += "</li>";
                }
                $('#tanchu_pl').append(str);
                if (obj.message.currentPage < (obj.message.totalCount / obj.message.pageSize)) {
                    $("#showMoreComment").show();
                    $("#showMoreComment").attr("showPage", parseInt(obj.message.currentPage) + 1);
                } else {
                    $("#showMoreComment").hide();
                }
                //$('#spage').html('');
                //$('#spage').append(obj.message);

                //$("#spage li").click(function() {
                //    var page=$(this).find('a').attr('page');
                //    getComment(page);
                //});

            } else {
                Main.showTip(obj.data);

            }
        }
    });
}

/**
 * 回复某个人
 * @param obj
 */
function reply(obj) {
    rid = $(obj).attr('id');
    rSign = $(obj).attr('rSign');
    var t = $(obj).prev().prev().find("span").html();
    $("#pinglun").val('@' + t + '   :');
}


/**
 * 提交评论
 */
function submitComment() {
    var s = $('#pinglun').val();
    var i = s.indexOf('@');
    var content = '';
    var t = '';
    if (i == -1) {
        content = s;
        t = '';
    } else {
        var j = s.indexOf(':');
        if (j == -1) {
            content = s;
            t = '';
        } else {
            t = s.slice(0, j);
            content = s.slice(j);
        }
    }

    $.ajax({
        type: 'post',
        url: '/view-trip/add-comment',
        data: {
            tripId: tripId,
            content: content,
            rTitle: t,
            rId: rid,
            rSign: rSign,
            _csrf: $('input[name="_csrf"]').val()
        },
        beforeSend: function () {
            //Main.showTip('正在提交，请稍后。。。');
        },
        error: function () {
            Main.showTip("系统异常。。。");
        },
        success: function (data) {
            var obj = eval('(' + data + ')');
            if (obj.status == 1) {
                //Main.showTip("发表成功。。。");
                $("#tanchu_pl").html("");
                getComment(page);
                $("#pinglun").val('');
            } else {
                Main.showTip(obj.data);

            }
        }
    });
}

/**
 * 点赞
 * @param id
 * @param obj
 */
function sumbmitZan(id, obj) {
    var s = $(obj).attr('class');
    var i = s.indexOf('active');
    if (i != -1) {
        Main.showTip('已经点赞');
        return;
    }
    $(obj).addClass('active');
    $.ajax({
        type: 'post',
        url: '/view-trip/add-support',
        data: {
            tripId: tripId,
            rId: id,
            _csrf: $('input[name="_csrf"]').val()
        },
        beforeSend: function () {
            //Main.showTip('正在提交，请稍后。。。');
        },
        error: function () {
            Main.showTip("系统异常。。。");
        },
        success: function (data) {
            var data = eval('(' + data + ')');
            if (data.status == 1) {
                //Main.showTip("发表成功。。。");
                getComment(page);
            } else {
                Main.showTip(data.data);
                $(obj).removeClass('active');

            }
        }
    });
}


/**
 * 同意加入随游
 * @param applyId
 * @param publisherId
 */
function agreeApply(applyId,publisherId)
{
    $.ajax({
        url :'/trip/agree-apply',
        type:'post',
        data:{
            applyId:applyId,
            publisherId:publisherId,
            _csrf: $('input[name="_csrf"]').val()
        },
        error:function(){
            Main.showTip("同意加入随游失败");
        },
        success:function(data){
            data=eval("("+data+")");
            if(data.status==1){
                Main.showTip("同意加入随游成功");
                window.location.href=window.location.href;
            }else{
                Main.showTip("同意加入随游失败");
            }
        }
    });
}


/**
 * 拒绝加入随游
 * @param applyId
 */
function opposeApply(applyId)
{
    if (!confirm("确认要忽略随友的申请吗？")) {
        return;
    }
    $.ajax({
        url :'/trip/oppose-apply',
        type:'post',
        data:{
            applyId:applyId,
            _csrf: $('input[name="_csrf"]').val()
        },
        error:function(){
            Main.showTip("拒绝加入随游失败");
        },
        success:function(data){
            data=eval("("+data+")");
            if(data.status==1){
                Main.showTip("拒绝加入随游成功");
                window.location.href=window.location.href;
            }else{
                Main.showTip("拒绝加入随游失败");
            }
        }
    });
}