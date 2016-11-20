<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Marcus Faccion <email>
 * @since 1.0
 */
class BootstrapFileInputAsset extends AssetBundle
{
    public $sourcePath = '@vendor/kartik-v';
    //public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'bootstrap-fileinput/css/fileinput.min.css',
    ];
    public $js = [
        'bootstrap-fileinput/js/fileinput.min.js',
        'bootstrap-fileinput/js/plugins/canvas-to-blob.min.js',
        'bootstrap-fileinput/js/plugins/sortable.min.js',
        'bootstrap-fileinput/js/plugins/purify.min.js',
        'bootstrap-fileinput/js/locales/pt-BR.js',
//        'bootstrap-fileinput/js/locales/es.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        //'yii\bootstrap\BootstrapThemeAsset',
    ];
}
