<!DOCTYPE html>
<html>
<script type="text/javascript" src="/assets/other/weixin/js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="/assets/chat/js/web_socket.js"></script>
<script type="text/javascript" src="/assets/chat/js/json.js"></script>
<link rel="stylesheet" href="/assets/other/weixin/css/common.css">
<link rel="stylesheet" href="/assets/other/weixin/css/weixin.css">
<link rel="stylesheet" href="/assets/other/weixin/css/jquery.mmenu.css">
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
<body  class="bgwhite" onload="showHtml()">

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
        <p class="navTop">登陆</p>
    </div>
    <div class="con login clearfix">
        <div>
            <button onclick="send()">test</button>
        </div>
    </div>

</div>

</body>
</html>