<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>圈子分享</title>

    <link rel="stylesheet" href="/assets/other/quanzi/css/qstyle.css">
</head>

<body>
<div class="container">
    <h2 class="qtitle"><?php echo $data['aTitle']?></h2>
    <?php if($data['aType']!=\common\entity\CircleArticle::ARTICLE_TYPE_PHOTO){?>
    <div class="qtext">
        <p><?php
            $str=$data['aContent'];
            $order=array("\r\n","\n","\r");
            $replace='<br/>';
            $newstr=str_replace($order,$replace,$str);
           echo $newstr;
            ?>
        </p>
    </div>
        <?php $imgList=json_decode($data['aImgList'],true);
        foreach($imgList as $val){?>
        <div class="qpic"><img src="<?php echo $pr.$val?>"></div>
            <?php }?>
    <?php }else{?>

        <?php

        $imgList=json_decode($data['aImgList'],true);
        $contList=json_decode($data['aContent'],true);
        for($i=0;$i<count($imgList);$i++){?>
            <div class="qpic"><img src="<?php echo $pr.$imgList[$i];?>"></div>
            <div class="qtext">
                <p><?php $str=$contList[$i];
                    $order=array("\r\n","\n","\r");
                    $replace='<br/>';
                    $newstr=str_replace($order,$replace,$str);
                    echo $newstr;?></p>
            </div>
        <?php }?>
    <?php }?>
</div>

</body>
</html>
