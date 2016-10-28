<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\web\JsExpression;

?>
<?php
Modal::begin([
    'id' => 'home_actions_modal',
    'size' => Modal::SIZE_LARGE,
    'header' =>"<div class='modal-title'></div>",
    //'closeButton' => ['dat'],
    'options'=>['class' => 'modal modal-wide'],
    'clientEvents' => [
        'shown.bs.modal'=>  new JsExpression(
                "function() {
                    var action = $('#home_actions_trigger').val().split(';');
                    $(this).find('.modal-title').text(action[0]);
                    var modal = $(this);
                    $.ajax({
                        type: 'GET',
                        url: '?r='+((action.length>2)?action[1]+'/'+action[2]:action[1]+'/widget'),    
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
