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
class SiteSignupAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
//    public $css = [
//    ];
    public $js = [
        'js/site/signup.js',
    ];
    public $depends = [
        'app\assets\SiteAsset',
    ];
}
