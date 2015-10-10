
    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        <p class="navTop">申请退款</p>
    </div>
<div class="con Remoney clearfix">
    <img src="/assets/other/weixin/images/logo02.png" class="logo">
    <p>请提交审核资料,稍后我们会与您联系</p>
    <label>订单手机号</label>
    <input id="tk_phone" type="">
    <label>退款理由</label>
    <textarea id="tk_reason"></textarea>
    <a href="javascript:;" class="btn" onclick="applyRefund()">提交申请</a>

</div>
<script>
    var isClick=false;
    function applyRefund()
    {
        var orderNumber='<?php echo $orderNumber?>';
        var refundReason=$('#tk_reason').val();
        var phone=$('#tk_phone').val();
        if(phone=="")
        {
            alert('手机号不能为空');
            return;
        }
        if(orderNumber=="")
        {
            alert('订单号不能为空');
            return;
        }
        if(refundReason=="")
        {
            alert('申请原因不能为空');
            return;
        }
        if(isClick)
        {
            alert("申请中...");
            return;
        }
        isClick=true;
        $.ajax({
            url :'/we-chat-order-list/apply-refund',
            type:'post',
            data:{
                orderNumber:orderNumber,
                _csrf: $('input[name="_csrf"]').val(),
                refundReason:refundReason,
                phone:phone
            },
            error:function(){
                isClick=false
                alert("申请退款异常");
            },
            success:function(data){
                isClick=false
                data=eval("("+data+")");
                if(data.status==1){
                    window.location.href= '<?php echo Yii::$app->params['weChatUrl'];?>'+'/we-chat-order-list/refund-success';
                }else if(data.status==-3){
                    window.location.href=data.data;
                }else{
                    alert(data.data);
                }
            }
        });
    }
</script>
