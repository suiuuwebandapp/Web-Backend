<?php
$wechat=false;
$sina=false;
$qq=false;
foreach($access as $val){
    if($val["type"]==\common\entity\UserAccess::ACCESS_TYPE_WECHAT){
        $wechat=true;
    }
    if($val["type"]==\common\entity\UserAccess::ACCESS_TYPE_QQ){
        $qq=true;
    }
    if($val["type"]==\common\entity\UserAccess::ACCESS_TYPE_SINA_WEIBO){
        $sina=true;
    }
}
?>
    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        <p class="navTop">账号绑定</p>
        <!--<a href="javascript:;" class="sures" onclick="submitUserInfo()">确定</a>-->
    </div>
    <div class="con cshezhi_bangding clearfix">
        <ul class="list clearfix">
            <?php if(!$wechat){?>
            <li onclick="to('/wechat-user-info/connect-wechat')">
            <?php }else{?>
            <li>
                <?php }?>
                <b class="icon weixin <?php if($wechat){echo "active";}?>"></b>
                <span>微信</span>
            </li>
            <?php if(!$sina){?>
            <li onclick="to('/wechat-user-info/connect-weibo')">
            <?php }else{?>
            <li>
             <?php }?>
                <b class="icon sina <?php if($sina){echo "active";}?>"></b>
                <span>新浪微博</span>
            </li>
            <li>
                <b class="icon qq <?php if($qq){echo "active";}?>"></b>
                <span>QQ</span>
            </li>
        </ul>



    </div>
    <form style="display: none" action="/wechat-user-info/update-user-info" method="post" id="userInfo" >
        <input name="qq" id="val_sub">
        <input type="hidden" name="_csrf" value="<?php echo Yii::$app->request->getCsrfToken()?>">
    </form>
<script>
    function to(url)
    {
        window.location.href=url;
    }
    function submitUserInfo()
    {
        var val= $("#val_info").val();
        if(val=="")
        {alert("请输入QQ");return;}
        $("#val_sub").val(val);
        $("#userInfo").submit();
    }
</script>