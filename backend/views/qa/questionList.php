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
                    <span class="caption-subject font-red-sunglo bold uppercase">问答管理</span>
                            <span class="caption-helper">问题列表
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
                            <input type="text" name="searchText" class="input-xlarge" placeholder="请输入问题编号、标题或用户昵称">
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
                        <th>标题</th>
                        <th>位置</th>
                        <th>创建人</th>
                        <th>标签</th>
                        <th>时间</th>
                        <th>pv数</th>
                        <th>回答数</th>
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
            'tableUrl' :'/qa/get-qa-list',
            'tableData':{},
            'tableOrder':[],
            'tableColumn':[
                {"targets": [0],"data": "qId",
                    "width":"150px","bSortable": false},
                {
                    "targets": [1],
                    "data": "qTitle",
                    "width":"150px",
                    "bSortable": false,
                    "render": function(data, type, full) {
                        return data;
                        if(data==null)
                        {
                            return "";
                        }
                        return data.length<50?data:data.substring(0,50);
                    }
                },
                {
                    "targets": [2],
                    "data": "qAddr",
                    "bSortable": false,
                    "width":"150px"
                },
                {
                    "targets": [3],
                    "data": "nickname",
                    "width":"150px",
                    "bSortable": false
                },
                {
                    "targets": [4],
                    "data": "qTag",
                    "bSortable": false,
                    "width":"150px"
                },
                {
                    "targets": [5],
                    "data": "qCreateTime",
                    "width":"100px",
                    "bSortable": false
                },
                {
                    "targets": [6],
                    "data": "pvNumber",
                    "width":"100px",
                    "bSortable": false
                },
                {
                    "targets": [7],
                    "data": "aNumber",
                    "width":"100px",
                    "bSortable": false
                },
                {
                    "targets": [8],
                    "data": "qId",
                    "bSortable": false,
                    "width":"100px",
                    "render": function(data, type, full) {
                        var html='';
                        html+='<div class="btn-group">';
                        html+='<button class="btn btn btn-success btn-sm dropdown-toggle" type="button" data-toggle="dropdown">&nbsp;&nbsp;设置&nbsp;&nbsp;<i class="fa fa-angle-down"></i></button>';
                        html+='<ul class="dropdown-menu" role="menu">';
                        html +='<li><a href="javascript:;"  onclick="showInfo(\''+data+'\')" ><i ></i> 查看</a></li>';
                        html +='<li><a href="javascript:;" onclick="answer(\''+data+'\')" ><i ></i> 回答</a></li>';
                        html +='<li><a href="javascript:;" onclick="deleteQ(\''+data+'\')" ><i ></i> 删除</a></li>';
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
        /*$("#addRe").bind("click",function(){
         Main.openModal("/circle/show-add")
         });*/

        $("#refresh,#search").bind("click",function(){
            TableAjax.refresh();
        });
    });

    function answer(id)
    {
        Main.openModal("/qa/sys-answer?id="+id);
    }

    function showInfo(id){
        Main.openModal("/qa/to-info?id="+id);
    }

    function deleteQ(id){

        Main.confirmTip("确认要删除此数据吗？",function(){
            $.ajax({
                type:"POST",
                url:"/qa/delete-question",
                data:{
                    id:id
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