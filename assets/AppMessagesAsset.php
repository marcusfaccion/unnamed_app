<?php

namespace app\assets;

/**
 * @author Marcus Faccion <marcusfaccion@bol.com.br>
 * @since 1.0
 */
class AppMessagesAsset extends AppAsset
{
    public $js = [
       'js/messages/messages.js',
       'js/messages/actions.js',
//     'js/home/functions.js',
//     'js/home/icons.js',
//     'js/home/actions.js'
    ];
    
    public $jsOptions = ['position' => \yii\web\View::POS_BEGIN];
    
    public $depends = [
        'app\assets\AppAsset',
//        'app\assets\LeafletAsset',
//        'app\assets\MapboxAsset',
    ];

}
