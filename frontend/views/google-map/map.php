<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/27
 * Time : 下午8:38
 * Email: zhangxinmailvip@foxmail.com
 */

?>
<body style="margin: 0px">
<script type="text/javascript" src="/assets/plugins/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="/assets/plugins/jquery-google-map/jquery-locationpicker/src/map.js"></script>
<script type="text/javascript"
        src="/assets/plugins/jquery-google-map/jquery-locationpicker/src/locationpicker.jquery.js"></script>


<input type="hidden" class="form-control" id="us3-address"/>
<input type="hidden" class="form-control" id="us3-radius"/>
<input type="hidden" class="form-control" id="us3-lat" value="<?= $lat ?>"/>
<input type="hidden" class="form-control" id="us3-lon" value="<?= $lon ?>"/>


<div id="us3" style="width: 440px; height: 260px;"></div>

<script>

    var lon = $("#us3-lon").val();
    var lat = $("#us3-lat").val();

    var map = $('#us3').locationpicker({
        location: {latitude: lat, longitude: lon},
        radius: 20,
        inputBinding: {
            latitudeInput: $('#us3-lat'),
            longitudeInput: $('#us3-lon'),
            radiusInput: $('#us3-radius'),
            locationNameInput: $('#us3-address')
        },
        enableAutocomplete: true,
        onchanged: function (currentLocation, radius, isMarkerDropped) {
            console.info(currentLocation);
            $("#us3-lon").val(currentLocation.longitude);
            $("#us3-lat").val(currentLocation.latitude);

            // Uncomment line below to show alert on each Location Changed event
            //alert("Location changed. New location (" + currentLocation.latitude + ", " + currentLocation.longitude + ")");
        }
    });

    function setMapSite(lon, lat) {
        if (lon != "" && lat != "") {
            $('#us3').locationpicker({
                location: {latitude: lat, longitude: lon}
            });
            $('#us3-lat').val(lat);
            $('#us3-lon').val(lon);
        }
    }

    function getMapSite() {
        return $("#us3-lon").val()+","+$("#us3-lat").val();
    }

    function test() {
        alert(1);
    }
</script>

</body>