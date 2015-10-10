<!doctype html>
<link rel="stylesheet" type="text/css" href="/assets/plugins/select2/select2_metro.css">
<script type="text/javascript" src="/assets/plugins/select2/select2.min.js"></script>
<style>
    .formTip{
        float: right;
        margin-right: 80px;
        font-size: 14px;
        height: 2.7rem;
    }
    .accAreaCodeSelect{
        font-size: 16px;
    }
    .accAreaCodeSelect .select2-choice{
        border-radius:0px !important;
        background: #eee !important;
        color: #858585;
        text-align: center;
        font-size: 0.85rem;
        height: 2.7rem;
    }
    .accAreaCodeSelect #select2-chosen-1{
        margin-top: 5px;
    }
    .select2-hidden-accessible{
        display: none;
    }
</style>
    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        <p class="navTop">常住地</p>
        <a href="javascript:;" class="sures" onclick="submitUserInfo()">确定</a>
    </div>
    <div class="con cshezhi_ziliaoSet clearfix">
        <select id="countryId" name="countryIds" class="accAreaCodeSelect" placeholder="国家"  required onchange="getCityList()">
            <option value=""></option>
            <?php if($countryList!=null){ ?>
                <?php foreach ($countryList as $c) { ?>
                    <?php if ($c['id'] == $userInfo["countryId"]) { ?>
                        <option selected
                                value="<?= $c['id'] ?>"><?= $c['cname'] . " /" . $c['ename'] ?></option>
                    <?php } else { ?>
                        <option value="<?= $c['id'] ?>"><?= $c['cname'] . " /" . $c['ename'] ?></option>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
        </select>
        <select id="cityId" name="cityIds" class="accAreaCodeSelect"  placeholder="城市" required>
            <option value=""></option>
            <?php if($cityList!=null){ ?>
                <?php foreach ($cityList as $c) { ?>
                    <?php if ($c['id'] == $userInfo["cityId"]) { ?>
                        <option selected
                                value="<?= $c['id'] ?>"><?= $c['cname'] . " /" . $c['ename'] ?></option>
                    <?php } else { ?>
                        <option value="<?= $c['id'] ?>"><?= $c['cname'] . "/ " . $c['ename'] ?></option>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
        </select>
    </div>
    <form style="display: none" action="/wechat-user-info/update-user-info" method="post" id="userInfo" >
        <input name="cityId" id="val_sub">
        <input type="hidden" name="_csrf" value="<?php echo Yii::$app->request->getCsrfToken()?>">
        <input name="countryId" id="val1_sub">
    </form>
<script>
    var cityId="";
    $(document).ready(function () {
        //初始化区号选择
        $(".accAreaCodeSelect").select2({
            'width':'100%',
            'height':'100%',
            formatNoMatches: function () {
                return "暂无匹配";
            }
        });
        cityId=$("#cityId").val("");
    });
    /**
     * 级联获取城市列表
     */
    function  getCityList(){
        var countryId=$("#countryId").val();
        if(countryId==""){
            return;
        }
        $("#cityId").empty();

        $("#cityId").append("<option value=''></option>");
        $("#cityId").val("").trigger("change");
        $.ajax({
            url :"/wechat-user-info/get-city-list-by-id?id="+countryId,
            type:'get',
            error:function(){
            },
            success:function(data){
                var datas=eval('('+data+')');
                if(datas.status==1){
                    var html = "";
                    for(var i=0;i<datas.data.length;i++){
                        var city=datas.data[i];
                        html+='<option value="'+city.id+'">'+city.cname+'</option>';
                    }
                    $("#cityId").append(html);
                    if(cityId!=""){
                        $("#cityId").val(cityId).trigger("change");
                    }else{
                        $("#cityId").val()
                    }
                }else{
                }
            }
        });
    }


    function submitUserInfo()
    {
        var val_country= $("#countryId").val();
        var val_city= $("#cityId").val();
        if(val_city=="")
        {alert("请输入城市");return;}
        $("#val_sub").val(val_city);
        $("#val1_sub").val(val_country);
        $("#userInfo").submit();
    }
</script>