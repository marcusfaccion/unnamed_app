<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\web\JsExpression;
?>

<?php
$url = yii\helpers\Url::toRoute('/users/challange');
Modal::begin([
    'id' => 'site_password_reset_modal', 
    'size' => Modal::SIZE_LARGE,
    'header' =>"<div class='modal-title text-primary strong-7'>Recuperando a senha</div>",
    //'closeButton' => ['dat'],
    'options'=>['class' => 'modal modal-wide'],
    'clientEvents' => [
        'shown.bs.modal'=>  new JsExpression(
                "function() {
                    var modal = $(this);
                    $.ajax({
                        type: 'POST',
                        url: '$url',
                        data: { 
                            step: 1,
                         },
                        success: function(response){
                            modal.find('.modal-body').html(response);
                        }
                    });
                 }"
                , []),
          'hide.bs.modal'=>  new JsExpression(
                "function() {
                      $(this).find('.modal-body').html('');
                 }"
                , [])
    ]
]);
?>

<?php
Modal::end();
?>