    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        <p class="navTop">他的心愿</p>
    </div>
    <div class="con g_xinyuan">
        <div class="content">
            <ul class="list clearfix">
                <?php foreach($list["data"] as $val){?>
                <li onclick="toInfo('<?=$val["tripId"]?>')">
                    <a href="javascript:;" class="pic"><img src="<?php echo $val["titleImg"]?>"></a>
                    <p><?php echo  mb_strlen($val['title'],"utf-8")>8?mb_substr($val['title'],0,8,"utf-8")."...":$val['title'] ?></p>
                    <p class="bottom">
                        <a href="javascript:;" class="colt"><?php echo $val['collectCount']?></a>
                        <a href="javascript:;" class="rest"><?php echo $val['commentCount']?></a>
                    </p>
                </li>
                <?php }?>
            </ul>


        </div>
    </div>
<script>
    function toInfo(id)
    {
        window.location.href="/wechat-trip/info?tripId="+id;
    }
</script>