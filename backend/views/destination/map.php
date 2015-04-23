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
<input type="hidden" class="form-control" id="us3-lat"/>
<input type="hidden" class="form-control" id="us3-lon"/>


<div id="us3" style="width: 100%; height: 300px;"></div>

<script>

    $('#us3').locationpicker({
        location: {latitude: 39.91295943669406, longitude: 116.40617084503174},
        radius: 0,
        inputBinding: {
            latitudeInput: $('#us3-lat'),
            longitudeInput: $('#us3-lon'),
            radiusInput: $('#us3-radius'),
            locationNameInput: $('#us3-address')
        },
        enableAutocomplete: true
    });
    $('#us6-dialog').on('shown.bs.modal', function() {
        $('#us3').locationpicker('autosize');
    });
</script>
