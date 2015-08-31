<!DOCTYPE html>
<html>
<script type="text/javascript" src="/assets/other/weixin/js/jquery-1.11.1.min.js"></script>
<script>
    function show()
    {
        $("#loading").hide();
    }
</script>
<style>
    .spinner {
        margin: 300px ;
        width: 50px;
        height: 60px;
        text-align: center;
        font-size: 10px;
    }

    .spinner > div {
        background-color: #a8ff04;
        height: 100%;
        width: 6px;
        display: inline-block;

        -webkit-animation: stretchdelay 1.2s infinite ease-in-out;
        animation: stretchdelay 1.2s infinite ease-in-out;
    }

    .spinner .rect2 {
        -webkit-animation-delay: -1.1s;
        animation-delay: -1.1s;
    }

    .spinner .rect3 {
        -webkit-animation-delay: -1.0s;
        animation-delay: -1.0s;
    }

    .spinner .rect4 {
        -webkit-animation-delay: -0.9s;
        animation-delay: -0.9s;
    }

    .spinner .rect5 {
        -webkit-animation-delay: -0.8s;
        animation-delay: -0.8s;
    }

    @-webkit-keyframes stretchdelay {
        0%, 40%, 100% { -webkit-transform: scaleY(0.4) }
        20% { -webkit-transform: scaleY(1.0) }
    }

    @keyframes stretchdelay {
        0%, 40%, 100% {
            transform: scaleY(0.4);
            -webkit-transform: scaleY(0.4);
        }  20% {
               transform: scaleY(1.0);
               -webkit-transform: scaleY(1.0);
           }
    }
    .overlay
    {   position: fixed;
        top:0;
        left:0;
        height:100%;
        width:100%;
        z-index:10;
        background-color: rgb(105, 105, 105);
    }

</style>
<body onload="show()" >
<p id="demo">点击这个按钮，获得您的坐标：</p>
<button onclick="getLocation()">试一下</button>
<script>
    var x=document.getElementById("demo");
    function getLocation()
    {
        if (navigator.geolocation)
        {
            navigator.geolocation.getCurrentPosition(showPosition);
        }
        else{x.innerHTML="Geolocation is not supported by this browser.";}
    }
    function showPosition(position)
    {
        x.innerHTML="Latitude: " + position.coords.latitude +
        "<br />Longitude: " + position.coords.longitude;
    }
</script>


<div >
    <img src="http://upload.cankaoxiaoxi.com/2015/0829/1440803986448.jpg">
<div  id="box"></div>
</div>
</body>
</html>