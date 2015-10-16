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
                    <span class="caption-subject font-red-sunglo bold uppercase">志愿产品管理</span>
                    <span class="caption-helper">志愿产品列表</span>
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
                            <input type="text" name="searchText" class="" style="width: 35% !important;margin-right: 20px" placeholder="请输入标题或用户昵称 或国家 城市">
                            <label class="col-md-1 control-label" style="text-align: right;padding: 3px">状态：</label>
                            <select name="status" class="form-control " style="width: 15% !important;">
                                <option value="">正常</option>
                                <option value="<?=\common\entity\VolunteerTrip::VOLUNTEER_STATUS_ONLINE?>">上线</option>
                                <option value="<?=\common\entity\VolunteerTrip::VOLUNTEER_STATUS_OUTLINE?>">下线</option>
                                <option value="<?=\common\entity\VolunteerTrip::VOLUNTEER_STATUS_DELETE?>">已删除</option>
                            </select>
                            <span class="input-group-btn">
                                <button id="search" class="btn green-meadow" type="button">搜索</button>
                            </span>
                        </div>
                        <div class="pull-right">
                            <a id="addVolunteer" href="javascript:" class="btn green-meadow"><i class="fa fa-plus"></i> 添加志愿产品</a>
                        </div>
                    </form>
                </div>
                <table id="table_list" class="table table-hover">
                    <thead class="flip-content">
                    <tr>
                        <th>编号</th>
                        <th>标题</th>
                        <th>国家城市</th>
                        <th>组织名称</th>
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
            'tableUrl' :'/volunteer/get-list',
            'tableData':{},
            'tableOrder':[],
            'tableColumn':[
                {"targets": [0],"data": "volunteerId",
                    "width":"50px","bSortable": false
                },
                {
                    "targets": [1],
                    "data": "title",
                    "width":"150px",
                    "bSortable": false,
                    "render": function(data, type, full) {
                        var html='';
                        html +='<a href="'+UrlManager.getVolunteerInfoUrl(full.volunteerId)+'" target="_blank" >'+data+'</a>';
                        return html;
                    }
                },
                {
                    "targets": [2],
                    "data": "countryCname",
                    "width":"150px",
                    "bSortable": false,
                    "render": function(data, type, full) {
                        return data+"/"+full.cityCname;
                    }
                },
                {
                    "targets": [3],
                    "data": "orgName",
                    "bSortable": false,
                    "width":"150px"
                },
                {
                    "targets": [4],
                    "data": "status",
                    "width":"100px",
                    "bSortable": false,
                    "render": function(data, type, full) {
                        switch (data)
                        {
                            case "1":return '<span class="label label-success">&nbsp;上&nbsp;线&nbsp;</span>';break;
                            case "2":return '<span class="label label-default">&nbsp;下&nbsp;线&nbsp;</span>';break;
                            case "3":return '<span class="label label-danger">&nbsp;已删除&nbsp;</span>';break;
                        }
                    }
                },
                {
                    "targets": [5],
                    "data": "volunteerId",
                    "bSortable": false,
                    "width":"100px",
                    "render": function(data, type, full) {
                        var html='';

                        html+='<div class="btn-group">';
                        html+='<button class="btn btn btn-success btn-sm dropdown-toggle" type="button" data-toggle="dropdown">&nbsp;&nbsp;设置&nbsp;&nbsp;<i class="fa fa-angle-down"></i></button>';
                        html+='<ul class="dropdown-menu" role="menu">';
                        if(full.status==1){
                            html+='<li><a href="javascript:;" onclick="changeStatus('+data+',2)"> 下线</a></li>';
                        }else if(full.status==2){
                            html+='<li><a href="javascript:;" onclick="changeStatus('+data+',1)"> 上线</a></li>';
                        }
                        html+='<li class="divider"></li>';
                        html+='<li><a href="javascript:;" onclick="editVolunteer('+data+')" target="_blank"> 编辑 </a></li>';
                        html+='<li><a href="javascript:;" onclick="changeStatus(\''+data+'\',3)" > 删除 </a></li>';
                        html+='</ul>';
                        html+='</div>';

                        return html;
                    }
                }
            ]
        };
        TableAjax.init(tableInfo);
        $("#refresh,#search").bind("click",function(){
            TableAjax.refresh();
        });

        $("#addVolunteer").on("click",function(){
            addVolunteerTrip()
        });
    });

    function addVolunteerTrip(){
        Main.refresh("/volunteer/to-add");
    }

    function editVolunteer(id){
        Main.refresh("/volunteer/edit?volunteerId="+id)
    }


    function changeStatus(id,status){
        $.ajax({
            type:"POST",
            url:"/volunteer/change-status",
            data:{
                volunteerId:id,
                status:status
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
                    Main.successTip("更新状态成功");
                    TableAjax.refresh();
                }else{
                    Main.errorTip("更新状态失败");
                }
            }
        });
    }

    function deleteHandle(id){
        Main.confirmTip("确认要删除此数据吗？",function(){
            $.ajax({
                type:"POST",
                url:"/trip/delete",
                data:{
                    tripId:id
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