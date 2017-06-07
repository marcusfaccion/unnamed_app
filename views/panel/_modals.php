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
                var _controller = '';
                var _geoJSON_layer = null;
                map.invalidateSize(); // atualiza o tamanho do mapa para novo tamanho do modal
                
                switch(parseInt(app.user.sharings.selectedTypeId)){
                    case app.user.sharings.types.alerts:
                        _controller = 'alerts';
                        _geoJSON_layer = geoJSON_layer.alerts;
                        break;
                    case app.user.sharings.types.bike_keepers:
                        _controller = 'bike-keepers';
                        _geoJSON_layer = geoJSON_layer.bike_keepers;
                        break;
                    case app.user.sharings.types.routes:
                        _controller = 'routes';
                        _geoJSON_layer = geoJSON_layer.routes;
                        break;
                }
                console.log(_controller)
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
                        modal.find('.modal-body2').html(response);
                        // Plotando layer
                        $.ajax({
                            url: _controller+'/get-feature',
                            type: 'GET',
                            async: false,
                            data: {id: app.user.sharings.content.id},
                            success: function(geojson){
                                _geoJSON_layer.addData(
                                        JSON.parse(geojson)
                                );
                            }
                        });
                        
                    }
                });
        }", []),
        'hide.bs.modal'=>  new JsExpression(
                "function() {
                            $(this).find('.modal-body2').html('');
                            geoJSON_layer.alerts.clearLayers() // remove os layers
                            geoJSON_layer.bike_keepers.clearLayers()
                            geoJSON_layer.routes.clearLayers()
                 }"
                , [])
    ]
]);
?>

<div id="map" style="margin-top: 0;">   
</div>

<div class="modal-body2">   
</div>

<?php
 Modal::end();
?>