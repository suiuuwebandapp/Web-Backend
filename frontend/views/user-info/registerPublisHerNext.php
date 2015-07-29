<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/7/28
 * Time : 下午4:48
 * Email: zhangxinmailvip@foxmail.com
 */

?>

<link rel="stylesheet" type="text/css" href="/assets/plugins/imgAreaSelect/css/imgareaselect-default.css" />
<link rel="stylesheet" type="text/css" href="/assets/plugins/jquery-uploadifive/uploadifive.css">
<style type="text/css">
    .queue{
        display: none;
    }
    #uploadifive-reImg{
        display: none;
    }
    .p_chose_card_front {
        height: 267px;
        width: 440px;
        text-align: center;
        line-height: 210px;
    }
    .upload_tip {
        font-size: 12px;
        text-align: center;
    }

    .imgPic {
        cursor: pointer;
        height: 267px;
        width: 440px;
    }

    .showImg {
        height: 267px;
        width: 440px;
    }

    #uploadifive-fileCardFront {
        display: none;
    }
    .pic{
        margin-top: 20px;
    }
    .syInformation02 .userPic{
        height: auto;
    }
    .syInformation02 .userPic div{
        width: 440px;
        border-radius:0;
        position: inherit;
        background-color: white;
    }
    .syInformation02 .userPic div{

    }
</style>


<div class="syInformation02 clearfix">
	<h2 class="title">上传个人头像</h2>
    <p>清楚的正面照对用户了解彼此起着重大作用。通过一张风景照或者卡通形象认识一个人可不靠谱！因此，请上传一张能清楚看到您脸部的照片。</p>
    <form id='coordinates_form' method="post">
        <input type='hidden' id="img_x" name='x' class='x' value='0'/>
        <input type='hidden' id="img_y" name='y' class='y' value='0'/>
        <input type='hidden' id="img_w" name='w' class='w' value='0'/>
        <input type='hidden' id="img_h" name='h' class='h' value='0'/>
        <input type='hidden' id="img_rotate" name='rotate' class='rotate' value='0'/>
        <input type="hidden" id="img_src" name="src" value=""/>
    </form>
    <div class="pic clearfix" id="crop_container">
        <input type="file" id="reImg" />

        <div id="uploadBtn">
            <img id="img_origin" src="/assets/images/syi.jpg" style="width: 440px;height: 267px" border="0"/>
            <input style="display: none" class="sect" type="button" value="点击上传照片"/>
        </div>
        <input id="uploadImgCancle" class="btn cancel colOrange" type="button" value="取消"/>
    </div>

     <div class="userPic clearfix">
         <div id="reQueue" class="queue"></div>
         <div class="wdzl-img clearfix">
             <div class="p_photo1" style="width:122px;height:122px;overflow:hidden;text-align: center;overflow: hidden;border-radius:360px;margin-right: 65px">
                 <img src="<?=$this->context->userObj->headImg ?>" alt="" width="122px" height="122px" style="border-radius:0px"/>
             </div>
             <div class="p_photo2"  style="width:70px;height:70px;overflow:hidden;text-align: center;overflow: hidden;border-radius:360px;margin-top: 20px;margin-left: 20px;margin-right: 65px">
                 <img src="<?=$this->context->userObj->headImg ?>" alt="" width="70px" height="70px" style="border-radius:0px">
             </div>
             <div class="p_photo3"  style="width:50px;height:50px;overflow:hidden;text-align: center;overflow: hidden;border-radius:360px;margin-top: 35px;margin-left: 20px;">
                 <img src="<?=$this->context->userObj->headImg ?>" alt="" width="50px" height="50px" style="border-radius:0px;">
             </div>
         </div>
    </div>
    <a href="javascript:;" id="uploadImgConfirm" class="nextBtn">保存信息</a>
</div>

<script type="text/javascript" src="/assets/plugins/imgAreaSelect/js/jquery.imgareaselect.js" ></script>
<script type="text/javascript" src="/assets/plugins/jquery-uploadifive/jquery.uploadifive.min.js"></script>



<script type="text/javascript">

    var rotate;
    var rotateCount=0;
    var containerDivWidth=440;
    var containerDivHeight=270;
    var imgAreaSelectApi;

    $(document).ready(function(){
        initUploadImg();
    });
    /**
     * 初始化上传插件
     */
    function initUploadImg(){

        $('#reImg').uploadifive({
            'auto': true,
            'queueID': 'reQueue',
            'uploadScript': '/upload/upload-head-img',
            'multi': false,
            'dnd': false,
            'onUpload':function(){
                $("#uploadBtn .sect").html("正在上传，请稍后...");
                $("#uploadBtn .sect").show();
                $("#img_origin").hide();
            },
            'onUploadComplete': function (file, data) {
                var datas = eval('(' + data + ')');
                if (datas.status == 1) {
                    $("#uploadBtn .sect").html("上传成功!");
                    $("#uploadBtn .sect").hide();
                    $("#img_src").val(datas.data);
                    $("#img_origin").attr("src",datas.data);
                    $(".p_photo1 img").attr("src",datas.data);
                    $(".p_photo2 img").attr("src",datas.data);
                    $(".p_photo3 img").attr("src",datas.data);

                    $("#img_origin").show();
                    initImgAreaSelect("#img_origin");
                } else {
                    $("#uploadBtn .sect").html("上传失败，请重试");
                    $("#img_origin").hide();
                    $("#uploadBtn .sect").show();
                }
            }
        });
        $("#uploadBtn").bind("click",function(){
            $("#uploadifive-reImg input[type='file'][id!='titleImgFile']").last().click();
        });
        $("#uploadImgConfirm").bind("click",function(){
            selectImg();
        });
        $("#uploadImgCancle").bind("click",function(){
            resetUploadHeadImg();
        });

    }

    /**
     * 重置上传头像插件
     */
    function resetUploadHeadImg(){
        removeImgAreaSelect();
        $("#uploadBtn .sect").html("点击上传图片");
        $("#uploadBtn .sect").hide();
        $("#img_origin").show();
        $("#img_origin").attr("src","/assets/images/syi.jpg");
        $("#img_src").val();
    }

    /**
     * 上传头像选择IMG（截头像）
     */
    function selectImg(){
        var x=$("#img_x").val();
        var y=$("#img_y").val();
        var w=$("#img_w").val();
        var h=$("#img_h").val();
        var rotate=$("#img_rotate").val();
        var imgSrc=$("#img_src").val();
        if(imgSrc==""){
            Main.showTip("您还没有选择图片哦！");
            return;
        }
        if(w==0||h==0){
            Main.showTip("请正确选择图片！");
            return;
        }
        if(isNaN(w)||isNaN(h)){
            Main.showTip("请正确选择图片！");
            return;
        }
        $.ajax({
            url: "/user-info/register-publisher-finish",
            type: "post",
            data:{
                "x":x,
                "y":y,
                "w":w,
                "h":h,
                "rotate":rotate,
                "src":imgSrc,
                "pWidth":$("#img_origin").width(),
                "pHeight":$("#img_origin").height()
            },
            error:function(){
                alert("上传头像异常，请刷新重试！");
            },
            success: function(data){
                var result=eval("("+data+")");
                if(result.status==1){
                    Main.showTip("恭喜您已经成为随友，快去创建随游吧");
                    window.location.href="/trip/new-trip";
                    //resetUploadHeadImg();
                }else{
                    alert("上传头像异常，请刷新重试！");
                }
            }
        });
    }

    /**
     * 重置截头像插件
     */
    function resetImg(){
        imgAreaSelectApi.update();
    }

    /**
     * 移除截图选择器
     */
    function removeImgAreaSelect(){
        if(Main.isNotEmpty(imgAreaSelectApi)){
            imgAreaSelectApi.cancelSelection();
        }
    }

    /**
     * 初始化头像截取插件
     * @param imgObj
     */
    function initImgAreaSelect(imgObj){
        imgAreaSelectApi = $(imgObj).imgAreaSelect({
            instance : true,	// true，返回一个imgAreaSelect绑定到的图像的实例，可以使用api方法
            onSelectChange : preview,	// 改变选区时的回调函数
            handles : true,	// true，调整手柄则会显示在选择区域内
            fadeSpeed:200,
            resizable : true,
            aspectRatio:"1:1"

        });
        imgAreaSelectApi.setRotate(0);
        //resetRotate();
    }

    /**
     * 图片加载完成触发事件
     */
    $('#img_origin').load(function(){
        if($(this).attr("src")=="/assets/images/syi.jpg"){
            return;
        }
        var form = $('#coordinates_form');

        //获取 x、y、w、h的值
        var left = parseInt(form.children('.x').val());
        var top = parseInt(form.children('.y').val());
        var width = parseInt(form.children('.w').val());
        var height = parseInt(form.children('.h').val());

        //imgAreaSelectApi 就是图像img_origin的实例 上边instance已解释
        //setSelection(),设置选区的坐标
        //update(),更新
        imgAreaSelectApi.setSelection(left, top, left+width, top+height);
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

        if (imgWidth > 100 && imgHeight > 100) {
            imgAreaSelectApi.setSelection(0, 0, 100, 100, true);
        }


        imgAreaSelectApi.setOptions({ show: true });

        imgAreaSelectApi.update();
        preview($("#img_origin"),imgAreaSelectApi.getSelection());

    });

    /**
     * 上传完成预览事件
     * @param img
     * @param selection
     */
    function preview(img, selection){

        var form = $('#coordinates_form');
        //重新设置x、y、w、h的值
        form.children('.x').val(selection.x1);
        form.children('.y').val(selection.y1);
        form.children('.w').val(selection.x2-selection.x1);
        form.children('.h').val(selection.y2-selection.y1);
        form.children('.rotate').val(imgAreaSelectApi.getRotate());
        preview_photo('p_photo1', selection);
        preview_photo('p_photo2', selection);
        preview_photo('p_photo3', selection);


    }

    /**
     * 用户拖动头像内容，生成预览图
     * @param div_class
     * @param selection
     */
    function preview_photo(div_class, selection){
        var div = $('div.'+div_class);

        //获取div的宽度与高度
        var width = div.outerWidth();
        var height = div.outerHeight();
        var scaleX = width/selection.width;
        var scaleY = height/selection.height;

        div.find('img').css({
            width : Math.round(scaleX * $('#img_origin').outerWidth())+'px',
            height : Math.round(scaleY * $('#img_origin').outerHeight())+'px',
            marginLeft : '-'+Math.round(scaleX * selection.x1)+'px',
            marginTop : '-'+Math.round(scaleY * selection.y1)+'px'
        });
    }

    /**
     * 重置旋转（暂时没有旋转功能）
     */
    function resetRotate(){
        rotateCount=0;
        var du=0;
        rotate(document.getElementById("crop_container"), du);
        rotate(document.getElementById("p_photo"),du);
        imgAreaSelectApi.setRotate(du);
        imgAreaSelectApi.update();
    }
</script>