<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/30
 * Time : 下午8:50
 * Email: zhangxinmailvip@foxmail.com
 */
?>
<style>
    body{
        background-color: #EEEEEE;
    }

    .select2-container .select2-choice {
        background-color: #EEEEEE !important;
        border-radius: 0px !important;
        font-size: 14px;
        color: dimgray;
        padding-top: 7px !important;
    }
    .select2-hidden-accessible{
        display: none;
    }
    .select2-drop {
        font-size: 14px;
    }
    .select2-highlighted {
        background-color: #eee;
    }
    .select2-no-results {
        font-size: 14px;
        color: dimgray;
        text-align: center;
    }
    .mddsx-left{
        padding-top: 30px;
        padding-left: 20px;
        width: 280px;
    }

</style>
<link rel="stylesheet" type="text/css" href="/assets/plugins/select2/select2_metro.css">
<script type="text/javascript" src="/assets/plugins/select2/select2.min.js"></script>
<div class="mddsx"><!--mddsx begin-->
    <div class="fl mddsx-left" style="z-index:888;">
        <select id="countryId" name="country" class="select2" required placeholder="国家">
            <option value=""></option>
            <?php foreach ($countryList as $c) { ?>
                <?php
                if(!in_array($c['id'],$countryArr)){
                    continue;
                }
                ?>
                <option value="<?= $c['id'] ?>"><?= $c['cname'] . "/" . $c['ename'] ?></option>

            <?php } ?>
        </select>
        <select id="cityId" name="city" class="select2" required placeholder="城市"></select>

 </div>
    <div class="fr mddsx-right" id="des_div">
        <ul>

        </ul>
        <a class="more" href="javascript:;" id="showDesMore">加载更多</a>
    </div>
</div>
<!--mddsx end-->


<script type="text/javascript">
    var currentPage=1;
    var existCityIds='<?=implode(",",$cityArr)?>';
    var cityArr=existCityIds.split(",");
    $(document).ready(function(){
        //初始化国家，城市
        $(".select2").select2({
            'width':'260px',
            containerCss: {
                'margin-bottom':'20px'
            },
            formatNoMatches: function () {
                return "暂无匹配数据";
            }
        });

        //绑定获取城市列表
        $("#countryId").on("change", function () {
            currentPage=1;
            getCityList(true);
        });
        $("#cityId").on("change", function () {
            currentPage=1;
            getDesList(true);
        });

        getDesList();
        $("#showDesMore").bind("click",function(){
            getDesList();
        });

    });

    //级联获取城市列表
    function  getCityList(){
        var countryId=$("#countryId").val();
        if(countryId==""){
            return;
        }
        $("#countryTip").html("");
        $("#cityId").empty();

        $("#cityId").append("<option value=''></option>");
        $("#cityId").val("").trigger("change");
        $.ajax({
            url :'/country/find-city-list',
            type:'post',
            data:{
                countryId:countryId,
                _csrf: $('input[name="_csrf"]').val()

            },
            error:function(){
                $("#cityTip").html("获取城市列表失败");
            },
            success:function(data){
                var datas=eval('('+data+')');
                if(datas.status==1){
                    var html = "";
                    for(var i=0;i<datas.data.length;i++){
                        var city=datas.data[i];
                        if(cityArr.indexOf(city.id)==-1){
                            continue;
                        }
                        html+='<option value="'+city.id+'">'+city.cname+"/"+city.ename+'</option>';
                    }
                    $("#cityId").append(html);
                }else{
                    $("#cityTip").html("获取城市列表失败");
                }
            }
        });
    }


    function getDesList(clear)
    {

        var countryId=$("#countryId").val();
        var cityId=$("#cityId").val();
        $.ajax({
            url :'/destination/find-list',
            type:'post',
            data:{
                countryId:countryId,
                cityId:cityId,
                p:currentPage,
                _csrf: $('input[name="_csrf"]').val()
            },
            error:function(){
                $("#cityTip").html("获取目的地列表失败");
            },
            success:function(data){
                var data=eval('('+data+')');

                if(data.status==1){
                    var html = "";
                    for(var i=0;i<data.data.result.length;i++){
                        var des=data.data.result[i];
                        html+='<li><a href="/destination/info?des='+des.destinationId+'">' +
                        '<img src="'+des.titleImg+'" alt="" style="width:879px;height:400px;">' +
                        '</a><p><font>'+des.title+'</font><span>'+des.intro+'</span></p></li>';
                    }
                    if(clear){
                        $("#des_div ul").html(html);
                        $("#showDesMore").unbind("click");
                        $("#showDesMore").bind("click",function(){
                            getDesList();
                        });
                    }else{
                        $("#des_div ul").append(html);
                    }
                    if(data.data.totalPage==currentPage){
                        $("#showDesMore").html("暂无更多");
                        $("#showDesMore").unbind("click");
                        return;
                    }
                    currentPage++;
                }else{
                    Main.showTip("获取目的地列表失败");
                }
            }
        });
    }

</script>