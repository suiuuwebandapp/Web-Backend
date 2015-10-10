

    <script type="text/javascript" src="/assets/other/weixin/js/jquery.SuperSlide.2.1.1.js"></script>

    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        <p class="navTop"><?php echo $info['info']['title'];?></p>
        <a href="javascript:;" class="collect <?php if(count($info['attention'])!=0){echo "active";}?>" id="collection_trip" attentionIdTrip="<?php if(count($info['attention'])!=0){echo $info['attention'][0]['attentionId'];}?>"></a>
    </div>
    <div class="syDetailBanner">
        <span class="smoney">￥<?= $info['info']['basePrice'];?></span>
        <!--banner开始-->
        <div class="bd">
            <ul class="banners" id="ul_id">
                <?php  foreach($info['picList'] as $pic){?>
                    <li class="banner01"><img src="<?= $pic['url'];?>"></li>
                <?php }?>
            </ul>
            <div class="banner-btn">
                <a class="prev" href="javascript:void(0);"></a>
                <a class="next" href="javascript:void(0);"></a>
            </div>
            <div class="hd"><ul></ul></div>
        </div>
        <!--banner结束-->
        <script type="text/javascript">
            $(document).ready(function(){

                $(".prev,.next").hover(function(){
                    $(this).stop(true,false).fadeTo("show",1);
                },function(){
                    $(this).stop(true,false).fadeTo("show",1);
                });

                $(".syDetailBanner").slide({
                    titCell:".hd ul",
                    mainCell:".banners",
                    effect:"fold",
                    interTime:3500,
                    delayTime:500,
                    autoPlay:true,
                    autoPage:true,
                    trigger:"click"
                });
                $("#ul_id").css("height",$("#ul_id").width()/830*466);
            });
        </script>
    </div>
    <div class="jtContent con">
        <div class="top clearfix">
            <a href="/wechat-user-info/user-info?userSign=<?= $createUserInfo->userSign;?>" class="userPic"><img src="<?= $createUserInfo->headImg;?>"></a>
            <span class="userName"><?=$createUserInfo->nickname;?></span>
            <p class="adress">&nbsp;<?=$info['info']['countryCname']?>，<?=$info['info']['cityCname']?></p>
        </div>
        <p class="bq"><span><?= $info['info']['tags'];?></span></p>
        <ul class="details clearfix">
            <li>
                <span class="icon icon1">服务时间
                    <b> <?php if(empty($info['info']['startTime'])){ ?>
                            全天24小时提供服务
                        <?php }else{ ?>
                            <?=\common\components\DateUtils::formatTime($info['info']['startTime']);?> -
                            <?=\common\components\DateUtils::formatTime($info['info']['endTime']);?>
                        <?php } ?></b>
                </span>
            </li>
            <li><span class="icon icon2">全天可包车<?=$info['info']['travelTime'];?>小时</b></span></li>
            <li class="last"><span class="icon icon3">最多乘坐<?=$info['trafficInfo']['seatCount'];?>人</span></li>
        </ul>
        <div class="bgbox">
            <h3 class="title colGreen">服务简介</h3>
            <?=str_replace("\n","</br>",$info['info']['info']);?>
        </div>

        <h3 class="title colGreen">车辆信息</h3>
        <div class="contian car clearfix">
            <span><b class="icon"></b>车型:<?=$info['trafficInfo']['carType'];?></span>
            <span><b class="icon"></b>司机驾龄:<?=date("Y",time())-date("Y",strtotime($info['trafficInfo']['driverLicenseDate']));?>年</span>
            <span><b class="icon"></b>携带宠物:<?=$info['trafficInfo']['allowPet']==1?'允许':'不允许';?></span>
            <span><b class="icon"></b>乘客吸烟:<?=$info['trafficInfo']['allowSmoke']==1?'允许':'不允许';?></span>
            <span><b class="icon"></b>每日公里限:<?=$info['trafficInfo']['serviceMileage'];?>公里</span>
            <span><b class="icon"></b>行李空间:<?=is_numeric($info['trafficInfo']['spaceInfo'])?$info['trafficInfo']['spaceInfo'].'件行李':$info['trafficInfo']['spaceInfo'];?>件</span>
            <span><b class="icon"></b>儿童座椅:<?=$info['trafficInfo']['childSeat']==1?'有':'无';?></span>
            <span><b class="icon"></b>最大载客:<?=$info['trafficInfo']['seatCount'];?>人</span>
            <span><b class="icon"></b>全天包车时长:<?=$info['trafficInfo']['serviceTime'];?>小时</span>
        </div>

        <div class="bgbox">
            <h3 class="title colGreen">价格内容</h3>
            <div class="contian clearfix">
                <?php foreach($info['includeDetailList'] as $val){?>
                    <span><img src="/assets/other/weixin/images/syhas.png"><?php echo $val['name']?></span>
                <?php }?>
                <?php foreach($info['unIncludeDetailList'] as $val){?>
                    <span><img src="/assets/other/weixin/images/syno.png"><?php echo $val['name']?></span>
                <?php }?>
            </div>
        </div>
        <p class="line clearfix">
            <b>用户评价<?php echo count($comment['data']);?></b>
            <img src="<?= $info['info']['score']>=10?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
            <img src="<?= $info['info']['score']>=8?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
            <img src="<?= $info['info']['score']>=6?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
            <img src="<?= $info['info']['score']>=4?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
            <img src="<?= $info['info']['score']>=2?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
        </p>
        <ul class="weblist clearfix">
            <?php foreach($comment['data'] as $val ){?>
                <li>
                    <div class="box clearfix">
                        <a href="/wechat-user-info/user-info?userSign=<?= $val['userSign'];?>" class="userPic"><img src="<?= $val['headImg']?>"></a>
                        <span class="userName"><?= $val['nickname']?></span>
                        <p class="text"><?= $val['content']?></p>
                    </div>

                </li>
            <?php }?>
        </ul>
        <div class="btns clearfix">
            <a href="/wechat-user-center/user-message-info?rUserSign=<?= $info['createPublisherInfo']['userSign'];?>" class="bgOrange fl">咨询</a>
            <a href="/wechat-trip/add-order-view?tripId=<?=$info['info']['tripId'];?>" class="bgBlue fr">预定</a>

        </div>
    </div>
<script>
    $('#collection_trip').bind('click',submitCollection);
    /**
     * 添加收藏
     */
    function submitCollection() {
        var tripId = "<?=$info['info']['tripId'];?>";
        if (tripId == '' || tripId == undefined || tripId == 0) {
            alert('未知的随游');
            return;
        }
        var isCollection = false;
        if ($('#collection_trip').attr('class') == 'collect '||$('#collection_trip').attr('class') == 'collect') {
            $('#collection_trip').addClass('active');
            isCollection = true;
        } else {
            $('#collection_trip').removeClass('active');
            isCollection = false;
        }
        if (isCollection) {
            //添加收藏
            $.ajax({
                url: '/wechat-trip/add-collection-travel',
                type: 'post',
                data: {
                    _csrf: $('input[name="_csrf"]').val(),
                    travelId: tripId
                },
                error: function () {
                    //hide load
                    alert("收藏随游失败");
                    $('#collection_trip').removeClass('active');
                    isCollection = false;
                },
                success: function (data) {
                    //hide load
                    data = eval("(" + data + ")");
                    if (data.status == 1) {
                        alert("收藏成功");
                        $('#collection_trip').attr('attentionIdTrip', data.data);
                        isCollection = true;
                    } else if (data.status == -3) {
                        $('#collection_trip').removeClass('active');
                        alert("请登录后再收藏");
                        isCollection = false;
                    } else {
                        $('#collection_trip').removeClass('active');
                        alert(data.data);
                        isCollection = false;
                    }
                }
            });
        } else {
            //取消收藏
            $.ajax({
                url: '/wechat-trip/delete-attention',
                type: 'post',
                data: {
                    _csrf: $('input[name="_csrf"]').val(),
                    attentionId: $('#collection_trip').attr('attentionIdTrip')
                },
                error: function () {
                    //hide load
                    $('#collection_trip').addClass('active');
                    isCollection = true;
                    alert("收藏随游失败");
                },
                success: function (data) {
                    //hide load
                    data = eval("(" + data + ")");
                    if (data.status == 1) {
                        alert("取消成功");
                        isCollection = false;
                    } else if (data.status == -3) {
                        $('#collection_trip').addClass('active');
                        isCollection = true;
                        alert("请登录后再取消");
                    }else{
                        $('#collection_trip').addClass('active');
                        isCollection = true;
                        alert(data.data);
                    }
                }
            });
        }
    }
</script>