<?php
?>
<link rel="stylesheet" type="text/css"
      href="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/data-tables/DT_bootstrap.css"
      xmlns="http://www.w3.org/1999/html"/>
<link rel="stylesheet" type="text/css" href="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/jquery-colorbox/colorbox.css" />

<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN SAMPLE TABLE PORTLET-->
        <div class="portlet light bg-inverse">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-list font-red-sunglo"></i>
                    <span class="caption-subject font-red-sunglo bold uppercase">版本管理</span>
                            <span class="caption-helper">版本列表
                            </span>
                </div>
                <div class="actions">
                    <a id="refresh" class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
                        <i class=" icon-refresh"></i>
                    </a>
                </div>
            </div>
            <div class="portlet-body flip-scroll" id="table_div">
                <div class="table-info-form ">
                    <form id="datatables_form" onsubmit="return false;">
                        <div class="col-md-8 input-group ">
                            <input type="text" name="searchText" class="input-xlarge" placeholder="请输入问题编号、标题或用户昵称">
                                    <span class="input-group-btn">
                                        <button id="search" class="btn green-meadow" type="button">搜索</button>
                                    </span>
                        </div>
                    </form>
                </div>
                <table id="table_list" class="table table-hover">
                    <thead class="flip-content">
                    <tr>
                        <th>编号</th>
                        <th>手机串号</th>
                        <th>类型</th>
                        <th>版本号</th>
                        <th>创建时间</th>
                        <th>更新时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!-- END SAMPLE TABLE PORTLET-->
    </div>
</div>

<!-- END DASHBOARD STATS -->
<div class="clearfix"></div>


<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/data-tables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/data-tables/DT_bootstrap.js"></script>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/admin/pages/scripts/table-ajax.js" ></script>

<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/jquery-colorbox/jquery.colorbox-min.js"></script>




<script type="text/javascript">

    $(document).ready(function() {
        var tableInfo = {
            'formObj'  :'#datatables_form',
            'tableDiv' :'#table_div',
            'tableObj' :'#table_list',
            'tableUrl' :'/version/get-list',
            'tableData':{},
            'tableOrder':[],
            'tableColumn':[
                {"targets": [0],"data": "id",
                    "width":"150px","bSortable": false},
                {
                    "targets": [1],
                    "data": "appId",
                    "width":"150px",
                    "bSortable": false
                },
                {
                    "targets": [2],
                    "data": "clientType",
                    "bSortable": false,
                    "width":"150px",
                    "render": function(data, type, full) {
                        if(data=="androidPhone")
                        {
                            return "安卓手机";
                        }
                        return data;
                    }
                },
                {
                    "targets": [3],
                    "data": "versionId",
                    "width":"150px",
                    "bSortable": false,
                    "render": function(data, type, full) {
                        return data+"."+full.versionMini;

                    }
                },
                {
                    "targets": [4],
                    "data": "createTime",
                    "bSortable": false,
                    "width":"150px"
                },
                {
                    "targets": [5],
                    "data": "updateTime",
                    "width":"100px",
                    "bSortable": false
                },
                {
                    "targets": [6],
                    "data": "id",
                    "bSortable": false,
                    "width":"200px",
                    "render": function(data, type, full) {
                        var html='';

                        html +='<a href="javascript:;"  onclick="showInfo(\''+data+'\')" class="btn default btn-xs blue-madison"><i class="fa fa-edit"></i> 查看</a>&nbsp;&nbsp;';
                        html +='<a href="javascript:;" onclick="answer(\''+data+'\')" class="btn default btn-xs red-sunglo"><i class="fa fa-trash-o"></i> 回答</a>&nbsp;&nbsp;';
                        html +='<a href="javascript:;" onclick="deleteQ(\''+data+'\')" class="btn default btn-xs red-sunglo"><i class="fa fa-trash-o"></i> 删除</a>';
                        return "";
                    }
                }
            ],
            'fnDrawCallBack': function(settings, json) {
                $(".titleImgGroup").colorbox({'close':''});
            }
        };
        TableAjax.init(tableInfo);
        /*$("#addRe").bind("click",function(){
         Main.openModal("/circle/show-add")
         });*/

        $("#refresh,#search").bind("click",function(){
            TableAjax.refresh();
        });
    });

    function deleteQ(id){

        Main.confirmTip("确认要删除此数据吗？",function(){
            $.ajax({
                type:"POST",
                url:"/qa/delete-question",
                data:{
                    id:id
                },beforeSend:function(){
                    Main.showWait("#table_list");
                },
                error:function(){
                    Main.errorTip("系统异常");
                },
                success:function(data){
                    data=eval("("+data+")");
                    Main.hideWait("#table_list");
                    if(data.status==1){
                        TableAjax.deleteRefresh();
                        Main.successTip("删除成功");
                    }else{
                        Main.errorTip("删除失败");
                    }
                }
            });
        });
    }
</script>