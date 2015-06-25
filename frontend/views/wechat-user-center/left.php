<div id="menu" class="navBar">
    <ul>
        <li class="user">
            <a href="#" class="userPic"><img src="<?= $userObj->headImg?>"></a>
            <span class="userName"><?= $userObj->nickname?></span>

        </li>
        <?php if($userObj->isPublisher==1){?>
        <li><a href="#" <?php if($active==1){echo 'class="active"';}?>>我的随游</a></li>
        <?php }?>
        <li><a href="#"<?php if($active==2){echo 'class="active"';}?> >我的定制</a></li>
        <li><a href="#" <?php if($active==3){echo 'class="active"';}?> >我的订单</a></li>
        <?php if($userObj->isPublisher==1){?>
        <li><a href="#" <?php if($active==4){echo 'class="active"';}?> >随游订单</a></li>
        <?php }?>
        <li><a href="#" <?php if($active==5){echo 'class="active"';}elseif($newMsg){echo '<b class="tip"></b>';}?> >收件箱</a></li>
        <li><a href="#">退出</a></li>
    </ul>
</div>