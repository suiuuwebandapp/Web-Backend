<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/5
 * Time : 下午1:18
 * Email: zhangxinmailvip@foxmail.com
 */
$travelInfo = json_decode($orderInfo->tripJsonInfo, true);
$serviceInfo = json_decode($orderInfo->serviceInfo, true);
$hasAirplane = 0;
if (!empty($travelInfo['info']['type'])&&$travelInfo['info']['type'] == \common\entity\TravelTrip::TRAVEL_TRIP_TYPE_TRAFFIC) {
    foreach ($serviceInfo as $service) {
        if ($service['type'] == 'airplane') {
            $hasAirplane = 1;
        }
    }
}
?>
<style>
    body {
        background: #F7F7F7;
    }

    .traffic_service_list span {
        display: inline-block;
        margin: 4px 0px;
    }

    .payforTraffic span {
        font-weight: bold;
    }

    .payforTraffic .right .detail p .span2 {
        margin-left: 85px;
    }

    .payforTraffic .left label {
        margin-bottom: 3px;
    }

    .payforTraffic .left .p1 {
        margin-top: 5px;
    }

    .pay_btn {
        margin-left: 120px;
        margin-top: 40px;
        color: #fff;
        font-size: 18px;
        text-align: center;
        display: block;
        width: 145px;
        height: 40px;
        line-height: 40px;
        border-radius: 5px;
        background: #4FD8C3;
    }

    .form_tip {
        margin-top: 10px;
        font-size: 14px !important;
        color: red;
    }
</style>
<input type="hidden" value="<?= $orderInfo->orderNumber ?>" id="orderNumber"/>


<div class="payforTraffic clearfix">
    <div class="left">
        <h2 class="title">为了确保您顺畅的出行体验，请确认或补充以下信息</h2>

        <div class="box">
            <p>联系方式</p>

            <p class="p1">用于接收相关提醒及预订确认信息</p>

            <p class="form_tip" id="contactTip"></p>
            <ul class="forms clearfix">
                <li>
                    <label for="">联系人姓名</label>
                    <input type="text" id="username" value="<?= $contact->username; ?>" placeholder="请填写实际姓名">
                </li>
                <li>
                    <label for="">微信号</label>
                    <input type="text" id="wechat" placeholder="选填" value="<?= $contact->wechat; ?>">
                </li>
                <li>
                    <label for="">主要联系号码</label>
                    <input type="text" placeholder="国内手机号" id="phone" value="<?= $contact->phone ?>">
                </li>
                <li>
                    <label for="">备用联系号码</label>
                    <input type="text" placeholder="选填" id="sparePhone" value="<?= $contact->sparePhone ?>">
                </li>
            </ul>
        </div>
        <div class="box">
            <p>紧急联系方式</p>

            <p class="p1">当无法联系到主要联系人时的备用联络方式</p>

            <p class="form_tip" id="urgentTip"></p>
            <ul class="forms clearfix">
                <li>
                    <label for="">紧急联系人姓名</label>
                    <input type="text" placeholder="请填写实际姓名" id="urgentUsername"
                           value="<?= $contact->urgentUsername ?>">
                </li>
                <li>
                    <label for="">紧急联系人联系方式</label>
                    <input type="text" placeholder="请填写联系方式" id="urgentPhone" value="<?= $contact->urgentPhone ?>">
                </li>
            </ul>
        </div>
        <div class="box" <?=(!empty($travelInfo['info']['type'])&&$travelInfo['info']['type'] == \common\entity\TravelTrip::TRAVEL_TRIP_TYPE_TRAFFIC)?'':'style="display:none"'?>>
            <p>出行信息</p>

            <p class="p1">请您准确填写出行信息，以免耽误行程</p>

            <p class="form_tip" id="flyTip"></p>
            <ul class="forms clearfix">
                <li>
                    <label for="">航班号</label>
                    <input type="text" placeholder="到达航班号" id="arriveFlyNumber"
                           value="<?= $contact->arriveFlyNumber ?>">
                </li>
                <li>
                    <label for="">离开航班号</label>
                    <input type="text" placeholder="离开航班号" id="leaveFlyNumber" value="<?= $contact->leaveFlyNumber ?>">
                </li>
                <li>
                    <label for="">目的地</label>
                    <input type="text" placeholder="到达酒店/目的地名称" id="destination" value="<?= $contact->destination ?>">
                </li>
            </ul>
        </div>
        <?php if ($orderInfo->status == \common\entity\UserOrderInfo::USER_ORDER_STATUS_PAY_WAIT) { ?>
            <div class="box" id="pay_box">
                <p>支付方式</p>

                <p class="p1">您将跳转到支付页面，完成交易后返回本页进行确认是否支付完成</p>

                <div class="maths clearfix">
                    <div class="zfdiv">
                        <b class="icon zfb"></b>
                        <input type="radio" id="radio01" value="1" name="payType"><label for="radio01"></label>
                        <b class="icon weixin"></b>
                        <input type="radio" id="radio02" value="2" name="payType"><label for="radio02"></label>
                    </div>
                </div>
                <a href="javascript:;" id="payBtn" class="pay_btn">立即支付</a>

            </div>
            <div class="finish clearfix" style="display: none;" id="result">
                <p class="title">支付成功！</p>

                <p class="tip">您可以在<a href="/user-info?myOrderManager"> 个人中心-我的订单 </a>查看您的订单状态</p>

                <p>分享这条随游</p>
                <ul class="share">
                    <li>
                        <div class="bdsharebuttonbox" data-tag="share_1">
                            <a data-cmd="tsina" href="javascript:;" class="icon sina"></a>
                            <a data-cmd="weixin" href="javascript:;" class="icon weixin"></a>
                        </div>
                    </li>
                </ul>
                <a href="/user-info?tab=myOrderManager" class="btn">确定</a>
            </div>
        <?php } ?>

    </div>
    <?php
    $carServiceCount = 0;
    $airServiceCount = 0;
    $nightServiceCount = 0;

    ?>


    <div class="right">
        <a href="<?= \common\components\SiteUrl::getTripUrl($travelInfo['info']['tripId']) ?>" target="_blank">
            <h2 class="title bgGreen"><?= $travelInfo['info']['title'] ?></h2>
        </a>
        <a href="<?= \common\components\SiteUrl::getTripUrl($travelInfo['info']['tripId']) ?>" target="_blank" class="product"><img
                src="<?= $travelInfo['info']['titleImg'] ?>"></a>
        <?php if (!empty($travelInfo['info']['type'])&&$travelInfo['info']['type'] == \common\entity\TravelTrip::TRAVEL_TRIP_TYPE_TRAFFIC) { ?>
            <div class="detail">
                <p class="traffic_service_list">预约日期：
                    <?php if (!empty($serviceInfo)) { ?>
                        <?php foreach ($serviceInfo as $key => $service) { ?>
                            <?php
                            if ($service['type'] == 'car') {
                                $carServiceCount++;
                            } else {
                                if ($service['price'] != $travelInfo['trafficInfo']['airplanePrice']) {
                                    $nightServiceCount++;
                                } else {
                                    $airServiceCount++;
                                }
                            }
                            ?>
                            <span <?= $key != 0 ? 'class="span2"' : '' ?>>
                        <?= $service['type'] == 'car' ? '包车' : '接机'; ?>
                                <?= date('Y-m-d H:i', strtotime($service['date'] . " " . $service['time'])); ?>
                                (<?= $service['person'] . "人"; ?>)
                        </span>
                        <?php }
                    } ?>
                </p>
                <p>出行人数： <span><?= $orderInfo->personCount ?>人</span></p>
                <p>随游类型： <span>交通服务</span></p>
                <p>包车价格： <span>￥<?= $travelInfo['trafficInfo']['carPrice'] ?> X <?= $carServiceCount ?></span></p>
                <p>接机服务： <span>￥<?= $travelInfo['trafficInfo']['airplanePrice'] ?> X <?= $airServiceCount ?></span></p>
                <?php if ($nightServiceCount > 0) { ?>
                    <p>夜间接机：
                        <span>￥<?= $travelInfo['trafficInfo']['airplanePrice'] + $travelInfo['trafficInfo']['nightServicePrice'] ?>
                            X <?= $nightServiceCount ?></span></p>
                <?php } ?>
                <p class="total">总价:&nbsp;&nbsp;&nbsp;&nbsp;<span>￥<?= intval($orderInfo->totalPrice) ?></span></p>
                <p>预订须知：</p>
            </div>
        <?php } else { ?>
            <div class="detail">
                <p class="traffic_service_list">预约日期： <?= date('Y-m-d H:i', strtotime($orderInfo->beginDate . " " . $orderInfo->startTime)); ?></p>
                <p>出行人数： <span><?= $orderInfo->personCount ?>人</span></p>
                <p>随游类型： <span>慢行探索</span></p>
                <p>基础价格： <span>￥<?= $orderInfo->basePrice ?> X <?= $orderInfo->personCount ?></span></p>


                <?php if (!empty($serviceInfo)) { ?>
                    <?php foreach ($serviceInfo as $key => $service) { ?>
                        <p><?=$service['name']?>：
                            <?php if($service['type']==\common\entity\TravelTripService::TRAVEL_TRIP_SERVICE_TYPE_PEOPLE){ ?>
                            <span <?= $key != 0 ? 'class="span2"' : '' ?>>￥<?=$service['money']?> X <?= $orderInfo->personCount ?></span></p>
                        <?php }else{ ?>
                            <span <?= $key != 0 ? 'class="span2"' : '' ?>>￥<?=$service['money']?></span></p>
                        <?php } ?>
                    <?php }
                } ?>


                <p class="total">总价:&nbsp;&nbsp;&nbsp;&nbsp;<span>￥<?= intval($orderInfo->totalPrice) ?></span></p>

                <p>预订须知：</p>
            </div>

        <?php } ?>
        <div class="suggest">
            <div class="line"></div>
            <p>预订前，请提前与当地人沟通时间、行程。</p>

            <p>支付成功后，“随游”会通过短信及站内信的方式通
                知您。</p>

            <p>支付后需当地人确认服务的时间、行程；成功预订后
                ，“随游”会以短信及站内通知的方式通知您。</p>

            <p>支付后如预订未成功，“随游”会将预订的钱款退回
                原支付账号。</p>
        </div>

    </div>


</div>


<div align="center" id="qrcode">
</div>

<div id="weixinPayWindow" class="wzhifu screens" style="z-index: 1001">
    <h2>微信支付</h2>

    <div class="con clearfix">
        <div class="left">
            <p class="weiP"></p>

            <p class="tip">
                <span>请使用微信扫一扫</span><br>
                <span>扫描二维码支付</span>
            </p>

        </div>
        <div class="right"><img src="/assets/images/phone.jpg" width="329" height="421"></div>
    </div>
    <a href="javascript:;" onclick="hideWxPay()" class="back"> &nbsp;< &nbsp;选择其他支付方式 </a>
</div>


<script src="/assets/other/weixin/js/qrcode.js"></script>


<script type="text/javascript">

    var interval;
    var isCreateWeixinPay = false;
    var hasAirplane =<?=$hasAirplane?>;
    $(document).ready(function () {
        initBtnClick();
    });

    function initBtnClick() {
        $("#username,#phone").bind("focus", function () {
            $("#contactTip").html("");
        });
        $("#urgentUsername,#urgentPhone").bind("focus", function () {
            $("#urgentTip").html("");
        });
        $("#arriveFlyNumber,#leaveFlyNumber,#destination").bind("focus", function () {
            $("#flyTip").html("");
        });


        $("#payBtn").bind("click", function () {
            var orderNumber = $("#orderNumber").val()
            var username = $("#username").val();
            var wechat = $("#wechat").val();
            var phone = $("#phone").val();
            var sparePhone = $("#sparePhone").val();
            var urgentUsername = $("#urgentUsername").val();
            var urgentPhone = $("#urgentPhone").val();
            var arriveFlyNumber = $("#arriveFlyNumber").val();
            var leaveFlyNumber = $("#leaveFlyNumber").val();
            var destination = $("#destination").val();


            if (username == '') {
                $("#contactTip").html("请输入联系人姓名");
                return;
            }
            if (phone == '') {
                $("#contactTip").html("请输入联系人联系方式");
                return;
            }

            if (urgentUsername == '') {
                $("#urgentTip").html("请输入紧急联系人姓名");
                return;
            }
            if (urgentPhone == '') {
                $("#urgentTip").html("请输入紧急联系人联系方式");
                return;
            }

            if (hasAirplane == 1) {
                if (arriveFlyNumber == '' || leaveFlyNumber == '') {
                    $("#flyTip").html("请输入航班号");
                    return;
                }
                if (destination == '') {
                    $("#flyTip").html("请输入目的地或酒店名称");
                    return;
                }
            }


            $.ajax({
                type: "post",
                url: "/user-order/save-order-contact",
                data: {
                    orderNumber: orderNumber,
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
                        var payType = $("input:radio[name='payType']:checked").val();
                        if (payType == undefined) {
                            Main.showTip("请选择支付方式");
                            return;
                        } else if (payType == 2) {
                            wxPay(orderNumber);
                        } else if (payType == 1) {
                            window.open("/pay?number=" + orderNumber + "&type=1");
                            interval = window.setInterval(function () {
                                getStatus();
                            }, 2000);
                        } else {
                            Main.showTip("请选择支付方式");
                        }
                    } else {
                        alert(data.data);
                    }
                }
            });
        });
    }
    function hideWxPay() {
        $("#myMask").hide();
        $("#weixinPayWindow").hide();
    }

    function wxPay(orderNumber) {
        if (isCreateWeixinPay) {
            $("#myMask").show();
            $("#weixinPayWindow").show();
        } else {
            $.ajax({
                type: "get",
                url: "/pay?number=" + orderNumber + "&type=2",
                success: function (data) {
                    data = eval("(" + data + ")");
                    if (data.status == 1) {
                        var url = data.data;
                        //参数1表示图像大小，取值范围1-10；参数2表示质量，取值范围'L','M','Q','H'
                        var qr = qrcode(10, 'H');
                        qr.addData(url);
                        qr.make();
                        $("#weixinPayWindow").find("p[class='weiP']").html(qr.createImgTag());
                        $("#myMask").show();
                        $("#weixinPayWindow").show();
                        isCreateWeixinPay = true;
                        interval = window.setInterval(function () {
                            getStatus();
                        }, 2000);
                    } else {
                        alert(data.data);
                    }
                }
            });
        }

    }
    /**
     * 获取订单状态
     */
    function getStatus() {
        var orderNumber = $("#orderNumber").val();
        if (orderNumber == '') {
            return;
        }
        $.ajax({
            type: "post",
            url: "/pay/status?number=" + orderNumber,
            success: function (data) {
                data = eval("(" + data + ")");
                if (data.status == 1) {
                    $("#result").show();
                    $("#pay_div").hide();
                    hideWxPay();
                    window.clearInterval(interval);
                }
            }

        });
    }

    window._bd_share_config = {
        common: {
            bdText: '随游网-<?=htmlspecialchars(str_replace("\n"," ",$travelInfo['info']['intro']))?>',
            bdDesc: '随游网-<?=htmlspecialchars(str_replace("\n"," ",$travelInfo['info']['title']))?>',
            bdUrl: '<?=Yii::$app->params['base_dir'].'/view-trip/info?trip='.$travelInfo['info']['tripId'];?>&',
            bdPic: '<?=$travelInfo['info']['titleImg']?>'
        },
        share: [{
            "bdSize": 16
        }]
    }

    //以下为js加载部分
    with (document)0[(getElementsByTagName('head')[0] || body).appendChild(createElement('script')).src = 'http://bdimg.share.baidu.com/static/api/js/share.js?cdnversion=' + ~(-new Date() / 36e5)];

</script>
