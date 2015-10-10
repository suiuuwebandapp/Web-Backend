

    <style>
        .logo{ width:4.6rem;display:block; margin:0 auto; margin-top:6.0rem; }
        .noOrder{ line-height:1.5rem;margin-top:10px;text-align: center; }
    </style>
        <div class="Uheader header mm-fixed-top">
            <a href="#menu"></a>
            收件箱
        </div>
        <div class="center_message" id="messageDiv">
            <?php foreach($sessionList as $info){?>
                <div sessionId="<?=$info["sessionKey"]?>" class="box clearfix" onclick="messageInfo('<?=$info["sessionKey"]?>','<?=$info["relateId"]?>')">
                    <div class="top clearfix">
                        <a href="#" class="userPic"><img src="<?php echo $info['headImg']?$info['headImg']:"/assets/other/weixin/images/logo01.png";?>"></a>
                        <span class="userName"><?php echo $info['nickname']?$info['nickname']:"随游网";?></span>
                    </div>
                    <p>
                       <?php echo $info['lastContentInfo'];?>
                    </p>
                </div>
            <?php }?>
            <?php foreach($list['data'] as $info){?>
                <div class="box clearfix" onclick="toInfo(<?=$info['remindId']?>,<?=$info['relativeId']?>,<?=$info['relativeType']?>,<?=$info['rType']?>)">
                    <div class="top clearfix">
                        <a href="#" class="userPic"><img src="<?php echo $info['headImg']?$info['headImg']:"/assets/other/weixin/images/logo01.png";?>"></a>
                        <span class="userName"><?php echo $info['nickname']?$info['nickname']:"随游网";?></span>
                    </div>
                    <p>
                        <?php echo $info['content'];?>
                        <?php
                        /*                        switch($info['relativeType'])
                                                {
                                                    case "1";
                                                        echo "评论了你";
                                                        break;
                                                    case "2";
                                                        echo 2;
                                                        break;
                                                    case "3";
                                                        echo 3;
                                                        break;
                                                    default:
                                                        echo "您有新的消息";
                                                }
                                                */?>
                    </p>
                </div>
            <?php }?>
        </div>
<script>
    function messageInfo(sessionKey,rUserSign){
        window.location.href='/wechat-user-center/user-message-info?sessionKey='+sessionKey+"&rUserSign="+rUserSign;
    }
    function changeSystemMessageRead(messageId){
        $.ajax({
            type: 'post',
            url: '/wechat-user-center/change-system-message-read',
            data: {
                _csrf: $('input[name="_csrf"]').val(),
                messageId:messageId
            },
            error:function(){
            },
            success: function (data) {
            }
        });

    }
    function toInfo(remindId,id,relativeType,rType)
    {

        changeSystemMessageRead(remindId);
        ///wechat-user-center/trip-order
        ///wechat-user-center/my-order
        ///wechat-user-center/my-order-info?id=2015062449535499
        var url="";
        switch (rType)
        {
            case 1:
                switch (relativeType)
                {
                    case 9:
                        url="/wechat-user-center/my-order-info?id="+id;
                        break;
                    case 10:
                        url='/wechat-user-center/trip-order';
                        break;
                    case 11:
                        url="/wechat-user-center/my-order-info?id="+id;
                        break;
                    case 12:
                        url='/wechat-user-center/trip-order';
                        break;
                }
                break;
            case 6:
                alert('请到官网修改');
                break;
            case 7:
                switch (relativeType)
                {
                    case 9:
                        url="/wechat-user-center/my-order-info?id="+id;
                        break;
                    case 10:
                        url='/wechat-user-center/trip-order';
                        break;
                    case 11:
                        url="/wechat-user-center/my-order-info?id="+id;
                        break;
                    case 12:
                        url='/wechat-user-center/trip-order';
                        break;
                    default :
                        url='/wechat-user-center/my-order';
                }
                break;
        }
        if(url!="")
        {
            window.location.href=url;
        }
    }
</script>
<script>
    function newMessage(messageInfo)
    {
        var relateId=messageInfo.receive_id;
        var sessionKey=messageInfo.session_key;
        var headImg=messageInfo.sender_HeadImg;
        var content=messageInfo.content;
        var nickName=messageInfo.sender_name;
        $("#messageDiv").find("div").each(function(){
            var sessionId = $(this).attr("sessionId");
            if(sessionId==sessionKey)
            {
                $(this).remove();
            }
        });
        var str = '<div sessionId="';
        str+=sessionKey;
        str+='"class="box clearfix" onclick="messageInfo(';
        str+="'"+sessionKey;
        str+="','";
        str+=relateId;
        str+="')";
        str+='">';
        str+='<div class="top clearfix">';
        str+='<a href="#" class="userPic"><img src="' ;
        str+=headImg;
        str+='"></a>';
        str+='<span class="userName">' +nickName+
        '</span>';
        str+=' </div>';
        str+='<p>';
        str+=content;
        str+='</p>';
        str+='</div>';
        $("#messageDiv").prepend(str);
    }
</script>
