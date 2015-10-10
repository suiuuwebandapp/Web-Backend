<style>
    .logo{ width:4.6rem;display:block; margin:0 auto; margin-top:6.0rem; }
    .noOrder{ line-height:1.5rem;margin-top:10px;text-align: center; }
</style>
    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        我的随游
    </div>
    <div class="content" style="margin-top: 3.2rem">
        <?php if(count($list)==0){?>
        <img src="/assets/other/weixin/images/logo02.png" class="logo">
        <p class="noOrder">你还没有发布随游哦</p>
        <?php }?>
        <?php foreach($list as $val){?>
        <div class="box clearfix">
            <a  href="javascript:;" class="pic fl"><img class="cover min-height-order" src="<?php echo $val['titleImg'];?>"></a>
            <div class="details fl">
                <a href="javascript:;" class="colBlue">已发布</a>
                <p><?php echo $val['title'];?></p>
            </div>

        </div>
        <?php }?>
    </div>
<script>

    /*var t_img; // 定时器
    var isLoad = true; // 控制变量

    // 判断图片加载状况，加载完成后回调
    isImgLoad(function(){
        // 加载完成
        alert(1);
    });
    // 判断图片加载的函数
    function isImgLoad(callback){
        // 注意我的图片类名都是cover，因为我只需要处理cover。其它图片可以不管。
        // 查找所有封面图，迭代处理
        $('.cover').each(function(){
            // 找到为0就将isLoad设为false，并退出each
            if(this.height === 0){
                isLoad = false;
                return false;
            }
        });
        // 为true，没有发现为0的。加载完毕
        if(isLoad){
            clearTimeout(t_img); // 清除定时器
            // 回调函数
            callback();
            // 为false，因为找到了没有加载完成的图，将调用定时器递归
        }else{
            isLoad = true;
            t_img = setTimeout(function(){
                isImgLoad(callback); // 递归扫描
            },100); // 我这里设置的是500毫秒就扫描一次，可以自己调整
        }
    }*/
</script>
