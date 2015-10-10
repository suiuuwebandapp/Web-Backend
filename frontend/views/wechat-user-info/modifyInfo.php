
    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        <p class="navTop">简介</p>
        <a href="javascript:;" class="sures" onclick="submitUserInfo()">确定</a>
    </div>
    <div class="con cshezhi_ziliaoSet clearfix">
        <textarea id="val_info" type="text"><?=$userInfo["info"]?></textarea>
    </div>
    <form style="display: none" action="/wechat-user-info/update-user-info" method="post" id="userInfo" >
        <textarea id="val_sub" name="info"  type="text"><?=$userInfo["info"]?></textarea>
        <input type="hidden" name="_csrf" value="<?php echo Yii::$app->request->getCsrfToken()?>">
    </form>
<script>
    function submitUserInfo()
    {
        var val= $("#val_info").val();
        if(val=="")
        {alert("请输入简介");return;}
        $("#val_sub").html(val);
        $("#userInfo").submit();
    }
</script>