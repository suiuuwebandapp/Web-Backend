<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/6/16
 * Time : 下午4:14
 * Email: zhangxinmailvip@foxmail.com
 */

?>

<link rel="stylesheet" type="text/css" href="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/data-tables/DT_bootstrap.css"/>
<link rel="stylesheet" type="text/css" href="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/jquery-colorbox/colorbox.css" />
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
                    <span class="caption-subject font-red-sunglo bold uppercase">退款申请</span>
                    <span class="caption-helper">
                        退款申请列表
                    </span>
                </div>
                <div class="actions">
                    <a id="refresh" class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
                        <i class=" icon-refresh"></i>
                    </a>
                </div>
            </div>
            <div class="portlet-body flip-scroll" id="refund_apply_div">
                <div class="table-info-form">
                    <form id="refund_apply_form" onsubmit="return false;">
                        <div class="col-md-12 input-group ">
                            <input type="text" name="searchText" class="input-xlarge" placeholder="请输入订单号 或 用户昵称 或 手机号" >
                            <select name="status" class="form-control input-medium" >
                                <option value="">请选择退款审核进度</option>
                                <option value="0">待审核</option>
                                <option value="1">审核通过</option>
                                <option value="2">审核不通过</option>
                                <option value="3">打款成功</option>

                            </select>
                            <span class="input-group-btn" >
                                <button id="search" class="btn green-meadow" type="button">搜索</button>
                            </span>
                        </div>
                    </form>
                </div>
                <table id="refund_apply_list" class="table table-hover">
                    <thead class="flip-content">
                    <tr>
                        <th>订单号</th>
                        <th>用户</th>
                        <th>手机</th>
                        <th>随游</th>
                        <th>价格</th>
                        <th>申请日期</th>
                        <th>处理日期</th>
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

<div class="modal fade modal-scrollable modal-scroll in" id="applyInfo" tabindex="-1" role="basic" aria-hidden="false" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">退款申请详情</h4>
            </div>
            <div class="modal-body">
                <div id="modalApplyContent"></div>
                <br/>
                <div class="form-group">
                    <label class="label label-danger">如果拒绝，请填写拒绝原因</label>
                    <textarea id="refuseRefundApplyContent" class="form-control" rows="4" style="margin-top: 10px;"></textarea>
                    <input type="hidden" id="divRefundApplyId" value="" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnAgree" class="btn blue" onclick="agreeRefundApply()">同意</button>
                <button type="button" id="btnRefuse" class="btn blue" onclick="refuseRefundApply()">拒绝</button>
                <button type="button" class="btn default" data-dismiss="modal" onclick="javascript:closeApplyInfo();">关闭</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/data-tables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/data-tables/DT_bootstrap.js"></script>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/admin/pages/scripts/table-ajax.js" ></script>

<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/jquery-colorbox/jquery.colorbox-min.js"></script>




<script type="text/javascript">

    $(document).ready(function() {

        var tableInfo = {
            'formObj'  :'#refund_apply_form',
            'tableDiv' :'#refund_apply_div',
            'tableObj' :'#refund_apply_list',
            'tableUrl' :'/trip-order/get-refund-apply-list',
            'tableData':{},
            'tableOrder':[],
            'tableColumn':[
                {
                    "targets": [0],
                    "data": "orderNumber",
                    "bSortable": false,
                    "width":"120px",
                    "render": function(data, type, full) {
                        var html='';
                        html +='<a href="javascript:;" onclick="showOrder(\''+full.orderId+'\')">'+data+'</a>&nbsp;&nbsp;';
                        return html;
                    }
                },
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
                        html='<a title="'+data.info.title+'" href="'+UrlManager.getTripInfoUrl(tripId)+'" target="_blank">'+title+'</a>';
                        return html;
                    }
                },
                {"targets": [4],"data": "totalPrice","bSortable": false,"width":"80px"},
                {"targets": [5],"data": "applyTime","bSortable": false,"width":"150px"},
                {
                    "targets": [6],
                    "data": "replyTime",
                    "width":"150px",
                    "bSortable": false,
                    "render": function(data, type, full) {
                        return Main.isNotEmpty(data)?data:"未处理";
                    }
                },
                {
                    "targets": [7],
                    "data": "status",
                    "bSortable": false,
                    "width":"100px",
                    "render": function(data, type, full) {
                        var html='';
                        if(data==0){
                            html='<span class="label label-success">&nbsp;待审核&nbsp;</span>'
                        }else if(data==1) {
                            html='<span class="label label-default">&nbsp;审核通过&nbsp;</span>';
                        }else if(data==2) {
                            html='<span class="label label-default">&nbsp;审核不同过&nbsp;</span>';
                        }else if(data==3){
                            html='<span class="label label-success">&nbsp;打款成功&nbsp;</span>'
                        }
                        return html;
                    }
                },
                {
                    "targets": [8],
                    "data": "orderId",
                    "bSortable": false,
                    "width":"200px",
                    "render": function(data, type, full) {
                        var html='';
                        html +='<a href="javascript:;" onclick="showApplyInfo(\''+full.refundApplyId+'\','+full.status+')" class="btn default btn-xs blue-madison"><i class="fa  fa-search-plus"></i> 查看申请</a>&nbsp;&nbsp;';
                        if(full.status==1){
                            html +='<a href="javascript:;" onclick="confirmRefund(\''+full.refundApplyId+'\')" class="btn default btn-xs red-sunglo"><i class="fa fa-check-circle-o"></i> 确认打款</a>&nbsp;&nbsp;';
                        }
                        if(full.status==3){
                            html +='<a href="javascript:;" onclick="showRefundInfo(\''+full.refundApplyId+'\')" class="btn default btn-xs blue-madison"><i class="fa fa-exclamation-circle"></i> 退款详情</a>&nbsp;&nbsp;';
                        }
                        html +='<textarea style="display:none" id="applyInfo_'+full.refundApplyId+'">'+full.applyContent+'</textarea>';
                        html +='<textarea style="display:none" id="refundInfo_'+full.refundApplyId+'">'+full.replyContent+'</textarea>';
                        return html;
                    }
                }
            ]
        };
        TableAjax.init(tableInfo);

        $("#refresh,#search").bind("click",function(){
            TableAjax.refresh();
        });
    });

    /**
     * 显示订单详情
     * @param id
     */
    function showOrder(id){
        Main.openModal("/trip-order/info?orderId="+id);
    }

    /**
     * 确认退款
     */
    function confirmRefund(id){
        Main.openModal("/trip-order/to-confirm-refund?refundApplyId="+id);
    }

    /**
     * 显示退款详情
     */
    function showRefundInfo(id){
        Main.openModal("/trip-order/to-refund-info?refundApplyId="+id);
    }

    /**
     * 显示退款申请详情
     * @param refundApplyId
     */
    function showApplyInfo(refundApplyId,status){
        var content=$("#applyInfo_"+refundApplyId).html();
        if(Main.isNotEmpty()){
            content=content.replaceAll("\n", "<br/>");
        }
        $("#modalApplyContent").html(content);
        $("#applyInfo").show();
        $("#divRefundApplyId").val(refundApplyId);
        if(status!=0){
            $("#btnAgree").hide();
            $("#btnRefuse").hide();
        }else{
            $("#btnAgree").show();
            $("#btnRefuse").show();
        }
        if(status==1){
            $("#refuseRefundApplyContent").hide();
        }else{
            $("#refuseRefundApplyContent").show();
            $("#refuseRefundApplyContent").html($("#refundInfo_"+refundApplyId).html());
        }
        if(status==2||status==3){
            $("#refuseRefundApplyContent").attr("readonly","readonly");
        }
        Main.showBackDrop();
    }

    /**
     * 关闭退款申请弹窗
     */
    function closeApplyInfo(){
        $("#modalApplyContent").html("");
        $("#divRefundApplyId").val("");
        $('#applyInfo').hide();
        Main.hideBackDrop();
    }



    /**
     * 同意申请退款
     */
    function agreeRefundApply(){
        var id=$("#divRefundApplyId").val();
        if(id==""){
            Main.errorTip("获取退款申请编号异常");
            return;
        }
        Main.confirmTip("确认要同意退款申请吗？",function(){
            $.ajax({
                type:"POST",
                url:"/trip-order/agree-refund-apply",
                data:{
                    refundApplyId:id
                },beforeSend:function(){
                    Main.showWait("#refund_apply_list");
                },
                error:function(){
                    Main.hideWait("#refund_apply_list");
                    Main.errorTip("系统异常");
                },
                success:function(data){
                    data=eval("("+data+")");
                    Main.hideWait("#refund_apply_list");
                    if(data.status==1){
                        TableAjax.refreshCurrent();
                        closeApplyInfo();
                        Main.successTip("同意退款申请成功");
                    }else{
                        Main.errorTip("同意退款申请失败");
                    }
                }
            });
        });
    }


    /**
     * 拒绝退款申请
     * @param obj
     */
    function refuseRefundApply(){
        var id=$("#divRefundApplyId").val();
        var content=$("#refuseRefundApplyContent").val();
        if(id==""){
            Main.errorTip("获取退款申请编号异常");
            return;
        }
        if(content==""){
            Main.errorTip("请填写拒绝原因");
            return;
        }
        Main.confirmTip("确认要拒绝退款申请吗？",function(){
            $.ajax({
                type:"POST",
                url:"/trip-order/refuse-refund-apply",
                data:{
                    refundApplyId:id,
                    content:content
                },beforeSend:function(){
                    Main.showWait("#refund_apply_list");
                },
                error:function(){
                    Main.hideWait("#refund_apply_list");
                    Main.errorTip("系统异常");
                },
                success:function(data){
                    data=eval("("+data+")");
                    Main.hideWait("#refund_apply_list");
                    if(data.status==1){
                        TableAjax.refreshCurrent();
                        closeApplyInfo();
                        Main.successTip("同意退款申请成功");
                    }else{
                        Main.errorTip("同意退款申请失败");
                    }
                }
            });
        });
    }
</script>