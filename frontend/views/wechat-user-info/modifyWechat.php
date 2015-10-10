
    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        <p class="navTop">微信</p>
        <a href="javascript:;" class="sures" onclick="submitUserInfo()">确定</a>
    </div>
    <div class="con cshezhi_ziliaoSet clearfix">
        <input id="val_info" type="text" placeholder="请输入微信号" value="<?=$userInfo["wechat"]?>">
    </div>
    <form style="display: none" action="/wechat-user-info/update-user-info" method="post" id="userInfo" >
        <input name="wechat" id="val_sub">
        <input type="hidden" name="_csrf" value="<?php echo Yii::$app->request->getCsrfToken()?>">
    </form>
<script>
    function submitUserInfo()
    {
        var val= $("#val_info").val();
        if(val=="")
        {alert("请输入微信");return;}
        $("#val_sub").val(val);
        $("#userInfo").submit();
    }
</script>