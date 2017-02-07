<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Marcus Faccion <marcusfaccion@bol.com.br>
 * @since 1.0
 */
class LeafletPluginsAsset extends AssetBundle
{
    public $sourcePath = '@vendor/bower/leaflet-control-geocoder';
    // public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
      'dist/Control.Geocoder.css',
    ];
    public $js = [
      'dist/Control.Geocoder.js',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_BEGIN];

    public $depends = [
     
        'app\assets\AppAsset',
    ];
}
