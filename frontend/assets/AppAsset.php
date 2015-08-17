<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'assets/css/cs.css',
        'assets/plugins/font-awesome/css/font-awesome.min.css',
    ];
    public $js = [
        'assets/js/common.js',
        'assets/js/myTab.js',
        'assets/js/main.js',
        'assets/pages/layout/layout.js'
    ];
    public $depends = [

    ];
}
