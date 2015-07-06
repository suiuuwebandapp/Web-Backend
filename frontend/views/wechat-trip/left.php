<div id="menu" class="navBar">
    <ul>
        <li class="user">
            <a href="#" class="userPic"><img src="<?= $userObj->headImg?>"></a>
            <span class="userName"><?= $userObj->nickname?></span>
        </li>
        <li><a href="/wechat-trip" <?php if($active==1){echo 'class="active"';}?>>首页</a></li>
        <li><a href="/we-chat-order-list/order-manage"<?php if($active==2){echo 'class="active"';}?> >我的定制</a></li>
        <li><a href="/wechat-user-center/my-order" <?php if($active==3){echo 'class="active"';}?> >我的订单</a></li>
        <li><a href="#" <?php if($active==4){echo 'class="active"';}elseif($newMsg){echo '<b class="tip"></b>';}?> >我的消息</a></li>
        <?php if($userObj->isPublisher==1){?>
            <li><a href="/wechat-user-center/my-trip" <?php if($active==5){echo 'class="active"';}?>>我的随游</a></li>
        <?php }?>
        <?php if($userObj->isPublisher==1){?>
            <li><a href="/wechat-user-center/trip-order" <?php if($active==6){echo 'class="active"';}?> >随游订单</a></li>
        <?php }?>
        <li><a href="/we-chat/logout">退出</a></li>
    </ul>
</div>