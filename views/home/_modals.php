<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

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
                        url: ((action.length>2)?action[1]+'/'+action[2]:action[1]+'/begin'),    
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

<?php /* Modal de confirmação universal
Modal::begin([
    'id' => 'home_actions_confirm_modal',
    'size' => Modal::SIZE_SMALL,
    'header' =>"<div class='modal-title'>Confirmação</div>",
    'footer' =>"<button type='button' class='btn btn-xs btn-danger' >Sim</button>
        <button type='button' class='btn btn-xs btn-success' data-dismiss='modal'>Não</button>",
    //'closeButton' => ['dat'],
    'options'=>['class' => 'modal modal-wide'],
    'clientEvents' => [
        'shown.bs.modal'=>  new JsExpression("
            function(){
                var modal = $(this);
                $.ajax({
                    type: 'POST',
                    url: 'home/get-confirm-message',
                    data: {message: confirm_message},
                    success: function(response){
                        modal.find('.modal-body').html(response);
                    }
                });
        }", []),
        'hide.bs.modal'=>  new JsExpression(
                "function() {
                            $(this).find('.modal-body').html('');
                 }"
                , [])
    ]
]);
*/ ?>

<?php
// Modal::end();
?>
