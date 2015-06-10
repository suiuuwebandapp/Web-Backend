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
                    <span class="caption-subject font-red-sunglo bold uppercase">定制列表</span>
                            <span class="caption-helper">
                                微信定制、定制旅行
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
                        <div class="col-md-12 input-group ">
                            <input type="text" name="searchText" class="input-xlarge" placeholder="请输入订单号 或 用户昵称 或 手机号" >
                            <label class="col-md-1 control-label" style="text-align: right;padding: 3px">状态</label>
                                <select name="status" class="form-control input-medium" >
                                    <option value="0">全部</option>
                                    <option value="1">未处理</option>
                                    <option value="2">未支付</option>
                                    <option value="3">已支付</option>
                                    <option value="4">已游玩</option>
                                    <option value="5">申请退款中</option>
                                    <option value="6">退款结束</option>
                                </select>
                             <span class="input-group-btn" >
                                        <button id="search" class="btn green-meadow" type="button">搜索</button>
                            </span>
                        </div>
                    </form>
                </div>
                <table id="table_list" class="table table-hover">
                    <thead class="flip-content">
                    <tr>
                        <th>订单号</th>
                        <th>城市</th>
                        <th>时间</th>
                        <th>人数</th>
                        <th>金钱</th>
                        <th>用户</th>
                        <th>手机</th>
                        <th>负责人</th>
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
            'tableUrl' :'/wechat-order/get-list',
            'tableData':{},
            'tableOrder':[],
            'tableColumn':[
                {"targets": [0],"data": "wOrderNumber",
                    "width":"120px","bSortable": false},
                {
                    "targets": [1],
                    "data": "wOrderSite",
                    "width":"120px",
                    "bSortable": false,
                    "render": function(data, type, full) {
                        if(data==null)
                        {
                            return "";
                        }
                        return data.length<10?data:data.substring(0,10);
                    }
                },
                {
                    "targets": [2],
                    "data": "wOrderTimeList",
                    "width":"120px",
                    "bSortable": false,
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
                    "data": "wUserNumber",
                    "bSortable": false,
                    "width":"50px",
                    "render": function(data, type, full) {
                        if(data==null)
                        {
                            return "";
                        }
                        return data.length<10?data:data.substring(0,10);
                    }
                },

                {"targets": [4],"data": "wMoney","bSortable": false,"width":"50px",
                    "render": function(data, type, full) {
                        return data;
                    }
                },
                {"targets": [5],"data": "nickName","bSortable": false,
                    "width":"80px",
                    "render": function(data, type, full) {
                        if(data==null)
                        {
                            return "";
                        }
                        return data.length<10?data:data.substring(0,10);
                    }
                },
                {"targets": [6],"data": "wPhone","bSortable": false,
                    "width":"80px"
                },
                {"targets": [7],"data": "rNickName","bSortable": false,
                    "width":"80px",
                    "render": function(data, type, full) {
                        if(data==null)
                        {
                            return "";
                        }
                        return data.length<10?data:data.substring(0,10);
                    }
                },
                {
                    "targets": [8],
                    "data": "wStatus",
                    "bSortable": false,
                    "width":"100px",
                    "render": function(data, type, full) {
                        var html='';
                        if(data==1){
                            html='<span class="label label-success">&nbsp;未处理&nbsp;</span>'
                        }else if(data==2) {
                            html='<span class="label label-default">&nbsp;未支付&nbsp;</span>';
                        }else if(data==3) {
                            html='<span class="label label-default">&nbsp;已支付&nbsp;</span>';
                        }else if(data==4) {
                            html='<span class="label label-default">&nbsp;游玩结束&nbsp;</span>';
                        }else if(data==5) {
                            html='<span class="label label-success">&nbsp;申请退款中&nbsp;</span>';
                        }else if(data==6) {
                            html='<span class="label label-default">&nbsp;退款结束&nbsp;</span>';
                        }else if(data==7) {
                            html='<span class="label label-default">&nbsp;拒绝退款&nbsp;</span>';
                        }
                        return html;
                    }
                },
                {
                    "targets": [9],
                    "data": "wOrderNumber",
                    "bSortable": false,
                    "width":"300px",
                    "render": function(data, type, full) {
                        var html='';

                        html +='<a href="javascript:;" onclick="editOrder(\''+data+'\')" class="btn default btn-xs blue-madison"><i class="fa fa-edit"></i> 编辑</a>&nbsp;&nbsp;';
                        html +='<a href="javascript:;" onclick="deleteOrder(\''+data+'\')" class="btn default btn-xs red-sunglo"><i class="fa fa-trash-o"></i> 删除</a>&nbsp;&nbsp;';
                        if(full['wStatus']==3){
                            html +='<a href="javascript:;" onclick="confirmOrder(\''+data+'\')" class="btn default btn-xs blue-madison"><i class="fa fa-edit"></i> 结束</a>';
                        }
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

    function confirmOrder(id)
    {
        Main.confirmTip("确认要结束此订单吗？",function(){
            $.ajax({
                type:"POST",
                url:"/wechat-order/over-order",
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
                        Main.successTip("结束订单成功");
                    }else{
                        Main.errorTip("结束订单失败");
                    }
                }
            });
        });
    }
    function editOrder(id){
        Main.refreshContentAjax("/wechat-order/edit?o="+id);
    }

    function deleteOrder(id){
        Main.confirmTip("确认要删除此数据吗？",function(){
            $.ajax({
                type:"POST",
                url:"/wechat-order/delete-order",
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