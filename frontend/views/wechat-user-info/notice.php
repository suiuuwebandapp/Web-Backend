<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title>随游</title>
    <link rel="stylesheet" href="/assets/other/weixin/css/common.css">
    <link rel="stylesheet" href="/assets/other/weixin/css/jquery.mmenu.css">
    <link rel="stylesheet" href="/assets/other/weixin/css/weixin.css">
    <script type="text/javascript" src="/assets/other/weixin/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="/assets/other/weixin/js/jquery.mmenu.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $('div#menu').mmenu();
        });
    </script>
    <script>
        function showHtml()
        {
            $("#page").show();
            $("#loading").hide();
        }
    </script>
    <link rel="stylesheet" href="/assets/other/weixin/css/loading.css">

</head>

<body onload="showHtml()">

<div id="loading" class="overlay">
    <div class="spinner" id="loading">
        <div class="rect1"></div>
        <div class="rect2"></div>
        <div class="rect3"></div>
        <div class="rect4"></div>
        <div class="rect5"></div>
    </div>
</div>
<div id="page" hidden="hidden" class="userCenter">
    <?php include "left.php"; ?>
    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        <p class="navTop">订购须知</p>
    </div>
</div>
<div class="con cshezhi_xuzhi clearfix">
    <div class="box">
        <p class="title">预定流程</p>
        <p>1.咨询随游的发布者，确认游玩细节</p>
        <p>2.填写日期，人数等信息并预支付订单。</p>
        <p>3.等待随友接单后，通过邮件，短信及站内信方式收到订单提醒。</p>
        <p>4.凭电子确认单进行游玩</p>
        <p>5.完成游玩后进行确认，评价您选择的随游及服务提供者</p>
    </div>
    <div class="box">
        <p class="title">退款说明</p>
        <p>1.支付并提交订单后48小时无人接单，则订单自动取消，全额返还 </p>
        <p>2.订单提交时间未满48小时，但超过订单预期服务时间的，全额返还服务费</p>
        <p>3.在订单被接单之前取消订单，全额返还所支付费用</p>
        <p>4.所提交订单被随友接单，在服务指定日期前5天可以申请取消预订并全额退款</p>
        <p>5.在指定日期内5天可以申请退款，经平台审核后返还部分预订费用
            在随游服务过程中及服务后且未确认完成服务前，可以提交退款请
            求，经平台调查审核后返还部分服务费用</p>
    </div>
    <div class="box">
        <p class="title">保险保障</p>
        <p>全天候客服热线</p>

        <p>和随游旅行的过程中，如果有任何问题，随时拨打随游客服电话或在微信公众号上与客服沟通，我们7x24随叫随到，为您服务。旅行保险一份100%赔付	和随游旅行过程中如出现意外情况，随友和游客无需承担保险范围内的任何费用，随游网提供的旅行保险全权处理100%赔付。</p>

        <p>据统计90%以上的游客和随友的相处都非常愉快，如需赔付，您只需要提供现场相关证据照片，在48小时内与随游客服联系，即可享受保险保障。</p>

    </div>
</div>
</body>
</html>