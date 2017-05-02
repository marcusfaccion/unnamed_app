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
                            $(this).find('#alerts_alert_form').html('');
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
