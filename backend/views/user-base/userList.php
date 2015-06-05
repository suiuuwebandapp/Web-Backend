<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/6/1
 * Time : 下午5:21
 * Email: zhangxinmailvip@foxmail.com
 */

?>



<link rel="stylesheet" type="text/css" href="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/data-tables/DT_bootstrap.css" />

<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN SAMPLE TABLE PORTLET-->
        <div class="portlet light bg-inverse">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-list font-red-sunglo"></i>
                    <span class="caption-subject font-red-sunglo bold uppercase">用户列表</span>
                            <span class="caption-helper">
                                所有用户列表，基本信息
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
                            <input type="text" name="searchText" class="input-xlarge" placeholder="请输入用户昵称、手机或邮箱">
                                    <span class="input-group-btn">
                                        <button id="search" class="btn green-meadow" type="button">搜索</button>
                                    </span>
                        </div>
                        <div class="pull-right">
                        </div>
                    </form>
                </div>

                <table id="table_list" class="table table-hover">
                    <thead class="flip-content">
                    <tr>
                        <th>编号</th>
                        <th>昵称</th>
                        <th>手机</th>
                        <th>邮箱</th>
                        <th>注册时间</th>
                        <th>随友</th>
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




<script type="text/javascript">

    $(document).ready(function() {
        var tableInfo = {
            'formObj'  :'#datatables_form',
            'tableDiv' :'#table_div',
            'tableObj' :'#table_list',
            'tableUrl' :'/user-base/user-list',
            'tableData':{},
            'tableOrder':[],
            'tableColumn':[
                {"targets": [0],"data": "userId","bSortable": false,"width":"50px"},
                {
                    "targets": [1],
                    "data": "nickname",
                    "bSortable": false,
                    "render": function(data, type, full) {
                        return data.length<20?data:data.substring(0,20);
                    }
                },
                {"targets": [2],"data": "phone","bSortable": false,"width":"180px"},
                {"targets": [3],"data": "email","bSortable": false,"width":"200px"},
                {"targets": [4],"data": "registerTime","bSortable": false,"width":"180px"},
                {
                    "targets": [5],
                    "data": "isPublisher",
                    "bSortable": false,
                    "width":"100px",
                    "render": function(data, type, full) {
                        var html='';
                        if(data==1){
                            html='<span class="label label-info">&nbsp;随&nbsp;友&nbsp;</span>'
                        }
                        return html;
                    }
                },
                {
                    "targets": [6],
                    "data": "status",
                    "bSortable": false,
                    "width":"100px",
                    "render": function(data, type, full) {
                        var html='';
                        if(data==1){
                            html='<span class="label label-success">&nbsp;正&nbsp;常&nbsp;</span>'
                        }else if(data==2){
                            html='<span class="label label-default">&nbsp;禁&nbsp;用&nbsp;</span>';
                        }
                        return html;
                    }
                },
                {
                    "targets": [7],
                    "data": "userSign",
                    "bSortable": false,
                    "width":"200px",
                    "render": function(data, type, full) {
                        var html='';
                        html +='<a href="javascript:;" onclick="showUserInfo(\''+full.userSign+'\')" class="btn default btn-xs blue-hoki"><i class="fa fa-cog"></i> 查看</a>&nbsp;&nbsp;';
                        if(full.status==1){
                            html +='<a href="javascript:;" onclick="changeStatus(\''+data+'\',\''+full.status+'\')" class="btn default btn-xs"><i class="fa fa-ban"></i> 禁用</a>&nbsp;&nbsp;';
                        }else if(full.status==2){
                            html +='<a href="javascript:;" onclick="changeStatus(\''+data+'\',\''+full.status+'\')" class="btn default btn-xs green-meadow"><i class="fa fa-check-circle"></i> 启用</a>&nbsp;&nbsp;';
                        }
                        return html;
                    }
                }
            ]
        };
        TableAjax.init(tableInfo);


        $("#addDes").bind("click",function(){
            Main.openModal("/destination/to-add-des")
        });


        $("#refresh,#search").bind("click",function(){
            TableAjax.refresh();
        });
    });

    var showUserInfo=function(userSign){
        Main.openModal("/user-base/to-user-info?id="+userSign);
    }

    /**
     * 改变用户状态
     * @param id
     * @param status
     */
    var changeStatus=function(id,status){
        var url="";
        if(status==1){
            url="/destination/outline";
        }else{
            url="/destination/online";
        }
        $.ajax({
            type:"POST",
            url:url,
            data:{
                destinationId:id
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
                    Main.refrenshTableCurrent();
                    Main.successTip("改变状态成功");
                }else{
                    Main.errorTip("操作失败");
                }
            }
        });
    }
