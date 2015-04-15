<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/10
 * Time : 下午2:06
 * Email: zhangxinmailvip@foxmail.com
 */


?>
<link rel="stylesheet" type="text/css" href="/assets/global/plugins/data-tables/DT_bootstrap.css" />
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN SAMPLE TABLE PORTLET-->
        <div class="portlet light bg-inverse">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-list font-red-sunglo"></i>
                    <span class="caption-subject font-red-sunglo bold uppercase">影视列表</span>
                            <span class="caption-helper">
                                电影，电视剧，微电影，NBA录像
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
                            <input type="text" name="search" class="input-xlarge" placeholder="请输入影视名称">
                                    <span class="input-group-btn">
                                        <button id="search" class="btn green-meadow" type="button">搜索</button>
                                    </span>
                        </div>
                        <div class="pull-right">
                            <a id="addMovie" href="javascript:" class="btn green-meadow"><i class="fa fa-plus"></i> 添加影视</a>
                        </div>
                    </form>
                </div>

                <table id="table_list" class="table table-hover">
                    <thead class="flip-content">
                    <tr>
                        <th>编号</th>
                        <th>中文名</th>
                        <th>英文名</th>
                        <th>时长</th>
                        <th>上映时间</th>
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


<script type="text/javascript" src="/assets/global/plugins/data-tables/jquery.dataTables.js"></script>
<script type="text/javascript" src="/assets/global/plugins/data-tables/DT_bootstrap.js"></script>
<script type="text/javascript" src="/assets/admin/pages/scripts/table-ajax.js" ></script>

<script type="text/javascript">

    $(document).ready(function() {
        var tableInfo = {
            'formObj'  :'#datatables_form',
            'tableDiv' :'#table_div',
            'tableObj' :'#table_list',
            'tableUrl' :'/article/article-list',
            'tableData':{},
            'tableOrder':[4,'asc'],
            'tableColumn':[
                {"targets": [0],"data": "number","bSortable": false},
                {"targets": [1],"data": "chineseName","bSortable": false},
                {"targets": [2],"data": "englishName","bSortable": false},
                {"targets": [3],"data": "mins","bSortable": false},
                {"targets": [4],"data": "showTime","bSortable": false},
                {
                    "targets": [5],
                    "data": "status",
                    "bSortable": false,
                    "width":"100px",
                    "render": function(data, type, full) {
                        var html='';
                        if(data=="ONLINE"){
                            html='<span class="label label-success">&nbsp;上&nbsp;线&nbsp;</span>'
                        }else{
                            html='<span class="label label-default">&nbsp;下&nbsp;线&nbsp;</span>';
                        }
                        return html;
                    }
                },
                {
                    "targets": [6],
                    "data": "id",
                    "bSortable": false,
                    "width":"400px",
                    "render": function(data, type, full) {
                        var html='';
                        if(full.status!="ONLINE"){
                            html +='<a href="javascript:;" onclick="changeStatus(\''+data+'\',\''+full.status+'\')" class="btn default btn-xs green-meadow"><i class="fa fa-check-circle"></i> 上线</a>&nbsp;&nbsp;';
                        }else{
                            html +='<a href="javascript:;" onclick="changeStatus(\''+data+'\',\''+full.status+'\')" class="btn default btn-xs"><i class="fa fa-ban"></i> 下线</a>&nbsp;&nbsp;';
                        }
                        html +='<a href="javascript:;" onclick="editMovie(\''+data+'\')" class="btn default btn-xs blue-madison"><i class="fa fa-edit"></i> 详情</a>&nbsp;&nbsp;';
                        html +='<a href="javascript:;" onclick="editMovieDetail(\''+data+'\')" class="btn default btn-xs blue-madison"><i class="fa fa-edit"></i> 图片</a>&nbsp;&nbsp;';
                        html +='<a href="javascript:;" onclick="editMovieDownload(\''+data+'\')" class="btn default btn-xs blue-madison"><i class="fa fa-edit"></i> 下载</a>&nbsp;&nbsp;';
                        html +='<a href="javascript:;" onclick="editMovieRole(\''+data+'\')" class="btn default btn-xs blue-madison"><i class="fa fa-edit"></i> 角色</a>&nbsp;&nbsp;';
                        html +='<a href="javascript:;" onclick="deleteMovie(\''+data+'\')" class="btn default btn-xs red-sunglo"><i class="fa fa-trash-o"></i> 删除</a>';
                        return html;
                    }
                }
            ]
        };
        TableAjax.init(tableInfo);
        $("#addMovie").bind("click",function(){
            parentMain.changeLocationUrl(addMovieUrlId);
        });
        $("#refresh,#search").bind("click",function(){
            TableAjax.refresh();
        });
    });

    function editMovie(id){
        parentMain.changeLocationUrl(editMovieUrlId,'movieId='+id);
    }
    function editMovieDetail(id){
        parentMain.changeLocationUrl(editMovieDetailUrlId,'movieId='+id);
    }
    function editMovieDownload(id){
        parentMain.changeLocationUrl(editMovieDownloadUrlId,'movieId='+id);
    }
    function editMovieRole(id){
        parentMain.changeLocationUrl(editMovieRoleUrlId,'movieId='+id);
    }

    function deleteMovie(id){
        parentMain.confirmTip("确认要删除此数据吗？",function(){
            $.ajax({
                type:"POST",
                url:"${base}/sys/movieInfo/delete",
                data:{
                    movieId:id
                },beforeSend:function(){
                    parentMain.showWait("#table_list");
                },
                error:function(){
                    parentMain.errorTip("系统异常");
                },
                success:function(data){
                    parentMain.hideWait("#table_list");
                    if(data=="success"){
                        TableAjax.deleteRefresh();
                        parentMain.successTip("删除电影成功");
                    }else{
                        parentMain.errorTip("删除失败");
                    }
                }
            });
        });
    }


    function changeStatus(id,status){
        var url="";
        if(status=="ONLINE"){
            url="${base}/sys/movieInfo/outline";
        }else{
            url="${base}/sys/movieInfo/online";
        }
        $.ajax({
            type:"POST",
            url:url,
            data:{
                movieId:id
            },beforeSend:function(){
                parentMain.showWait("#table_list");
            },
            error:function(){
                parentMain.errorTip("系统异常");
            },
            success:function(data){
                parentMain.hideWait("#table_list");
                if(data=="success"){
                    TableAjax.refresh();
                    parentMain.successTip("改变影片状态成功");
                }else{
                    parentMain.errorTip("操作失败");
                }
            }
        });
    }
</script>