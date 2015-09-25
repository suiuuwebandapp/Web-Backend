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
                    系统回答
                </h4>
            </div>
            <div class="modal-body">
                <div  style="height: 500px;overflow: scroll">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet-body form">
                                <!-- BEGIN FORM-->
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="show_info_div">
                                                <label class="control-label col-md-4">提问用户:</label>
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
                                    <div class="row">
                                        <!--/span-->
                                        <div class="col-md-12">
                                            <label class=" col-md-4">系统回答:</label>
                                            <div class="col-md-12">
                                               <textarea id="sysAnswer" class="col-md-12"><?= isset($answer[0]["aContent"])?$answer[0]["aContent"]:""?></textarea>
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
                <button type="button" id="sumb" onclick="subAnswer()" class="btn default">
                    保存
                </button>
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
function subAnswer()
{
    var answer = $("#sysAnswer").val();
    $.ajax({
        url: "/qa/sys-answer",
        type: 'post',
        data: {
            id: "<?=$id ?>",
            answer: answer
        },
        error: function () {
            //hide load
            alert("保存异常");
        },
        success: function (data) {
            //hide load
           var  data = eval("(" + data + ")");
            if(data.status==1)
            {
                alert("保存成功，请关闭！");
            }else
            {
                alert(data.data);
            }
        }
    });
}
</script>
</body>
</html>