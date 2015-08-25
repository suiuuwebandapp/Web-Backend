<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title>随游-填写需求</title>
    <link rel="stylesheet" href="/assets/other/weixin/css/common.css">
    <link rel="stylesheet" href="/assets/other/weixin/css/weixin.css">
    <script type="text/javascript" src="/assets/other/weixin/js/jquery-1.11.1.min.js"></script>
    <script src="/assets/other/weixin/js/mobiscroll.core.js"></script>
    <script src="/assets/other/weixin/js/mobiscroll.util.datetime.js"></script>
    <script src="/assets/other/weixin/js/mobiscroll.datetimebase.js"></script>
    <script src="/assets/other/weixin/js/mobiscroll.datetime.js"></script>

    <!-- Mobiscroll JS and CSS Includes -->
    <link rel="stylesheet" href="/assets/other/weixin/css/mobiscroll.custom-2.14.4.min.css" type="text/css" />
    <script src="/assets/other/weixin/js/mobiscroll-2.14.4-crack.js"></script>
    <link rel="stylesheet" href="/assets/other/weixin/css/jquery.mmenu.css">
    <script type="text/javascript" src="/assets/other/weixin/js/jquery.mmenu.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $('div#menu').mmenu();
        });
    </script>
    <script type="text/javascript">
        $(document).bind("mobileinit", function () {
            //覆盖的代码
            $.mobile.ajaxEnabled = false;
            $.mobile.hashListeningEnabled = false;
            //$.mobile.linkBindingEnabled = false;
        });
    </script>
    <script type="text/javascript">

        $(function () {
            var now = new Date(),
                year = now.getFullYear(),
                month = now.getMonth();
            // Mobiscroll Calendar initialization
            $('#dateList').mobiscroll().calendar({
                theme: 'mobiscroll',  // Specify theme like: theme: 'ios' or omit setting to use default
                lang: 'zh',           // Specify language like: lang: 'pl' or omit setting to use default
                display: 'bottom',    // Specify display mode like: display: 'bottom' or omit setting to use default
                counter: true,        // More info about counter: http://docs.mobiscroll.com/2-15-1/calendar#!opt-counter
                multiSelect: true,    // More info about multiSelect: http://docs.mobiscroll.com/2-15-1/calendar#!opt-multiSelect
                selectedValues: []
            });
        });
    </script>
</head>

<body>
<div id="page" class="userCenter">
    <?php include "left.php"; ?>
    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        我的定制
    </div>
<div class="con sy_need02 clearfix">
    <input id="site" type="text" placeholder="目的地城市" class="selet">
    <!--<a href="/we-chat/show-country?rUrl=/we-chat-order-list/order-view" id="site" class="selet" areaCode="<?php /*echo $c*/?>" ><?php /*echo $n;*/?></a>-->
    <p>出游人数</p>
    <div class="row">
        <a href="javascript:;" class="minus" onclick="updateNumber(false)"></a>
        <input id="userNumber" type="text" class="text" value="1">
        <a href="javascript:;" class="add" onclick="updateNumber(true)"></a>
    </div>
    <p>联系方式</p>
    <input id="userPhone" class="sdate" placeholder="请输入手机号" />
    <p>你在哪天需要随友？</p>
    <input id="dateList" class="sdate" placeholder="请选择出行日期(可多选) ..." />
    <p>旅行需求</p>
    <div class="box">
        <p class="type">舒适度</p>
        <div id="ssd" class="sels clearfix">
            <a href="javascript:;" class="">经济</a>
            <a href="javascript:;">舒适</a>
            <a href="javascript:;">豪华</a>
        </div>
    </div>
    <div class="box">
        <p class="type">类型</p>
        <div id="lx" class="sels clearfix">
            <a href="javascript:;" class="">家庭</a>
            <a href="javascript:;">美食</a>
            <a href="javascript:;">惊险</a>
            <a href="javascript:;">博物馆</a>
            <a href="javascript:;">浪漫</a>
            <a href="javascript:;">猎奇</a>
            <a href="javascript:;">购物</a>
            <a href="javascript:;">自然</a>

        </div>
    </div>
    <div class="box">
        <p class="type">导游</p>
        <div id="dy" class="sels clearfix">
            <a href="javascript:;" class="">男</a>
            <a href="javascript:;">女</a>
            <a href="javascript:;">留学生</a>
            <a href="javascript:;">移民</a>
            <a href="javascript:;">专业导游</a>

        </div>
    </div>
    <p>写下你的旅行愿望</p>
    <textarea id="content"></textarea>
    <div class="btnDiv">
        <a href="javascript:;" class="btn" onclick="submit()">提交定制</a>
    </div>

</div>
</div>
<script>
    $("#ssd a").bind("click",function(){
        if( $(this).attr('class')=="active"){
            $(this).attr('class',"");
        }else{
            $("#ssd a[class='active']").each(function(){
                $(this).attr('class',"");
            });
            $(this).attr('class',"active");
        }
    });
    $("#lx a").bind("click",function(){
        if( $(this).attr('class')=="active"){
            $(this).attr('class',"");
        }else{
            $(this).attr('class',"active");
        }
    });
    $("#dy a").bind("click",function(){
        if( $(this).attr('class')=="active"){
            $(this).attr('class',"");
        }else{

            $(this).attr('class',"active");
        }
    });
    function updateNumber(isAdd)
    {
        if(isAdd)
        {
            var n=$('#userNumber').val();
            n++;
            $('#userNumber').val(n);
        }else
        {
            var n=$('#userNumber').val();
            n--;
            if(n<1)
            {
                n=1;
            }
            $('#userNumber').val(n);
        }
    }
    function submit()
    {
        var ssd_str = "";
        $("#ssd a[class='active']").each(function(){
            if(ssd_str=='')
            {
                ssd_str+=$(this).html();
            }else
            {
                ssd_str+=',';
                ssd_str+=$(this).html();
            }
        });
        var lx_str = "";
        $("#lx a[class='active']").each(function(){
            if(lx_str=='')
            {
                lx_str+=$(this).html();
            }else
            {
                lx_str+=',';
                lx_str+=$(this).html();
            }
        });
        var dy_str = "";
        $("#dy a[class='active']").each(function(){
            if(dy_str=='')
            {
                dy_str+=$(this).html();
            }else
            {
                dy_str+=',';
                dy_str+=$(this).html();
            }
        });
        var str = "舒适度:"+ssd_str+"||类型:"+lx_str+"||导游:"+dy_str+"||";



        var site=$('#site').val();
        var content=$('#content').val();
        var timeList=$('#dateList').val();
        var userNumber=$('#userNumber').val();
        var userPhone=$('#userPhone').val();
        if(site=="")
        {
            alert('请选择出行国家');
            return;
        }
        if(content=="")
        {
            alert('请描述出行需求');
            return;
        }
        if(timeList=="")
        {
            alert('请选择出行时间');
            return;
        }
        if(userNumber<1)
        {
            alert('出行人数不能小于1人');
            return;
        }
        if(userPhone.length<6)
        {
            alert('手机号格式不正确');
            return;
        }
        content=str+content;
        $.ajax({
            url :'/we-chat-order-list/add-order',
            type:'post',
            data:{
                site:site,
                content:content,
                timeList:timeList,
                phone:userPhone,
                userNumber:userNumber
            },
            error:function(){
                alert("提交订购异常");
            },
            success:function(data){
                //hide load
                data=eval("("+data+")");
                if(data.status==1){
                    window.location.href=data.data;
                }else if(data.status==-3){
                    window.location.href=data.data;
                }else{
                    alert(data.data);
                }
            }
        });

    }
</script>
</body>
</html>
