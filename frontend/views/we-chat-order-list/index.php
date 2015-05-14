<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title>任性随游</title>
    <link rel="stylesheet" href="/assets/other/weixin/css/common.css" />
    <link rel="stylesheet" href="/assets/other/weixin/css/weiHtml5.css" />
    <script type="text/javascript" src="/assets/other/weixin/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="/assets/other/weixin/js/jquery.touchSwipe.min.js"></script>
    <script type="text/javascript">
        $(document).ready(
            function() {
                var nowpage = 0;
                //给最大的盒子增加事件监听
                $(".container").swipe(
                    {
                        swipe:function(event, direction, distance, duration, fingerCount) {
                            if(direction == "up"){
                                nowpage = nowpage + 1;
                            }else if(direction == "down"){
                                nowpage = nowpage - 1;
                            }

                            if(nowpage > 4){
                                nowpage = 4;
                            }

                            if(nowpage < 0){
                                nowpage = 0;
                            }

                            $(".container").animate({"top":nowpage * -100 + "%"},400);

                            $(".page").eq(nowpage).addClass("cur").siblings().removeClass("cur");
                        }
                    }
                );
            }
        );
    </script>
</head>

<body onmousewheel="return false;">
<div class="container">
    <div class="page page0 clearfix cur">
        <div class="top clearfix">
            <img src="/assets/other/weixin/images/oneZ01.png">
            <img src="/assets/other/weixin/images/oneZ02.png">
            <img src="/assets/other/weixin/images/oneZ03.png">
        </div>
        <div class="down">
            <img src="/assets/other/weixin/images/oneZ04.png">
        </div>
    </div>
    <div class="page page1 clearfix">
        <div class="ball">
            <img src="/assets/other/weixin/images/page2Q01.png">
            <img src="/assets/other/weixin/images/page2Q02.png">
            <img src="/assets/other/weixin/images/page2Q03.png">
            <img src="/assets/other/weixin/images/page2Q04.png">
        </div>
        <div class="AdTv">
            <img src="/assets/other/weixin/images/page2Bg.png" class="Tv">
            <img src="/assets/other/weixin/images/page2Zi.png" class="zi">
        </div>
        <div class="water">
            <img src="/assets/other/weixin/images/page2W1.png" class="water1">
            <img src="/assets/other/weixin/images/page2W2.png" class="water2">
            <img src="/assets/other/weixin/images/page2W3.png" class="water3">

        </div>
    </div>
    <div class="page page2 clearfix">
        <div class="top">
            <img src="/assets/other/weixin/images/page3Zi01.png">
            <img src="/assets/other/weixin/images/page3Zi02.png">
            <img src="/assets/other/weixin/images/page3Zi03.png">


        </div>
        <div class="down">
            <img src="/assets/other/weixin/images/page3che.png" class="che">
            <img src="/assets/other/weixin/images/page3lu.png" class="lu">
        </div>
    </div>
    <div class="page page3 clearfix">
        <div class="top">
            <img src="/assets/other/weixin/images/page4Zi01.png">
            <img src="/assets/other/weixin/images/page4Zi02.png">
            <img src="/assets/other/weixin/images/page4Zi03.png">
            <img src="/assets/other/weixin/images/page4Zi04.png">
        </div>
        <div class="roll">
            <img src="/assets/other/weixin/images/page4Ball.png" class="ball">
            <img src="/assets/other/weixin/images/page4Re.png" class="Re">
        </div>

    </div>
    <div class="page page4 clearfix">
        <div class="top">
            <img src="/assets/other/weixin/images/page5Sun.png" class="sun">
            <img src="/assets/other/weixin/images/page5Sun01.png"  class="zi">
        </div>
        <div class="down">
            <a href="tel:010-58483692"></a>
            <a href="#"></a>
            <a href="#"></a>


        </div>


    </div>
</div>


</body>
</html>
