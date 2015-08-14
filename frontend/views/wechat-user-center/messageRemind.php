<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title>我的收件箱</title>
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
    <style>
        .logo{ width:4.6rem;display:block; margin:0 auto; margin-top:6.0rem; }
        .noOrder{ line-height:1.5rem;margin-top:10px;text-align: center; }
    </style>
</head>
<body class="bgwhite">
    <div id="page" class="userCenter">
        <?php include "left.php"; ?>
        <div class="Uheader header mm-fixed-top">
            <a href="#menu"></a>
            收件箱
        </div>
        <div class="center_message">
            <?php foreach($list['data'] as $info){?>
                <div class="box clearfix" onclick="toInfo(<?=$info['relativeId']?>,<?=$info['relativeType']?>,<?=$info['rType']?>)">
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
    </div>
<script>
    function toInfo(id,relativeType,rType)
    {
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
                url='/wechat-user-center/my-order';
                break;
        }
        if(url!="")
        {
            window.location.href=url;
        }
    }
</script>
</body>
</html>