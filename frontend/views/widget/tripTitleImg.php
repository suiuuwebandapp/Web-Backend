<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/8/25
 * Time : 下午8:07
 * Email: zhangxinmailvip@foxmail.com
 */

?>

<link rel="stylesheet" type="text/css" href="/assets/plugins/jquery-uploadifive/uploadifive.css">
<link rel="stylesheet" type="text/css" href="/assets/plugins/imgAreaSelect/css/imgareaselect-default.css"/>

<script type="text/javascript" src="/assets/plugins/jquery-uploadifive/jquery.uploadifive.min.js"></script>
<script type="text/javascript" src="/assets/plugins/imgAreaSelect/js/jquery.imgareaselect.base.js"></script>


<span class="spn_title">封面</span>
<span id="titleImgTip" class="form_tip"></span>

<?php if(empty($defaultImg)){ ?>
    <div id="divCardFront" class="pic fPic">
        <img src="<?=empty($defaultImg)?'':$defaultImg?>" id="titleImg" style="display: none" class="showImg"/>
        <p class="p_chose_title_img">点击上传封面图</p>
    </div>
<?php } else{ ?>
    <div id="divCardFront" class="pic fPic">
        <img src="<?=empty($defaultImg)?'':$defaultImg?>" id="titleImg"  class="showImg"/>
        <p class="p_chose_title_img" style="display: none">点击上传封面图</p>
    </div>

<?php }?>

<input id="titleImgFile" type="file" style="display: none"/>

<p class="upload_tip">上传文件大小请不能大于2M，支持格式png、jpg、jpeg</p> <br/>


<form id='coordinates_form' method="post">
    <input type='hidden' id="img_x" name='x' class='x' value='0'/>
    <input type='hidden' id="img_y" name='y' class='y' value='0'/>
    <input type='hidden' id="img_w" name='w' class='w' value='0'/>
    <input type='hidden' id="img_h" name='h' class='h' value='0'/>
    <input type='hidden' id="img_rotate" name='rotate' class='rotate' value='0'/>
    <input type="hidden" id="img_src" name="src" value=""/>
</form>

<div id="showTripImgDiv" class="picPop" style="display: none">
    <div class="clearfix">
        <p id="show_img_tip">正在上传...</p>
        <img id="img_origin" style="display: none"/>
    </div>
    <a href="javascript:;" class="btn sure" id="show_img_confirm">确定</a>
    <a href="javascript:;" class="btn cancle" id="show_img_cancel">取消</a>
</div>

<script type="text/javascript">


    $(document).ready(function(){

        initUploadTitleImg();
        CutImg.initCutImg();

    });

    var CutImg = function () {
        var rotate;
        var rotateCount = 0;
        var containerDivWidth = 520;
        var containerDivHeight = 450;

        var x = 153;
        var y = 100;

        var imgAreaSelectApi;


        return {
            initCutImg: function () {
                //截图弹窗点击确认  选择截取头像
                $("#show_img_confirm").bind("click", function () {
                    CutImg.selectImg();
                });

                //弹窗点击取消
                $("#show_img_cancel").bind("click", function () {
                    $("#showTripImgDiv").hide();
                    $("#myMask").hide();
                    CutImg.removeImgAreaSelect();
                });

                //图片加载完成触发事件
                $('#img_origin').load(function () {
                    var form = $('#coordinates_form');

                    //获取 x、y、w、h的值
                    var left = parseInt(form.children('.x').val());
                    var top = parseInt(form.children('.y').val());
                    var width = parseInt(form.children('.w').val());
                    var height = parseInt(form.children('.h').val());

                    //imgAreaSelectApi 就是图像img_origin的实例 上边instance已解释
                    //setSelection(),设置选区的坐标
                    //update(),更新
                    imgAreaSelectApi.setSelection(left, top, left + width, top + height);
                    imgAreaSelectApi.update();

                    //图片居中
                    var imgWidth = $("#img_origin").width();
                    var imgHeight = $("#img_origin").height();
                    $("#img_origin").css("margin", "0");
                    $("#img_origin").attr("oldWidth", imgWidth);
                    $("#img_origin").attr("oldHeight", imgHeight);

                    if ((containerDivWidth / containerDivHeight) < (imgWidth / imgHeight)) {
                        $("#img_origin").width(containerDivWidth);
                    } else {
                        $("#img_origin").height(containerDivHeight);
                    }
                    imgWidth = $("#img_origin").width();
                    imgHeight = $("#img_origin").height();

                    if (imgWidth > imgHeight) {
                        var padding = (containerDivHeight - imgHeight) / 2;
                        $("#img_origin").css("margin-top", padding);
                        //imgAreaSelectApi.setSelection((imgWidth/2)-(imgHeight/4), (imgHeight/2)-(imgHeight/4), (imgWidth/2)+(imgHeight/4), (imgHeight/2)+(imgHeight/4), true);

                    }
                    if (imgHeight > imgWidth) {
                        var padding = (containerDivWidth - imgWidth) / 2;
                        $("#img_origin").css("margin-left", padding);
                        //imgAreaSelectApi.setSelection((imgHeight/2)-(imgWidth/4)-padding, (imgHeight/2)-(imgWidth/4), (imgHeight/2)+(imgWidth/4)-padding, (imgHeight/2)+(imgWidth/4), true);

                    }
                    if (imgHeight == imgWidth) {
                        if (containerDivHeight > containerDivWidth) {
                            $("#img_origin").css("margin-top", (containerDivHeight - imgHeight) / 2);
                        } else {
                            $("#img_origin").css("margin-left", (containerDivWidth - imgWidth) / 2);
                        }
                    }
                    if (imgWidth > 153 && imgHeight > 100) {
                        imgAreaSelectApi.setSelection(0, 0, 153, 100, true);
                    }
                    imgAreaSelectApi.setOptions({show: true});

                    imgAreaSelectApi.update();
                    CutImg.preview($("#img_origin"), imgAreaSelectApi.getSelection());

                });
            },
            /**
             * 重置剪切图片插件
             */
            resetUploadHeadImg: function () {
                removeImgAreaSelect();
                $("#uploadBtn").val("点击上传图片");
                $("#uploadBtn").show();
                $("#img_origin").hide();
                $("#img_origin").attr("src", "");
                $("#img_src").val();
            },

            /**
             * 重置截头像插件
             */
            resetImg: function () {
                imgAreaSelectApi.update();
            },

            /**
             * 移除截图选择器
             */
            removeImgAreaSelect: function () {
                if (Main.isNotEmpty(imgAreaSelectApi)) {
                    imgAreaSelectApi.cancelSelection();
                }
            },

            /**
             * 初始化头像截取插件
             * @param imgObj
             */
            initImgAreaSelect: function (imgObj) {
                imgAreaSelectApi = $(imgObj).imgAreaSelect({
                    instance: true,	// true，返回一个imgAreaSelect绑定到的图像的实例，可以使用api方法
                    onSelectChange: this.preview,	// 改变选区时的回调函数
                    handles: true,	// true，调整手柄则会显示在选择区域内
                    fadeSpeed: 200,
                    resizable: true,
                    aspectRatio: "153:100"
                });
                $(".mask").unbind("click");
            },

            /**
             * 上传完成预览事件
             * @param img
             * @param selection
             */
            preview: function (img, selection) {
                var form = $('#coordinates_form');
                //重新设置x、y、w、h的值
                form.children('.x').val(selection.x1);
                form.children('.y').val(selection.y1);
                form.children('.w').val(selection.x2 - selection.x1);
                form.children('.h').val(selection.y2 - selection.y1);
            },

            /**
             * 上传头像选择IMG（截头像）
             */
            selectImg: function () {
                var x = $("#img_x").val();
                var y = $("#img_y").val();
                var w = $("#img_w").val();
                var h = $("#img_h").val();
                var imgSrc = $("#img_src").val();

                if (imgSrc == "") {
                    Main.showTip("您还没有选择图片哦！");
                    return;
                }
                if (w == 0 || h == 0) {
                    Main.showTip("请正确选择图片！");
                    return;
                }
                if (isNaN(w) || isNaN(h)) {
                    Main.showTip("请正确选择图片！");
                    return;
                }


                $(".p_chose_title_img").html("正在上传，请稍后。。。");
                $(".p_chose_title_img").show();
                $("#titleImg").hide();
                $("#titleImg").attr("src", "");
                $("#show_img_cancel").click();


                $.ajax({
                    url: "/upload/cut-trip-img",
                    type: "post",
                    data: {
                        "x": x,
                        "y": y,
                        "w": w,
                        "h": h,
                        "src": imgSrc,
                        "pWidth": $("#img_origin").width(),
                        "pHeight": $("#img_origin").height()
                    },
                    error: function () {
                        Main.showTip("上传随游图片异常，请刷新重试！");
                    },
                    success: function (data) {
                        var result = eval("(" + data + ")");
                        if (result.status == 1) {
                            $(".p_chose_title_img").html("");
                            $(".p_chose_title_img").hide();
                            $("#titleImg").show();
                            $("#titleImg").attr("src", result.data);

                            return;
                        } else {
                            $(".p_chose_title_img").val("上传失败，请稍后重试。。。");
                        }
                    }
                });
            }

        };
    }();


    /**
     * 初始化封面图上传插件
     */
    function initUploadTitleImg() {

        $("#titleImgFile").bind("change", function () {
            var file = this.files[0];
            //判断类型是不是图片
            if (!/image\/\w+/.test(file.type)) {
                Main.showTip("请确保文件为图像类型");
                return false;
            }
            if (file.type.indexOf("gif") != -1) {
                Main.showTip("请确保文件为图像类型为JPG、PNG");
                return false;
            }
            //判断大小
            if (file.size > 2048000) {
                Main.showTip("图片大小不能超过2M");
                return false;
            }
            var reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = function (e) {
                //$("#upload_div").append('<img src="'+this.result+'" width="205" height="115">');
                if ($("#showTripImgDiv").css("display") == "none") {
                    $("#myMask").show();
                    $("#showTripImgDiv").show();
                }
                $("#img_origin").width("");
                $("#img_origin").height("");
                $("#img_origin").attr("src", this.result);
                $("#img_src").val(this.result);
                $("#img_origin").show();
                $("#show_img_tip").hide();
                $("#uploadBtn").hide();
                $("#titleImgTip").html("");
                CutImg.initImgAreaSelect("#img_origin");
            }
        });

        $("#divCardFront").bind("click", function () {
            $("#titleImgFile").click();
        });
    }

</script>
