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
        <p class="navTop">我的定制</p>
    </div>
<div class="con cdzOder clearfix">
    <div class="content">
    <a href="/we-chat-order-list/order-view" class="bgBlue colWit dbtnfixed">添加定制</a>
    <?php
        $i=0;
        foreach($list as $val){
            $i++;
        if(empty($val['wRelativeSign'])){
        ?>

            <div class="box clearfix"">
                <div class="pic fl">
                    <a href="" class="userPic"><img src="/assets/other/weixin/images/logo02.png"></a>
                    <p class="name">未分配</p>
                </div>
                <div class="details fr" id="details">
                    <p class="data">目的地：<span><?php echo $val['wOrderSite'];?></span></p>
                    <p class="data">日期：
                    <?php
                    $dateList=$val['wOrderTimeList'];
                    $dataArr=explode(',',$dateList);
                        ?>
                        <span><?php echo $dataArr[0];if(count($dataArr)>2){echo " ...";}?></span>
                    </p>
                    <p class="data">联系方式：<span><?php echo $val['wPhone'];?></span></p>
                    <p class="btns">
                        <a href="/we-chat-order-list/edit-order?orderNumber=<?=$val['wOrderNumber'];?>" class="btn btn01" id="xg">编辑修改</a>
                        <a href="javascript:;" class="btn btn02" id="qx" onclick="overOrder('<?php echo $val['wOrderNumber']?>')">取消定制</a>
                    </p>
                </div>
            </div>
     <?php }else{ ?>
    <div class="box clearfix"">
        <div class="pic fl">
            <a href="/wechat-user-info/trip-list?userSign=<?php echo $val['wRelativeSign']?>" class="userPic"><img src="<?php echo $val['headImg']?>"></a>
            <p class="name"><?php echo $val['nickName'];?></p>
        </div>
        <div class="details fr" id="details">
            <p class="data">目的地：<span><?php echo $val['wOrderSite'];?></span></p>
            <p class="data">日期：
                <?php
                $dateList=$val['wOrderTimeList'];
                $dataArr=explode(',',$dateList);
                ?>
                <span><?php echo $dataArr[0];if(count($dataArr)>2){echo " ...";}?></span>
            </p>
            <?php if($val['wStatus']>\common\entity\WeChatOrderList::STATUS_PROCESSED){?>
            <p class="data">随友电话：<span class="colGreen"><?php echo $val['phone'];?></span></p>
            <?php }?>
            <p class="data">负责人电话：<span class="colGreen"><?php echo $val['phone'];?></span></p>
            <p class="money">总价：<span>￥<?php echo $val['wMoney'] ?></span></p>
            <a href="/we-chat-order-list/order-info?orderNumber=<?php echo $val['wOrderNumber'];?>" class="seen">查看行程</a>

        </div>
    </div>

        <?php }?>
    <?php }?>
    </div>
</div>
</div>


<script>

    $(document).ready(function(){
        //$(".box.clearfix").bind('click', clickKb);
    });
    function clickKb(e){
        alert($(e.target)[0].id );return;
        if($(e.target)[0].id !="xg"&&$(e.target)[0].id !="qx"){
            window.location.href="/we-chat-order-list/order-info?orderNumber="+$(e.target)[0].id ;
        }
            return;
    }

    function overOrder(orderNumber)
    {
        $.ajax({
            url :'/we-chat-order-list/over-order',
            type:'post',
            data:{
                o:orderNumber
            },
            error:function(){
                alert("结束订购异常");
            },
            success:function(data){
                data=eval("("+data+")");
                if(data.status==1){
                    alert(data.data);
                    setTimeout(function(){location.reload()},1000);
                }else if(data.status==-3){
                    window.location.href=data.data;
                }else{
                    alert(data.data);
                }
            }
        });
    }
    function deleteOrder(orderNumber)
    {
        $.ajax({
            url :'/we-chat-order-list/delete-order',
            type:'post',
            data:{
                orderNumber:orderNumber
            },
            error:function(){
                alert("删除订购异常");
            },
            success:function(data){
                data=eval("("+data+")");
                if(data.status==1){
                    alert(data.data);
                    setTimeout(function(){location.reload()},1000);
                }else if(data.status==-3){
                    window.location.href=data.data;
                }else{
                    alert(data.data);
                }
            }
        });
    }
</script>

</body>
</html>
