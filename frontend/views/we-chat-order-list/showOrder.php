<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title>随游-订单</title>
    <link rel="stylesheet" href="/assets/other/weixin/css/common.css">
    <link rel="stylesheet" href="/assets/other/weixin/css/weixin.css">
    <script type="text/javascript" src="/assets/other/weixin/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="/assets/other/weixin/js/weixin.js"></script>
</head>

<body>
<div class="con sy_order clearfix">
    <div class="box clearfix">
        <?php if(empty($val)||$val==false){
            echo "未知的订单";
            ?>
        <?php }else{?>
        <div class="top clearfix">

            <?php if($val['wStatus']!=\common\entity\WeChatOrderList::STATUS_PAY_SUCCESS&&$val['wStatus']!=\common\entity\WeChatOrderList::STATUS_APPLY_REFUND){?>
                <a href="javascript:;" class="delete" onclick="deleteOrder('<?php echo $val['wOrderNumber']?>')"></a>
            <?php }?>
            <div class="left"><a href="#" class="user"><img src="<?php echo $val['headImg']?>" class="logo"><span class="name"><?php echo $val['nickName'];?></span></a></div>
            <div class="right">
                <p>城市：<b><?php echo $val['wOrderSite'];?></b></p>
                <?php
                $dateList=$val['wOrderTimeList'];
                $dataArr=explode(',',$dateList);
                foreach($dataArr as $dataV){
                    ?>
                    <p>日期：<b><?php echo $dataV;?></b></p>
                <?php } ?>
            </div>
        </div>
        <div class="down clearfix">
            <p><?php echo $val['wDetails'];?></p>
        </div>
        <b class="money"><?php $val['wMoney']?></b>
        <?php if($val['wStatus']==\common\entity\WeChatOrderList::STATUS_PAY_SUCCESS){?>
            <a href="/we-chat-order-list/show-refund?o=<?php echo $val['wOrderNumber']?>" class="btn payback">申请退款</a>
        <?php }elseif($val['wStatus']==\common\entity\WeChatOrderList::STATUS_PROCESSED){?>
            <a href="javascript:;" class="btn pay" onclick="callpay('<?php echo $val['wOrderNumber']?>')">支付</a>
        <?php }elseif($val['wStatus']==\common\entity\WeChatOrderList::STATUS_APPLY_REFUND){?>
            <a href="javascript:;" class="btn finish">退款中</a>
        <?php }else{?>
            <a href="javascript:;" class="btn finish">已结束</a>
        <?php }?>
        <?php }?>
    </div>
</div>
</body>
</html>
