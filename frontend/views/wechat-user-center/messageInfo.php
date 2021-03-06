<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title>随游</title>
    <link rel="stylesheet" href="/assets/other/weixin/css/common.css">
    <link rel="stylesheet" href="/assets/other/weixin/css/weixin.css">
    <script type="text/javascript" src="/assets/other/weixin/js/jquery-1.11.1.min.js"></script>
</head>
<script>
    var sessionId = '<?=session_id()?>';
</script>
<?php if(!empty($userObj)&&!empty($userObj->userSign)){
    ?>
    <script type="text/javascript" src="/assets/other/weixin/js/ws.js"></script>
<?php }?>
<body  class="bgwhite">

<div class="con center_message02 clearfix">
    <div class="talk clearfix" id="messageDiv">
        <ul class="clearfix" id="messageUl">
            <?php foreach($list as $info){?>
                <?php if($info['senderId'] == $userSign){?>
            <li class="you clearfix">
                <img src="<?php echo isset($userObj->headImg)?$userObj->headImg:"/assets/other/weixin/images/logo01.png" ?>" class="pic">
                <p class="p1"><?php echo $info['content'];?></p>
            </li>
                    <?php }else{?>
            <li class="zuo clearfix">
                <img src="<?php echo isset($rInfo->headImg)?$rInfo->headImg:"/assets/other/weixin/images/logo01.png" ?>" class="pic">
                <p class="p1"><?php echo $info['content'];?></p>
            </li>
                    <?php }?>
            <?php }?>
        </ul>
    </div>
    <div class="pass">
        <a href="javascript:;" class="btn" onclick="wxSendMessage('<?=$rInfo->userSign?>','<?php echo isset($userObj->headImg)?$userObj->headImg:"/assets/other/weixin/images/logo01.png" ?>')">发送</a>
        <input id="wxSendMessage" type="text" class="text" onfocus="onfocus_1()" onblur="clearTimeout_1()">
    </div>
</div>

<script>
    $(document).ready(function(){
        changeHight();
        window.addEventListener('keydown', doKeyDown,true);
    });
var timeOutId=0;
    function onfocus_1()
    {

        /*alert(document.body.clientHeight);
        alert(document.body.offsetHeight);
        alert(document.body.scrollHeight);
        alert(document.body.scrollTop);
        alert(window.screen.height);
        alert(window.screen.availHeight);*/
        timeOutId=setInterval(function(){ $(window).scrollTop(document.body.scrollHeight/2+20);},20);


    }
    function clearTimeout_1()
    {
        clearInterval(timeOutId);
    }
    function doKeyDown(e)
    {
        var keyID = e.keyCode ? e.keyCode :e.which;
        if(keyID==13)
        {
            wxSendMessage('<?=$rInfo->userSign?>','<?php echo isset($userObj->headImg)?$userObj->headImg:"/assets/other/weixin/images/logo01.png" ?>');
        }
    }
    function newMessage(messageInfo)
    {
        var headImg=messageInfo.sender_HeadImg;
        var content=messageInfo.content;
        var str = '<li class="zuo clearfix">';
        str+='<img src="' ;
        str+= headImg;
        str+='" class="pic">';
        str+='<p class="p1">';
        str+=content;
        str+='</p>';
        str+='</li>';
        $("#messageUl").append(str);
        changeHight();
    }
</script>
</body>
</html>
