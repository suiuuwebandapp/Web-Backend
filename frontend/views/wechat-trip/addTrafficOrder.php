<!doctype html>
<html lang="zh-CN"><head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title>随游</title>
    <link type="text/css" rel="stylesheet" href="/assets/other/weixin/css/common.css">
    <link type="text/css" rel="stylesheet" href="/assets/other/weixin/css/weixin.css">
    <script type="text/javascript" src="/assets/other/weixin/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="/assets/other/weixin/js/jquery.SuperSlide.2.1.1.js"></script>
    <link rel="stylesheet" href="/assets/other/weixin/css/jquery.mmenu.css">
    <script type="text/javascript" src="/assets/other/weixin/js/jquery.mmenu.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $('div#menu').mmenu();
        });
    </script>
    <script>
        function showHtml()
        {
            $("#page").show();
            $("#loading").hide();
        }
    </script>
    <link rel="stylesheet" href="/assets/other/weixin/css/loading.css">
    <script src="/assets/other/weixin/js/mobiscroll.core.js"></script>
    <script src="/assets/other/weixin/js/mobiscroll.util.datetime.js"></script>
    <script src="/assets/other/weixin/js/mobiscroll.datetimebase.js"></script>
    <script src="/assets/other/weixin/js/mobiscroll.datetime.js"></script>

    <!-- Mobiscroll JS and CSS Includes -->
    <link rel="stylesheet" href="/assets/other/weixin/css/mobiscroll.custom-2.14.4.min.css" type="text/css" />
    <script src="/assets/other/weixin/js/mobiscroll-2.14.4-crack.js"></script>

    <script type="text/javascript">
        $(document).bind("mobileinit", function () {
            //覆盖的代码
            $.mobile.ajaxEnabled = false;
            $.mobile.hashListeningEnabled = false;
            //$.mobile.linkBindingEnabled = false;
        });
    </script>
    <script type="text/javascript">

        $(function () {
            var now = new Date(),
                year = now.getFullYear(),
                month = now.getMonth();
            // Mobiscroll Calendar initialization
            $('#timeListBc').mobiscroll().time({
                theme: 'mobiscroll',
                display: 'bottom',
                lang: 'zh',
                headerText: false
            });

            $('#dateListBc').mobiscroll().calendar({
                theme: 'mobiscroll',  // Specify theme like: theme: 'ios' or omit setting to use default
                lang: 'zh',           // Specify language like: lang: 'pl' or omit setting to use default
                display: 'bottom',    // Specify display mode like: display: 'bottom' or omit setting to use default
                counter: true
            });
            $('#timeListJj').mobiscroll().time({
                theme: 'mobiscroll',
                display: 'bottom',
                lang: 'zh',
                headerText: false
            });

            $('#dateListJj').mobiscroll().calendar({
                theme: 'mobiscroll',  // Specify theme like: theme: 'ios' or omit setting to use default
                lang: 'zh',           // Specify language like: lang: 'pl' or omit setting to use default
                display: 'bottom',    // Specify display mode like: display: 'bottom' or omit setting to use default
                counter: true
            });
            $('#timeListSj').mobiscroll().time({
                theme: 'mobiscroll',
                display: 'bottom',
                lang: 'zh',
                headerText: false
            });

            $('#dateListSj').mobiscroll().calendar({
                theme: 'mobiscroll',  // Specify theme like: theme: 'ios' or omit setting to use default
                lang: 'zh',           // Specify language like: lang: 'pl' or omit setting to use default
                display: 'bottom',    // Specify display mode like: display: 'bottom' or omit setting to use default
                counter: true
            });
        });
    </script>
</head>

<body  onload="showHtml()">

<div id="loading" class="overlay">
    <div class="spinner" id="loading">
        <div class="rect1"></div>
        <div class="rect2"></div>
        <div class="rect3"></div>
        <div class="rect4"></div>
        <div class="rect5"></div>
    </div>
</div>
<div id="page" class="userCenter">
    <?php include "left.php"; ?>
    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        <p class="navTop">服务项目选择</p>
    </div>
    <script type="text/javascript">
        $(function(){
            $('.jtseverSelect .box .items').click(function(e) {
                $(this).next('.items_drop').toggle();
            });
        })
    </script>
    <div class="con jtseverSelect clearfix">
        <?php if(!empty($info['trafficInfo']['carPrice'])){ ?>
            <div class="box">
                <p class="items">包车</p>
                <div class="items_drop" id="bcList">
                    <input type="text" placeholder="人数" id="bcrs" value="1">
                    <p class="line clearfix">
                        <input type="text" placeholder="时间（当地）" id="dateListBc">
                        <input type="text" placeholder="预约日期（当地）" id="timeListBc">
                        <a href="javascript:;" class="add" onclick="addbc(this)"></a>
                    </p>
                    <a href="javascript:;"  class="btn" onclick="bcOk()">确定</a>
                </div>
            </div>
        <?php } ?>
        <?php if(!empty($info['trafficInfo']['airplanePrice'])){ ?>
            <div class="box">
                <p class="items">接机</p>
                <div class="items_drop" id="jjDiv">
                    <input type="text" placeholder="预约日期（当地）" id="dateListJj">
                    <input type="text" placeholder="时间（当地）" id="timeListJj">
                    <input type="text" placeholder="人数" id="jjNumber" value="1">
                    <a href="javascript:;" class="btn" onclick="jjOk()">确定</a>
                </div>
            </div>
        <?php } ?>
        <?php if(!empty($info['trafficInfo']['airplanePrice'])){ ?>
            <div class="box">
                <p class="items">送机</p>
                <div class="items_drop" id="sjDiv">
                    <input type="text" placeholder="预约日期（当地）" id="dateListSj">
                    <input type="text" placeholder="时间（当地）" id="timeListSj">
                    <input type="text" placeholder="人数" id="sjNumber" value="1">
                    <a href="javascript:;" class="btn" onclick="sjOk()">确定</a>
                </div>
            </div>
        <?php } ?>



        <div class="box res" id="serviceListHtml">
            <p class="money">总价：<span id="totalMoney">¥0</span></p>
        </div>
        <div class="btns clearfix">
            <a href="javascript:;" class="btn btn01" onclick="addOrder()">确定</a>
        </div>
        <form action="/wechat-trip/add-traffic-order" method="post" id="trafficOrder">
            <input type="hidden" id="tripId" name="tripId" value="<?=$info['info']['tripId']?>"/>
            <input type="hidden" id="serviceList" name="serviceList"/>
        </form>
    </div>
</div>


<script>
    var basePrice='<?=intval($info['info']['basePrice']);?>';
    var basePriceType='<?=$info['info']['basePriceType'];?>';
    var maxPeopleCount='<?=$info['info']['maxUserCount'];?>';
    var type=<?=empty($info['info']['type'])?0:$info['info']['type'];?>;
    var nightTimeStart=<?=empty($info['trafficInfo']['nightTimeStart'])?'null':"'".$info['trafficInfo']['nightTimeStart']."'";?>;
    var nightTimeEnd=<?=empty($info['trafficInfo']['nightTimeEnd'])?'null':"'".$info['trafficInfo']['nightTimeEnd']."'";?>;
    var nightServicePrice=<?=empty($info['trafficInfo']['nightServicePrice'])?'null':$info['trafficInfo']['nightServicePrice'];?>;
    var carPrice=<?=empty($info['trafficInfo']['carPrice'])?'null':$info['trafficInfo']['carPrice'];?>;
    var airplanePrice=<?=empty($info['trafficInfo']['airplanePrice'])?'null':$info['trafficInfo']['airplanePrice'];?>;
    function bcOk()
    {
        var i=0;
        var bcPeopleCount="";
        var bcDate="";
        var bcTime="";
        var arrDate=[];
        var arrTime=[];
        bcPeopleCount=$("#bcrs").val();
        $("#bcList").find("p input").each(function(){

            if(i%2==0){
                bcDate =$(this).val();
                if(bcDate!="")
                {
                arrDate.push(bcDate);
                }
            }else if(i%2==1)
            {
                bcTime =$(this).val();
                if(bcTime!="")
                {
                    arrTime.push(bcTime);
                }
            }
            i++;
        });


        if(bcPeopleCount==""||bcPeopleCount==0)
        {
            alert("人数不能为0");
        }
        if(bcPeopleCount>Number(maxPeopleCount)){
            alert("人数不能超出"+maxPeopleCount+"人");
            return;
        }
        if(arrDate.length==0||arrTime.length==0){
            alert("请填写包车日期和时间");
            return;
        }
        for(var j=0;j<arrDate.length;j++)
        {
            var str='<p >包车：<span>'+arrDate[j]+'</span> <span>'+arrTime[j]+'</span><span>￥'+carPrice+'</span> <a  serviceType="car" orderDate='+arrDate[j]+' orderTime='+arrTime[j]+' orderPerson='+bcPeopleCount+
                ' basePrice='+carPrice+' href="javascript:;" class="close" onclick="removeList(this)"></a></p>';
            $("#serviceListHtml").prepend(str);
        }
        changeTotalMoney();

    }


    function jjOk()
    {

        var jjNumb=$("#jjNumber").val();
        var timeListJj=$("#timeListJj").val();
        var dateListJj=$("#dateListJj").val();

        if(jjNumb==""||jjNumb==0)
        {
            alert("人数不能为0");
            return;
        }
        if(jjNumb>Number(maxPeopleCount))
        {
            alert("人数不能超出"+maxPeopleCount+"人");
            return;
        }
        var price=0;

        if(isNightServiceTime(timeListJj,nightTimeStart,nightTimeEnd)){
            price=parseInt(airplanePrice)+parseInt(nightServicePrice);
        }else{
            price=airplanePrice;
        }
        var str='<p >接机：<span>'+dateListJj+'</span> <span>'+timeListJj+'</span><span>￥'+price+'</span> <a  serviceType="airplane_come" orderDate='+dateListJj+' orderTime='+timeListJj+' orderPerson='+jjNumb+
            ' basePrice='+price+' href="javascript:;" class="close" onclick="removeList(this)"></a></p>';
        $("#serviceListHtml").prepend(str);
        changeTotalMoney();
        $("#jjDiv").hide();
    }

    function sjOk()
    {
        var sjNumb=$("#sjNumber").val();
        var timeListSj=$("#timeListSj").val();
        var dateListSj=$("#dateListSj").val();

        if(sjNumb==""||sjNumb==0)
        {
            alert("人数不能为0");
            return;
        }
        if(sjNumb>Number(maxPeopleCount))
        {
            alert("人数不能超出"+maxPeopleCount+"人");
            return;
        }
        var price=0;
        if(isNightServiceTime(timeListSj,nightTimeStart,nightTimeEnd)){
            price=parseInt(airplanePrice)+parseInt(nightServicePrice);
        }else{
            price=airplanePrice;
        }
        var str='<p >送机：<span>'+dateListSj+'</span> <span>'+timeListSj+'</span><span>￥'+price+'</span> <a  serviceType="airplane_send" orderDate='+dateListSj+' orderTime='+timeListSj+' orderPerson='+sjNumb+
            ' basePrice='+price+' href="javascript:;" class="close" onclick="removeList(this)"></a></p>';
        $("#serviceListHtml").prepend(str);
        changeTotalMoney();
        $("#sjDiv").hide();
    }

    function addOrder()
    {
        var tempDate,tempTime,tempPerson,type,jsonStr='';
        $("#serviceListHtml a").each(function(){
            tempDate=$(this).attr("orderDate");
            tempTime=$(this).attr("orderTime");
            type=$(this).attr("serviceType");
            tempPerson=$(this).attr("orderPerson");
            if(tempDate!=''&&tempTime!=''&&type!=''){
                jsonStr+='{"date":"'+tempDate+'","time":"'+tempTime+'","type":"'+type+'","person":'+tempPerson+'},';
            }
        });
        if(jsonStr!=''){
            jsonStr=jsonStr.substring(0,jsonStr.length-1);
            jsonStr='['+jsonStr+']';
            $("#serviceList").val(jsonStr);
            $("#trafficOrder").submit();
        }

    }
    function changeTotalMoney()
    {
        var totalMoney=0;
        $("#serviceListHtml").find("a").each(function()
        {
            var basePrice=Number($(this).attr("basePrice"));
            if(basePrice!="NaN"){
                totalMoney+=basePrice;
            }
        });
        $("#totalMoney").html("￥"+totalMoney);
    }
    function removeList(obj)
    {
        $(obj).parent().remove();
        changeTotalMoney();
    }

    function compareTime(time1,time2,addDate){
        time1="1990-01-01 "+time1;
        if(isNotEmpty(addDate)){
            time2="1990-01-02 "+time2;
        }else{
            time2="1990-01-01 "+time2;
        }

        time1 = time1.replace(/-/g,"/");
        time2 = time2.replace(/-/g,"/");

        var d1 = new Date(time1);
        var d2 = new Date(time2);

        if(d1.getTime()>=d2.getTime()){
            return true;
        }else{
            return false;
        }
    }
    function isNotEmpty(obj){
        if(obj==null||obj=="null"||obj==""||obj==undefined){
            return false;
        }else{
            return true;
        }
    }

    function isNightServiceTime(choseTime,startTime,endTime) {
        var isNight=false;
        //如果结束时间大于开始时间 那么是正常情况
        if(choseTime==startTime||choseTime==endTime){
            return true;
        }
        if(compareTime(endTime,startTime)){
            if(compareTime(choseTime,startTime)&&!compareTime(choseTime,endTime)){
                isNight=true;
            }
        }else{
            if((compareTime(choseTime,startTime)&&!compareTime(choseTime,endTime,1))||(!compareTime(choseTime,startTime,1)&&compareTime(endTime,choseTime))){
                isNight=true;
            }
        }
        return isNight;
    }
    function addbc(obj)
    {
        var timeListBc=$("#timeListBc").val();
        var dateListBc=$("#dateListBc").val();
        if(timeListBc==""||dateListBc==""){
            alert("请填写包车日期和时间");
            return;
        }
        var bcStr='<p class="line clearfix" >'
            +'<input type="text" placeholder="时间（当地）"  value="'+dateListBc+'">'
            +'<input type="text" placeholder="预约日期（当地）" value="'+timeListBc+'">'
            +'<a href="javascript:;" class="min" onclick="minBc(this)"></a>'
            +'</p>';
        $("#bcrs").after(bcStr);
        $("#timeListBc").val("");
        $("#dateListBc").val("");
    }
    function minBc(obj)
    {
        $(obj).parent().remove();
    }
</script>

</body>
</html>
