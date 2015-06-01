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
                    <span class="caption-subject font-red-sunglo bold uppercase">推荐列表</span>
                            <span class="caption-helper">推荐列表
                            </span>
                </div>
                <div class="actions">
                    <a id="refresh" class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
                        <i class=" icon-refresh"></i>
                    </a>
                </div>
            </div>
            <div class="portlet-body flip-scroll" id="table_div">
                <div class="table-info-form">
                    <form id="datatables_form" onsubmit="return false;">
                        <div class="input-group input-xlarge pull-left">
                            <input type="text" name="searchText" class="input-xlarge" placeholder="请输入订单号 或 用户昵称 ">

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
                        <th>推荐编号</th>
                        <th>推荐类型</th>
                        <th>背景图片</th>
                        <th>状态</th>
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
            'tableUrl' :'/wechat-order-refund/get-list',
            'tableData':{},
            'tableOrder':[],
            'tableColumn':[
                {"targets": [0],"data": "orderNumber",
                    "width":"150px","bSortable": false},
                {
                    "targets": [1],
                    "data": "refundTime",
                    "width":"150px",
                    "bSortable": false
                },
                {
                    "targets": [2],
                    "data": "refundReason",
                    "bSortable": false,
                    "width":"150px",
                    "render": function(data, type, full) {
                        if(data==null)
                        {
                            return "";
                        }
                        return data.length<10?data:data.substring(0,10);
                    }
                },
                {
                    "targets": [3],
                    "data": "nickName",
                    "bSortable": false,
                    "width":"150px",
                    "render": function(data, type, full) {
                        if(data==null)
                        {
                            return "";
                        }
                        return data.substring(0,10);
                    }
                },
                {
                    "targets": [4],
                    "data": "money",
                    "width":"100px",
                    "bSortable": false
                },
                {
                    "targets": [5],"data": "updateReason","bSortable": false,"width":"180px",
                    "render": function(data, type, full) {
                        if(data==null)
                        {
                            return "";
                        }
                        return data.length<10?data:data.substring(0,10);
                    }
                },
                {"targets": [6],"data": "rName",
                    "width":"150px","bSortable": false,
                    "render": function(data, type, full) {
                        return data;
                    }
                },
                {
                    "targets": [7],
                    "data": "status",
                    "bSortable": false,
                    "render": function(data, type, full) {
                        var html='';
                        if(data==1){
                            html='<span class="label label-success">&nbsp;未处理&nbsp;</span>'
                        }else if(data==2) {
                            html='<span class="label label-default">&nbsp;拒绝退款&nbsp;</span>';
                        }else if(data==3) {
                            html='<span class="label label-default">&nbsp;已经退款&nbsp;</span>';
                        }else {
                            html='<span class="label label-default">&nbsp;未知&nbsp;</span>';
                        }
                        return html;
                    }
                },
                {
                    "targets": [8],
                    "data": "orderNumber",
                    "bSortable": false,
                    "width":"200px",
                    "render": function(data, type, full) {
                        var html='';
                        html +='<a href="javascript:;" onclick="editOrder(\''+data+'\')" class="btn default btn-xs blue-madison"><i class="fa fa-edit"></i> 编辑</a>&nbsp;&nbsp;';
                        html +='<a href="javascript:;" onclick="deleteOrder(\''+data+'\')" class="btn default btn-xs red-sunglo"><i class="fa fa-trash-o"></i> 删除</a>';
                        return html;
                    }
                }
            ],
            'fnDrawCallBack': function(settings, json) {
                $(".titleImgGroup").colorbox({'close':''});
            }
        };
        TableAjax.init(tableInfo);

        $("#refresh,#search").bind("click",function(){
            TableAjax.refresh();
        });
    });

    function editOrder(id){
        Main.refreshContentAjax("/wechat-order-refund/edit?o="+id);
    }

    function deleteOrder(id){

        Main.confirmTip("确认要删除此数据吗？",function(){
            $.ajax({
                type:"POST",
                url:"/wechat-order-refund/delete-refund",
                data:{
                    orderNumber:id
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