<script>
    var sessionId = '<?=session_id()?>';
    function newMessage(messageInfo)
    {
        $("#left_msg").attr("class","tip");
    }
</script>
<?php if(!empty($userObj)&&!empty($userObj->userSign)){
    ?>
    <script type="text/javascript" src="/assets/other/weixin/js/ws.js"></script>
<?php }?>
<?php if(!isset($userObj->userSign)){
    $userObj=new \common\entity\UserBase();
    $active=7;
    $newMsg="";
}
?>
<div id="menu" class="navBar">
    <ul>
        <li class="user">
            <a href="<?= empty($userObj->userSign)?"javascript:;":"/wechat-user-info/user-info?userSign=$userObj->userSign" ?>" class="userPic"><img src="<?= $userObj->headImg?$userObj->headImg:'/assets/other/weixin/images/logo02.png'?>"></a>
            <span class="userName"><?= $userObj->nickname?></span>
        </li>
        <li><a href="/wechat-trip" <?php if($active==1){echo 'class="active"';}?>>首页</a></li>
        <li><a href="/we-chat-order-list/order-manage"<?php if($active==2){echo 'class="active"';}?> >我的定制</a></li>
        <li><a href="/wechat-user-center/my-order" <?php if($active==3){echo 'class="active"';}?> >我的订单</a></li>
        <li ><a  href="/wechat-user-center/get-user-remind" <?php if($active==4){echo 'class="active"';}?> ><b id="left_msg" <?php if($newMsg){echo 'class="tip"';}?>></b> 我的消息</a></li>
        <?php if($userObj->isPublisher==1){?>
            <li><a href="/wechat-user-center/my-trip" <?php if($active==5){echo 'class="active"';}?>>我的随游</a></li>
        <?php }?>
        <?php if($userObj->isPublisher==1){?>
            <li><a href="/wechat-user-center/trip-order" <?php if($active==6){echo 'class="active"';}?> >随游订单</a></li>
        <?php }?>
        <?php if(empty($userObj->userSign)){?>
            <li><a href="/we-chat/login" <?php if($active==7){echo 'class="active"';}?>>登录</a></li>
        <?php }else{?>
            <li><a href="/wechat-user-info/setting" <?php if($active==8){echo 'class="active"';}?>>设置</a></li>
            <li><a href="/we-chat/logout">退出</a></li>
        <?php }?>
    </ul>
</div>