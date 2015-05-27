<?php

//echo $jsApiParameters;
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <title>微信安全支付</title>
    <script type="text/javascript" src="/assets/other/weixin/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript">

        //调用微信JS api 支付
        function jsApiCall()
        {
            WeixinJSBridge.invoke(
                'getBrandWCPayRequest',
                <?php echo $jsApiParameters; ?>,
                function(res){
                    WeixinJSBridge.log(res.err_msg);
                    var url ="<?php echo Yii::$app->params['weChatUrl'];?>/we-chat-order-list/order-manage";
                    window.location.href=url;
                }
            );
        }

        function callpay()
        {
            if (typeof WeixinJSBridge == "undefined"){
                if( document.addEventListener ){
                    document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                }else if (document.attachEvent){
                    document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                    document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                }
            }else{
                jsApiCall();
            }
        }
    </script>
</head>
<body>
<?php /*echo $jsApiParameters; */?>
</br></br></br></br>

<!--<div align="center">
    <button style="width:210px; height:30px; background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="button" onclick="callpay()" >贡献一下</button>
</div>-->
</body>
<script>
    $(document).ready(function(){
        callpay();
    });
</script>
</html>