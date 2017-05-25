<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\bootstrap\Carousel;
use yii\helpers\Url;
?>

<?php
    $carousel_itens = [];
    foreach($bike_keeper->multimedias as $multimedia){
       $carousel_itens[] = [ 
                        'content' => "<img src='".Url::to('@bike_keepers_dir/'.$bike_keeper->public_dir_name.'/images/'.$multimedia->src)."' title='foto bicicletário'/>",
                        'caption' => '',
                        'options' => [],
                    ];
    }
?>

<?php
Carousel::begin([
    'id'=>'home_bike_keeper_photos_carousel',
    'items' => $carousel_itens,
    'clientEvents' =>[],
    'clientOptions' =>[],
]);
?>

<?php
Carousel::end();
?>

