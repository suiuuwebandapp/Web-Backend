<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/6/17
 * Time : 上午9:40
 * Email: zhangxinmailvip@foxmail.com
 */

?>


<?php
if($orderInfo!=null){
    $info=$orderInfo['info'];
    $user=$orderInfo['user'];
    $publisher=$orderInfo['publisher'];
    $travelInfo=json_decode($info['tripJsonInfo'],true);
}else{
    throw new \yii\base\Exception("无效的订单信息");
}
?>

<!DOCTYPE html>
<head>
</head>
<body>
<form onsubmit="return false"  id=form_validate class="form-horizontal" novalidate="novalidate">
    <div class="modal-dialog" style="width: 700px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="false"></button>
                <h4 class="modal-title">
                    订单详情
                </h4>
            </div>
            <div class="modal-body">
                <div class="scroller" style="height: 400px" data-always-visible="1" data-rail-visible1="1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet-body form">
                                <!-- BEGIN FORM-->
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="control-label col-md-4">订单号:</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"><?=$info['orderNumber']?></p>
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <label class="control-label col-md-4">创建日期:</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"><?=$info['status']?></p>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="control-label col-md-4">随游:</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"><a target="_blank" href="<?php echo Yii::$app->params['suiuu_url']?>/view-trip/info?trip=<?=$travelInfo['info']['tripId']?>"><?=$travelInfo['info']['title']?></a></p>
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <label class="control-label col-md-4">订单金额:</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"><?=$info['totalPrice']?></p>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="control-label col-md-4">用户昵称:</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"><?=$user['nickname']?></p>
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <label class="control-label col-md-4">用户手机:</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"><?=$user['phone']?></p>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="control-label col-md-4">:</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"><?=$user['nickname']?></p>
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <label class="control-label col-md-4">用户邮箱:</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"><?=$user['email']?></p>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <?php
                                    if(isset($publisher)){
                                        var_dump($publisher);exit;
                                    }
                                    ?>
                                    <hr/><!-- 随友信息 -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="control-label col-md-4">注册IP:</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"><?=$info['orderNumber']?></p>
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <label class="control-label col-md-4">登录IP:</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"><?=$info['orderNumber']?></p>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                </div>
                                <!-- END FORM-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="modal_close" data-dismiss="modal" class="btn default">
                    关闭
                </button>
            </div>
        </div>
    </div>
</form>
<!-- END PAGE LEVEL STYLES -->

<script type="text/javascript">
    jQuery(document).ready(function() {
    });

</script>
</body>
</html>