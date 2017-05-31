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
   'header' =>"<div class='modal-title text-primary tsize-5'><strong><span class='glyphicon glyphicon-log-in'></span> Entrar</strong></div>",
   //'toggleButton' => ['label' => 'click me'],
]);
echo $this->renderFile('@app/views/site/_form_login.php', ['form_number' => 1]);
?>

<?php
Modal::end();
?>