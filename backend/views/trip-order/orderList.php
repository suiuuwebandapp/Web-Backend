<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/6/16
 * Time : 下午4:14
 * Email: zhangxinmailvip@foxmail.com
 */

?>

<link rel="stylesheet" type="text/css" href="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/data-tables/DT_bootstrap.css" />
<style type="text/css">
    .table-info-form select{
        margin-left: 20px !important;
    }
</style>
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN SAMPLE TABLE PORTLET-->
        <div class="portlet light bg-inverse">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-list font-red-sunglo"></i>
                    <span class="caption-subject font-red-sunglo bold uppercase">订单列表</span>
                            <span class="caption-helper">
                                随游订单列表
                            </span>
                </div>
                <div class="actions">
                    <a id="refresh" class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
                        <i class=" icon-refresh"></i>
                    </a>
                </div>
            </div>
            <div class="portlet-body flip-scroll" id="trip_order_div">
                <div class="table-info-form">
                    <form id="trip_order_form" onsubmit="return false;">
                        <div class="col-md-12 input-group ">
                            <input type="text" name="searchText" class="input-xlarge" placeholder="请输入订单号 或 用户昵称 或 手机号" >
                            <select name="status" class="form-control input-medium" >
                                <option value="">请选择订单状态</option>
                                <option value="0">待付款</option>
                                <option value="1">待接单</option>
                                <option value="2">已接单</option>
                                <option value="3">已取消</option>
                                <option value="4">待退款</option>
                                <option value="5">退款成功</option>
                                <option value="6">确认游玩</option>
                                <option value="7">游玩结束</option>
                                <option value="8">退款审核中</option>
                                <option value="9">退款失败</option>
                                <option value="10"></option>
                            </select>
                            <span class="input-group-btn" >
                                <button id="search" class="btn green-meadow" type="button">搜索</button>
                            </span>
                        </div>
                    </form>
                </div>
                <table id="trip_order_list" class="table table-hover">
                    <thead class="flip-content">
                    <tr>
                        <th>订单号</th>
                        <th>用户</th>
                        <th>手机</th>
                        <th>随游</th>
                        <th>价格</th>
                        <th>出行日期</th>
                        <th>创建日期</th>
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
            'formObj'  :'#trip_order_form',
            'tableDiv' :'#trip_order_div',
            'tableObj' :'#trip_order_list',
            'tableUrl' :'/trip-order/get-list',
            'tableData':{},
            'tableOrder':[],
            'tableColumn':[
                {"targets": [0],"data": "orderNumber","width":"120px","bSortable": false},
                {
                    "targets": [1],
                    "data": "userNickname",
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
                {"targets": [2],"data": "userPhone","bSortable": false,"width":"50px"},
                {
                    "targets": [3],
                    "data": "tripJsonInfo",
                    "width":"200px",
                    "bSortable": false,
                    "render": function(data, type, full) {
                        if(data==null)
                        {
                            return "";
                        }
                        var title,tripId;
                        data=$.parseJSON(data);
                        tripId=data.info.tripId;
                        title=data.info.title;
                        title=title.length<20?title:title.substring(0,20);
                        html='<a title="'+data.info.title+'" href="'+FrontUrl.tripUrl+tripId+'" target="_blank">'+title+'</a>';
                        return html;
                    }
                },
                {"targets": [4],"data": "totalPrice","bSortable": false,"width":"80px"},
                {
                    "targets": [5],
                    "data": "beginDate",
                    "width":"150px",
                    "bSortable": false,
                    "render": function(data, type, full) {
                        return data+" "+full.startTime;
                    }
                },
                {"targets": [6],"data": "createTime","bSortable": false,"width":"150px"},
                {
                    "targets": [7],
                    "data": "status",
                    "bSortable": false,
                    "width":"100px",
                    "render": function(data, type, full) {
                        var html='';
                        if(data==0){
                            html='<span class="label label-success">&nbsp;待付款&nbsp;</span>'
                        }else if(data==1) {
                            html='<span class="label label-default">&nbsp;待接单&nbsp;</span>';
                        }else if(data==2) {
                            html='<span class="label label-default">&nbsp;已接单&nbsp;</span>';
                        }else if(data==3) {
                            html='<span class="label label-default">&nbsp;已取消&nbsp;</span>';
                        }else if(data==4) {
                            html='<span class="label label-default">&nbsp;待退款&nbsp;</span>';
                        }else if(data==5) {
                            html='<span class="label label-success">&nbsp;退款成功&nbsp;</span>';
                        }else if(data==6) {
                            html='<span class="label label-default">&nbsp;确认游玩&nbsp;</span>';
                        }else if(data==7) {
                            html='<span class="label label-default">&nbsp;游玩结束&nbsp;</span>';
                        }else if(data==8) {
                            html='<span class="label label-default">&nbsp;退款审核中&nbsp;</span>';
                        }else if(data==9) {
                            html='<span class="label label-default">&nbsp;退款失败&nbsp;</span>';
                        }else if(data==10){
                            html='<span class="label label-default">&nbsp;随友取消订单&nbsp;</span>';
                        }
                        return html;
                    }
                },
                {
                    "targets": [8],
                    "data": "orderId",
                    "bSortable": false,
                    "width":"100px",
                    "render": function(data, type, full) {
                        var html='';
                        html +='<a href="javascript:;" onclick="showOrder(\''+data+'\')" class="btn default btn-xs red-sunglo"><i class="fa fa-trash-o"></i> 查看</a>&nbsp;&nbsp;';
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

    function showOrder(id)
    {
        Main.openModal("/trip-order/info?orderId="+id);
    }
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