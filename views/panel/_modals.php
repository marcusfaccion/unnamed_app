<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\bootstrap\Modal;
use yii\web\JsExpression;

?>

<?php
/** 
 * Modal de confirmação universal 
 */
Modal::begin([
    'id' => 'panel_user_feeding_item_modal',
    'size' => Modal::SIZE_LARGE,
    'header' =>"<div class='modal-title text-primary strong-6 tsize-4'>Item compartilhado <span class='glyphicon glyphicon-comment'></span></div>",
  /* 'footer' =>"<button id='yes-confirm' type='button' class='btn btn-xs btn-success' value='1' data-dismiss='modal'>Sim</button>
      <button id='no-confirm' type='button' class='btn btn-xs btn-danger' value='0' data-dismiss='modal'>Não</button>",*/
    'closeButton' => ['dat'],
    'options'=>['class' => 'modal modal-wide'],
    'clientEvents' => [
        'shown.bs.modal'=>  new JsExpression("
            function(e){
                var modal = $(this);
                $.ajax({
                    type: 'POST',
                    url: 'user-feedings/interaction-panel',
                    data: {
                        UserFeedings: {
                            id: app.user_feedings.id,
                        }
                    },
                    success: function(response){
                        //retorna com a mensagem
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
?>

<?php
 Modal::end();
?>