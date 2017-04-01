<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Marcus Faccion <marcusfaccion@bol.com.br>
 * @since 1.0
 */
class LeafletAsset extends AssetBundle
{
    public $sourcePath = '@vendor/bower/leaflet';
    // public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
      'dist/leaflet.css',
    ];
    public $js = [
      'dist/leaflet.js',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];

    public $depends = [
     
        'app\assets\AppAsset',
    ];
}
