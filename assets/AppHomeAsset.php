<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppHomeAsset extends AppAsset
{
    public $js = [
        'js/home/home.js',
        'js/home/functions.js',
        'js/home/icons.js',
        'js/home/actions.js'
        
    ];

    public $depends = [
        'app\assets\AppAsset',
        'app\assets\LeafletAsset',
        'app\assets\LeafletGeocoderControlPluginAsset',
        'app\assets\MapboxAsset',
        'app\assets\MapboxDirectionsPluginAsset'
    ];
    
    public $jsOptions = ['position' => \yii\web\View::POS_BEGIN];

}
