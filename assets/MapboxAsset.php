<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Marcus Faccion <marcusfaccion@bol.com.br>
 * @since 1.0
 */
class MapboxAsset extends AssetBundle
{
    public $sourcePath = '@vendor/bower/mapbox.js';
    // public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
      'mapbox.standalone.css',
    ];
    public $js = [
      'mapbox.standalone.js',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];

    public $depends = [
     
        'app\assets\LeafletAsset',
    ];
}
