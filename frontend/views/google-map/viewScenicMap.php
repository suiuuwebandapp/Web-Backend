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
    <link href="/assets/plugins/jquery-google-map/mapsed/mapsed.css" rel="stylesheet">
    <style>
        .mapsed-name{
            text-align: center;
            height: 30px;
            line-height: 30px;
        }
        .mapsed-left,.mapsed-right{
            display: none;
        }
    </style>
</head>

<body style="margin: 0 auto">
<div id="custom_places" class="maps" style="width: 100%;height: 300px;"></div>
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

    $(function(){
        var list=<?=$scenicObj;?>;
        $("#custom_places").mapsed({
            showOnLoad:list
        });
    });
</script>
</body>
</html>