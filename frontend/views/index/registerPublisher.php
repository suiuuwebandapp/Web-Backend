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
    .syRegister span {
        display: inline;
    }
    .select2-container .select2-choice {
        background-color: #eee;
        border-radius: 0px;
        font-size: 14px;
        color: dimgray;
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

    .syzcy-text {
        font-size: 14px;
    }


    .p_chose_card_front {
        height: 170px;
        width: 286px;
        text-align: center;
        line-height: 170px;
    }
    .upload_tip {
        font-size: 12px;
        text-align: center;
    }

    .imgPic {
        cursor: pointer;
        width: 286px;
        height: 170px;
    }

    .showImg {
        width: 286px;
        height: 170px;
    }

    #uploadifive-fileCardFront {
        display: none;
    }
    .queue {
        display: none;
    }

    #code {
        width: 100px !important;
    }

    #getCode {
        width: 130px;
    }
    .form_tip{
        font-size: 14px;
        padding-left: 20px;
        color: red;
        display: inline-block !important;
        text-align: right;
        float: right;
        width: 250px !important;
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
<div class="syRegister">
    <form id="validateForm">
        <div>
            <span>昵称:</span><span id="nicknameTip" class="form_tip"></span>
            <input type="text" id="nickname" name="nickname" value="<?= $nickname ?>" class="syzcy-text" maxlength="10" required>

        </div>
        <input id="userCardFront" type="hidden" value="<?=$idCardImg?>">
        <div>
            <span>邮箱:</span><span id="emailTip" class="form_tip"></span>
            <input type="text" id="email" name="email" value="<?= $email ?>" class="syzcy-text" maxlength="50" required>

        </div>
        <div id="password_div">
            <div>
                <span>密码:</span><span class="form_tip"></span>
                <input type="password" value="" name="password" class="syzcy-text" maxlength="20" id="password" required>
            </div>
            <div>
                <span>确认密码:</span><span class="form_tip"></span>
                <input type="password" value="" name="confirm_password" class="syzcy-text" maxlength="20" id="passwordConfirm" required>
            </div>
        </div>
        <div>
            <span>国家:</span><span class="form_tip" id="countryTip"></span>
            <select id="countryId" name="country" class="select2" required>
                <option value=""></option>
                <?php foreach ($countryList as $c) { ?>
                    <option value="<?= $c['id'] ?>"><?= $c['cname'] . "/" . $c['ename'] ?></option>

                <?php } ?>
            </select>
        </div>
        <div>
            <span>城市:</span><span class="form_tip" id="cityTip"></span>
            <select id="cityId" name="city" class="select2" required></select>
        </div>
        <div>
            <span id="cardTip" class="form_tip"></span>
            <div id="divCardFront" class="imgPic">
                <img src="<?=$idCardImg?>" id="imgFront" style="display: none" class="showImg"/>

                <p class="p_chose_card_front">点击上传护照</p>
            </div>
            <input id="fileCardFront" type="file"/>

            <div id="frontQueue" class="queue"></div>

            <p class="upload_tip">上传文件大小请不能大于1M，支持格式png、jpg、jpeg</p> <br/>
            <input type="button" value="上传" class="schuan" id="uploadAll">
        </div>

        <div id="phone_div">
            <span>手机:</span><span id="phoneTip" class="form_tip"></span>

            <div class="phone-select">
                <div class="sect">
                    <select id="codeId" name="countryIds" class="areaCodeSelect" required>
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
                <input type="text" name="phone" value="<?=$phone;?>" class="phone fl syzcy-text" id="phone" maxlength="11">
            </div>

        </div>
        <div id="code_div">
            <p class="p1">
                <span class="fl">输入验证码</span>
                <input type="text" class="text fl syzcy-text" maxlength="6" id="code">
                <input type="button" value="获取验证码" class="btn fl" id="getCode">
            </p>
        </div>

        <input type="button" value="注册" class="zbtn" id="createPublisher">
    </form>
</div>
<!-------随友注册------>

<script type="text/javascript">
    var frontFinish = false;
    var phoneTime = 0;
    var phoneTimer;
    var finishPhone=false;

    $(document).ready(function () {
        var email = $("#email").val();
        var phone = $("#phone").val();
        var areaCode = $("#areaCodeSelect").val();

        //初始化Form内容，判断是否已经绑定相关信息
        if (email != "" && phone == "") {
            $("#email").attr("disabled", "disabled");
            $("#emailTip").html("（您已经绑定邮箱，无需验证）");
            $("#password_div").hide();
            $("#password").val("password");
            $("#passwordConfirm").val("password");

        } else if (email == "" && phone != "") {
            $("#phone").attr("disabled", "disabled");
            $("#phoneTip").html("（您已经绑定手机，无需验证）");
            $("#areaCodeSelect").val(areaCode);
            $("#code_div").hide();
            $("#password_div").hide();
            $("#password").val("password");
            $("#passwordConfirm").val("password");
            finishPhone=true;

        }else if(email != "" && phone != ""){
            $("#email").attr("disabled", "disabled");
            $("#emailTip").html("（您已经绑定邮箱，无需验证）");
            $("#phoneTip").html("（您已经绑定手机，无需验证）");
            $("#areaCodeSelect").val(areaCode);
            $("#code_div").hide();
            $("#password_div").hide();
            $("#password").val("password");
            $("#passwordConfirm").val("password");
            finishPhone=true;

        }
        //初始化区号选择
        $(".areaCodeSelect").select2({
            'width':'130px',
            formatNoMatches: function () {
                return "暂无匹配";
            }
        });
        //初始化国家，城市
        $(".select2").select2({
            'width':'350px',
            formatNoMatches: function () {
                return "暂无匹配数据";
            }
        });

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

        //绑定获取城市列表
        $("#countryId").on("change", function () {
            getCityList();
        });
        $("#cityId").on("change", function () {
           if($("#cityId").val()!=""){
               $("#cityTip").html("");
           }
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
                password: {
                    required: true,
                    minlength: 5
                },
                confirm_password: {
                    required: true,
                    minlength: 5,
                    equalTo: "#password"
                },
                country: {
                    required: true
                },
                city: {
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
                password: {
                    required: "请输入密码",
                    minlength: jQuery.format("密码不能小于{0}个字符")
                },
                confirm_password: {
                    required: "请输入确认密码",
                    minlength: "确认密码不能小于5个字符",
                    equalTo: "两次输入密码不一致不一致"
                },
                country:{
                    required: "请选择国家"
                },
                city:{
                    required: "请选择城市"
                }

            },
            errorPlacement: function (error, element) { // render error
                var span = $(element).parent().find("span[class='form_tip']");
                $(span).html(error.text());
            },
            success : function(label, element) {
                var span = $(element).parent().find("span[class='form_tip']");
                $(span).html("");
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
            $("#getCode").val(+phoneTime + "秒后重新发送");
            $("#getCode").attr("disabled", "disabled");

            $("#getCode").css("background", "gray");
            $("#getCode").unbind("click");
        } else {
            $("#getCode").val("获取验证码");
            $("#getCode").removeAttr("disabled");
            $("#getCode").css("background", "#ff7a4d");
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
                $("#getCode").val("正在发送...");
            },
            error: function () {
                $("#getCode").val("发送失败...");
            },
            success: function (data) {
                var datas = eval('(' + data + ')');
                if (datas.status == 1) {
                    $("#getCode").val("发送成功");
                    phoneTime = 60;
                    initPhoneTimer();
                } else {
                    $("#getCode").val("发送失败...");
                    $("#phoneTip").html(datas.data);
                }
            }
        });
    }


    function createPublisher() {

        var nickname=$("#nickname").val();
        var email = $("#email").val();
        var cardImgFront = $("#userCardFront").val();
        var password = $("#password").val();
        var passwordConfirm = $("#passwordConfirm").val();
        var countryId = $("#countryId").val();
        var cityId = $("#cityId").val();
        var areaCode = $("#codeId").val();
        var phone = $("#phone").val();
        var code = $("#code").val();

        if(cardImgFront==""){
            $("#cardTip").html("请上传有效的护照并验证");
            $("#cardTip").focus();
            return;
        }
        if(!finishPhone&&(phone==""||code=="")){
            $("#phoneTip").html("请输入有效的手机号和验证码");
            $("#phoneTip").focus();
            return;
        }


        $.ajax({
            url :'/index/register-publisher',
            type:'post',
            data:{
                nickname:nickname,
                email:email,
                userCard:cardImgFront,
                password:password,
                passwordConfirm:passwordConfirm,
                countryId:countryId,
                cityId:cityId,
                areaCode:areaCode,
                phone:phone,
                code:code,
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