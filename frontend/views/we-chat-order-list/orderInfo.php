
    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        <p class="navTop">定制详情</p>
    </div>
    <?php if($info['wStatus']==\common\entity\WeChatOrderList::STATUS_NORMAL||$info['wStatus']==\common\entity\WeChatOrderList::STATUS_CANCEL){?>
        <div class="con cdzOder clearfix">
            <div class="content cdzdetail02">
                <p>目的地：<span><?= $info['wOrderSite'];?></span></p>
                <p>出行人数：<span><?= $info['wUserNumber'];?>人</span></p>
                <?php
                $dateList=$info['wOrderTimeList'];
                $dataArr=explode(',',$dateList);
                foreach($dataArr as $dataV){
                    ?>
                    <p>随游陪同日期：<span><?php echo $dataV;?></span></p>
                <?php } ?>
                <p>旅行需求：<span><?php
                        $arr = explode("||",$info['wOrderContent']);
                        if(count($arr)==4)
                        {
                            $lx=explode(":",$arr[1])[1];
                            $dy=explode(":",$arr[2])[1];
                            $str = explode(":",$arr[0])[1]."、".join("、",explode(",",$lx))."、"."导游：".join("、",explode(",",$dy));
                            echo $str;
                        }
                        ?>
                    </span></p>
                <p>其他留言：<span><?php $arr = explode("||",$info['wOrderContent']);if(count($arr)==4)
                        {
                            echo $arr[3];
                        }else
                        {
                            echo $info['wOrderContent'];
                        }
                        ?> </span></p>
                <?php if($info['wStatus']==\common\entity\WeChatOrderList::STATUS_NORMAL){?>
                <div class="fixed">
                    <a href="/we-chat-order-list/edit-order?orderNumber=<?php echo $info['wOrderNumber'];?>" class="btn btn01">修改</a>
                    <a href="javascript:;" class="btn btn02" onclick="overOrder('<?php echo $info['wOrderNumber']?>')">取消行程</a>
                </div>
                <?php }?>
            </div>
        </div>
        <script>
            function overOrder(orderNumber)
            {
                $.ajax({
                    url :'/we-chat-order-list/over-order',
                    type:'post',
                    data:{
                        _csrf: $('input[name="_csrf"]').val(),
                        o:orderNumber
                    },
                    error:function(){
                        alert("结束订购异常");
                    },
                    success:function(data){
                        data=eval("("+data+")");
                        if(data.status==1){
                            alert("取消成功");
                            setTimeout(function(){location.reload()},1000);
                        }else if(data.status==-3){
                            window.location.href=data.data;
                        }else{
                            alert("取消异常");
                        }
                    }
                });
            }
        </script>
    <?php }else{?>

<div class="con cdzOder_Detail clearfix">
    <div class="box clearfix">
        <div class="down clearfix">

            <?php
            $str =$info['wDetails'];//'rrrr######qweqweqwe###09###ssssss######qqqqqqqq###asd###asdasdasd';
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
            <div class="boxTop">
                <h3 class="title"><?php echo $contentTitle;?></h3>
                <?php if($info['wStatus']==\common\entity\WeChatOrderList::STATUS_PAY_SUCCESS){?>
                <p>随友电话：<span><?php echo $info["tripContact"]?></span></p>
                <?php }?>
                <p>负责人电话：<span><?php echo $info["rPhone"]?></span></p>
            </div>
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
        <div class="last clearfix">
            <b class="colOrange fl money">￥ <?php echo $info['wMoney']?$info['wMoney']:0 ;?></b>

        <?php if($info['wStatus']==\common\entity\WeChatOrderList::STATUS_PAY_SUCCESS){?>
            <a href="/we-chat-order-list/show-refund?o=<?php echo $info['wOrderNumber']?>" class="btn bgOrange fl">申请退款</a>
            <a href="javascript:;" class="btn bgBlue fl" onclick="overOrder('<?php echo $info['wOrderNumber']?>')">确认游玩</a>
        <?php }elseif($info['wStatus']==\common\entity\WeChatOrderList::STATUS_PROCESSED){?>
            <?php if($info['wMoney']!=0){?>
                <a href="javascript:;" class="btn bgBlue fl" id="payC" onclick="callpay('<?php echo $info['wOrderNumber']?>')">支付</a>
            <?php }else{?>
                <a href="javascript:alert('金额不能为0');" class="btn finish" >支付</a>
            <?php }?>
        <?php }elseif($info['wStatus']==\common\entity\WeChatOrderList::STATUS_APPLY_REFUND){?>
            <span class="state">退款中</span>
        <?php }elseif($info['wStatus']==\common\entity\WeChatOrderList::STATUS_END){?>
            <span class="state">已结束</span>
        <?php }elseif($info['wStatus']==\common\entity\WeChatOrderList::STATUS_REFUND_FAL){?>
            <span class="state">拒绝退款</span>
        <?php }elseif($info['wStatus']==\common\entity\WeChatOrderList::STATUS_REFUND_SUCCESS){?>
            <span class="state">退款成功</span>
        <?php }else{?>
            <span class="state">已取消</span>
        <?php }?>
        <!--状态1-->


        </div>

        <!--状态2-->
        <!--        <div class="last clearfix">
                    <b class="colOrange fl money">800￥</b>
                    <a href="#" class="btn bgBlue fl">支付</a>
                </div>
        -->


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


<script type="text/javascript">
    function cancelOrder(orderNumber)
    {
        $.ajax({
            url :'/we-chat-order-list/cancel-order',
            type:'post',
            data:{
                _csrf: $('input[name="_csrf"]').val(),
                o:orderNumber
            },
            error:function(){
                alert("结束订购异常");
            },
            success:function(data){
                data=eval("("+data+")");
                if(data.status==1){
                    alert("取消成功");
                    setTimeout(function(){location.reload()},1000);
                }else if(data.status==-3){
                    window.location.href=data.data;
                }else{
                    alert("取消异常");
                }
            }
        });
    }
    function overOrder(orderNumber)
    {
        $.ajax({
            url :'/we-chat-order-list/over-order',
            type:'post',
            data:{
                _csrf: $('input[name="_csrf"]').val(),
                o:orderNumber
            },
            error:function(){
                alert("结束订购异常");
            },
            success:function(data){
                data=eval("("+data+")");
                if(data.status==1){
                    alert("确认成功");
                    setTimeout(function(){location.reload()},1000);
                }else if(data.status==-3){
                    window.location.href=data.data;
                }else{
                    alert("确认异常");
                }
            }
        });
    }
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
    function callpay(orderNumber)
    {
        urlR ="<?php echo Yii::$app->params['weChatUrl'];?>/we-chat/wxpay-js?t=1&n="+orderNumber;
        urlA ="<?php echo Yii::$app->params['weChatUrl'];?>/we-chat/ali-pay-url?t=1&o="+orderNumber;

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
<?php }?>