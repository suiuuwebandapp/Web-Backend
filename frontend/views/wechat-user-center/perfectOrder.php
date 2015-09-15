<?php $travelInfo = json_decode($orderInfo->tripJsonInfo, true);
$serviceInfo = json_decode($orderInfo->serviceInfo, true);
$hasAirplane = 0;
if (!empty($travelInfo['info']['type'])&&$travelInfo['info']['type'] == \common\entity\TravelTrip::TRAVEL_TRIP_TYPE_TRAFFIC) {
    foreach ($serviceInfo as $service) {
        if ($service['type'] != 'car') {
            $hasAirplane = 1;
        }
    }
}?>
<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title>随游</title>
    <link rel="stylesheet" href="/assets/other/weixin/css/common.css">
    <link rel="stylesheet" href="/assets/other/weixin/css/weixin.css">
    <link rel="stylesheet" href="/assets/other/weixin/css/jquery.mmenu.css">
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
        <p class="navTop">完善资料</p>
    </div>
    <div class="con jtpersonal clearfix">
        <input type="text" id="orderNumber" value="<?=$orderNumber?>" style="display: none">
        <h2 class="title03">为确保您顺畅的出行体验，请完善以下信息</h2>
        <p>联系人姓名</p>
        <input type="text" id="username" placeholder="姓名" value="<?=$contact->username?>">
        <p>微信号</p>
        <input type="text" id="wechat" placeholder="选填" value="<?=$contact->wechat?>">
        <p>主要联系号码</p>
        <input type="text" id="phone" placeholder="国内手机号" value="<?=$contact->phone?>">
        <p>备用联系号码</p>
        <input type="text" id="sparePhone" placeholder="选填" value="<?=$contact->sparePhone?>">


        <h2 class="title03">紧急联系方式</h2>
        <p>联系人姓名</p>
        <input type="text" id="urgentUsername" placeholder="请填写实际姓名" value="<?=$contact->urgentUsername?>">
        <p>联系人手机</p>
        <input type="text" id="urgentPhone" value="<?=$contact->urgentPhone?>">

        <?php if($hasAirplane==1){?>
        <h2 class="title03" style="margin-bottom:0;">出行信息</h2>
        <h2 class="title04">请您准确填写出行信息，以免耽误行程</h2>
        <p>航班号</p>
        <input type="text" placeholder="到达航班号" id="arriveFlyNumber" value="<?=$contact->arriveFlyNumber?>">
        <input type="text" placeholder="离开航班号" id="leaveFlyNumber" value="<?=$contact->leaveFlyNumber?>">
        <p>目的地</p>
        <input type="text" placeholder="到达酒店/目的地名称" id="destination" value="<?=$contact->destination?>">
        <?php }?>

        <div class="btns clearfix">
            <a href="javascript:;" class="btn btn01" onclick="savePerfect()">确定</a>
        </div>

    </div>
</div>
<script>
    var hasAirplane ="<?=$hasAirplane?>";
    function savePerfect()
    {
        var username = $("#username").val();
        var wechat = $("#wechat").val();
        var phone = $("#phone").val();
        var sparePhone = $("#sparePhone").val();
        var urgentUsername = $("#urgentUsername").val();
        var urgentPhone = $("#urgentPhone").val();
        var arriveFlyNumber = $("#arriveFlyNumber").val();
        var leaveFlyNumber = $("#leaveFlyNumber").val();
        var destination = $("#destination").val();
        var orderNumber = $("#orderNumber").val();

        if (orderNumber == '') {
            alert("未知的订单");
            return;
        }
        if (username == '') {
           alert("请输入联系人姓名");
            return;
        }
        if (phone == '') {
            alert("请输入联系人联系方式");
            return;
        }

        if (urgentUsername == '') {
            alert("请输入紧急联系人姓名");
            return;
        }
        if (urgentPhone == '') {
            alert("请输入紧急联系人联系方式");
            return;
        }

        if (hasAirplane == 1) {
            if (arriveFlyNumber == '' || leaveFlyNumber == '') {
                alert("请输入航班号");
                return;
            }
            if (destination == '') {
                alert("请输入目的地或酒店名称");
                return;
            }
        }


        $.ajax({
            type: "post",
            url: "/wechat-user-center/order-contact",
            data: {
                orderNumber:orderNumber,
                username: username,
                wechat: wechat,
                phone: phone,
                sparePhone: sparePhone,
                urgentUsername: urgentUsername,
                urgentPhone: urgentPhone,
                arriveFlyNumber: arriveFlyNumber,
                leaveFlyNumber: leaveFlyNumber,
                destination: destination
            },
            success: function (data) {
                data = $.parseJSON(data);
                if (data.status == 1) {
                    window.location.href="/wechat-user-center/my-order-info?id="+orderNumber;
                } else {
                    alert(data.data);
                }
            }
        });
    }
</script>
</body>
</html>
