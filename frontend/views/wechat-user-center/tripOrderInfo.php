<?php $tripInfo=json_decode($info->tripJsonInfo,true);
?>
<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title>随游订单-订单详情</title>
    <link rel="stylesheet" href="/assets/other/weixin/css/common.css">
    <script type="text/javascript" src="/assets/other/weixin/js/jquery-1.11.1.min.js"></script>
    <link rel="stylesheet" href="/assets/other/weixin/css/weixin.css">
</head>

<body>
<div class="csyoderDetail">
    <div class="content">
        <div class="box">
            <a href="" class="pic"><img src="<?= $tripInfo['info']['titleImg'];?>"></a>
            <div class="details">
                <h3 class="title"><?= $tripInfo['info']['title'];?></h3>
                <p class="line clearfix">
                    <b class="colOrange">￥<?= $info->totalPrice;?></b>
                    <img src="<?= $tripInfo['info']['score']>=2?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                    <img src="<?= $tripInfo['info']['score']>=4?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                    <img src="<?= $tripInfo['info']['score']>=6?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                    <img src="<?= $tripInfo['info']['score']>=8?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                    <img src="<?= $tripInfo['info']['score']>=10?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                </p>
            </div>
        </div>
        <div class="part clearfix">
            <?php  if(!empty($userInfo)){?>
            <a href="#" class="userPic"><img src="<?= $userInfo->headImg?>"></a>
            <span class="userName"><?= $userInfo->nickname?></span>
            <?php }else{?>
                <a href="#" class="btnfr02 colOrange">未知用户</a>
            <?php }?>
        </div>
        <div class="part clearfix">
            <?php if($info->status!=\common\entity\UserOrderInfo::USER_ORDER_STATUS_PAY_SUCCESS||$info->status!=\common\entity\UserOrderInfo::USER_ORDER_STATUS_PAY_WAIT){
                if(!empty($userInfo)){
                ?>
                <p>联系电话：<a href="tel:<?php echo $userInfo->phone;?>" class="colBlue"><?php echo $userInfo->phone;?></a></p>
            <?php } }?>
            <p>出发日期：<b><?php echo $info->beginDate;?></b></p>
            <p>开始时间：<b><?php echo \common\components\DateUtils::convertTimePicker($tripInfo['info']['startTime'],2);?></b></p>
            <p>随游人数：<b><?= $info->personCount?>人</b></p>
        </div>
        <div class="part clearfix">
            <p>附加服务：</p>
            <?php if(count($tripInfo['serviceList'])==0){?>
                <p> 暂无附加服务</p>
            <?php }else{foreach($tripInfo['serviceList'] as $val){?>
                <p><?php echo $val['title'];?>：<b>￥<?php echo intval($val['money']);?></b></p>
            <?php }}?>
            <a href="#" class="btnfr colOrange">总价￥<?php echo intval($info->totalPrice);?></a>
        </div>
        <?php if($info->status==\common\entity\UserOrderInfo::USER_ORDER_STATUS_PAY_SUCCESS){?>
        <div class="part mrTop60 clearfix">
            <a href="#" class="btn bgOrange"   onclick="PublisherIgnoreOrder('<?= $info->orderId;?>')">忽略</a>
            <a href="javascript:;" class="btn bgBlue" onclick="confirmOrder('<?= $info->orderId;?>')">确认</a>
        </div>
        <?php }?>
    </div>
</div>
<script>
    function confirmOrder(id)
    {
        $.ajax({
            url :'/wechat-user-center/publisher-confirm-order',
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
                    window.location.href="/wechat-user-center/trip-order";
                }else{
                    alert('确认失败');
                }
            }
        });
    }
    //忽略
    function PublisherIgnoreOrder(id){
        $.ajax({
            url :'/wechat-user-center/publisher-ignore-order',
            type:'post',
            data:{
                orderId:id
            },
            error:function(){
                //hide load
                alert('忽略失败');
            },
            success:function(data){
                //hide load
                data=eval("("+data+")");
                if(data.status==1){
                    window.location.href="/wechat-user-center/trip-order";
                }else{
                    alert('忽略失败');
                }
            }
        });
    }
</script>

</body>
</html>
