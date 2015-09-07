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
                    <span class="caption-subject font-red-sunglo bold uppercase">随游管理</span>
                            <span class="caption-helper">随游列表
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
                            <input type="text" name="searchText" class="" style="width: 35% !important;margin-right: 20px" placeholder="请输入标题或用户昵称 或国家 城市">
                            <label class="col-md-1 control-label" style="text-align: right;padding: 3px">状态：</label>
                            <select name="status" class="form-control " style="width: 15% !important;">
                                <option value="1">正常</option>
                                <option value="2">草稿</option>
                                <option value="3">已删除</option>
                                <option value="0">全部</option>
                            </select>
                            <label class="col-md-1 control-label" style="text-align: right;padding: 3px">状态：</label>
                            <select name="type" class="form-control " style="width: 15% !important;">
                                <option value="0">全部</option>
                                <option value="<?=\common\entity\TravelTrip::TRAVEL_TRIP_TYPE_TRAFFIC?>">交通服务</option>
                                <option value="<?=\common\entity\TravelTrip::TRAVEL_TRIP_TYPE_PERSONALITY?>">个性玩法</option>
                                <option value="<?=\common\entity\TravelTrip::TRAVEL_TRIP_TYPE_EXPLORE?>">慢行探索</option>
                            </select>
                            <span class="input-group-btn">
                                <button id="search" class="btn green-meadow" type="button">搜索</button>
                            </span>
                        </div>
                       <!-- <div class="pull-right">
                            <a id="addRe" href="javascript:" class="btn green-meadow"><i class="fa fa-plus"></i> 添加推荐</a>
                        </div>-->
                    </form>
                </div>
                <table id="table_list" class="table table-hover">
                    <thead class="flip-content">
                    <tr>
                        <th>编号</th>
                        <th>标题</th>
                        <th>国家城市</th>
                        <th>创建人</th>
                        <th>基础价格</th>
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
            'tableUrl' :'/trip/get-list',
            'tableData':{},
            'tableOrder':[],
            'tableColumn':[
                {"targets": [0],"data": "tripId",
                    "width":"50px","bSortable": false
                },
                {
                    "targets": [1],
                    "data": "title",
                    "width":"150px",
                    "bSortable": false,
                    "render": function(data, type, full) {
                        var html='';
                        html +='<a href="<?php echo Yii::$app->params["suiuu_url"]."/view-trip/info?trip="?>'+full.tripId+'" target="_blank" >'+data+'</a>';
                        return html;
                    }
                },
                {
                    "targets": [2],
                    "data": "cname",
                    "width":"150px",
                    "bSortable": false,
                    "render": function(data, type, full) {
                        return data+"/"+full.ctName;
                    }
                },
                {
                    "targets": [3],
                    "data": "nickname",
                    "bSortable": false,
                    "width":"150px"
                },
                {
                    "targets": [4],
                    "data": "basePrice",
                    "bSortable": false,
                    "width":"80px"
                },
                {
                    "targets": [5],
                    "data": "status",
                    "width":"100px",
                    "bSortable": false,
                    "render": function(data, type, full) {
                        switch (data)
                        {
                            case "1":
                                return "正常";
                                break;
                            case "2":
                                return "草稿";
                                break;
                            case "3":
                                return "已删除";
                                break;
                        }
                    }
                },
                {
                    "targets": [6],
                    "data": "tripId",
                    "bSortable": false,
                    "width":"100px",
                    "render": function(data, type, full) {
                        var html='';

                        html+='<div class="btn-group">';
                        html+='<button class="btn btn btn-success btn-sm dropdown-toggle" type="button" data-toggle="dropdown">&nbsp;&nbsp;设置&nbsp;&nbsp;<i class="fa fa-angle-down"></i></button>';
                        html+='<ul class="dropdown-menu" role="menu">';
                        html+='<li><a href="javascript:;" onclick="editUserRecommend('+data+')"> 推荐信息 </a></li>';
                        html+='<li><a href="javascript:;" onclick="editTripInfo('+data+')"> 展示设置 </a></li>';
                        html+='<li><a href="javascript:;" onclick="updateTripPublisher('+data+')"> 修改所属人 </a></li>';
                        html+='<li class="divider"></li>';
                        html+='<li><a href="<?php echo Yii::$app->params["suiuu_url"]."/sys/edit-trip?trip="?>'+data+'" target="_blank"> 编辑 </a></li>';
                        html+='<li><a href="javascript:;" onclick="deleteHandle(\''+data+'\')" > 删除 </a></li>';
                        html+='</ul>';
                        html+='</div>';

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


    function editUserRecommend(tripId){
        Main.openModal("/trip/to-edit-user-recommend?tripId="+tripId)
    }

    function editTripInfo(tripId){
        Main.openModal("/trip/to-update-trip-info?tripId="+tripId)
    }

    function updateTripPublisher(tripId) {
        Main.openModal("/trip/to-change-publisher?tripId="+tripId)

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