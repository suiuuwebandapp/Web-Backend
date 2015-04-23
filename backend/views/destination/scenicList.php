<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/21
 * Time : 上午11:08
 * Email: zhangxinmailvip@foxmail.com
 */

?>


<link rel="stylesheet" type="text/css" href="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/data-tables/DT_bootstrap.css" />
<link rel="stylesheet" type="text/css" href="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/jquery-colorbox/colorbox.css" />

<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN SAMPLE TABLE PORTLET-->
        <div class="portlet light bg-inverse">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-list font-red-sunglo"></i>
                    <span class="caption-subject font-red-sunglo bold uppercase">景区列表</span>
                            <span class="caption-helper">
                                <?= $desInfo->title ?>的景区列表
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
                            <input type="text" name="searchText" class="input-xlarge" placeholder="请输入景区名称">
                                    <span class="input-group-btn">
                                        <button id="search" class="btn green-meadow" type="button">搜索</button>
                                    </span>
                        </div>
                        <div class="pull-right">
                            <a id="addDes" href="javascript:" class="btn green-meadow"><i class="fa fa-plus"></i> 添加景区</a>
                        </div>
                    </form>
                </div>

                <table id="table_list" class="table table-hover">
                    <thead class="flip-content">
                    <tr>
                        <th>编号</th>
                        <th>标题</th>
                        <th>封面图</th>
                        <th>国家</th>
                        <th>城市</th>
                        <th>更新时间</th>
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


<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/data-tables/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/data-tables/DT_bootstrap.js"></script>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/admin/pages/scripts/table-ajax.js" ></script>

<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/jquery-colorbox/jquery.colorbox-min.js"></script>




<script type="text/javascript">

    $(document).ready(function() {
        var tableInfo = {
            'formObj'  :'#datatables_form',
            'tableDiv' :'#table_div',
            'tableObj' :'#table_list',
            'tableUrl' :'/destination/des-list',
            'tableData':{},
            'tableOrder':[],
            'tableColumn':[
                {"targets": [0],"data": "destinationId","bSortable": false,"width":"50px"},
                {
                    "targets": [1],
                    "data": "title",
                    "bSortable": false,
                    "render": function(data, type, full) {
                        return data.length<10?data:data.substring(0,10);
                    }
                },
                {
                    "targets": [2],
                    "data": "titleImg",
                    "bSortable": false,
                    "width":"200px",
                    "render": function(data, type, full) {
                        return '<a  class="titleImgGroup"  href="'+data+'"><img alt="" src="'+data+'" style="max-height:50px;"/></a>'
                    }
                },
                {"targets": [3],"data": "countryCname","bSortable": false,"width":"180px"},
                {"targets": [4],"data": "cityCname","bSortable": false,"width":"180px"},
                {"targets": [5],"data": "lastUpdateTime","bSortable": false,"width":"180px"},
                {
                    "targets": [6],
                    "data": "status",
                    "bSortable": false,
                    "width":"100px",
                    "render": function(data, type, full) {
                        var html='';
                        if(data==1){
                            html='<span class="label label-success">&nbsp;上&nbsp;线&nbsp;</span>'
                        }else{
                            html='<span class="label label-default">&nbsp;下&nbsp;线&nbsp;</span>';
                        }
                        return html;
                    }
                },
                {
                    "targets": [7],
                    "data": "destinationId",
                    "bSortable": false,
                    "width":"240px",
                    "render": function(data, type, full) {
                        var html='';
                        if(full.status!=1){
                            html +='<a href="javascript:;" onclick="changeStatus(\''+data+'\',\''+full.status+'\')" class="btn default btn-xs green-meadow"><i class="fa fa-check-circle"></i> 上线</a>&nbsp;&nbsp;';
                        }else{
                            html +='<a href="javascript:;" onclick="changeStatus(\''+data+'\',\''+full.status+'\')" class="btn default btn-xs"><i class="fa fa-ban"></i> 下线</a>&nbsp;&nbsp;';
                        }
                        html +='<a href="javascript:;" onclick="editArticle(\''+data+'\')" class="btn default btn-xs blue-madison"><i class="fa fa-edit"></i> 编辑</a>&nbsp;&nbsp;';
                        html +='<a href="javascript:;" onclick="deleteDes(\''+data+'\')" class="btn default btn-xs red-sunglo"><i class="fa fa-trash-o"></i> 删除</a>';
                        return html;
                    }
                }
            ],
            'fnDrawCallBack': function(settings, json) {
                $(".titleImgGroup").colorbox({'close':''});
            }
        };
        TableAjax.init(tableInfo);


        $("#addDes").bind("click",function(){
            Main.openModal("/destination/to-add-scenic");
        });


        $("#refresh,#search").bind("click",function(){
            TableAjax.refresh();
        });
    });


    function editArticle(id){
        Main.openModal("/destination/to-edit-des?desId="+id);
    }

    function deleteDes(id){
        Main.confirmTip("确认要删除此数据吗？",function(){
            $.ajax({
                type:"POST",
                url:"/destination/delete",
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
                        TableAjax.deleteRefresh();
                        Main.successTip("删除目的地成功");
                    }else{
                        Main.errorTip("删除失败");
                    }
                }
            });
        });
    }
 </script>