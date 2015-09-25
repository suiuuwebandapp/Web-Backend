<!DOCTYPE html>
<head>
    <link rel="stylesheet" type="text/css"
          href="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/data-tables/DT_bootstrap.css"
          xmlns="http://www.w3.org/1999/html"/>
    <link rel="stylesheet" type="text/css" href="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/jquery-colorbox/colorbox.css" />
</head>
<body>
<form onsubmit="return false"  id=form_validate class="form-horizontal" novalidate="novalidate">
    <div class="modal-dialog" style="width: 800px;height: 400px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="false"></button>
                <h4 class="modal-title">
                    详情
                </h4>
            </div>
            <div class="modal-body">
                <div  style="height: 500px;overflow: scroll">
                    <div class="row">
                        <div class="">
                            <div class="portlet-body form">
                                <!-- BEGIN FORM-->
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="show_info_div">
                                                <label class="control-label col-md-4">提问用户:</label>
                                                <div class="col-md-8">
                                                    <p class="form-control-static"><?=empty($info['nickname'])?'暂无数据':$info['nickname']?></p>
                                                </div>
                                            </div>

                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <label class="control-label col-md-2"></label>
                                            <img src="<?=$info['headImg']?>" width="140px" style="border-radius: 100px !important"/>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="control-label col-md-4">地点:</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"><?=$info['qAddr']?></p>
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <label class="control-label col-md-4">创建时间:</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"><?=$info['qCreateTime']?></p>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="control-label col-md-4">回答数:</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"><?=$info['aNumber']?></p>
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <label class="control-label col-md-4">关注数:</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"><?=$info['attentionNumber']?></p>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="control-label col-md-4">pv数:</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"><?=$info['pvNumber']?></p>
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <label class="control-label col-md-4">标签:</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"><?=$info['qTag']?></p>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class=" col-md-4">问题标题:</label>
                                            <div class="col-md-12">
                                                <p class="form-control-static"><?=$info['qTitle']?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!--/span-->
                                        <div class="col-md-12">
                                            <label class=" col-md-4">问题详情:</label>
                                            <div class="col-md-12">
                                                <p class="form-control-static"><?=$info['qContent']?></p>
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
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/data-tables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/data-tables/DT_bootstrap.js"></script>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/admin/pages/scripts/table-ajax.js" ></script>
<script type="text/javascript">

</script>
</body>
</html>