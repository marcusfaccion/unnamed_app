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
class AppHomeBikeKeepersAsset extends AppHomeAsset
{
    public $js = [
        'js/home-bike-keepers/form.js',
        'js/home-bike-keepers/popup.js',
    ];
    
    public $jsOptions = ['position' => \yii\web\View::POS_BEGIN];
    
    public $depends = [
        'app\assets\AppHomeAsset',
    ];

}
