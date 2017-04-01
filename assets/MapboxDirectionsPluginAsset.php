<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Marcus Faccion <marcusfaccion@bol.com.br>
 * @since 1.0
 */
class MapboxDirectionsPluginAsset extends AssetBundle
{
    public $sourcePath = '@vendor/bower/mapbox-directions.js';
    // public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
      'dist/mapbox.directions.css',
    ];
    public $js = [
      'dist/mapbox.directions.js',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];

    public $depends = [
     
        'app\assets\MapboxAsset',
    ];
}
