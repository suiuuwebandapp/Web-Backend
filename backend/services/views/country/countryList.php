<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/10
 * Time : 下午2:06
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
                    <span class="caption-subject font-red-sunglo bold uppercase">国家列表</span>
                            <span class="caption-helper">
                                国家，城市配置
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
                            <input type="text" name="searchText" class="input-xlarge" placeholder="请输入国家中文或英文名称">
                                    <span class="input-group-btn">
                                        <button id="search" class="btn green-meadow" type="button">搜索</button>
                                    </span>
                        </div>
                        <div class="pull-right">
                            <a id="addCountry" href="javascript:" class="btn green-meadow"><i class="fa fa-plus"></i> 添加国家</a>
                        </div>
                    </form>
                </div>

                <table id="table_list" class="table table-hover">
                    <thead class="flip-content">
                    <tr>
                        <th>编号</th>
                        <th>中文名</th>
                        <th>英文名</th>
                        <th>简称</th>
                        <th>手机代码</th>
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
            'tableUrl' :'/country/country-list',
            'tableData':{},
            'tableOrder':[],
            'tableColumn':[
                {"targets": [0],"data": "id","bSortable": false,"width":"50px"},
                {"targets": [1],"data": "cname","bSortable": false},
                {"targets": [2],"data": "ename","bSortable": false},
                {"targets": [3],"data": "code","bSortable": false},
                {"targets": [4],"data": "areaCode","bSortable": false},
                {
                    "targets": [5],
                    "data": "id",
                    "bSortable": false,
                    "width":"240px",
                    "render": function(data, type, full) {
                        var html='';
                        html +='<a href="javascript:;" onclick="manageCity(\''+data+'\',\''+full.status+'\')" class="btn default btn-xs green-meadow"><i class="fa fa-cog"></i> 管理城市</a>&nbsp;&nbsp;';
                        html +='<a href="javascript:;" onclick="toEditCountry(\''+data+'\')" class="btn default btn-xs blue-madison"><i class="fa fa-edit"></i> 编辑</a>&nbsp;&nbsp;';
                        html +='<a href="javascript:;" onclick="deleteCountry(\''+data+'\')" class="btn default btn-xs red-sunglo"><i class="fa fa-trash-o"></i> 删除</a>';
                        return html;
                    }
                }
            ],
            'fnDrawCallBack': function(settings, json) {
                $(".titleImgGroup").colorbox({'close':''});
            }
        };
        TableAjax.init(tableInfo);


        $("#addCountry").bind("click",function(){
           Main.openModal("/country/to-add-country")
        });


        $("#refresh,#search").bind("click",function(){
            TableAjax.refresh();
        });
    });

    /**
     * 编辑国家
     * @param id
     */
    function toEditCountry(id){
        Main.openModal("/country/to-edit-country?countryId="+id);
    }

    /**
     * 删除国家
     * @param id
     */
    function deleteCountry(id){
        Main.confirmTip("确认要删除此数据吗？",function(){
            $.ajax({
                type:"POST",
                url:"/country/delete-country",
                data:{
                    countryId:id
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
                        Main.successTip("删除国家成功");
                    }else{
                        Main.errorTip("删除失败");
                    }
                }
            });
        });
    }


    /**
     * 管理城市页面
     * @param id
     */
    function manageCity(id){
        if(id==""){
            Main.errorTip("获取国家主键失败");
            return;
        }
        Main.refreshContentAjax("/country/to-city-list?countryId="+id);
    }
</script>