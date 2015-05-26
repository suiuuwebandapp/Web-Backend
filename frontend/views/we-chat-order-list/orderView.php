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
    <p>写下你的旅行愿望</p>
    <textarea id="content"></textarea>

    <a href="javascript:;" class="btn" onclick="submit()">让随游来帮你</a>

</div>

<script>
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
