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
                                                <label class="control-label col-md-4">用户:</label>
                                                <div class="col-md-8">
                                                    <p class="form-control-static"><?=empty($info['nickname'])?'暂无':$info['nickname']?></p>
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
                                                <p class="form-control-static"><?=$info['address']?></p>
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <label class="control-label col-md-4">创建时间:</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"><?=$info['createTime']?></p>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="control-label col-md-4">关注数:</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"><?=$info['attentionCount']?></p>
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <label class="control-label col-md-4">评论数:</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"><?=$info['commentCount']?></p>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class=" col-md-4">标题:</label>
                                            <div class="col-md-12">
                                                <p class="form-control-static"><?=$info['title']?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!--/span-->
                                        <div class="col-md-12">
                                            <label class=" col-md-4">详情:</label>
                                            <div class="col-md-12">
                                                <p class="form-control-static"><?php
                                                    $picNumb=count(json_decode($info['picList'],true));
                                                     $contentsNumb=count(json_decode($info['contents'],true));
                                                    $maxNumb=$picNumb>$contentsNumb?$picNumb:$contentsNumb;
                                                    for($i=0;$i<$maxNumb;$i++)
                                                    {

                                                        $str= isset(json_decode($info['picList'],true)[$i])?json_decode($info['picList'],true)[$i]:"";
                                                        if(!empty($str)){
                                                            echo ' <div class="col-md-12">';
                                                            echo '<img src="'.$str.'" width="240px" style="!important"/>';
                                                            echo '</div>';
                                                        }
                                                        $contents= isset(json_decode($info['contents'],true)[$i])?json_decode($info['contents'],true)[$i]:"";
                                                        if(!empty($contents)){
                                                            echo ' <div class="col-md-12">';
                                                            echo '<p class="form-control-static">'.$contents.'</p>';
                                                            echo '</div>';
                                                        }
                                                    }
                                                    ?></p>
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