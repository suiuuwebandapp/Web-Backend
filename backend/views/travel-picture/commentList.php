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
                    <span class="caption-subject font-red-sunglo bold uppercase">旅图管理</span>
                            <span class="caption-helper">评论列表
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
                            <input type="text" name="searchText" class="input-xlarge" placeholder="请输入或用户昵称">
                                    <span class="input-group-btn">
                                        <button id="search" class="btn green-meadow" type="button">搜索</button>
                                    </span>
                        </div>
                    </form>
                </div>
                <table id="table_list" class="table table-hover">
                    <thead class="flip-content">
                    <tr>
                        <th>所属旅图</th>
                        <th>内容</th>
                        <th>创建人</th>
                        <th>时间</th>
                        <th>点赞数</th>
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
            'tableUrl' :'/travel-picture/get-comment-list',
            'tableData':{},
            'tableOrder':[],
            'tableColumn':[
                {"targets": [0],"data": "tpId",
                    "width":"150px","bSortable": false},
                {
                    "targets": [1],
                    "data": "comment",
                    "width":"150px",
                    "bSortable": false
                },
                {
                    "targets": [2],
                    "data": "nickname",
                    "bSortable": false,
                    "width":"150px"
                },
                {
                    "targets": [3],
                    "data": "createTime",
                    "width":"150px",
                    "bSortable": false
                },
                {
                    "targets": [4],
                    "data": "supportCount",
                    "bSortable": false,
                    "width":"150px"
                },
                {
                    "targets": [5],
                    "data": "id",
                    "bSortable": false,
                    "width":"200px",
                    "render": function(data, type, full) {
                        var html='';
                        html +='<a href="javascript:;" onclick="deleteC(\''+data+'\',\''+full.tpId+'\')" class="btn default btn-xs red-sunglo"><i class="fa fa-trash-o"></i> 删除</a>';
                        return html;
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


    function showInfo(id){
        Main.openModal("/travel-picture/to-info?id="+id);
    }

    function deleteC(id,tpId){

        Main.confirmTip("确认要删除此数据吗？",function(){
            $.ajax({
                type:"POST",
                url:"/travel-picture/delete-comment",
                data:{
                    id:id,
                    tpId:tpId
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