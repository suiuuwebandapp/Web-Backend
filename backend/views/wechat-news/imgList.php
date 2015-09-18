<!DOCTYPE html>
<head>
    <link rel="stylesheet" type="text/css"
          href="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/data-tables/DT_bootstrap.css"
          xmlns="http://www.w3.org/1999/html"/>
    <link rel="stylesheet" type="text/css" href="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/jquery-colorbox/colorbox.css" />
</head>
<body>
<form onsubmit="return false"  id=form_validate class="form-horizontal" novalidate="novalidate">
    <div class="modal-dialog" style="width: 800px;height: 600px;overflow: scroll">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="false"></button>
                <h4 class="modal-title">
                    请复制对应图片的id
                </h4>
            </div>
            <div class="portlet light bg-inverse">
            <div class="portlet-body flip-scroll" id="table_div">
                <div class="table-info-form">
                    <form id="datatables_form" onsubmit="return false;">
                    </form>
                </div>
                <table id="table_list" class="table table-hover">
                    <thead class="flip-content">
                    <tr>
                        <th>图片</th>
                        <th>media_id</th>
                    </tr>
                    </thead>
                </table>
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
    $(document).ready(function() {
        var tableInfo = {
            'formObj'  :'#datatables_form',
            'tableDiv' :'#table_div',
            'tableObj' :'#table_list',
            'tableUrl' :'/wechat-news/img-list',
            'tableData':{},
            'tableOrder':[],
            'tableColumn':[
                {
                    "targets": [0],
                    "data": "url",
                    "bSortable": false,
                    "render": function(data, type, full) {
                        html='<img src="'+data+'" width="80px" height="80px" >';
                        return html;
                    }
                },
                {"targets": [1],"data": "media_id","bSortable": false,"width":"180px"}

            ]
        };
        TableAjax.init(tableInfo);

    });

</script>
</body>
</html>