<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/6/16
 * Time : 下午4:14
 * Email: zhangxinmailvip@foxmail.com
 */

?>
<style type="text/css">
    .table-info-form select{
        margin-left: 20px !important;
    }
</style>

<link rel="stylesheet" type="text/css" href="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/data-tables/DT_bootstrap.css" />
<input type="hidden" id="nowTime" value="<?=time()?>"/>
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN SAMPLE TABLE PORTLET-->
        <div class="portlet light bg-inverse">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-list font-red-sunglo"></i>
                    <span class="caption-subject font-red-sunglo bold uppercase">体现列表</span>
                            <span class="caption-helper">
                                用户提现记录
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
                    <form id="cash_form" onsubmit="return false;">
                        <div class="col-md-12 input-group">
                            <input type="text" name="searchText" class="input-xlarge form-control input-medium" placeholder="请输入用户昵称 或 手机号" />
                            <select name="status" class="form-control input-medium">
                                <option value="">请选择状态</option>
                                <option value="<?=\common\entity\UserCashRecord::USER_CASH_RECORD_STATUS_WAIT?>">等待打款</option>
                                <option value="<?=\common\entity\UserCashRecord::USER_CASH_RECORD_STATUS_SUCCESS?>">打款成功</option>
                                <option value="<?=\common\entity\UserCashRecord::USER_CASH_RECORD_STATUS_FAIL?>">打款失败</option>
                            </select>
                            <select name="type" class="form-control input-medium">
                                <option value="">请选择类型</option>
                                <option value="<?=\common\entity\UserAccount::USER_ACCOUNT_TYPE_WECHAT?>">微信</option>
                                <option value="<?=\common\entity\UserAccount::USER_ACCOUNT_TYPE_ALIPAY?>">支付宝</option>
                            </select>
                            <span class="input-group-btn" >
                                <button id="search" class="btn green-meadow" type="button">搜索</button>
                            </span>
                        </div>
                    </form>
                </div>
                <table id="cash_list" class="table table-hover">
                    <thead class="flip-content">
                    <tr>
                        <th>流水号</th>
                        <th>用户</th>
                        <th>手机</th>
                        <th>金额</th>
                        <th>申请日期</th>
                        <th>打款日期</th>
                        <th>类型</th>
                        <th>剩余天数</th>
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
            'formObj'  :'#cash_form',
            'tableDiv' :'#cash_div',
            'tableObj' :'#cash_list',
            'tableUrl' :'/user-account/cash-list',
            'tableData':{},
            'tableOrder':[],
            'tableColumn':[
                {"targets": [0],"data": "cashNumber","width":"120px","bSortable": false},
                {
                    "targets": [1],
                    "data": "nickname",
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
                {"targets": [2],"data": "phone","bSortable": false,"width":"50px"},
                {"targets": [3],"data": "money","bSortable": false,"width":"50px"},
                {"targets": [4],"data": "createTime","bSortable": false,"width":"150px"},
                {
                    "targets": [5],
                    "data": "finishTime",
                    "width":"150px",
                    "bSortable": false,
                    "render": function(data, type, full) {
                        return data==""?"尚未打款":data;
                    }
                },
                {
                    "targets": [6],
                    "data": "type",
                    "width":"80px",
                    "bSortable": false,
                    "render": function(data, type, full) {
                        return data==1?"微信":"支付宝";
                    }
                },
                {
                    "targets": [7],
                    "data": "createTime",
                    "width":"80px",
                    "bSortable": false,
                    "render": function(data, type, full) {
                        if(full.status!=UserCashRecordType.USER_CASH_RECORD_STATUS_WAIT){
                            return '<span class="label label-success">&nbsp;已处理&nbsp;</span>';
                        }else{
                            var nowTime=$("#nowTime").val();
                            var str=data.split(" ")[0];
                            str = str.replace(/-/,'/'); // 将-替换成/，因为下面这个构造函数只支持/分隔的日期字符串
                            var date = new Date(str);
                            var createTime=date.getTime()/1000;
                            var d=Math.ceil((parseInt(nowTime)-parseInt(createTime))/(60*60*24));
                            if(d>=USER_CASH_CYCLE){
                                return '<span class="label label-danger">&nbsp;已超期&nbsp;</span>';
                            }else if(d-USER_CASH_CYCLE==1){
                                return '<span class="label label-danger">&nbsp;剩余1天&nbsp;</span>';
                            }else{
                                return '<span class="label label-success">&nbsp;剩余'+(d-USER_CASH_CYCLE)+'天&nbsp;</span>';
                            }
                        }

                    }
                },
                {
                    "targets": [8],
                    "data": "status",
                    "bSortable": false,
                    "width":"100px",
                    "render": function(data, type, full) {
                        var html='';
                        if(data==UserCashRecordType.USER_CASH_RECORD_STATUS_WAIT){
                            html='<span class="label label-default">&nbsp;等待打款&nbsp;</span>'
                        }else if(data==UserCashRecordType.USER_CASH_RECORD_STATUS_SUCCESS) {
                            html='<span class="label label-success">&nbsp;打款成功&nbsp;</span>';
                        }else if(data==UserCashRecordType.USER_CASH_RECORD_STATUS_FAIL) {
                            html='<span class="label label-danger">&nbsp;打款失败&nbsp;</span>';
                        }
                        return html;
                    }
                },
                {
                    "targets": [9],
                    "data": "cashId",
                    "bSortable": false,
                    "width":"100px",
                    "render": function(data, type, full) {
                        var html='';
                        html +='<a href="javascript:;" onclick="showCashInfo(\''+data+'\')" class="btn default btn-xs blue-madison"><i class="fa fa-search-plus"></i> 查看详情</a>&nbsp;&nbsp;';
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

    function showCashInfo(id)
    {
        Main.openModal("/user-account/get-cash-info?cashId="+id);
    }

</script>