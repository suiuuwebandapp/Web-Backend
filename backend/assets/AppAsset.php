<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

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
        'assets/global/plugins/font-awesome/css/font-awesome.min.css',
        'assets/global/plugins/simple-line-icons/simple-line-icons.min.css',
        'assets/global/plugins/bootstrap/css/bootstrap.min.css',
        'assets/global/plugins/uniform/css/uniform.default.css',
        'assets/global/plugins/gritter/css/jquery.gritter.css',
        'assets/global/css/components.css',
        'assets/global/css/plugins.css',
        'assets/admin/layout/css/layout.css',
        'assets/admin/pages/css/tasks.css',
        'assets/admin/layout/css/themes/light.css',
        'assets/admin/layout/css/custom.css'
    ];
    public $js = [
        'assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js',
        'assets/global/plugins/bootstrap/js/bootstrap.min.js',
        'assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js',
        'assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js',
        'assets/global/plugins/jquery.blockui.min.js',
        'assets/global/plugins/jquery.cokie.min.js',
        'assets/global/plugins/uniform/jquery.uniform.min.js',
        'assets/global/scripts/app.js',
        'assets/global/scripts/metronic.js',
        'assets/global/scripts/common-utils.js',
        'assets/global/plugins/gritter/js/jquery.gritter.js',
        'assets/global/plugins/jquery.pulsate.min.js',
        'assets/admin/layout/scripts/layout.js',
        'assets/admin/layout/scripts/quick-sidebar.js',
        'assets/admin/layout/scripts/demo.js',
        'assets/admin/pages/scripts/index.js',
        'assets/admin/pages/scripts/tasks.js',
        'assets/chat/js/web_socket.js',
        'assets/chat/js/json.js',
        'assets/global/scripts/main.js'
    ];
    public $depends = [
    ];


}
