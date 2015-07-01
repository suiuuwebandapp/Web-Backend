<!doctype html>
<html lang="zh-CN"><head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title>随游</title>
    <link rel="stylesheet" href="/assets/other/weixin/css/weixin.css">
    <link rel="stylesheet" href="/assets/other/weixin/css/pagepiling.css" />
    <script type="text/javascript" src="/assets/other/weixin/js/jquery-1.11.1.min.js"></script>
    <!-- 上下滚动JS/css play -->
    <script type="text/javascript" src="/assets/other/weixin/js/pagepiling.js"></script>
    <script type="text/javascript">
        $(function() {
            $('#pagepiling').pagepiling({
                sectionsColor: ['#fff','#fff', '#fff', '#fff','#fff'],
                navigation: {
                    'position': 'right',
                    'tooltips': ['Page 1', 'Page 2', 'Page 3', 'Page 4', 'Pgae 5']
                }
            });
        });

    </script>
    <!-- 上下滚动JS/css stop -->

</head>
<body>
<div id="pagepiling">
    <div class="section" id="section1">
        <div class="box">
            <h2 class="title">澳大利亚</h2>
            <p class="detail">和神兽一起玩耍</p>
            <img src="/assets/other/weixin/images/pic01.jpg" class="pics">
            <div class="search_out">
                <input type="text" placeholder="你的旅行目的地" class="search">
                <a href="###" class="btn"><img src="/assets/other/weixin/images/top-search.png"> </a>
            </div>

        </div>
    </div>
    <div class="section" id="section2">
        <div class="box">
            <h2 class="title">香港</h2>
            <p class="detail">校园行一起做学霸</p>
            <img src="/assets/other/weixin/images/pic02.jpg" class="pics">
            <div class="search_out">
                <input type="text" placeholder="你的旅行目的地" class="search">
                <a href="###" class="btn"><img src="/assets/other/weixin/images/top-search.png"> </a>
            </div>

        </div>
    </div>
    <div class="section" id="section3">
        <div class="box">
            <h2 class="title">新加坡</h2>
            <p class="detail">不只有鱼尾狮</p>
            <img src="/assets/other/weixin/images/pic03.jpg" class="pics">
            <div class="search_out">
                <input type="text" placeholder="你的旅行目的地" class="search">
                <a href="###" class="btn"><img src="/assets/other/weixin/images/top-search.png"> </a>
            </div>

        </div>
    </div>
    <div class="section" id="section4">
        <div class="box">
            <h2 class="title">意大利</h2>
            <p class="detail">买买买</p>
            <img src="/assets/other/weixin/images/pic04.jpg" class="pics">
            <div class="search_out">
                <input type="text" placeholder="你的旅行目的地" class="search">
                <a href="###" class="btn"><img src="/assets/other/weixin/images/top-search.png"> </a>
            </div>

        </div>
    </div>
    <div class="section" id="section5">
        <div class="content">
            <?php foreach($recommendTravel as $val){?>
            <div class="box01">
                <a href="" class="pic"><img src="<?php echo $val['titleImg']?>"></a>
                <div class="details">
                    <h3 class="title"><?php echo $val['title']?></h3>
                    <p class="line clearfix">
                        <b class="colOrange">￥<?php echo $val['basePrice']?></b>
                        <img src="<?= $val['score']>=2?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                        <img src="<?= $val['score']>=4?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                        <img src="<?= $val['score']>=6?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                        <img src="<?= $val['score']>=8?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                        <img src="<?= $val['score']>=10?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                    </p>
                </div>
            </div>
            <?php }?>
        </div>
    </div>
</div>
</body>
</html>