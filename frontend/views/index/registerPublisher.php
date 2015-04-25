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

<style type="text/css">
    .syRegister span {
        display: inline;
    }

    .reg_pub_tip {
        font-size: 14px;
        padding-left: 20px;
        color: gray;
    }

    .areaCodeSelect {
        width: 130px;
    }

    .select2-container .select2-choice {
        background-color: #eee;
        border-radius: 0px;
        font-size: 14px;
        color: dimgray;
    }

    .select2-result {
    }

    .select2-drop {
        font-size: 14px;
    }

    .select2-highlighted {
        background-color: #0088e4;
    }

    #phone {
        height: 38px;
        margin-left: 40px;
        width: 190px;
    }

    .syzcy-text {
        font-size: 14px;
    }

    .select2-no-results {
        font-size: 14px;
        color: dimgray;
    }

    .p_chose_card_front {
        height: 170px;
        width: 286px;
        text-align: center;
        line-height: 170px;

    }

    .p_chose_card_back {
        height: 170px;
        width: 286px;
        text-align: center;
        line-height: 170px;
    }

    .upload_tip {
        font-size: 12px;
        padding-left: 25px;
    }

    .imgPic {
        cursor: pointer;
        max-height: 170px;
        max-width: 286px;
    }

    .uploadContainer {
        background-color: red;
    }

    #uploadifive-fileCareFront {
        display: none;
    }
</style>
<!--初始化select-->
<!-------随友注册------>
<div class="syRegister">
    <span>邮箱:</span><span id="emailTip" class="reg_pub_tip"></span>
    <input type="text" id="email" value="<?= $email ?>" class="syzcy-text" maxlength="50">

    <div id="password_div">
        <span>密码:</span>
        <input type="password" value="" class="syzcy-text" maxlength="20">
        <span>确认密码:</span>
        <input type="password" value="" class="syzcy-text" maxlength="20">
    </div>
    <span>城市:</span>
    <input type="text" value="" class="syzcy-text" maxlength="20">

    <div id="phone_div">
        <div id="divCareFront" class="imgPic">
            <img src="" id="imgFront" style="display: none"/>

            <p class="p_chose_card_front">点击选择身份证正面</p>
        </div>
        <div class="imgPic">
            <img src="" style="display: none"/>

            <p class="p_chose_card_back">点击选择身份证反面</p>
        </div>
        <input id="fileCareFront" type="file" style="display: none"/>

        <p class="upload_tip">上传文件大小请不能大于1M，支持格式png、jpg、jpeg</p> <br/>
        <input type="button" value="上传" class="schuan">
        <span>手机:</span><span id="phoneTip" class="reg_pub_tip"></span>

        <div class="phone-select">
            <div class="sect">
                <select id="countryId" name="countryIds" class="areaCodeSelect" required>
                    <option value=""></option>
                    <?php foreach ($phoneCodeList as $c) { ?>
                        <?php if ($c['areaCode'] == $areaCode) { ?>
                            <option selected
                                    value="<?= $c['areaCode'] ?>"><?= $c['cname'] . " " . $c['areaCode'] ?></option>
                        <?php } else { ?>
                            <option value="<?= $c['areaCode'] ?>"><?= $c['cname'] . " " . $c['areaCode'] ?></option>
                        <?php } ?>

                    <?php } ?>
                </select>

            </div>
            <input type="text" value="" class="phone fl syzcy-text" id="phone" maxlength="11">
        </div>

    </div>
    <div id="code_div">
        <p class="p1">
            <span class="fl">输入验证码</span>
            <input type="text" class="text fl syzcy-text" maxlength="6">
            <input type="button" value="获取验证码" class="btn fl">
        </p>
    </div>

    <p class="p1 agree">
        <input name="" type="checkbox" value="" id="rad">
        <label for="rad">同意</label><a href="javascript:;">《网站注册协议》</a>
    </p>
    <input type="button" value="注册" class="zbtn">
</div>
<!-------随友注册------>

<script type="text/javascript">
    <?php $timestamp = time();?>
    $(function () {

        $('#fileCareFront').uploadifive({
            'auto': false,
            'queueID': 'queue',
            'uploadScript': '/upload/upload-title-img',
            'multi': false,
            'onUploadComplete': function (file, data) {
                var datas = eval('(' + data + ')');
                if (datas.status == 1) {
                    $("#queue").html("");
                    $("#titleImg").val(datas.data);
                    $("#titleImgPre").attr("src", datas.data);
                    $("#titleImgPre").show();
                } else {
                    //Main.errorTip("上传封面图失败");
                }
            },
            onSelect: function () {
                alert("asdfa");
            }
        });
    });

    $(document).ready(function () {
        var email = $("#email").val();
        var phone = $("#phone").val();
        var areaCode = $("#areaCodeSelect").val();


        if (email != "" && phone == "") {
            $("#email").attr("disabled", "disabled");
            $("#emailTip").html("（您已经绑定邮箱，无需验证）");
            $("#password_div").hide();
        } else if (email == "" && phone != "") {
            $("#phone").attr("disabled", "disabled");
            $("#phoneTip").html("（您已经绑定手机，无需验证）");
            $("#areaCodeSelect").val(areaCode);
            $("#code_div").hide();
            $("#password_div").hide();
        }

        $(".areaCodeSelect").select2({
            formatNoMatches: function () {
                return "暂无匹配";
            }
        });
        $(".p_chose_card_front").bind("click", function () {
            var file = $("#uploadifive-fileCareFront input[type='file']").eq(1);
            $(file).click();
        });
        alert($("#uploadifive-fileCareFront input[type='file']").size());
        $("#uploadifive-fileCareFront input[type='file']").eq(1).uploadPreview({
            Img: "imgFront",
            Width: 120,
            Height: 120,
            ImgType: [
                "gif", "jpeg", "jpg", "bmp", "png"
            ], Callback: function () {
                alert("back");
                $("#imgFront").show();
            }
        });


    });


    jQuery.fn.extend({
        uploadPreview: function (opts) {
            var _self = this,
                _this = $(this);
            opts = jQuery.extend({
                Img: "ImgPr",
                Width: 100,
                Height: 100,
                ImgType: ["gif", "jpeg", "jpg", "bmp", "png"],
                Callback: function () {
                }
            }, opts || {});
            _self.getObjectURL = function (file) {
                var url = null;
                if (window.createObjectURL != undefined) {
                    url = window.createObjectURL(file)
                } else if (window.URL != undefined) {
                    url = window.URL.createObjectURL(file)
                } else if (window.webkitURL != undefined) {
                    url = window.webkitURL.createObjectURL(file)
                }
                return url
            };
            _this.change(function () {
                if (this.value) {
                    if (!RegExp("\.(" + opts.ImgType.join("|") + ")$", "i").test(this.value.toLowerCase())) {
                        alert("选择文件错误,图片类型必须是" + opts.ImgType.join("，") + "中的一种");
                        this.value = "";
                        return false
                    }
                    if ($.browser.msie) {
                        try {
                            $("#" + opts.Img).attr('src', _self.getObjectURL(this.files[0]))
                        } catch (e) {
                            var src = "";
                            var obj = $("#" + opts.Img);
                            var div = obj.parent("div")[0];
                            _self.select();
                            if (top != self) {
                                window.parent.document.body.focus()
                            } else {
                                _self.blur()
                            }
                            src = document.selection.createRange().text;
                            document.selection.empty();
                            obj.hide();
                            obj.parent("div").css({
                                'filter': 'progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale)',
                                'width': opts.Width + 'px',
                                'height': opts.Height + 'px'
                            });
                            div.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = src
                        }
                    } else {
                        $("#" + opts.Img).attr('src', _self.getObjectURL(this.files[0]))
                    }
                    opts.Callback()
                }
            })
        }
    });

</script>