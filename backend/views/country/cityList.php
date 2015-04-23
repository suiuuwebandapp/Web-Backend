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
    <input type="hidden" value="<?= $country->id ?>" id="countryId" />
    <div class="col-md-12">
        <!-- BEGIN SAMPLE TABLE PORTLET-->
        <div class="portlet light bg-inverse">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-list font-red-sunglo"></i>
                    <span class="caption-subject font-red-sunglo bold uppercase"><?= $country->cname?>城市列表</span>
                            <span class="caption-helper">
                                <?= $country->cname?>的城市配置
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
                            <a id="addCity" href="javascript:" class="btn green-meadow"><i class="fa fa-plus"></i> 添加城市</a>
                            <a id="backCountry" href="javascript:" class="btn green-meadow"><i class="fa fa-chevron-left"></i> 返回国家列表</a>
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
            'tableUrl' :'/country/city-list',
            'tableData':{
                countryId:$("#countryId").val()
            },
            'tableOrder':[],
            'tableColumn':[
                {"targets": [0],"data": "id","bSortable": false,"width":"50px"},
                {"targets": [1],"data": "cname","bSortable": false},
                {"targets": [2],"data": "ename","bSortable": false},
                {"targets": [3],"data": "code","bSortable": false},
                {
                    "targets": [4],
                    "data": "id",
                    "bSortable": false,
                    "width":"200px",
                    "render": function(data, type, full) {
                        var html='';
                        html +='<a href="javascript:;" onclick="toEditCity(\''+data+'\')" class="btn default btn-xs blue-madison"><i class="fa fa-edit"></i> 编辑</a>&nbsp;&nbsp;';
                        html +='<a href="javascript:;" onclick="deleteCity(\''+data+'\')" class="btn default btn-xs red-sunglo"><i class="fa fa-trash-o"></i> 删除</a>';
                        return html;
                    }
                }
            ],
            'fnDrawCallBack': function(settings, json) {
                $(".titleImgGroup").colorbox({'close':''});
            }
        };
        TableAjax.init(tableInfo);


        $("#addCity").bind("click",function(){
            var countryId=$("#countryId").val();
            Main.openModal("/country/to-add-city?countryId="+countryId);
        });


        $("#refresh,#search").bind("click",function(){
            TableAjax.refresh();
        });
    });

    /**
     * 编辑城市
     * @param id
     */
    function toEditCity(id){
        Main.openModal("/country/to-edit-city?cityId="+id);
    }

    /**
     * 删除城市
     * @param id
     */
    function deleteCity(id){
        Main.confirmTip("确认要删除此数据吗？",function(){
            $.ajax({
                type:"POST",
                url:"/country/delete-city",
                data:{
                    cityId:id
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
                        Main.successTip("删除城市成功");
                    }else{
                        Main.errorTip("删除失败");
                    }
                }
            });
        });
    }

</script>