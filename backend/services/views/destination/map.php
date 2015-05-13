<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/23
 * Time : 下午1:07
 * Email: zhangxinmailvip@foxmail.com
 */

?>

<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/jquery-google-map/jquery-locationpicker/src/map.js"></script>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/jquery-google-map/jquery-locationpicker/src/locationpicker.jquery.js"></script>


<input type="hidden" class="form-control" id="us3-address"/>
<input type="hidden" class="form-control" id="us3-radius"/>
<input type="hidden" class="form-control" id="us3-lat" value="<?=$lat?>"/>
<input type="hidden" class="form-control" id="us3-lon" value="<?=$lon?>"/>


<div id="us3" style="width: 100%; height: 300px;"></div>

<script>

     var lon=$("#us3-lon").val();
     var lat=$("#us3-lat").val();

     var map=$('#us3').locationpicker({
        location: {latitude:lat , longitude:lon },
        radius: 0,
        inputBinding: {
            latitudeInput: $('#us3-lat'),
            longitudeInput: $('#us3-lon'),
            radiusInput: $('#us3-radius'),
            locationNameInput: $('#us3-address')
        },
        enableAutocomplete: true,
        onchanged: function (currentLocation, radius, isMarkerDropped) {
            // Uncomment line below to show alert on each Location Changed event
            //alert("Location changed. New location (" + currentLocation.latitude + ", " + currentLocation.longitude + ")");
        }
    });

    function setMapSite(lon,lat)
    {
        if(lon!=""&&lat!=""){
            $('#us3').locationpicker({
                location: {latitude: lat, longitude: lon}
            });
            $('#us3-lat').val(lat);
            $('#us3-lon').val(lon);
        }
    }

    function test()
    {
        alert(1);
    }
</script>
