<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title></title>
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
    <style>
         .btn{ font-size:0.85rem; color:#fff; background:#97CBFF; display:block; width:10.8rem; height:2.14rem; line-height:2.14rem; text-align:center; margin:3rem auto 0; text-decoration:none;}
        .btn:hover{ text-decoration:underline;}
    </style>
</head>

<body>
<div id="page" class="userCenter">
        <?php include "left.php"; ?>
        <div class="Uheader header mm-fixed-top">
            <a href="#menu"></a>
            我的订单
        </div>
        <div class="content">
            <?php if(count($list)==0&&count($unList)==0){?>
                <img src="/assets/other/weixin/images/logo02.png" class="logo">
            <p class="noOrder">你还没有订单哦</p>
                <a href="/wechat-trip" class="btn">任性随游</a>
            <?php }?>
            <?php foreach($unList as $val){
                $tripInfo = json_decode($val['tripJsonInfo'],true);
                ?>
                <div class="box clearfix" onclick="toInfo('<?php echo $val['orderNumber'] ?>')">
                    <a href="javascript:;"  class="pic fl"><img  src="<?php echo $tripInfo['info']['titleImg'];?>"></a>
                    <div class="details fr">
                        <?php if($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_PAY_WAIT){?>
                            <a href="javascript:;" class="colOrange">未付款</a>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_PAY_SUCCESS){?>
                            <a href="javascript:;" class="colOrange">待接单</a>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_CONFIRM){?>
                            <a href="javascript:;" class="colBlue">游玩中</a>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_CANCELED){?>
                            <a href="javascript:;" class="colGrey " >已取消</a>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_REFUND_WAIT){?>
                            <a href="javascript:;" class="colGrey" >退款中</a>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_REFUND_SUCCESS){?>
                            <a href="javascript:;" class="colGrey" >退款成功</a>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_PLAY_SUCCESS){?>
                            <a href="javascript:;" class="colGrey" >已结束</a>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_PLAY_FINISH){?>
                            <a href="javascript:;" class="colGrey" >已结束</a>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_REFUND_VERIFY){?>
                            <a href="javascript:;" class="colGrey" >审核中</a>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_REFUND_FAIL){?>
                            <a href="javascript:;" class="colGrey" >审核失败</a>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_PUBLISHER_CANCEL){?>
                            <a href="javascript:;" class="colGrey" >随友取消</a>
                        <?php }?>

                        <p><?php echo $tripInfo['info']['title'];?></p>
                    </div>
                    <p class="data">出发日期：<span><?php echo $val['beginDate'];?></span></p>
                </div>
            <?php }?>
            <?php foreach($list as $val){
                $tripInfo = json_decode($val['tripJsonInfo'],true);
                ?>
                <div class="box clearfix" onclick="toInfo('<?php echo $val['orderNumber'] ?>')">
                    <a href="javascript:;"  class="pic fl"><img  src="<?php echo $tripInfo['info']['titleImg'];?>"></a>
                    <div class="details fr">
                        <?php if($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_PAY_WAIT){?>
                            <a href="javascript:;" class="colOrange">未付款</a>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_PAY_SUCCESS){?>
                            <a href="javascript:;" class="colOrange">待接单</a>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_CONFIRM){?>
                            <a href="javascript:;" class="colBlue">游玩中</a>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_CANCELED){?>
                            <a href="javascript:;" class="colGrey " >已取消</a>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_REFUND_WAIT){?>
                            <a href="javascript:;" class="colGrey" >退款中</a>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_REFUND_SUCCESS){?>
                            <a href="javascript:;" class="colGrey" >退款成功</a>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_PLAY_SUCCESS){?>
                            <a href="javascript:;" class="colGrey" >已结束</a>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_PLAY_FINISH){?>
                            <a href="javascript:;" class="colGrey" >已结束</a>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_REFUND_VERIFY){?>
                            <a href="javascript:;" class="colGrey" >审核中</a>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_REFUND_FAIL){?>
                            <a href="javascript:;" class="colGrey" >审核失败</a>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_PUBLISHER_CANCEL){?>
                            <a href="javascript:;" class="colGrey" >随友取消</a>
                        <?php }?>

                        <p><?php echo $tripInfo['info']['title'];?></p>
                    </div>
                    <p class="data">出发日期：<span><?php echo $val['beginDate'];?></span></p>
                </div>
            <?php }?>
        </div>
    </div>
</div>
<script>

    function toInfo(id)
    {
        window.location.href="/wechat-user-center/my-order-info?id="+id;
    }
</script>
</body>
</html>
