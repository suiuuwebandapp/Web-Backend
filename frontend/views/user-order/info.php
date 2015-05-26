<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/5
 * Time : 下午1:18
 * Email: zhangxinmailvip@foxmail.com
 */
$travelInfo=json_decode($orderInfo->tripJsonInfo,true);
$serviceInfo=json_decode($orderInfo->serviceInfo,true);
?>

<style>
    body{
        background: #F7F7F7;
    }
</style>
<input type="hidden" value="<?=$orderInfo->orderNumber?>" id="orderNumber"/>
<div id="checkOut" class="w1200 clearfix">
    <div class="check clearfix">
        <dl class="checkList  clearfix">
            <dt class="title">
                <span>订单</span><span>随游</span><span>开始时间</span><span>坐标</span><span>随游时长</span><span>随友</span><span>出行日期</span><span>人数</span><span>单项服务</span>
            </dt>
            <dd>
                <a target="_blank" href="/view-trip/info?trip=<?=$travelInfo['info']['tripId']?>">
                    <span class="pic"><img src="<?=$travelInfo['info']['titleImg']?>"></span>
                </a>
                <a target="_blank" href="/view-trip/info?trip=<?=$travelInfo['info']['tripId']?>"><span><?=$travelInfo['info']['title']?></span></a>
                <span><?=\common\components\DateUtils::convertTimePicker($orderInfo->startTime,2)?></span>
                <span><?=$travelInfo['info']['countryCname']."-".$travelInfo['info']['cityCname']?></span>
                <span>
                    <?=$travelInfo['info']['travelTime'] ?>
                    <?=$travelInfo['info']['travelTimeType']==\common\entity\TravelTrip::TRAVEL_TRIP_TIME_TYPE_DAY?'天':'小时';?>
                </span>
                <span>
                    <a href="#" class="user"> <img src="<?=$travelInfo['createPublisherInfo']['headImg']?>" ></a>
                    <a href="#" class="message"><b><?=$travelInfo['createPublisherInfo']['nickname']?></b><br>
                        <img src="/assets/images/xf.fw.png" width="18" height="12">
                    </a>
                </span>
                <span><?=$orderInfo->beginDate?></span>
                <span><?=$orderInfo->personCount?></span>
                <span>
                <?php if(!empty($serviceInfo)){?>
                    <?php foreach($serviceInfo as $service){ ?>
                        <?=$service['title']?><br>
                <?php }} ?>
                </span>
            </dd>
        </dl>
        <p><span>总价：<b><?=$orderInfo->totalPrice?></b></span></p>
    </div>
    <?php if($orderInfo->status==\common\entity\UserOrderInfo::USER_ORDER_STATUS_PAY_WAIT){ ?>


    <div class="yuding clearfix" id="pay_div">
        <ul class="con clearfix">
            <li>
                <img src="/assets/images/zfbBg.png" width="66" height="66">
                <img src="/assets/images/weixinBg.png" width="66" height="66">
            </li>
            <li>
                <input type="radio" name="payType" value="1" id="zfb"><label for="zfb" class="zb"></label>
                <input type="radio" name="payType" value="2" id="weixin"><label for="weixin" class="wei"></label>
            </li>

        </ul>
        <p>您将跳转到支付页面，完成交易后返回本页进行确认是否支付完成</p>
        <a href="javascript:;" class="btn" id="pay">立即预定</a>
    </div>
    <div class="finish clearfix" style="display: none;" id="result">
        <p class="title">支付成功！</p>
        <p class="tip">您可以在<a href="/user-info?myOrderManager"> 个人中心-我的订单 </a>查看您的订单状态</p>
        <p>分享这条随游</p>
        <ul class="share">
            <li><b class="icon sina"></b>
                <b class="icon weixin"></b></li>
        </ul>
        <a href="/user-info?myOrderManager" class="btn">确定</a>
    </div>
    <?php } ?>
</div>


<div align="center" id="qrcode">

</div>

<div id="weixinPayWindow" class="wzhifu screens">
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
    <a href="javascript:hideWxPay();" class="back"> &nbsp;< &nbsp;选择其他支付方式 </a>

</div>


<script src="/assets/other/weixin/js/qrcode.js"></script>


<script type="text/javascript">

    var interval;
    var isCreateWeixinPay=false;
    $(document).ready(function(){
        $("#pay").bind("click",function(){
            var payType=$("input:radio[name='payType']:checked").val();
            var orderNumber=$("#orderNumber").val();
            if(payType==undefined){
                Main.showTip("请选择支付方式");
                return;
            }else if(payType==2){
                wxPay(orderNumber);
            }else if(payType==1){
                window.open("/pay?number="+orderNumber+"&type=1");
                interval=window.setInterval(function(){
                    getStatus();
                },2000);
            }else{
                Main.showTip("请选择支付方式");
            }
        });

    });
    function hideWxPay()
    {
        $("#myMask").hide();
        $("#weixinPayWindow").hide();
    }

    function wxPay(orderNumber)
    {
        if(isCreateWeixinPay){
            $("#myMask").show();
            $("#weixinPayWindow").show();
        }else{
            $.ajax({
                type:"get",
                url:"/pay?number="+orderNumber+"&type=2",
                success:function(data){
                    data=eval("("+data+")");
                    if(data.status==1){
                        var url = data.data;
                        //参数1表示图像大小，取值范围1-10；参数2表示质量，取值范围'L','M','Q','H'
                        var qr = qrcode(10, 'H');
                        qr.addData(url);
                        qr.make();
                        $("#weixinPayWindow").find("p[class='weiP']").html(qr.createImgTag());
                        $("#myMask").show();
                        $("#weixinPayWindow").show();
                        isCreateWeixinPay=true;
                        interval=window.setInterval(function(){
                            getStatus();
                        },2000);
                    }else
                    {
                        alert(data.data);
                    }
                }
            });
        }

    }
    /**
     * 获取订单状态
     */
    function getStatus(){
        var orderNumber=$("#orderNumber").val();
        if(orderNumber==''){
            return;
        }
        $.ajax({
            type:"post",
            url:"/pay/status?number="+orderNumber,
            success:function(data){
                data=eval("("+data+")");
                if(data.status==1){
                    $("#result").show();
                    $("#pay_div").hide();
                    hideWxPay();
                    window.clearInterval(interval);
                }
            }

        });
    }

</script>
