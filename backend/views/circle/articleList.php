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
                    <span class="caption-subject font-red-sunglo bold uppercase">圈子列表</span>
                            <span class="caption-helper">文章列表
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
                            <input type="text" name="searchText" class="input-xlarge" placeholder="请输入标题或用户昵称 或圈子">
                            <label class="col-md-1 control-label" style="text-align: right;padding: 3px">状态：</label>
                            <select name="status" class="form-control input-medium" >
                                <option value="1">正常</option>
                                <option value="2">已删除</option>
                                <option value="0">全部</option>
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
                        <th>封皮</th>
                        <th>评论数</th>
                        <th>类型</th>
                        <th>创建人</th>
                        <th>所属主题</th>
                        <th>所属地区</th>
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
            'tableUrl' :'/circle/get-article-list',
            'tableData':{},
            'tableOrder':[],
            'tableColumn':[
                {"targets": [0],"data": "articleId",
                    "width":"150px","bSortable": false},
                {
                    "targets": [1],
                    "data": "aTitle",
                    "width":"150px",
                    "bSortable": false
                },
                {
                    "targets": [2],
                    "data": "aImg",
                    "bSortable": false,
                    "width":"150px",
                    "render": function(data, type, full) {
                        if(data!=""&&data!=null){
                            return '<a  class="titleImgGroup"  href="'+data+'"><img alt="" src="'+data+'" style="max-height:50px;"/></a>'
                        }else
                        {
                            return "暂无封皮";
                        }
                    }
                },
                {
                    "targets": [3],
                    "data": "aCmtCount",
                    "width":"150px",
                    "bSortable": false
                },
                {
                    "targets": [4],
                    "data": "aType",
                    "bSortable": false,
                    "width":"150px",
                    "render": function(data, type, full) {
                        switch (data)
                        {
                            case "1":
                                return "随拍";
                                break;
                            case "2":
                                return "随问";
                                break;
                            case "3":
                                return "随记";
                                break;
                            default :
                                return "未知";
                        }
                    }
                },
                {
                    "targets": [5],
                    "data": "nickname",
                    "width":"100px",
                    "bSortable": false
                },
                {
                    "targets": [6],
                    "data": "ztName",
                    "width":"100px",
                    "bSortable": false
                },
                {
                    "targets": [7],
                    "data": "dqName",
                    "width":"100px",
                    "bSortable": false
                },
                {
                    "targets": [8],
                    "data": "aStatus",
                    "width":"100px",
                    "bSortable": false,
                    "render": function(data, type, full) {
                        switch (data)
                        {
                            case "1":
                                return "正常";
                                break;
                            case "2":
                                return "被删除";
                                break;
                        }
                    }
                },
                {
                    "targets": [9],
                    "data": "articleId",
                    "bSortable": false,
                    "width":"200px",
                    "render": function(data, type, full) {
                        var html='';

                        html +='<a href="<?php echo Yii::$app->params["suiuu_url"]."/circle/web-info?infoId="?>'+data+'" target="_blank"  class="btn default btn-xs blue-madison"><i class="fa fa-edit"></i> 查看</a>&nbsp;&nbsp;';
                        html +='<a href="javascript:;" onclick="deleteArticle(\''+data+'\')" class="btn default btn-xs red-sunglo"><i class="fa fa-trash-o"></i> 删除</a>';
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

    function deleteArticle(id){

        Main.confirmTip("确认要删除此数据吗？",function(){
            $.ajax({
                type:"POST",
                url:"/circle/delete-article",
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