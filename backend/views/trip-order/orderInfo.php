<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/6/17
 * Time : 上午9:40
 * Email: zhangxinmailvip@foxmail.com
 */

?>


<?php
if($orderInfo!=null){
    $info=$orderInfo['info'];
    $user=$orderInfo['user'];
    $publisher=$orderInfo['publisher'];
    $travelInfo=json_decode($info['tripJsonInfo'],true);
    $serviceList=json_decode($info['serviceInfo'],true);
}else{
    throw new \yii\base\Exception("无效的订单信息");
}
?>

<!DOCTYPE html>
<head>
</head>
<body>
<form onsubmit="return false"  id=form_validate class="form-horizontal" novalidate="novalidate">
    <div class="modal-dialog" style="width: 700px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="false"></button>
                <h4 class="modal-title">
                    订单详情
                </h4>
            </div>
            <div class="modal-body">
                <div class="scroller" style="height: 400px" data-always-visible="1" data-rail-visible1="1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet-body form">
                                <!-- BEGIN FORM-->
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="control-label col-md-4">订单号:</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"><?=$info['orderNumber']?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="control-label col-md-4">订单状态:</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static">
                                                    <?=\common\entity\UserOrderInfo::getOrderStatusDes($info['status']); ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="control-label col-md-4">随游:</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"><a target="_blank" href="<?=\common\components\SiteUrl::getTripUrl($travelInfo['info']['tripId'])?>"><?=$travelInfo['info']['title']?></a></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="control-label col-md-4">订单金额:</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"><?=$info['totalPrice']?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="control-label col-md-4">创建日期:</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"><?=$info['createTime']?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="control-label col-md-4">用户昵称:</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"><?=$user['nickname']?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="control-label col-md-4">用户手机:</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"><?=$user['phone']?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="control-label col-md-4">用户邮箱:</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"><?=empty($user['email'])?"暂无":$user['email'];?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="control-label col-md-4">出行人数:</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"><?=$info['personCount']?>人</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="control-label col-md-4">出行日期:</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"><?=$info['beginDate']." ".\common\components\DateUtils::convertTimePicker($info['startTime'],2);?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="control-label col-md-4">基础价格:</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static">
                                                    ￥<?=$info['basePrice']?>
                                                    <?=isset($info['basePriceType'])&&$info['basePriceType']==\common\entity\TravelTrip::TRAVEL_TRIP_BASE_PRICE_TYPE_COUNT?"/次":"/人"?>

                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="control-label col-md-4">附加服务:</label>
                                            <div class="col-md-8">
                                                <?php  foreach($travelInfo['serviceList'] as $service){ ?>
                                                    <p class="form-control-static">
                                                        <?=$service['title']?> ￥<?=$service['money']?>
                                                        <?=$service['type']==\common\entity\TravelTripService::TRAVEL_TRIP_SERVICE_TYPE_COUNT?"/次":"/人"?>
                                                    </p>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if(isset($publisher)){ ?>
                                        <hr/><!-- 随友信息 -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="control-label col-md-4">接单随友:</label>
                                                <div class="col-md-8">
                                                    <p class="form-control-static"><?=$publisher['nickname']?></p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="control-label col-md-4">接单时间:</label>
                                                <div class="col-md-8">
                                                    <p class="form-control-static"><?=isset($info['confirmTime'])?$info['confirmTime']:"";?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="control-label col-md-4">随友手机:</label>
                                                <div class="col-md-8">
                                                    <p class="form-control-static"><?=$publisher['phone']?></p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="control-label col-md-4">随友邮箱:</label>
                                                <div class="col-md-8">
                                                    <p class="form-control-static"><?=$publisher['email'];?></p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <!-- END FORM-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="modal_close" data-dismiss="modal" class="btn default">
                    关闭
                </button>
            </div>
        </div>
    </div>
</form>
<!-- END PAGE LEVEL STYLES -->

</body>
</html>