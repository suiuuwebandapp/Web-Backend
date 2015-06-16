<?php

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
                                微信服务号用户
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
                        <th>微信昵称</th>
                        <th>openId</th>
                        <th>唯一Id</th>
                        <th>关注时间</th>
                        <th>网站昵称</th>
                        <th>userSign</th>
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

    function   formatDate(now)   {
        var   year=now.getYear();
        var   month=now.getMonth()+1;
        var   date=now.getDate();
        var   hour=now.getHours();
        var   minute=now.getMinutes();
        var   second=now.getSeconds();
        return   year+"-"+month+"-"+date+"   "+hour+":"+minute+":"+second;
    }
    $(document).ready(function() {
        var tableInfo = {
            'formObj'  :'#datatables_form',
            'tableDiv' :'#table_div',
            'tableObj' :'#table_list',
            'tableUrl' :'/user-base/wechat-user-list',
            'tableData':{},
            'tableOrder':[],
            'tableColumn':[
                {"targets": [0],"data": "v_nickname","bSortable": false,"width":"100px",
                    "render": function(data, type, full) {
                        return decodeURI(data);
                    }
                },
                {
                    "targets": [1],
                    "data": "openId",
                    "bSortable": false
                },
                {"targets": [2],"data": "unionID","bSortable": false,"width":"180px"},
                {"targets": [3],"data": "v_createTime","bSortable": false,"width":"200px"
                },
                {"targets": [4],"data": "nickname","bSortable": false,"width":"180px"},
                {
                    "targets": [5],
                    "data": "userSign",
                    "bSortable": false,
                    "width":"100px"
                },
                {
                    "targets": [6],
                    "data": "userSign",
                    "bSortable": false,
                    "width":"150px",
                    "render": function(data, type, full) {
                        var html='';
                        if(data=="null"||data==null){
                            html='<span class="label label-success">&nbsp;未绑定&nbsp;</span>'
                        }else {
                            html='<span class="label label-default">&nbsp;已绑定&nbsp;</span>';
                        }
                        return html;
                    }
                },
                {
                    "targets": [7],
                    "data": "openId",
                    "bSortable": false,
                    "width":"200px",
                    "render": function(data, type, full) {
                        var html='';
                        html +='<a href="javascript:;" onclick="showUserInfo(\''+data+'\')" class="btn default btn-xs blue-hoki"><i class="fa fa-cog"></i> 查看</a>&nbsp;&nbsp;';
                        if(full.v_subscribe_time=="0"||full.v_subscribe_time==null){
                            html +='<a href="javascript:;" onclick="changeStatus(\''+data+'\')" class="btn default btn-xs"><i class="fa fa-check-circle"></i>更新</a>&nbsp;&nbsp;';
                        }
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

    var showUserInfo=function(userSign){
        Main.errorTip("暂无查看");
        return;
        Main.openModal("/user-base/to-user-info?id="+userSign);
    }


    /**
     * 改变用户状态
     * @param id
     * @param status
     */
    var changeStatus=function(id){
        var url="/user-base/update-wechat-user";
        $.ajax({
            type:"POST",
            url:url,
            data:{
                openId:id
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
                    Main.successTip("更新成功");
                }else{
                    Main.errorTip("操作失败");
                }
            }
        });
    }
</script>
