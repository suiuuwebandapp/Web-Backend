<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/25
 * Time : 下午2:18
 * Email: zhangxinmailvip@foxmail.com
 */

?>


<div id="finish">
    <div class="pic">
        <img src="/assets/images/ico.png" alt="">

        <p><?= $result ?></p>
    </div>
    <p class="dnav">
        <a href="<?php echo Yii::$app->params['base_dir']; ?>" class="orange">返回首页</a>
        <a href="<?php echo Yii::$app->params['base_dir']; ?>/user-info?userInfo" class="m14 blue">进入个人主页</a>
    </p>
</div>
