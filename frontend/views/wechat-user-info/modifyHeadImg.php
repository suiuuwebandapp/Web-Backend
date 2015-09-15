<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title>随游</title>
    <link rel="stylesheet" href="/assets/other/weixin/css/common.css">
    <link rel="stylesheet" href="/assets/other/weixin/css/jquery.mmenu.css">
    <link rel="stylesheet" href="/assets/other/weixin/css/weixin.css">
    <script type="text/javascript" src="/assets/other/weixin/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="/assets/other/weixin/js/jquery.mmenu.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $('div#menu').mmenu();
        });
    </script>
    <script>
        function showHtml()
        {
            $("#page").show();
            $("#loading").hide();
        }
    </script>
    <link rel="stylesheet" href="/assets/other/weixin/css/loading.css">
    <link rel="stylesheet" href="/assets/cropper/cropper.min.css">
    <script src="/assets/cropper/cropper.min.js"></script>
</head>

<body onload="showHtml()">

<div id="loading" class="overlay">
    <div class="spinner" id="loading">
        <div class="rect1"></div>
        <div class="rect2"></div>
        <div class="rect3"></div>
        <div class="rect4"></div>
        <div class="rect5"></div>
    </div>
</div>
<div id="page" hidden="hidden" class="userCenter">
    <?php include "left.php"; ?>
    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        <p class="navTop">上传头像</p>
        <a href="javascript:;" class="sures" onclick="submitUserInfo()">确定</a>
    </div>
    <div class="con cshezhi_ziliao clearfix">
        <div class="box">
            <div class="user userPic clearfix">
                <a href="#" class="pic"><img src="<?=$userInfo["headImg"]?>"></a>
            </div>
            <div class="Pic"  id="localImag">
                <img id="preview" src="<?=$userInfo["headImg"]?>">
            </div>
            <div class="fileBtn">
                <form class="avatar-form" action="/wechat-user-info/wechat-upload-head-img" enctype="multipart/form-data" method="post" id="avatar-form">
                <input id="avatar_src" type="hidden" class="avatar-src" name="avatar_src">
                <input id="avatar_data" type="hidden" class="avatar-data" name="avatar_data">
                <label for="file_head">上传头像</label><input type="file" id="file_head" name="file_head" onchange="javascript:setImagePreview();">
                </form>
            </div>
        </div>
    </div>

    <div class="container" style="margin-top: 10%;">
        <iframe name="uploadfrm" id="uploadfrm" style="display: none;"></iframe>
        <form name="formHead" method="post" action="" id="formHead" enctype="multipart/form-data" target="uploadfrm">

            <div>
                <div>
                   <!-- <input type="file" name="file_head" id="file_head" onchange="javascript:setImagePreview();" />-->
                </div>
                <div>
                    <div id="DivUp" style="display: none">
                        <input type="submit" data-inline="true" id="BtnUp" value="确认上传" data-mini="true" />
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
    <script type="text/javascript">
        function setImagePreview() {
            var preview, img_txt, localImag, file_head = document.getElementById("file_head"),
                picture = file_head.value;
            if (!picture.match(/.jpg|.gif|.png|.bmp|.jpeg/i)) return alert("您上传的图片格式不正确1，请重新选择！"),
                !1;
            if (preview = document.getElementById("preview"), file_head.files && file_head.files[0])
            {
                preview.src = window.navigator.userAgent.indexOf("Chrome") >= 1 || window.navigator.userAgent.indexOf("Safari") >= 1 ? window.webkitURL.createObjectURL(file_head.files[0]) : window.URL.createObjectURL(file_head.files[0]);
            }else {
                file_head.select(),
                    file_head.blur(),
                    img_txt = document.selection.createRange().text,
                    localImag = document.getElementById("localImag");
                try {
                    localImag.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale)",
                        localImag.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = img_txt
                } catch(f) {
                    return alert("您上传的图片格式不正确，请重新选择！"),
                        !1
                }
                preview.style.display = "none",
                    document.selection.empty()
            }
            var $previews = $('.pic');
            $('#preview').cropper({
                aspectRatio: 1 / 1,
                dragCrop:false,
                build: function (e) {
                    var $clone = $(this).clone();
                    $clone.css({
                    });

                    $previews.html($clone);
                },

                crop: function (e) {
                    var imageData = $(this).cropper('getImageData');
                    var previewAspectRatio = e.width / e.height;

                    $previews.each(function () {
                        var $preview = $(this);
                        var previewWidth = $preview.width();
                        var previewHeight = previewWidth / previewAspectRatio;
                        var imageScaledRatio = e.width / previewWidth;

                        $preview.height(previewHeight).find('img').css({
                            width: imageData.naturalWidth / imageScaledRatio,
                            height: imageData.naturalHeight / imageScaledRatio,
                            marginLeft: -e.x / imageScaledRatio,
                            marginTop: -e.y / imageScaledRatio
                        });
                        var json = [
                            '{"x":' + e.x,
                            '"y":' + e.y,
                            '"height":' + e.height,
                            '"width":' + e.width,
                            '"rotate":' + e.rotate + '}'
                        ].join();

                        $("#avatar_data").val(json);
                    });
                }
            });
        }
    </script>
<script>
    function submitUserInfo()
    {
        var data=new FormData($("#avatar-form")[0]);
        $.ajax("/wechat-user-info/wechat-upload-head-img", {
            type: 'post',
            data: data,
            dataType: 'json',
            processData: false,
            contentType: false,

            beforeSend: function () {
                alert("上传中...");
            },

            success: function (data) {
                if(data.status==1){
                    window.location.href="/wechat-user-info/info";
                }else if(data.status==-1){
                    alert("上传头像异常");
                }else{
                    alert(data.data);
                }
            },

            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert("上传头像异常");
            },

            complete: function () {

            }
        });
        return;
        $("#avatar-form").submit();
    }
</script>
<script>
    function to(url)
    {
        window.location.href=url;
    }
</script>
</body>
</html>