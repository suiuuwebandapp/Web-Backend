<script type="text/javascript" src="/assets/other/weixin/js/weixin.js"></script>

<div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        <p class="navTop">工作</p>
        <a href="javascript:;" class="sures" onclick="submitUserInfo()">确定</a>
    </div>
    <div class="con cshezhi_work clearfix">
        <ul class="list clearfix">
            <li  onclick="hiddenInput_('持证导游')"> <input type="radio" name="sex" id="rad01"><label for="rad01">持证导游</label></li>
            <li onclick="hiddenInput_('业余导游')"><input type="radio" name="sex" id="rad02"><label for="rad02" >业余导游</label></li>
            <li onclick="hiddenInput_('学生')" class="last"><input type="radio" name="sex" id="rad03"><label for="rad03" >学生</label></li>
            <li onclick="hiddenInput_('旅游爱好者')"> <input type="radio" name="sex" id="rad04"><label for="rad04" >旅游爱好者</label></li>
            <li  onclick="showInput_()" class="other"><input type="radio" name="sex" id="rad05"><label for="rad05">其他</label></li>
        </ul>
        <input id="val_work" type="text" placeholder="请输入其他职业" style="display: none">
    </div>
    <form style="display: none" action="/wechat-user-info/update-user-info" method="post" id="userInfo" >
        <input name="profession" id="val_sub">
        <input type="hidden" name="_csrf" value="<?php echo Yii::$app->request->getCsrfToken()?>">
    </form>
<script>
    var val="";
    function showInput_()
    {
        $("#val_work").show();
    }
    function hiddenInput_(str)
    {
        val=str;
        $("#val_work").hide();
    }
    function submitUserInfo()
    {
        if(val==""){
        val= $("#val_work").val();
        }
        if(val=="")
        {alert("请输入工作");return;}
        $("#val_sub").val(val);
        $("#userInfo").submit();
    }
</script>