<?php

namespace app\assets;

/**
 * @author Marcus Faccion <marcusfaccion@bol.com.br>
 * @since 1.0
 */
class AppHomeAsset extends AppAsset
{
    public $js = [
        'js/home/home.js',
        'js/home/functions.js',
        'js/home/actions.js'
        
    ];

    public $depends = [
        'app\assets\AppAsset',
        'app\assets\LeafletGeocoderControlPluginAsset',
        'app\assets\MapboxDirectionsPluginAsset'
    ];
    
    public $jsOptions = ['position' => \yii\web\View::POS_BEGIN];

}
