<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\bootstrap\Modal;
use yii\web\JsExpression;

?>
<?php
Modal::begin([
    'id' => 'alert_update_modal',
    'size' => Modal::SIZE_LARGE,
    'header' =>"<div class='modal-title'>Editando Alerta</div>",
    //'closeButton' => ['dat'],
    'options'=>['class' => 'modal modal-wide'],
    'clientEvents' => [
        'shown.bs.modal'=>  new JsExpression(
                "function() {
                    var modal = $(this);
                    $.ajax({
                        type: 'POST',
                        url: 'alerts/update',
                        data: { 
                            Alerts: {
                                id: app.alert.id, 
                            },
                            nonSubmition: true,
                         },
                        success: function(response){
                            modal.find('#alerts_alert_form').html(response);
                        }
                    });
                 }"
                , []),
          'hide.bs.modal'=>  new JsExpression(
                "function() {
                        Loading.show();    
                        $(this).find('#alerts_alert_form').html('');
                    $.ajax({
                        type: 'GET',
                        url: 'alerts/active-alerts',
                        data: { user_id: app.user.id },
                        success: function(response){
                            $('#active-alerts').html(response); // atualiza a tabela de alertas
                            Loading.hide();
                        }
                    });
                 }"
                , [])
    ]
]);
?>
<div id='alerts_alert_form' class="row col-xs-offset-1 bottom-buffer-5">

</div>
<?php
Modal::end();
?>

<?php
/** 
 * Modal de confirmação universal 
 */
Modal::begin([
    'id' => 'alerts_confirmation_modal',
    'size' => Modal::SIZE_SMALL,
    'header' =>"<div class='modal-title'>Confirmação <span class='glyphicon glyphicon-question-sign'></span></div>",
    'footer' =>"<button id='yes-confirm' type='button' class='btn btn-xs btn-danger' value='1' data-dismiss='modal'>Sim</button>
        <button id='no-confirm' type='button' class='btn btn-xs btn-success' value='0' data-dismiss='modal'>Não</button>",
    //'closeButton' => ['dat'],
    'options'=>['class' => 'modal modal-wide'],
    'clientEvents' => [
        'shown.bs.modal'=>  new JsExpression("
            function(e){
                var modal = $(this);
                $.ajax({
                    type: 'POST',
                    url: 'app/get-confirm-message',
                    data: {confirm_message: app.message_code},
                    success: function(response){
                        //retorna com a mensagem
                        modal.find('.modal-body').html(response);
                    }
                });
        }", []),
        'hide.bs.modal'=>  new JsExpression(
                "function() {
                     Loading.show();    
                        $(this).find('#alerts_alert_form').html('');
                    $.ajax({
                        type: 'GET',
                        url: 'alerts/active-alerts',
                        data: { user_id: app.user.id },
                        success: function(response){
                            $('#active-alerts').html(response); // atualiza a tabela de alertas
                            Loading.hide();
                        }
                    });
                 }"
                , [])
    ]
]);
?>

<?php
 Modal::end();
?>

<?php
/** 
 * Modal de informação universal 
 */
Modal::begin([
    'id' => 'alerts_information_modal',
    'size' => Modal::SIZE_SMALL,
    'header' =>"<div class='modal-title'>Atenção <span class='glyphicon glyphicon-alert'></span></div>",
    'footer' =>"",
    //'closeButton' => ['dat'],
    'options'=>['class' => 'modal modal-wide'],
    'clientEvents' => [
        'shown.bs.modal'=>  new JsExpression("
            function(e){
                var modal = $(this);
                $.ajax({
                    type: 'POST',
                    url: 'app/get-confirm-message',
                    data: {confirm_message: app.message_code},
                    success: function(response){
                        //retorna com a mensagem
                        modal.find('.modal-body').html(response);
                    }
                });
        }", []),
        'hide.bs.modal'=>  new JsExpression(
                "function() {
                 }"
                , [])
    ]
]);
?>

<?php
 Modal::end();
?>

