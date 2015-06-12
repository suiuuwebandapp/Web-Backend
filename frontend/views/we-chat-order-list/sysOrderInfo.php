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
                <div class="top clearfix">
                    <?php if($val['wStatus']!=\common\entity\WeChatOrderList::STATUS_PAY_SUCCESS&&$val['wStatus']!=\common\entity\WeChatOrderList::STATUS_APPLY_REFUND){?>
                        <a href="javascript:;" class="delete" onclick="deleteOrder('<?php echo $val['wOrderNumber']?>')"></a>
                    <?php }?>
                    <div class="left"><a href="#" class="user"><img src="<?php echo $val['headImg']?>" class="logo"></a><span class="name"><?php echo $val['nickName'];?></span></div>
                    <div class="right">
                        <p>城市：<b><?php echo $val['wOrderSite'];?></b></p>
                        <?php
                        $dateList=$val['wOrderTimeList'];
                        $dataArr=explode(',',$dateList);
                        foreach($dataArr as $dataV){
                            ?>
                            <p>日期：<b><?php echo $dataV;?></b></p>
                        <?php } ?>
                        <p>手机：<a href="tel:<?php echo $val['areaCode'].$val['phone'];?>"><?php echo $val['areaCode'].$val['phone'];?></a></p>
                    </div>
                </div>
                <div class="down clearfix">
                    <?php
                    $str =$val['wDetails'];//'rrrr######qweqweqwe###09###ssssss######qqqqqqqq###asd###asdasdasd';
                    $arr_i=array();
                    $arr_t=array();
                    $contentTitle="";
                    if(!empty($str)){
                        $arr=explode('###',$str);
                        $contentTitle=$arr[0];
                        for($i=1;$i<count($arr);$i++)
                        {
                            if($i%2==0)
                            {
                                $arr_i[]=$arr[$i];
                            }else
                            {
                                $arr_t[]=$arr[$i];
                            }
                        }
                    }
                    ?>
                    <h3 class="title"><?php echo $contentTitle;?></h3>
                    <dl>
                        <?php for($j=0;$j<count($arr_i);$j++){?>
                            <?php if(empty($arr_t[$j])){ ?>
                                <dt class="title02"><?php echo $arr_i[$j];?></dt>
                            <?php }else{?>
                                <dd><span class="time"><?php echo $arr_t[$j];?> </span><p class="detail"><?php echo $arr_i[$j];?></p></dd>
                            <?php }?>
                        <?php }?>
                    </dl>
                </div>
                <?php if($val['wStatus']==\common\entity\WeChatOrderList::STATUS_PAY_SUCCESS){?>
                    <b class="money money2">￥ <?php echo $val['wMoney'];?></b>
                <?php }else{?>
                    <b class="money ">￥ <?php echo $val['wMoney'];?></b>
                <?php }?>
                <?php if($val['wStatus']==\common\entity\WeChatOrderList::STATUS_PAY_SUCCESS){?>
                    <a href="/we-chat-order-list/show-refund?o=<?php echo $val['wOrderNumber']?>" class="btn payback">申请退款</a>
                    <a href="javascript:;" class="btn sure" onclick="overOrder('<?php echo $val['wOrderNumber']?>')">确认游玩</a>
                <?php }elseif($val['wStatus']==\common\entity\WeChatOrderList::STATUS_PROCESSED){?>
                    <?php if($val['wMoney']!=0){?>
                        <a href="javascript:;" class="btn pay" onclick="callpay('<?php echo $val['wOrderNumber']?>')">支付</a>
                    <?php }else{?>
                        <a href="javascript:alert('金额不能为0');" class="btn finish" >支付</a>
                    <?php }?>
                <?php }elseif($val['wStatus']==\common\entity\WeChatOrderList::STATUS_APPLY_REFUND){?>
                    <a href="javascript:;" class="btn finish">退款中</a>
                <?php }elseif($val['wStatus']==\common\entity\WeChatOrderList::STATUS_END){?>
                    <a href="javascript:;" class="btn finish">已结束</a>
                <?php }elseif($val['wStatus']==\common\entity\WeChatOrderList::STATUS_REFUND_FAL){?>
                    <a href="javascript:;" class="btn finish">拒绝退款</a>
                <?php }elseif($val['wStatus']==\common\entity\WeChatOrderList::STATUS_REFUND_SUCCESS){?>
                    <a href="javascript:;" class="btn finish">退款成功</a>
                <?php }else{?>
                    <a href="javascript:;" class="btn finish">已结束</a>
                <?php }?>
            </div>
</div>
</div>
<div class="order_pay clearfix">
    <p>选择支付方式</p>
    <div class="select clearfix">
        <a href="javascript:;" class="zfb" onclick="aliPayUrl()"></a>
        <a href="javascript:;" class="wei" onclick="payUrl()"></a>
        <a href="javascript:;" class="btn">取消</a>
    </div>
</div>

<script>

</script>
<script type="text/javascript">

</script>
</body>
</html>
