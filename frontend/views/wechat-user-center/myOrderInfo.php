<?php $tripInfo=json_decode($info->tripJsonInfo,true);
$serviceInfo=json_decode($info->serviceInfo,true);
$tripType = $tripInfo["info"]["type"];
?>
<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title>随游</title>
    <link rel="stylesheet" href="/assets/other/weixin/css/common.css">
    <script type="text/javascript" src="/assets/other/weixin/js/jquery-1.11.1.min.js"></script>
    <link rel="stylesheet" href="/assets/other/weixin/css/weixin.css">
    <link rel="stylesheet" href="/assets/other/weixin/css/jquery.mmenu.css">
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

<body  onload="showHtml()">

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
            <p class="navTop">订单详情</p>
        </div>
<div class="jtoderDetail">
    <div class="content">
        <div class="box">
            <a href="/wechat-trip/info?tripId=<?= $tripInfo['info']['tripId'];?>" class="pic"><img src="<?= $tripInfo['info']['titleImg'];?>"></a>
            <div class="details">
                <h3 class="title"><?= $tripInfo['info']['title'];?></h3>
                <p class="line clearfix">
                    <b class="colOrange">￥<?= intval($info->basePrice);?></b>
                    <img src="<?= $tripInfo['info']['score']>=10?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                    <img src="<?= $tripInfo['info']['score']>=8?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                    <img src="<?= $tripInfo['info']['score']>=6?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                    <img src="<?= $tripInfo['info']['score']>=4?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                    <img src="<?= $tripInfo['info']['score']>=2?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                </p>
            </div>
        </div>
        <?php if(empty($publisherBase)){?>
        <div class="part clearfix">
            <?php  if($info->status==\common\entity\UserOrderInfo::USER_ORDER_STATUS_PAY_SUCCESS){?>
            <a href="#" class="btnfr02 colOrange">待接单</a>
            <?php }?>
            <p class="datas">订单创建时间：<span><?php echo $info->createTime?></span></p>
            <p class="numbers">订单号 :<span> <?php echo $info->orderNumber?></span></p>
        </div>
        <?php }else{?>
            <div class="part clearfix">
                <a href="/wechat-user-info/user-info?userSign=<?= $publisherBase->userSign?>" class="userPic"><img src="<?= $publisherBase->headImg;?>"></a>
                <span class="userName"><?= $publisherBase->nickname;?></span>
                <a href="/wechat-user-center/user-message-info?rUserSign=<?= $publisherBase->userSign;?>" class="chat"></a>
                <p class="datas">订单创建时间：<span><?php echo $info->createTime?></span></p>
                <p class="numbers">订单号 :<span> <?php echo $info->orderNumber?></span></p>
            </div>
        <?php }?>

        <?php if($tripType==\common\entity\TravelTrip::TRAVEL_TRIP_TYPE_TRAFFIC){?>
            <div class="part clearfix">
                <p class="title">订单信息：</p>
                    <?php if(count($serviceInfo)==0){?>
                    <p> 暂无附加服务</p>
                <?php }else{?>
                <ul class="list clearfix">
                    <li class="title">
                        <p class="first">项目</p>
                        <p>服务日期</p>
                        <p>时间</p>
                        <p class="last">人数</p>
                    </li>
                    <?php foreach($serviceInfo as $val){
                    $typeName="";
                    switch($val["type"])
                    {
                        case "car":
                            $typeName="包车";
                            break;
                        case "airplane_come":
                            $typeName="接机";
                            break;
                        case "airplane_send":
                            $typeName="送机";
                            break;
                        default:
                    }
                    ?>
                    <li>
                        <p class="first"><?=$typeName?></p>
                        <p><?php echo $val["date"];?></p>
                        <p><?php echo \common\components\DateUtils::convertTimePicker($val["time"],2);?></p>
                        <p class="last"><?php echo $val["person"];?></p>
                    </li>
                <?php }?>
                </ul>
                    <?php }?>
                <a href="#" class="btnfr colOrange">总价￥<?php echo intval($info->totalPrice);?></a>
            </div>
        <?php }else{?>
            <div class="part clearfix">
                <?php if(!empty($publisherBase)){?>
                    <p>联系电话：<a href="tel:<?php echo $publisherBase->phone;?>" class="colBlue"><?php echo $publisherBase->phone;?></a></p>
                <?php }?>
                <p>出发日期：<b><?php echo $info->beginDate;?></b></p>
                <p>开始时间：<b><?php echo \common\components\DateUtils::convertTimePicker($info->startTime,2);?></b></p>
                <p>随游人数：<b><?= $info->personCount?>人</b></p>
            </div>
            <div class="part clearfix">
                <p class="title">附加服务：</p>
                <?php if(count($serviceInfo)==0){?>
                    <p> 暂无附加服务</p>
                <?php }else{foreach($serviceInfo as $val){?>
                    <p><?php echo $val['title'];?>：<b>￥<?php echo intval($val['money']);?></b></p>
                <?php }}?>
                <a href="#" class="btnfr colOrange">总价￥<?php echo intval($info->totalPrice);?></a>
            </div>
        <?php }?>

        <div class="part clearfix">
            <p class="title">我的信息：</p>
            <?php if(empty($contact)){?>
                <p> 暂无信息</p>
            <?php }else{?>
                <?php if(!empty($contact->arriveFlyNumber)){?>
                    <p>航班号：<b><?php echo $contact->arriveFlyNumber;?></b></p>
                <?php }?>
                <?php if(!empty($contact->leaveFlyNumber)){?>
                    <p>离开航班号：<b><?php echo $contact->leaveFlyNumber;?></b></p>
                <?php }?>
                <?php if(!empty($contact->destination)){?>
                    <p>目的地：<b><?php echo $contact->destination;?></b></p>
                <?php }?>
                <p>主要联系人：<b><?php echo $contact->username;?></b></p>
                <p>微信号：<b><?php echo $contact->wechat;?></b></p>
                <p>联系号码：<b><?php echo $contact->phone;?></b></p>
                <p>备用联系号码：<b><?php echo $contact->sparePhone;?></b></p>
                <p>紧急联系人：<b><?php echo $contact->urgentUsername;?></b></p>
                <p>紧急联系号码：<b><?php echo $contact->urgentPhone;?></b></p>

            <?php }?>
        </div>
        <div class="part clearfix">
            <?php if($info->status==\common\entity\UserOrderInfo::USER_ORDER_STATUS_PAY_WAIT){?>
                    <a href="javascript:;" class="btn bgOrange"  id="payC"  onclick="pay('<?= $info->orderNumber;?>')">支付</a>
                    <a href="javascript:;" class="btn bgBlue" onclick="cancelOrder('<?= $info->orderId;?>')">取消订单</a>
            <?php }elseif($info->status==\common\entity\UserOrderInfo::USER_ORDER_STATUS_PAY_SUCCESS){?>
                <a href="javascript:;" class="btn bgOrange one"   onclick="refundOrder('<?= $info->orderId;?>')">申请退款</a>
            <?php }elseif($info->status==\common\entity\UserOrderInfo::USER_ORDER_STATUS_CONFIRM){?>
                <a href="javascript:;" class="btn bgBlue" onclick="confirmOrder('<?= $info->orderId;?>')">确认游玩</a>
                <a href="javascript:;" class="btn bgOrange"   onclick="refundByMessage('<?= $info->orderId;?>')">申请退款</a>
            <?php }elseif($info->status==\common\entity\UserOrderInfo::USER_ORDER_STATUS_CANCELED){?>
                <a href="javascript:;" class="btn bgGrey one " >已取消</a>
            <?php }elseif($info->status==\common\entity\UserOrderInfo::USER_ORDER_STATUS_REFUND_WAIT){?>
                <a href="javascript:;" class="btn bgGrey one" >退款中</a>
            <?php }elseif($info->status==\common\entity\UserOrderInfo::USER_ORDER_STATUS_REFUND_SUCCESS){?>
                <a href="javascript:;" class="btn bgGrey one" >退款成功</a>
            <?php }elseif($info->status==\common\entity\UserOrderInfo::USER_ORDER_STATUS_PLAY_SUCCESS){?>
                <a href="javascript:;" class="btn bgGrey one" >已结束</a>
            <?php }elseif($info->status==\common\entity\UserOrderInfo::USER_ORDER_STATUS_PLAY_FINISH){?>
                <a href="javascript:;" class="btn bgGrey one" >已结束</a>
            <?php }elseif($info->status==\common\entity\UserOrderInfo::USER_ORDER_STATUS_REFUND_VERIFY){?>
                <a href="javascript:;" class="btn bgGrey one" >审核中</a>
            <?php }elseif($info->status==\common\entity\UserOrderInfo::USER_ORDER_STATUS_REFUND_FAIL){?>
                <a href="javascript:;" class="btn bgGrey one" >审核失败</a>
            <?php }elseif($info->status==\common\entity\UserOrderInfo::USER_ORDER_STATUS_PUBLISHER_CANCEL){?>
                <a href="javascript:;" class="btn bgGrey one" >随友取消</a>
            <?php }?>
        </div>
    </div>
</div>
<div class="order_pay clearfix">
    <p>选择支付方式</p>
    <div class="select clearfix">
        <a href="javascript:;" class="zfb" onclick="aliPayUrl()"></a>
        <a href="javascript:;" class="wei" onclick="payUrl()"></a>
        <a href="javascript:;" class="btn" id="qxPay">取消</a>
    </div>
</div>
</div>
<script type="text/javascript">
    $(function(){
        $('#payC').click(function(e) {
            $('.order_pay').animate({height:'6.5rem'},500);
        });
        $('#qxPay').click(function(e) {
            $('.order_pay').animate({height:'0'},500);
        });

    })
    var urlR="";
    var urlA="";
    function pay(orderNumber)
    {
        urlR ="<?php echo Yii::$app->params['weChatUrl'];?>/we-chat/wxpay-js?t=2&n="+orderNumber;
        urlA ="<?php echo Yii::$app->params['weChatUrl'];?>/we-chat/ali-pay-url?t=2&o="+orderNumber;

    }
    function payUrl()
    {
        if(urlR=="")
        {
            alert("未知的订单");
            return;
        }
        window.location.href=urlR;
    }
    function aliPayUrl()
    {
        if(urlA=="")
        {
            alert("未知的订单");
            return;
        }
        window.location.href=urlA;
    }
</script>
<script>

    function refundByMessage(orderId)
    {
        window.location.href="/wechat-user-center/apply-refund?id="+orderId;
    }
    function refundOrder(orderId)
    {
        $.ajax({
            url :'/wechat-user-center/refund-order',
            type:'post',
            data:{
                orderId:orderId
            },
            error:function(){
                //hide load
                alert('退款失败');
            },
            success:function(data){
                //hide load
                data=eval("("+data+")");
                if(data.status==1){
                    window.location.href="/wechat-user-center/my-order";
                }else{
                    alert('退款失败');
                }
            }
        });
    }
    //取消
    function cancelOrder(id)
    {
        $.ajax({
            url :'/wechat-user-center/cancel-order',
            type:'post',
            data:{
                orderId:id
            },
            error:function(){
                //hide load
                alert('取消失败');
            },
            success:function(data){
                //hide load
                data=eval("("+data+")");
                if(data.status==1){
                    window.location.href="/wechat-user-center/my-order";
                }else{
                    alert(data.data);
                }
            }
        });
    }
    //确认
    function confirmOrder(id){
        $.ajax({
            url :'/wechat-user-center/user-confirm-play',
            type:'post',
            data:{
                orderId:id
            },
            error:function(){
                //hide load
                alert('确认失败');
            },
            success:function(data){
                //hide load
                data=eval("("+data+")");
                if(data.status==1){
                    window.location.href="/wechat-user-center/my-order";
                }else{
                    alert('确认失败');
                }
            }
        });
    }
</script>

</body>
</html>
