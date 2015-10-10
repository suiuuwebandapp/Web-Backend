
    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        <p class="navTop">签名</p>
        <a href="javascript:;" class="sures" onclick="submitUserInfo()">确定</a>
    </div>
    <div class="con cshezhi_ziliaoSet clearfix">
        <textarea id="val_info" type="text"><?=$userInfo["intro"]?></textarea>
    </div>
    <form style="display: none" action="/wechat-user-info/update-user-info" method="post" id="userInfo" >
        <textarea id="val_sub" name="intro"  type="text"><?=$userInfo["intro"]?></textarea>
        <input type="hidden" name="_csrf" value="<?php echo Yii::$app->request->getCsrfToken()?>">
    </form>
<script>
    function submitUserInfo()
    {
        var val= $("#val_info").val();
        if(val=="")
        {alert("请输入签名");return;}
        $("#val_sub").html(val);
        $("#userInfo").submit();
    }
</script>