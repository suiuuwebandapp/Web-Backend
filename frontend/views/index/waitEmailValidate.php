<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/27
 * Time : 下午3:19
 * Email: zhangxinmailvip@foxmail.com
 */


?>


<!---------------随友注册完成----------->
<div class="syRegisterT">
    <p>您的随游注册申请已提交成功,我们将会发送邮件到您的注册邮箱，请注意查收。</p>
    <p><span id="time">5</span>秒后自动跳转回随游主页，如页面没有跳转，请点击
        <a href="<?=Yii::$app->params['base_dir']; ?>" style="padding-left: 20px;color:#ffeb81;">返回主页</a>
    </p>
</div>

<script type="text/javascript">

    var time=5;
    window.setInterval(function(){
        time--;
        $("#time").html(time);
        if(time==1){
            window.location.href='<?=Yii::$app->params['base_dir']; ?>';
        }
    },1000)

</script>
