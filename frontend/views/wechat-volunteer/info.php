
<div class="Uheader header mm-fixed-top">
    <a href="/static/product" class="back"></a>
    <p class="navTop">志愿产品详情</p>

</div>
<div id="zhiyuanBanner" class="syDetailBanner zhiyuanBanner">
    <!--banner开始-->
    <div class="bd">
        <ul class="banners">
            <?php if(!empty($volunteerInfo['picList'])){$volunteerInfo['picList']=json_decode($volunteerInfo['picList'],true); ?>
                <?php foreach($volunteerInfo['picList'] as $pic ){?>
                    <li class="banner01"><img src="<?= $pic;?>"></li>
                <?php }?>
            <?php } ?>
        </ul>
    </div>
    <div class="moneyDiv">
        <h2 class="title"><?=$volunteerInfo['title']?></h2>
        <p class="p1"><?=$volunteerInfo['countryCname']?> <?=$volunteerInfo['cityCname']?></p>
        <?php if(!empty($volunteerInfo['priceList'])){ $volunteerInfo['priceList']=json_decode($volunteerInfo['priceList'],true);?>
        <div id="priceSpanList" class="times">
            <?php foreach($volunteerInfo['priceList'] as $key=> $priceInfo){ ?>
                <span <?=$key==0?"class='active'":"";?> price="￥<?=$priceInfo['price']?>"><?=$priceInfo['day']?>天</span>
            <?php } ?>
            <strong id="showMoney">￥<?=$volunteerInfo['priceList'][0]['price']?></strong>
        </div>
        <?php } ?>
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

        });
    </script>
</div>
<div  id="volunteerInfo" class="actCon" style="display:block;">
    <div class="syContent con zyDetails">
        <div class="top clearfix">
            <a href="javascript:;" class="userPic"><img src="<?=$volunteerInfo['orgImg']?>"></a>
            <span class="userName">合作机构</span>
            <p  class="role"><span><?=$volunteerInfo['orgName']?></span></p>
        </div>
        <h3 class="title colGreen">基本信息</h3>
        <ul class="details clearfix">
            <li>
                <span class="icon icon1">年龄限制：<b><?=$volunteerInfo['ageInfo']?>岁以上</b></span>
            </li>
            <li>
                    <span class="icon icon2">出发地点
                        <b><?=$volunteerInfo['beginSite']?></b>
                    </span>
            </li>
            <li><span class="icon icon3">团队人数：<b><?=$volunteerInfo['teamCount']?>人以内</b></span></li>
            <li class="last"><span class="icon icon4">有效期至:<b><?=$volunteerInfo['endDate']?></b></span></li>
        </ul>
        <div class="tuijian clearfix">
            <?php if(!empty($volunteerInfo['recommendInfo'])){ ?>
                <h3 class="title colGreen">推荐理由</h3>
                <p><?=nl2br($volunteerInfo['recommendInfo'])?></p>
            <?php } ?>
        </div>
        <h3 class="title colGreen">项目详情</h3>
        <p><?=nl2br($volunteerInfo['info'])?></p>
        <?php if(!empty($volunteerInfo['scheduleIntro'])){ ?>
            <h3 class="title colGreen">行程安排</h3>

            <p><?=nl2br($volunteerInfo['scheduleIntro'])?></p>
        <?php } ?>
        <div class="dataCon">
            <?php if(!empty($volunteerInfo['scheduleList'])){ $volunteerInfo['scheduleList']=json_decode($volunteerInfo['scheduleList'],true);?>
                <div class="line"></div>
                <?php foreach($volunteerInfo['scheduleList'] as $key => $schedule){ ?>
                    <h4 class="datas"> <b></b>第<?=$key+1?>天</h4>
                    <p><?=nl2br($schedule)?></p>
                <?php } ?>
            <?php } ?>
        </div>

        <div class="tuijian clearfix">
            <h3 class="title colGreen">住宿安排</h3>
            <p><?=nl2br($volunteerInfo['hotel'])?></p>
        </div>
        <?php if(!empty($volunteerInfo['eat'])){ ?>
            <h3 class="title colGreen">餐饮安排</h3>
            <p><?=nl2br($volunteerInfo['eat'])?></p>
        <?php } ?>
        <?php if(!empty($volunteerInfo['note'])){ ?>
        <div class="tuijian clearfix">
            <h3 class="title colGreen">注意事项</h3>
            <p><?=nl2br($volunteerInfo['note'])?></p>
        </div>
        <?php } ?>
        <?php if(!empty($volunteerInfo['prepare'])){ ?>
            <h3 class="title colGreen">预定说明</h3>
            <p><?=nl2br($volunteerInfo['prepare'])?></p>
        <?php } ?>
        <?php if(!empty($volunteerInfo['includeList'])&&!empty($volunteerInfo['unIncludeList'])){ ?>
            <h3 class="title colGreen">价格内容</h3>
            <div class="contian clearfix">
                <?php if(!empty($volunteerInfo['includeList'])){$volunteerInfo['includeList']=json_decode($volunteerInfo['includeList'],true); ?>
                    <?php foreach($volunteerInfo['includeList'] as $detail){ ?>
                        <span><img src="/assets/other/weixin/images/syhas.png"><?=$detail?></span>
                    <?php } ?>
                <?php } ?>
                <?php if(!empty($volunteerInfo['unIncludeList'])){$volunteerInfo['unIncludeList']=json_decode($volunteerInfo['unIncludeList'],true); ?>
                    <?php foreach($volunteerInfo['unIncludeList'] as $detail){ ?>
                        <span><img src="/assets/other/weixin/images/syno.png"><?=$detail?></span>
                    <?php } ?>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</div>
<div class="actCon" id="volunteerAsk">
    <div class="con zyCon_chat">
        <ul class="list">
            <li><span>微信客服</span><b>chipmunkfoxy</b> <a href="javascript:;" class="get">点击获取<img src="/assets/other/weixin/images/ws.png"></a></li>
            <li><span>QQ客服</span><b>1295913524</b></li>
            <li><span>电话咨询</span><b>010-58483692</b></li>
        </ul>
        <p class="tip">随游客服周一至周日09:00 - 22:00</p>
    </div>
    <script type="text/javascript">
        $(function(){
            $('.zyCon_chat .get').click(function(e) {
                $('.chatPro').css('display','block');
            })
            $('.chatPro .close').click(function(e) {
                $('.chatPro').css('display','none');
            })
        })

    </script>
</div>

<div class="chatPro">
    <a href="javascript:;" class="close"></a>
    <img src="/assets/other/weixin/images/ws.png">
    <p>长按添加随游微信客服</p>
</div>
</div><!--不用去除 闭合layout中的标签-->
<div class="fixed zybtns clearfix">
    <a href="javascript:showInfo();">
        <b class="icon icon01"></b>
        <span>详情</span>
    </a>
    <a href="javascript:;" id="dateList">
        <b class="icon icon02"></b>
        <span >日期</span>
    </a>
    <a href="javascript:showAsk();">
        <b class="icon icon03"></b>
        <span>咨询</span>
    </a>
    <a href="javascript:;" class="last">
        <b class="icon icon04"></b>
        <span>预定</span>
    </a>
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
    <script src="/assets/other/weixin/js/mobiscroll.core.js"></script>
    <script src="/assets/other/weixin/js/mobiscroll.util.datetime.js"></script>
    <script src="/assets/other/weixin/js/mobiscroll.datetimebase.js"></script>
    <script src="/assets/other/weixin/js/mobiscroll.datetime.js"></script>

    <!-- Mobiscroll JS and CSS Includes -->
    <link rel="stylesheet" href="/assets/other/weixin/css/mobiscroll.custom-2.14.4.min.css" type="text/css" />
    <script src="/assets/other/weixin/js/mobiscroll-2.14.4-crack.js"></script>

    <style type="text/css">
        .zhiyuanNav .inner .p1{font-size: 20px;margin: 5px 0px 0px 5px;}
        .mbsc-mobiscroll .dwwr{background: #ffffff;}
        .mbsc-mobiscroll .dw-persp{text-align: center;}
        .sydetail .web-right .kuang{padding-bottom: 0px;}
        .mbsc-ic-arrow-left5:before{background-position: 0 0 !important;background-size: 36px 40px !important;}
        .mbsc-ic-arrow-right5:before{background-position: 0 0 !important;background-size: 36px 40px !important;}
    </style>
    <script>

        function showInfo()
        {
            $("body").attr("class","bgwhite");
            $("#zhiyuanBanner").show();
            $("#volunteerInfo").show();
            $("#volunteerAsk").hide();
        }
        function showAsk()
        {
            $("body").attr("class","");
            $("#zhiyuanBanner").hide();
            $("#volunteerInfo").hide();
            $("#volunteerAsk").show();
        }
        var nowDate='<?=date("Y-m-d",$nowDate)?>';
        var maxDate='<?=date("Y-m-d",$maxDate)?>';
        var selectDateArray='<?=implode(',',$selectTimeArray)?>';
        var unSelectDateArray='<?=implode(',',$unSelectTimeArray)?>';
        $(document).ready(function(){
            initBtnClick();
            initDatPicker();
        });

        function initBtnClick(){
            $("#priceSpanList span").on("click",function(){
                $("#priceSpanList span").removeClass("active");
                $(this).addClass("active");
                $("#showMoney").html($(this).attr("price"));
            });
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
                date = parseDate(markDateList[i]);
                /*info={d: date,color: '#3dd9c3'};*/
                markDateArray.push(date);
            }
            for(var j=0;j<invalidDateList.length;j++){
                date = parseDate(invalidDateList[j]);
                invalidDateArray.push(date);
            }
            $('#dateList').mobiscroll().calendar({
                onShow: function () {
                    $(".dw-cal-table").on("touchend",function(){
                        return false;
                    });
                },
                /*onSelect: function (valueText, inst) {
                    alert(1);
                    return;
                    var selectedDate = inst.getVal(); // Call the getVal method
                    var d = new Date(selectedDate);
                    // + ' ' + d.getHours() + ':' + d.getMinutes() + ':' + d.getSeconds()
                    var s=d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDate();
                },*/
                theme: 'mobiscroll',  // Specify theme like: theme: 'ios' or omit setting to use default
                lang: 'zh',           // Specify language like: lang: 'pl' or omit setting to use default
                display: 'bottom',    // Specify display mode like: display: 'bottom' or omit setting to use default
                counter: false,        // More info about counter: http://docs.mobiscroll.com/2-15-1/calendar#!opt-counter
                multiSelect: true,    // More info about multiSelect: http://docs.mobiscroll.com/2-15-1/calendar#!opt-multiSelect
                invalid:invalidDateArray,
               /* marked: markDateArray,*/
                minDate:parseDate(nowDate),
                maxDate:parseDate(maxDate),
                selectedValues:markDateArray
            });
        }
        function parseDate(strDate)
        {
           return eval('new Date(' + strDate.replace(/\d+(?=-[^-]+$)/,
               function (a) { return parseInt(a, 10) - 1; }).match(/\d+/g) + ')');
        }
    </script>