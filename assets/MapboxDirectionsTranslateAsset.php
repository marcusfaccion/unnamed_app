<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Marcus Faccion <marcusfaccion@bol.com.br>
 * @since 1.0
 */
class MapboxDirectionsTranslateAsset extends AssetBundle
{
    public $sourcePath = '@vendor/marcusfaccion/mapbox';
    // public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
      'directions/messages/en-US2.js',
      'directions/messages/pt-BR2.js',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_BEGIN];

    public $depends = [
     
      //  'app\assets\AppAsset',
    ];
}
