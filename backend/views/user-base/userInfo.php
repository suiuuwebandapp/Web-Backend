<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/6/5
 * Time : 下午1:30
 * Email: zhangxinmailvip@foxmail.com
 */
?>


<?php
    if($userInfo!=null){
        $userBase=$userInfo['userBase'];
        $userPublisher=$userInfo['userPublisher'];
    }else{
        throw new \yii\base\Exception("无效的用户信息");
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
                    用户详情
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
                                            <div class="show_info_div">
                                                <label class="control-label col-md-4">昵称:</label>
                                                <div class="col-md-8">
                                                    <p class="form-control-static"><?=empty($userBase['nickname'])?'暂无数据':$userBase['nickname']?></p>
                                                </div>
                                            </div>
                                            <div class="show_info_div">
                                                <label class="control-label col-md-4">手机:</label>
                                                <div class="col-md-8">
                                                    <p class="form-control-static"><?=empty($userBase['phone'])?'暂无数据':$userBase['phone']?></p>
                                                </div>
                                            </div>
                                            <div class="show_info_div">
                                                <label class="control-label col-md-4">邮箱:</label>
                                                <div class="col-md-8">
                                                    <p class="form-control-static"><?=empty($userBase['email'])?'暂无数据':$userBase['email']?></p>
                                                </div>
                                            </div>
                                            <div class="show_info_div">
                                                <label class="control-label col-md-4">性别:</label>
                                                <div class="col-md-8">
                                                    <p class="form-control-static"><?=$userBase['sex']?></p>
                                                </div>
                                            </div>
                                            <div class="show_info_div">
                                                <label class="control-label col-md-4">生日:</label>
                                                <div class="col-md-8">
                                                    <p class="form-control-static"><?=$userBase['birthday']?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <label class="control-label col-md-2"></label>
                                            <img src="<?=$userBase['headImg']?>" width="140px" style="border-radius: 100px !important"/>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                                <label class="control-label col-md-4">昵称:</label>
                                                <div class="col-md-8">
                                                    <p class="form-control-static"><?=$userBase['nickname']?></p>
                                                </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                                <label class="control-label col-md-4">性别:</label>
                                                <div class="col-md-8">
                                                    <p class="form-control-static"><?=$userBase['sex']?></p>
                                                </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                                <label class="control-label col-md-4">手机:</label>
                                                <div class="col-md-8">
                                                    <p class="form-control-static"><?=$userBase['phone']?></p>
                                                </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                                <label class="control-label col-md-4">邮箱:</label>
                                                <div class="col-md-8">
                                                    <p class="form-control-static"><?=$userBase['email']?></p>
                                                </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <h3 class="form-section">Address</h3>
                                    <div class="row">
                                        <div class="col-md-6">
                                                <label class="control-label col-md-4">Address:</label>
                                                <div class="col-md-8">
                                                    <p class="form-control-static">
                                                        #24 Sun Park Avenue, Rolton Str
                                                    </p>
                                                </div>
                                        </div>
                                        <div class="col-md-6">
                                                <label class="control-label col-md-4">Address:</label>
                                                <div class="col-md-8">
                                                    <p class="form-control-static">
                                                        #24 Sun Park Avenue, Rolton Str
                                                    </p>
                                                </div>
                                        </div>
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