<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/29
 * Time : 下午7:28
 * Email: zhangxinmailvip@foxmail.com
 */

?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <style>
        .mapsed-name{
            margin: 0px;
            font-size: 14px;
            color: #858585;
            font-weight: normal;
            font-family: "Microsoft YaHei";
        }
        .gm-style-iw{
            background-color: white;
            text-align: center;
            border: 1px solid #dddddd;
            border-radius: 5px;
            height: 38px;
            line-height: 33px;
            cursor: pointer;
        }
    </style>
</head>

<body style="margin: 0 auto">
<div id="custom_places" class="maps" style="width: 100%;height: 300px;"></div>
<input type="button" value="+" onclick="add()" />

<?php
$scenicObj="[";
if($scenicList!=null){
    $arrPush=array();
    foreach($scenicList as $scenic){
        if(!empty($scenic['lon'])&&!empty($scenic['lat'])){
            $temp="{autoShow:true,lat:".$scenic['lat'].",lng:".$scenic['lon'].",name:'".$scenic['name']."'}";
            $arrPush[]=$temp;
        }
    }
    $scenicObj.=implode(",",$arrPush);
}
$scenicObj.="]";
?>
<input type="hidden" />
<script src="/assets/plugins/jquery-google-map/map.js"></script>
<script src="/assets/plugins/jquery-google-map/mapsed/jquery-1.10.2.js"></script>
<script src="/assets/plugins/jquery-google-map/mapsed/mapsed.js"></script>
<script>

    var interval,list;
    var googleMap,map,m;

    $(document).ready(function(){

        list=<?=$scenicObj;?>;

        m=$("#custom_places").mapsed({
            showOnLoad:list
        });
        googleMap= m.getGoogleMap();

        initInterval();
    });


    function initInterval()
    {
        interval=window.setInterval(function(){
            if($("div[class='gm-style-iw']").size()>0){
                var showInfoDiv=$("div[class='gm-style-iw']");
                var closeDiv=$(showInfoDiv).next();
                $(closeDiv).hide();
                $(showInfoDiv).prev().remove();
                $(showInfoDiv).bind("click",function(){
                    $(closeDiv).find("img").click();
                });
                window.clearInterval(interval);
            }
            if(googleMap.getZoom()==0){
                googleMap.setZoom(10);
            }

        },30);
    }


    function clearMarker()
    {
        list=[];
        m.clearMarker();
    }

    function addMarker(lon,lat,title)
    {
        var marker={autoShow:true,lat:lat,lng:lon,name:title};
        list.push(marker);

        m.resetMarker(list);

        initInterval();

    }
</script>

</body>
</html>