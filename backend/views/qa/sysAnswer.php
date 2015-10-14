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
                        <div class="col-md-12" >
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
                                            <label class=" col-md-2 control-label">问题标题:</label>
                                            <div class="col-md-7  valdate right">
                                                <p class="form-control-static"><?=$info['qTitle']?></p>
                                            </div>
                                    </div>
                                    <div class="row">
                                        <!--/span-->
                                            <label class=" col-md-2 control-label">问题详情</label>
                                            <div class="col-md-7 valdate right">
                                                <p class="form-control-static"><?=$info['qContent']?></p>
                                            </div>
                                        <!--/span-->
                                    </div>
                                    <div class="row">
                                        <!--/span-->
                                            <div class="col-md-12">
                                               <div class="portlet grey-cascade box">

                                                    <div class="portlet-title">

                                                        <div class="caption">

                                                            <i class="fa fa-cogs"></i>回答列表

                                                        </div>

                                                    </div>

                                                    <div class="portlet-body">

                                                        <div class="table-responsive">

                                                            <table class="table table-hover table-bordered table-striped">

                                                                <thead>

                                                                <tr>

                                                                    <th>

                                                                        回答人

                                                                    </th>

                                                                    <th>

                                                                       内容

                                                                    </th>



                                                                </tr>

                                                                </thead>

                                                                <tbody>
                                                                <?php foreach($answer as $val){?>
                                                                    <tr>

                                                                        <td class="col-md-2">

                                                                            <?= isset($val["nickname"])?$val["nickname"]:"匿名"?>

                                                                        </td>

                                                                        <td>
                                                                            <?= isset($val["aContent"])?$val["aContent"]:""?>
                                                                        </td>

                                                                    </tr>
                                                                <?php }?>

                                                                </tbody>

                                                            </table>

                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                        <!--/span-->
                                    </div>
                                    <div class="row">
                                        <!--/span-->
                                            <div class="col-md-12">
                                                <label class=" col-md-4">选择回答用户:</label>
                                                <select id="selectUser" class="col-md-2 ">
                                                    <option value="">随小游</option>
                                                    <?php foreach($userRst as $val){?>
                                                        <option value="<?=$val["userSign"]?>"><?=$val['nickname']?></option>
                                                    <?php }?></select>
                                                <textarea id="sysAnswer" class="col-md-12"></textarea>
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
    if(answer=="")
    {
        alert("请填写回答内容");
        return;
    }
    $.ajax({
        url: "/qa/sys-answer",
        type: 'post',
        data: {
            id: "<?=$id ?>",
            answer: answer,
            userSign:$("#selectUser").val()
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