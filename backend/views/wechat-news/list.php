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
                    <span class="caption-subject font-red-sunglo bold uppercase">微信消息</span>
                            <span class="caption-helper">消息列表
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
                        <div class="col-md-8 input-group ">
                            <input type="text" name="searchText" class="input-xlarge" placeholder="请输入标题 或 关键字">
                            <label class="col-md-1 control-label" style="text-align: right;padding: 3px">状态：</label>
                            <select name="status" class="form-control input-medium" style="width: 15% !important;" >
                                <option value="1">上线</option>
                                <option value="2">下线</option>
                                <option value="0">全部</option>
                            </select>
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
                        <th>Tid</th>
                        <th>关键字</th>
                        <th>标题</th>
                        <th>简介</th>
                        <th>封皮</th>
                        <th>内容或url</th>
                        <th>消息类型</th>
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
            'tableUrl' :'/wechat-news/get-list',
            'tableData':{},
            'tableOrder':[],
            'tableColumn':[
                {"targets": [0],"data": "newsId",
                    "width":"50px","bSortable": false},
                {
                    "targets": [1],
                    "data": "nTid",
                    "width":"50px",
                    "bSortable": false
                },
                {
                    "targets": [2],
                    "data": "nAntistop",
                    "bSortable": false,
                    "width":"150px"
                },
                {
                    "targets": [3],
                    "data": "nTitle",
                    "bSortable": false,
                    "width":"150px"
                },
                {
                    "targets": [4],
                    "data": "nIntro",
                    "bSortable": false,
                    "width":"150px",
                    "render": function(data, type, full) {
                        if(data==null)
                        {
                            return "";
                        }
                        return data.length<10?data:data.substring(0,10);
                    }
                },
                {
                    "targets": [5],
                    "data": "nCover",
                    "width":"150px",
                    "bSortable": false,
                    "render": function(data, type, full) {
                        if(data!=""&&data!=null){
                            return '<a  class="titleImgGroup"  href="'+data+'"><img alt="" src="'+data+'" style="max-height:50px;"/></a>'
                        }else
                        {
                            return "暂无背景";
                        }
                    }
                },
                {
                    "targets": [6],
                    "data": "nContent",
                    "bSortable": false,
                    "width":"150px",
                    "render": function(data, type, full) {
                        if(data==null||data=='')
                        {
                            return full.nUrl<20?full.nUrl:full.nUrl.substring(0,20);
                        }
                        return data.length<10?data:data.substring(0,10);
                    }
                },
                {
                    "targets": [7],
                    "data": "nType",
                    "bSortable": false,
                    "width":"150px",
                    "render": function(data, type, full) {
                        switch (data)
                        {
                            case "1":
                                return "文本消息";
                                break;
                            case "2":
                                return "图文消息";
                                break;
                            case "3":
                                return "图片消息";
                                break;
                            case "4":
                                return "学校统计";
                                break;
                            default:
                                return "未知";
                        }
                    }
                },
                {
                    "targets": [8],
                    "data": "nStatus",
                    "bSortable": false,
                    "width":"50px",
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
                    "targets": [9],
                    "data": "newsId",
                    "bSortable": false,
                    "width":"100px",
                    "render": function(data, type, full) {
                        var html='';
                        html+='<div class="btn-group">';
                        html+='<button class="btn btn btn-success btn-sm dropdown-toggle" type="button" data-toggle="dropdown">&nbsp;&nbsp;设置&nbsp;&nbsp;<i ></i></button>';
                        html+='<ul class="dropdown-menu" role="menu">';
                        if(full.nStatus!=1){
                            html +='<li><a href="javascript:;" onclick="changeStatus(\''+data+'\',\''+full.nStatus+'\',\''+full.nAntistop+'\')" ><i ></i> 上线</a></li>';
                        }else{
                            html +='<li><a href="javascript:;" onclick="changeStatus(\''+data+'\',\''+full.nStatus+'\',\''+full.nAntistop+'\')"><i></i> 下线</a></li>';
                        }
                        html +='<li><a href="<?php echo Yii::$app->params["suiuu_url"]."/we-chat/get-news-info?id="?>'+data+'" target="_blank"  ><i></i> 查看</a></li>';
                        html +='<li><a href="javascript:;" onclick="editOrder(\''+data+'\')"><i></i> 编辑</a></li>';
                        html +='<li><a href="javascript:;" onclick="deleteOrder(\''+data+'\')" class="btn default btn-xs red-sunglo"><i class="fa fa-trash-o"></i> 删除</a></li>';
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

    function changeStatus(id,status,nAntistop)
    {
        if(nAntistop.indexOf("禁止修改")!=-1){
            alert('系统内容部使用禁止修改');
            return;
        }
        $.ajax({
            type:"POST",
            url:"/wechat-news/change",
            data:{
                id:id,
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
                    Main.refrenshTableCurrent();
                    Main.successTip("修改成功");
                }else{
                    Main.errorTip("修改失败");
                }
            }
        });

    }
    function editOrder(id){
        Main.goAction("/wechat-news/show-edit?id="+id);
    }

    function deleteOrder(id){

        Main.confirmTip("确认要删除此数据吗？",function(){
            $.ajax({
                type:"POST",
                url:"/wechat-news/delete-news",
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