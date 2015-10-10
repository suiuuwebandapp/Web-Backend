<script type="text/javascript" src="/assets/other/weixin/js/weixin.js"></script>

<div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        <p class="navTop">性别</p>
        <a href="javascript:;" class="sures" onclick="submitUserInfo()">确定</a>
    </div>
    <div class="con cshezhi_sex clearfix">
    <div class="loadBack"></div>
        <ul class="list clearfix">
            <li  onclick="selectSex(1)"> <input type="radio" name="sex" id="rad01"><label for="rad01">男</label></li>
            <li onclick="selectSex(0)"><input type="radio" name="sex" id="rad02"><label for="rad02" >女</label></li>
            <li onclick="selectSex(2)"><input type="radio" name="sex" id="rad03"><label for="rad03" >保密</label></li>
        </ul>
    </div>
    <form style="display: none" action="/wechat-user-info/update-user-info" method="post" id="userInfo" >
        <input name="sex" id="val_sub">
        <input type="hidden" name="_csrf" value="<?php echo Yii::$app->request->getCsrfToken()?>">
    </form>
<script>
    var val="";
    function selectSex(str)
    {
        val=str;
    }
    function submitUserInfo()
    {
        if(val==="")
        {alert("请选择性别");return;}
        $("#val_sub").val(val);
        $("#userInfo").submit();
    }
</script>