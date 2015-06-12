<?php
use yii\helpers\Html;
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/3/31
 * Time : 下午5:06
 * Email: zhangxinmailvip@foxmail.com
 */
?>
<!-- BEGIN LOGO -->
<div class="logo">
    <img src="<?=Yii::$app->params['res_url'] ?>/assets/admin/layout/img/logo-big.png" alt="" />
</div>
<!-- END LOGO -->
<!-- BEGIN LOGIN -->
<div class="content">
    <!-- BEGIN LOGIN FORM -->
    <?= Html::beginForm('/login/login','post',['class'=>'login-form'])?>

        <input type="hidden" name="returnUrl" value=""/>
        <input type="hidden" name="processUrl" value=""/>

        <h3 class="form-title">Login to your account</h3>
        <?php if($errors!=null&&count($errors)>0){ ?>
            <div class="alert alert-danger">
                <button class="close" data-close="alert"></button>
                <?php foreach($errors as $e ){ ?>
                    <span><?php echo $e; ?></span>
                    <?php break; ?>
                <?php }?>
            </div>
        <?php } ?>
        <div class="form-group">
            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
            <label class="control-label visible-ie8 visible-ie9">Username</label>
            <div class="input-icon">
                <i class="fa fa-user"></i>
                <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="请输入用户名" autofocus name="username" maxlength="50" />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">Password</label>
            <div class="input-icon">
                <i class="fa fa-lock"></i>
                <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="请输入密码" name="password" maxlength="32"/>
            </div>
        </div>
        <?php if($showVerifyCode){ ?>
        <div class="form-group" style="padding-bottom: 20px;">
            <script async type="text/javascript" src="http://api.geetest.com/get.php?gt=b3a60a5dd8727fe814b43fce2ec7412a"></script>
        </div>
        <?php } ?>
        <div class="form-actions">
            <label class="checkbox"> <input type="checkbox" name="remember" value="1"/> 记住密码 </label>
            <button type="submit" class="btn blue pull-right"> 登录 <i class="m-icon-swapright m-icon-white"></i> </button>
        </div>
        <br/>
        <br/>
        <!--
        <div class="forget-password">
            <h4>Forgot your password ?</h4>
            <p> no worries, click <a href="javascript:;"  id="forget-password">here</a> to reset your password. </p>
        </div>
        -->
    <?= Html::endForm()?>
    <!-- END LOGIN FORM -->
    <!-- BEGIN FORGOT PASSWORD FORM -->
    <form class="forget-form" action="index.html" method="post">
        <h3 >Forget Password ?</h3>
        <p>Enter your e-mail address below to reset your password.</p>
        <div class="form-group">
            <div class="input-icon">
                <i class="fa fa-envelope"></i>
                <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email" />
            </div>
        </div>
        <div class="form-actions">
            <button type="button" id="back-btn" class="btn"> <i class="m-icon-swapleft"></i> Back </button>
            <button type="submit" class="btn blue pull-right"> Submit <i class="m-icon-swapright m-icon-white"></i> </button>
        </div>
    </form>
    <!-- END FORGOT PASSWORD FORM -->
</div>
<!-- END LOGIN -->