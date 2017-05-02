<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Marcus Faccion <marcusfaccion@bol.com.br>
 * @since 1.0
 */
class AppAsset extends AssetBundle
{
    //public $sourcePath = '@app/assets';
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/app.css',
        'css/Loading.css',
    ];
    public $js = [
        'js/app.js',
        'js/functions.js',
        'js/icons.js',
        'js/helpers.js',
        'js/Loading.js',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];

    public $depends = [
        'yii\web\JqueryAsset',
        //'yii\jui\JuiAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'app\assets\LeafletAsset',
        'app\assets\MapboxAsset',
        //'yii\bootstrap\BootstrapPluginAsset',
        //'yii\bootstrap\BootstrapThemeAsset',
    ];
}
