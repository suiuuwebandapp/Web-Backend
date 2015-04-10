<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['index/active', 'e' => $e,'p'=>$p,'c'=>$c]);
?>
<div class="password-reset">
    <p>Hello <?= Html::encode('随友') ?>,</p>

    <p>点击下面链接，完成注册</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
