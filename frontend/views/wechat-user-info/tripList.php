
    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        <p class="navTop">他的随游</p>
    </div>
    <div class="con g_suiyou">
        <div class="content">
            <?php foreach($tripList as $val){?>
            <div class="box">
                <a href="/wechat-trip/info?tripId=<?= $val['tripId']?>" class="pic"><img src="<?php echo $val['titleImg']?>"></a>
                <div class="details">
                    <h3 class="title"><?php echo $val['title']?></h3>
                    <p class="line clearfix">
                        <b class="colOrange">￥<?php echo $val['basePrice']?></b>
                        <img src="<?= $val['score']>=10?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                        <img src="<?= $val['score']>=8?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                        <img src="<?= $val['score']>=6?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                        <img src="<?= $val['score']>=4?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                        <img src="<?= $val['score']>=2?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                    </p>
                </div>
            </div>
            <?php }?>
        </div>
    </div>