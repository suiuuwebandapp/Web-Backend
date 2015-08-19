<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/25
 * Time : 下午3:00
 * Email: zhangxinmailvip@foxmail.com
 */

?>
<link rel="stylesheet" type="text/css" href="/assets/css/my_select.css">
<script type="text/javascript" src="/assets/js/squid.js"></script>
<script type="text/javascript" src="/assets/js/jselect-1.0.js"></script>

<link rel="stylesheet" type="text/css" href="/assets/plugins/select2/select2_metro.css">
<link rel="stylesheet" type="text/css" href="/assets/plugins/jquery-uploadifive/uploadifive.css">

<script type="text/javascript" src="/assets/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="/assets/plugins/jquery-uploadifive/jquery.uploadifive.min.js"></script>
<script type="text/javascript" src="/assets/plugins/jquery-validation/dist/jquery.validate.js"></script>

<style type="text/css">
    .syInformation .syRegister span {
        display: inline;
    }
    .select2-container .select2-choice {
        background-color: #eee;
        border-radius: 0px;
        font-size: 14px;
        color: dimgray;
        height: 33px !important;
    }
    .select2-drop {
        font-size: 14px;
    }

    .select2-highlighted {
        background-color: #0088e4;
    }
    .select2-no-results {
        font-size: 14px;
        color: dimgray;
        text-align: center;
    }

    #phone {
        height: 38px;
        margin-left: 40px;
        width: 190px;
    }
    .select2-container .select2-choice > .select2-chosen{
        margin-top: 3px;
    }
    .syInformation .forms div.makes .star{
        top: 7px;
    }
    .syInformation .forms p{
        height: 22px;
        margin-top: 20px;
    }
    .syInformation .forms .name input{
        margin: 0px;
        width: 197px;
    }



    .syInformation .syzcy-text {
        font-size: 14px;
    }


    .syInformation .p_chose_card_front {
        height: 170px;
        width: 286px;
        text-align: center;
        line-height: 170px;
    }
    .syInformation .upload_tip {
        font-size: 12px;
        text-align: center;
    }

    .syInformation .imgPic {
        cursor: pointer;
        width: 286px;
        height: 170px;
    }

    .syInformation .showImg {
        width: 286px;
        height: 170px;
    }

    #uploadifive-fileCardFront {
        display: none;
    }
    .syInformation .queue {
        display: none;
    }

    .syInformation .form_tip{
        font-size: 14px;
        padding-left: 20px;
        color: red;
        display: inline-block !important;
        text-align: right;
        float: right;
        width: 250px !important;
    }
    .syInformation .forms .phones input{
        width: 197px;
    }
    .syInformation .forms .nick_name_p{
        margin-top: 0px;
    }
    .syInformation .forms div.sexs{
        margin-bottom: 10px;
    }
    .syInformation .forms div.codes{
        margin-top: 10px;
    }
    .syInformation .forms .name_div{
        width: 215px;
        display: inline-block;
    }

    .syInformation .forms .sexs input+label{
        margin-right: 35px;
    }

</style>

<?php
    $idCardImg="";
    if(!empty($userPublisher)&&!empty($userPublisher->idCardImg)){
        $idCardImg=$userPublisher->idCardImg;
    }
?>
<!--初始化select-->
<!-------随友注册------>


<div class="w1200 syInformation clearfix">
    <form id="validateForm" method="post" action="/register-publisher">
    <h2 class="title">在发布随游之前，您需要补充一些个人信息，让您的随游更加可信</h2>
    <div class="forms clearfix">
        <div>
            <p class="nick_name_p">昵称<span id="nicknameTip" class="form_tip"></span></p>
            <div class="makes">
                <span class="star">*</span>
                <input type="text" value="<?=$this->context->userObj->nickname?>" name="nickname" id="nickname">
            </div>
        </div>
        <div class="clearfix sexs">
            <span>性别</span>
            <input type="radio" id="sex01" name="sex" value="<?=\common\entity\UserBase::USER_SEX_MALE?>"><label for="sex01">男</label>
            <input type="radio" id="sex02" name="sex" value="<?=\common\entity\UserBase::USER_SEX_FEMALE?>"><label for="sex02">女</label>
            <input type="radio" id="sex03" name="sex" checked value="<?=\common\entity\UserBase::USER_SEX_FEMALE?>"><label for="sex03" style="background-position: 0px -47px;">保密<label>

        </div>
        <div class="clearfix name">
            <div class="name_div">
                <span style="width: 33px"> 姓氏</span><span id="surnameTip" class="form_tip" style="width: 160px !important; "></span>
            </div>
            <div class="name_div">
                <span style="width: 33px"> 名字</span><span id="nameTip" class="form_tip" style="width: 160px !important; "></span>
            </div>

            <div class="makes">
                <span class="star">*</span>
                <input type="text" class="makes" maxlength="20" name="surname" id="surname" data-tip-id="surname" value="<?=$this->context->userObj->surname?>">
                <input type="text" maxlength="20" name="name" id="name" data-tip-id="name" value="<?=$this->context->userObj->name?>">
                <div class="nameTip">
                    <h2 class="tit bgGreen">姓名</h2>
                    <p>作为随游服务的重要保障，只有预订确认后，您的名字才会对预订者显示。</p>
                </div>
            </div>
        </div>
        <div>
            <p>国家<span class="form_tip" id="countryTip"></span></p>
            <div class="makes">
                <span class="star">*</span>
                <select id="countryId" name="country" class="select_country" required>
                    <option value=""></option>
                    <?php foreach ($countryList as $c) { ?>
                        <option value="<?= $c['id'] ?>"
                            <?php  if($c['id']==$this->context->userObj->countryId){echo "selected";} ?>
                            ><?= $c['cname'] . "/" . $c['ename'] ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div>
            <p>城市<span class="form_tip" id="cityTip"></span></p>
            <div class="makes">
                <span class="star">*</span>
                <select id="cityId" name="city" class="select_city" required></select>
            </div>
        </div>
        <div>
            <?php if(!empty($phone)){ ?>
            <p>手机验证<span id="phoneTip" class="form_tip"></span></p>
            <div class="makes">
                <span class="star">*</span>
                <input type="text" placeholder="请输入您的手机号码" name="phone" value="<?=$areaCode." ".$phone;?>" readonly />
            </div>
        <?php }else{ ?>
            <p>手机验证<span id="phoneTip" class="form_tip"></span></p>
            <div class="phones clearfix">
                <div class="set makes">
                    <span class="star">*</span>
                    <select id="codeId" name="areaCode" class="areaCodeSelect" required>
                        <option value=""></option>
                        <?php foreach ($countryList as $c) { ?>
                            <?php if(empty($c['areaCode'])){continue;} ?>
                            <?php if ($c['areaCode'] == $areaCode) { ?>
                                <option selected
                                        value="<?= $c['areaCode'] ?>"><?= $c['cname'] . " " . $c['areaCode'] ?></option>
                            <?php } else { ?>
                                <option value="<?= $c['areaCode'] ?>"><?= $c['cname'] . " " . $c['areaCode'] ?></option>
                            <?php } ?>

                        <?php } ?>
                    </select>
                </div>
                <input type="text" id="phone"  name="phone" placeholder="请输入您的手机号码" value="<?=$phone;?>" />
            </div>
            <div class="codes clearfix">
                <div class="makes"><span class="star">*</span>
                    <input type="text"  maxlength="6" name="code" id="code" placeholder="请输入验证码">
                </div>
                <a href="javascript:;" class="btn bgOrange" id="getCode">获取验证码</a>
            </div>
        <?php } ?>
        </div>
        <p>仅当您和另一名随游用户确认预订时，此资料才会被分享。这是我们帮助大家联系彼此的方式</p>
        <br/>
        <br/>
        <div>
            <p class="nick_name_p">QQ<span id="qqTip" class="form_tip"></span></p>
            <div class="makes">
                <input type="text" value="<?=$this->context->userObj->qq?>" name="qq" id="qq">
            </div>
        </div>
        <div>
            <p class="nick_name_p">微信<span id="wechatTip" class="form_tip"></span></p>
            <div class="makes">
                <input type="text" value="<?=$this->context->userObj->wechat?>" name="wechat" id="wechat">
            </div>
        </div>
        <a href="javascript:;" id="createPublisher" class="nextBtn">下一步</a>
    </div>
</div>


<!-------随友注册------>

<script type="text/javascript">
    var frontFinish = false;
    var phoneTime = 0;
    var phoneTimer;
    var finishPhone=false;
    var cityId='<?=$this->context->userObj->cityId; ?>';
    var oldPhone='<?=$this->context->userObj->phone; ?>';
    var oldAreaCode='<?=$this->context->userObj->areaCode; ?>';


    $(document).ready(function () {
        var email = $("#email").val();
        var phone = $("#phone").val();

        //初始化区号选择
        $(".areaCodeSelect").select2({
            'width':'218px',
            'placeholder':'请选择区号',
            formatNoMatches: function () {
                return "暂无匹配";
            }
        });
        //初始化国家，城市
        $(".select_country").select2({
            'placeholder':'您所常驻的国家',
            'width':'440px',
            formatNoMatches: function () {
                return "暂无匹配数据";
            }
        });
        $(".select_city").select2({
            'placeholder':'您所居住的城市',
            'width':'440px',
            formatNoMatches: function () {
                return "暂无匹配数据";
            }
        });

        //绑定获取城市列表
        $("#countryId").on("change", function () {
            getCityList();
        });
        $("#countryId").change();



        //初始化上传身份证等功能
        $(".p_chose_card_front").bind("click", function () {
            $("#uploadAll").val("上 传");
            $("#uploadAll").css("width", "80px");
            $("#userCardFront").val("");
            var file = $("#uploadifive-fileCardFront input[type='file']").last();
            $(file).click();
        });
        if($("#imgFront").attr("src")!=''){
            $(".p_chose_card_front").hide();
            $("#imgFront").show();
            $("#uploadAll").hide();
            $(".upload_tip").html("您已经提交过护照信息，无需再次提交");
        }
        //绑定上传事件
        $("#uploadAll").bind("click", function () {
            uploadAll();
        });

        //绑定发送验证码事件
        $("#getCode").bind("click", function () {
            sendTravelCode();
        });


        //绑定注册随友事件
        $("#createPublisher").bind("click", function () {
            $("#validateForm").submit();
        });



        initUploadfive();
        initValidate();

    });

    function initValidate(){

        $("#validateForm").validate({
            errorElement: 'span', // default input error message container
            errorClass: 'errorTip', // default input error message class
            focusInvalid: true, // do not focus the last invalid input
            focusCleanup: true,
            ignore: 'ignore',
            rules: {
                nickname: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                country: {
                    required: true
                },
                city: {
                    required: true
                },
                phone:{
                    required: true
                },
                name:{
                    required: true
                },
                surname:{
                    required: true
                }
            },
            messages: {
                nickname: {
                    required: "请输入您的昵称"
                },
                email: {
                    required: "请输入Email地址",
                    email: "请输入正确的email地址"
                },
                country:{
                    required: "请选择国家"
                },
                city:{
                    required: "请选择城市"
                },
                phone:{
                    required: "请输入有效的手机号和验证码"
                },
                name:{
                    required: "请输入您的名字"
                },
                surname:{
                    required: "请输入您的姓氏"
                }

            },
            errorPlacement: function (error, element) { // render error
                var tip=$(element).attr("data-tip-id")
                if(Main.isNotEmpty(tip)){
                    $("#"+tip+"Tip").html(error.text());
                }else{
                    var span = $(element).parent().find("span[class='form_tip']");
                    if(span[0]){
                        $(span).html(error.text());
                    }else{
                        span = $(element).parent().parent().find("span[class='form_tip']");
                        $(span).html(error.text());
                    }
                }
            },
            success : function(label, element) {
                var tip=$(element).attr("data-tip-id");
                if(Main.isNotEmpty(tip)){
                    $("#"+tip+"Tip").html("");
                }else{
                    var span = $(element).parent().find("span[class='form_tip']");
                    if(span[0]){
                        $(span).html("");
                    }else{
                        span = $(element).parent().parent().find("span[class='form_tip']");
                        $(span).html("");
                    }
                }

            },
            submitHandler: function (form) {
                createPublisher();
            }

        });

    }
    function initUploadfive(){
        $('#fileCardFront').uploadifive({
            'auto': false,
            'queueID': 'frontQueue',
            'uploadScript': '/upload/upload-card-img',
            'multi': false,
            'dnd': false,
            'onUploadComplete': function (file, data) {
                var datas = eval('(' + data + ')');
                if (datas.status == 1) {
                    frontFinish = true;
                    $("#userCardFront").val(datas.data);
                    $("#cardTip").html("");
                    $("#uploadAll").val("上传成功！");
                } else {
                    $("#uploadAll").val("上传失败，请稍后重试。。。");
                }

            },
            onSelect:function(){
                $("#uploadifive-fileCardFront input[type='file']").uploadPreview({
                    Img: "imgFront",
                    Width: 120,
                    Height: 120,
                    ImgType: [
                        "jpeg", "jpg", "png"
                    ], Callback: function () {
                        $("#imgFront").show();
                        $("#imgFront").unbind("click");
                        $("#imgFront").bind("click", function () {
                            $(".p_chose_card_front").click();
                        });
                    }
                });
            },
            onInit: function () {
                //初始化预览图片
                $("#uploadifive-fileCardFront input[type='file']").uploadPreview({
                    Img: "imgFront",
                    Width: 120,
                    Height: 120,
                    ImgType: [
                        "jpeg", "jpg", "png"
                    ], Callback: function () {
                        $("#imgFront").show();
                        $("#imgFront").unbind("click");
                        $("#imgFront").bind("click", function () {
                            $(".p_chose_card_front").click();
                        });
                    }
                });
            }
        });
    }

    function initPhoneTimer() {
        phoneTimer = window.setInterval(function () {
            if (phoneTime > 0) {
                phoneTime--;
                initPhoneTime();
            } else {
                window.clearInterval(phoneTimer);
                initPhoneTime();
            }
        }, 1000);
    }

    function initPhoneTime() {

        if (phoneTime != "" && phoneTime > 0) {
            $("#getCode").html(+phoneTime + "秒后重新发送");
            $("#getCode").attr("disabled", "disabled");

            $("#getCode").css("background", "gray");
            $("#getCode").unbind("click");
        } else {
            $("#getCode").html("获取验证码");
            $("#getCode").removeAttr("disabled");
            $("#getCode").css("background", "#FFAA00");
            $("#getCode").unbind("click");
            $("#getCode").bind("click", function () {
                sendTravelCode();
            });
        }
    }


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
                        html+='<option value="'+city.id+'">'+city.cname+"/"+city.ename+'</option>';
                    }
                    $("#cityId").append(html);
                    if(cityId!=""){
                        $("#cityId").val(cityId).trigger("change");
                    }
                }else{
                    $("#cityTip").html("获取城市列表失败");
                }
            }
        });
    }


    function uploadAll() {
        if ($("#imgFront").attr("src") == "") {
            Main.showTip("请选择护照图片");
            return;
        }
        $('#fileCardFront').uploadifive('upload');
        $("#uploadAll").val("正在上传，请稍后...");
        $("#uploadAll").css("width", "200px");
    }

    /**
     * 发送手机验证码
     */
    function sendTravelCode() {
        //TODO 验证手机有效性
        var phone = $("#phone").val();
        var areaCode = $("#codeId").val();

        if (phone == "") {
            $("#phoneTip").html("请输入有效的手机号");
            return;
        } else {
            $("#phoneTip").html("");
        }
        $.ajax({
            url: '/index/send-travel-code',
            type: 'post',
            data: {
                phone: phone,
                areaCode: areaCode,
                _csrf: $('input[name="_csrf"]').val()

            },
            beforeSend: function () {
                $("#getCode").html("正在发送...");
            },
            error: function () {
                $("#getCode").html("发送失败...");
            },
            success: function (data) {
                var datas = eval('(' + data + ')');
                if (datas.status == 1) {
                    $("#getCode").html("发送成功");
                    phoneTime = 60;
                    initPhoneTimer();
                } else {
                    $("#getCode").html("发送失败...");
                    $("#phoneTip").html(datas.data);
                }
            }
        });
    }


    function createPublisher() {

        var nickname=$("#nickname").val();
        var surname = $("#surname").val();
        var name = $("#name").val();
        var countryId = $("#countryId").val();
        var cityId = $("#cityId").val();
        var areaCode = $("#codeId").val();
        var phone = $("#phone").val();
        var code = $("#code").val();
        var sex=$('input:radio[name="sex"]:checked').val();
        var qq = $("#qq").val();
        var wechat = $("#wechat").val();

        if(qq==''&&wechat==''){
            $("#qqTip").html("QQ和微信至少填写一个");
            return;
        }

        if(oldPhone!=''){
            phone=oldPhone;
            areaCode=oldAreaCode;
            code='code';
        }
        if(phone==""||code==""){
            $("#phoneTip").html("请输入有效的手机号和验证码");
            $("#phoneTip").focus();
            return;
        }



        $.ajax({
            url :'/user-info/register-publisher',
            type:'post',
            data:{
                nickname:nickname,
                surname:surname,
                name:name,
                countryId:countryId,
                cityId:cityId,
                areaCode:areaCode,
                phone:phone,
                code:code,
                sex:sex,
                qq:qq,
                wechat:wechat,
                _csrf: $('input[name="_csrf"]').val()
            },
            beforeSend:function()
            {
                $("#createPublisher").attr("disabled","disabled");
            },
            error:function(){
                $("#createPublisher").removeAttr("disabled");
                Main.showTip("注册随友失败，请稍后再试");
            },
            success:function(data){
                $("#createPublisher").removeAttr("disabled");
                var datas=eval('('+data+')');
                if(datas.status==1){
                    window.location.href=datas.data;
                }else{
                    Main.showTip(datas.data);
                }
            }
        });

    }


</script>