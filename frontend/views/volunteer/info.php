<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/10/15
 * Time : 15:53
 * Email: zhangxinmailvip@foxmail.com
 */

?>
<!-- Mobiscroll JS and CSS Includes -->
<link rel="stylesheet" href="/assets/other/weixin/css/mobiscroll.custom-2.14.4.min.css" type="text/css" />


<style type="text/css">
    .zhiyuanNav .inner .p1{font-size: 20px}
    .mbsc-mobiscroll .dwwr{background: #ffffff;}
    .mbsc-mobiscroll .dw-persp{text-align: center;}
    .sydetail .web-right .kuang{padding-bottom: 0px;}
    .mbsc-mobiscroll .dw-cal .dw-sel .dw-i{border-radius: 50%;}
    .mbsc-ic-arrow-left5:before{background-position: 0 0 !important;background-size: 36px 40px !important;}
    .mbsc-ic-arrow-right5:before{background-position: 0 0 !important;background-size: 36px 40px !important;}
</style>

<div class="zybanner_out">
    <div class="sydetailBanner web-banner" id="imgs">
        <div class="banner">
            <ul class="clearfix">
                <?php if(!empty($volunteerInfo['picList'])){$volunteerInfo['picList']=json_decode($volunteerInfo['picList'],true); ?>
                    <?php foreach($volunteerInfo['picList'] as $pic ){?>
                        <li style="width:auto"><a href="javascript:;"><img src="<?= $pic;?>" height="335px" alt=""></a></li>
                    <?php }?>
                <?php } ?>
            </ul>
        </div>
        <a href="javascript:;" class="pre"></a>
        <a href="javascript:;" class="next"></a>
    </div>
    <div class="zhiyuanNav clearfix">
        <div class="inner">
            <h3 class="title"><?=$volunteerInfo['title']?></h3>
            <p class="p1"><?=$volunteerInfo['countryCname']?> <?=$volunteerInfo['cityCname']?></p>
            <div class="right">
                <?php if(!empty($volunteerInfo['priceList'])){ $volunteerInfo['priceList']=json_decode($volunteerInfo['priceList'],true);?>
                    <?php foreach($volunteerInfo['priceList'] as $priceInfo){ ?>
                        <span><?=$priceInfo['day']?>天</span>
                    <?php } ?>
                    <div class="tip">
                        <?php foreach($volunteerInfo['priceList'] as $priceInfo){ ?>
                            <p>￥<?=$priceInfo['price']?></p>
                        <?php } ?>
                    </div>
                <?php } ?>
                <script type="text/javascript">
                    $(function(){
                        $('.zhiyuanNav .inner .right span').hover(function(e) {
                            var num=$(this).index();
                            $('.zhiyuanNav .right .tip p').eq(num).toggle();
                        });
                    })
                </script>
            </div>
        </div>
    </div>
</div>
<div class="sydetail zhiyuan_detail clearfix">
    <div class="web-content fl">
        <div class="web-left">
            <div class="map">
                <div class="map">
                    <div class="contian  prerequisite clearfix">
                        <span><b class="icon icon01"></b>年龄限制：<b><?=$volunteerInfo['ageInfo']?></b></span>
                        <span><b class="icon icon02"></b>团队人数：<b><?=$volunteerInfo['teamCount']?></b></span>
                        <span><b class="icon icon03"></b>出发地点：<b><?=$volunteerInfo['beginSite']?></b></span>
                        <span><b class="icon icon04"></b>有效期至：<b><?=$volunteerInfo['endDate']?></b></span>
                    </div>
                    <p id="detail" class="title02">推荐理由</p>
                    <div><?=nl2br($volunteerInfo['recommendInfo'])?></div>
                    <p id="detail" class="title02">项目详情</p>
                    <div><?=nl2br($volunteerInfo['info'])?></div>
                    <p id="detail" class="title02">行前准备</p>
                    <div><?=nl2br($volunteerInfo['prepare'])?></div>
                    <p id="detail" class="title02">行程安排</p>
                    <div><?=nl2br($volunteerInfo['scheduleIntro'])?></div>
                    <div class="design">
                        <?php if(!empty($volunteerInfo['scheduleList'])){ $volunteerInfo['scheduleList']=json_decode($volunteerInfo['scheduleList'],true);?>
                            <div class="line"></div>
                            <?php foreach($volunteerInfo['scheduleList'] as $key => $schedule){ ?>
                                <p class="tP"><span></span>第<?=$key+1?>天</p>
                                <div style="margin:0 0 15px 20px;"><?=nl2br($schedule)?></div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                    <p id="detail" class="title02">餐饮安排</p>
                    <div><?=nl2br($volunteerInfo['eat'])?></div>
                    <p id="detail" class="title02">住宿安排</p>
                    <div><?=nl2br($volunteerInfo['hotel'])?></div>
                    <p id="detail" class="title02">注意事项</p>
                    <div><?=nl2br($volunteerInfo['note'])?></div>

                    <p class="title02">价格内容</p>
                    <div class="contian clearfix">
                        <?php if(empty($volunteerInfo['includeList'])){$volunteerInfo['includeList']=json_decode($volunteerInfo['includeList'],true); ?>
                            <?php foreach($volunteerInfo['includeList'] as $detail){ ?>
                                <span><b class="icon icon01"></b><?=$detail?></span>
                            <?php } ?>
                        <?php } ?>
                        <?php if(empty($volunteerInfo['unIncludeList'])){$volunteerInfo['unIncludeList']=json_decode($volunteerInfo['unIncludeList'],true); ?>
                            <?php foreach($volunteerInfo['unIncludeList'] as $detail){ ?>
                                <span><b class="icon icon02"></b><?=$detail?></span>
                            <?php } ?>
                        <?php } ?>
                    </div>
                    <ul class="detNav tabTitle clearfix">
                        <li><a href="javascript:;" class="icon icon01 active">预定流程</a></li>
                        <li><a href="javascript:;" class="icon icon02">退款说明</a></li>
                        <li><a href="javascript:;" class="icon icon03">保险保障</a></li>
                    </ul>
                    <div class="detCon con01 tabCon" style="display:block;">
                        <div class="line"></div>
                        <p><span>1</span> 咨询随游的发布者，确认游玩细节。</p>
                        <p><span>2</span> 填写日期，人数等信息并预支付订单。</p>
                        <p><span>3</span> 等待随友接单后，通过邮件，短信及站内信方式收到订单提醒。</p>
                        <p><span>4</span> 凭电子确认单进行游玩。</p>
                        <p><span>5</span> 完成游玩后进行确认，评价您选择的随游及服务提供者。</p>
                    </div>
                    <div class="detCon tabCon">
                        <h3 class="title03">作为用户，您的权益会在随游得到充分保障。</h3>
                        <h3 class="title03">作为旅行者，您如果选择预订随游产品，可以享受以下的退款政策</h3>
                        <p>1.支付并提交订单后48小时无人接单，则订单自动取消，全额返还服务费</p>
                        <p>2.订单提交时间未满48小时，但超过订单预期服务时间的，全额返还服务费</p>
                        <p>3.在订单被接单之前取消订单，全额返还所支付费用</p>
                        <p>4.所提交订单被随友接单，在服务指定日期前5天可以申请取消预订并全额退款</p>
                        <p>5.在指定日期内5天可以申请退款，经平台审核后返还部分预订费用。</p>
                        <p>在随游服务过程中及服务后且未确认完成服务前，可以提交退款请求，经平台调查审核后返还部分服务费用。</p>
                    </div>
                    <div class="detCon tabCon">
                        <h3 class="title03">全天候客服热线</h3>
                        <p>和随游旅行的过程中，如果有任何问题，随时拨打随游客服电话或在微信公众号上与客服沟通，我们7x24随叫随到，为您服务。</p>
                        <h3 class="title03">旅行保险一份100%赔付</h3>
                        <p>和随游旅行过程中如出现意外情况，随友和游客无需承担保险范围内的任何费用，随游网提供的旅行保险全权处理100%赔付。据统计90%以上的游客和随友的相处都非常愉快，如需赔付，您只需要提供现场相关证据照片，在48小时内与随游客服联系，即可享受保险保障。</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="web-right">
            <div class="kuang clearfix">
                <input type="hidden" id="dateList" value="" />
            </div>
            <div class="kuang clearfix">
                <div class="user bgGreen">
                    <div class="user-name">
                        <img src="<?=$volunteerInfo['orgImg']?>" alt="" class="user-pic">
                        <span>合作机构</span>
                    </div>
                    <p><?=$volunteerInfo['orgName']?></p>
                </div>
                <div class="text clearfix">
                    <h3>分享</h3>
                    <div class="share">
                        <a href="javascript:;" class="icon sina"></a>
                        <a href="javascript:;" class="icon weixin"></a>
                        <a href="javascript:;" class="icon qq"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript" src="/assets/other/weixin/js/mobiscroll.core.js"></script>
<script type="text/javascript" src="/assets/other/weixin/js/mobiscroll.util.datetime.js"></script>
<script type="text/javascript" src="/assets/other/weixin/js/mobiscroll.datetimebase.js"></script>
<script type="text/javascript" src="/assets/other/weixin/js/mobiscroll.datetime.js"></script>
<script type="text/javascript" src="/assets/other/weixin/js/mobiscroll-2.14.4-crack.js"></script>

<script type="text/javascript">
    window._bd_share_config = {
        common : {
            bdText : '随游网-<?=htmlspecialchars(str_replace("\n"," ",$volunteerInfo['title']))?>',
            bdDesc : '随游网-<?=htmlspecialchars(str_replace("\n"," ",$volunteerInfo['info']))?>',
            bdUrl : '<?=\common\components\SiteUrl::getTripUrl($volunteerInfo['volunteerId']);?>',
            bdPic : '<?=$volunteerInfo['titleImg']?>'
        },
        share : [{
            "bdSize" : 16
        }]
    }
    //以下为js加载部分
    with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?cdnversion='+~(-new Date()/36e5)];
</script>


<?php
    $nowDate=strtotime(date('Y-m-d',time()));

    $dateList=$volunteerInfo['dateList'];
    $dateList=explode(",",$dateList);
    $maxDate=null;
    //获取最大值
    $selectTimeArray=[];
    $unSelectTimeArray=[];
    foreach($dateList as $tempDate){
        $time=strtotime($tempDate);
        $selectTimeArray[]=date('Y-m-d',$time);
        if($maxDate==null){$maxDate=$time;continue;}
        if($time>$maxDate){$maxDate=$time;}
    }
    $dayCount=($maxDate-$nowDate)/(60*60*24);
    for($i=0;$i<$dayCount;$i++){
        $tempDay=date('Y-m-d',strtotime("+".$i." days",$nowDate));
        if(!in_array($tempDay,$selectTimeArray)){
            $unSelectTimeArray[]=$tempDay;
        }
    }
?>
<script type="text/javascript">
    var nowDate='<?=date("Y-m-d",$nowDate)?>';
    var maxDate='<?=date("Y-m-d",$maxDate)?>';
    var selectDateArray='<?=implode(',',$selectTimeArray)?>';
    var unSelectDateArray='<?=implode(',',$unSelectTimeArray)?>';


    $(document).ready(function(){
        setLineHeight();
        initScroll();
    });

    /**
     * 鼠标滚动事件处理
     */
    function initScroll(){
        $(document).scroll(function () {
            var scrollTop = $(document).scrollTop();

            var documentHeight = $(document).height();//浏览器时下窗口可视区域高度
            var fixHeight = $(".web-right").offset().top + $(".web-right").height();
            var footHeight = $("#footer-out").height();

            var maxHeight = documentHeight - footHeight;
            //console.info($(".web-right").offset().top+"-"+fixHeight+"-"+maxHeight);

            if (scrollTop > 325) {
                $(".zhiyuanNav").addClass('fixed');
                $(".sydetail .web-right").css('margin-left','172px');
                $('.sydetail .web-right').addClass('fixed')
            } else {
                $(".zhiyuanNav").removeClass('fixed');
                $(".sydetail .web-right").css('margin-left','0');
                $('.sydetail .web-right').removeClass('fixed')
            }
            if (fixHeight > maxHeight) {
                $(".sydetail .web-right").hide();
            } else {
                $(".sydetail .web-right").show();
            }
            if (scrollTop + fixHeight > documentHeight - maxHeight) {
                $(".sylx-xiangxi").css("position", "absolute");
            } else {
                $(".sylx-xiangxi").css("position", "fixed");
            }
        });
    }

    function setLineHeight(){
        var first=$("div[class='design'] p[class='tP']").first().offset().top;
        var last=$("div[class='design'] p[class='tP']").last().offset().top;
        var height=parseInt(last)-parseInt(first);
        $("div[class='design'] div[class='line']").height(height);

        initDatPicker();
    }


    function getInvalidDateList(){
    }

    function initDatPicker(){
        getInvalidDateList();


        var markDateStr=selectDateArray;
        var markDateArray=new Array();
        var markDateList=markDateStr.split(",");
        var invalidDateStr=unSelectDateArray;
        var invalidDateArray=new Array();
        var invalidDateList=invalidDateStr.split(",");
        var date;var info;

        for(var i=0;i<markDateList.length;i++){
            date = Main.parseDate(markDateList[i]);
            info={d: date,color: '#3dd9c3'};
            markDateArray.push(info);
        }
        for(var j=0;j<invalidDateList.length;j++){
            date = Main.parseDate(invalidDateList[j]);
            invalidDateArray.push(date);
        }

        $('#dateList').mobiscroll().calendar({
            theme: 'mobiscroll',  // Specify theme like: theme: 'ios' or omit setting to use default
            lang: 'zh',           // Specify language like: lang: 'pl' or omit setting to use default
            display: 'inline',    // Specify display mode like: display: 'bottom' or omit setting to use default
            counter: false,        // More info about counter: http://docs.mobiscroll.com/2-15-1/calendar#!opt-counter
            multiSelect: false,    // More info about multiSelect: http://docs.mobiscroll.com/2-15-1/calendar#!opt-multiSelect
            invalid:invalidDateArray,
            marked: markDateArray,
            minDate:Main.parseDate(nowDate),
            maxDate:Main.parseDate(maxDate)
        });
    }

</script>