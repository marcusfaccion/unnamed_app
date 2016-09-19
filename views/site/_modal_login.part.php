<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
?>

<?php
Modal::begin([
    'id' => 'modal_login', 
   // 'header' => '<h2>Hello world</h2>',
   //'toggleButton' => ['label' => 'click me'],
]);
echo $this->renderFile('@app/views/site/_form_login.part.php', ['numero_formulario' => 1]);
?>

<?php
Modal::end();
?>