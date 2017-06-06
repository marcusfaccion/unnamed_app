<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\bootstrap\Modal;
use yii\web\JsExpression;

?>
<?php
//Modal de escolha de ações
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
                            map.closePopup(map_popup_menu);
                 }"
                , [])
    ]
]);
?>

<?php
Modal::end();
?>

<?php
// Modal de informações
Modal::begin([
    'id' => 'home_information_modal',
    'size' => Modal::SIZE_SMALL,
    'header' =>"<div class='modal-title text-primary strong-6'>Informação <span class='glyphicon glyphicon-info-sign'></div>",
    'footer' =>"<button type='button' class='btn btn-xs btn-danger' value='1' data-dismiss='modal'>Fechar</button>",
    //'closeButton' => ['dat'],
    'options'=>['class' => 'modal modal-wide'],
    'clientEvents' => [
        'shown.bs.modal'=>  new JsExpression(
                "function() {
                    var modal = $(this);
                    $.ajax({
                       type:  app.request.ajax.type,
                        url:  app.request.ajax.url,
                        data:  app.request.ajax.data,
                        success: function(response){
                            modal.find('.modal-body').html(response);
                        }
                    });
                 }"
                , []),
          'hide.bs.modal'=>  new JsExpression(
                "function() {
                            $(this).find('.modal-body').html('');
                            map.closePopup(map_popup_menu);
                 }"
                , [])
    ]
]);
?>

<?php
Modal::end();
?>

<?php
// Modal de informações da navegação do usuário
Modal::begin([
    'id' => 'home_user_navigation_modal',
    'size' => Modal::SIZE_LARGE,
    'header' =>"<div class='modal-title'><strong class='text-primary'>Informações de rota</strong></div>",
    //'closeButton' => ['dat'],
    'options'=>['class' => 'modal modal-wide'],
    'clientEvents' => [
        'shown.bs.modal'=>  new JsExpression(
                "function() {
                    if(app.directions.routes.length==0){
                        app.directions.inputSearch = true;
                        $(this).modal('hide');
                        return;
                    }
                    Loading.show();
                    //var action = $('#home_actions_trigger').val().split(';');
                    //$(this).find('.modal-title').text(action[0]);
                    var modal = $(this);
                    var myRoute = {};
                    var myRoutes = [];
                     
                    app.directions.routes.forEach(function(route, i){
                        myRoute.geometry = JSON.stringify(route.geometry);
                        myRoute.duration = route.duration;
                        myRoute.distance = route.distance;
                        myRoutes[i] = myRoute;
                     });
                     
                     
                        app.directions.origin = directions.getOrigin();
                     
                        app.directions.destination = directions.getDestination();
                        
                    $.ajax({
                        type: 'POST',
                        url: 'routes/plan',
                        data: {
                                    Routes: {
                                        multiRoutes: (myRoutes.length>1)?1:0,
                                        origin_geojson: JSON.stringify(app.directions.origin.geometry),
                                        destination_geojson: JSON.stringify(app.directions.destination.geometry),
                                        routes: myRoutes,
                                    }
                                },
                        success: function(response){
                            modal.find('.modal-body').html(response);
                            Loading.hide();
                        }
                    });
                 }"
                , []),
          'hide.bs.modal'=>  new JsExpression(
                "function() {
                            $(this).find('.modal-body').html('');

                            app.directions.loadbyUser = false;
                            
                            if(app.user.location){
                                if(app.directions.myOrigin){
                                    if(directions.queryable())
                                        app.directions.pause=false; //para que o mapa possa aplicar flyTo() caso a origem seja a localização do usuário
                                    map.setView(me.latLng, map_conf.options.maxZoom);
                                    $('#home-user-navigation-stop').fadeIn('now');
                                    app.message_code = 'routes.start.navigation.brief';
                                    app.request.ajax.url = 'app/get-confirm-message';
                                    app.request.ajax.type = 'post';
                                    app.request.ajax.data = {confirm_message: app.message_code};
                                    $('#home_information_modal').modal('show');
                                    me.latlng_history.destroy();// para zerar e começar a gravar as latlngs da rota
                                    app.t0 = performance.now(); // começa a guardar o tempo da rota em msec
                                }
                            }
                            map.closePopup(map_popup_menu);
                 }"
                , []),
          'hidden.bs.modal'=>  new JsExpression(
                "function() {
                            ;;
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
 * Modal de confirmação universal 
 */
Modal::begin([
    'id' => 'home_confirmation_modal',
    'size' => Modal::SIZE_SMALL,
    'header' =>"<div class='modal-title'>Confirmação <span class='glyphicon glyphicon-question-sign'></span></div>",
    'footer' =>"<button id='yes-confirm' type='button' class='btn btn-xs btn-success' value='1' data-dismiss='modal'>Sim</button>
        <button id='no-confirm' type='button' class='btn btn-xs btn-danger' value='0' data-dismiss='modal'>Não</button>",
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
                            $(this).find('.modal-body').html('');
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
 * Modal de fotos dos bicicletários
 */
Modal::begin([
    'id' => 'home_bike_keeper_photos_modal',
    'size' => Modal::SIZE_LARGE,
    'header' =>"<div class='modal-title text-primary tsize-5'><strong><span class='glyphicon glyphicon-picture'></span> Fotos do bicicletário</strong></div>",
    'footer' =>"",
    //'closeButton' => ['dat'],
    'options'=>['class' => 'modal modal-wide'],
    'clientEvents' => [
        'shown.bs.modal'=>  new JsExpression("
            function(e){
                var modal = $(this);
                $.ajax({
                    type: 'GET',
                    url: 'bike-keepers/multimideas',
                    data: { id: app.bike_keeper.id},
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

<?php
/** 
 * Modal de compartilhamento
 */
Modal::begin([
    'id' => 'home_user_sharings_modal',
    'size' => Modal::SIZE_LARGE,
    'header' =>"<div class='modal-title text-primary tsize-5'><strong><span class='glyphicon glyphicon-share'></span> Compartilhando rota...</strong></div>",
    'footer' =>"<button id='no-confirm-sharing' type='button' class='btn btn-xs btn-danger toBeclosed' value='0' data-dismiss='modal'>Cancelar</button>
        <button id='yes-confirm-sharing' type='button' class='btn btn-xs btn-success toBeclosed' value='1'>Compartilhar</button>
        <button id='confirm-sharing-close' type='button' class='btn btn-xs btn-default' data-dismiss='modal' style='display:none;'>Fechar</button>",
    //'closeButton' => ['dat'],
    'options'=>['class' => 'modal modal-wide'],
    'clientEvents' => [
        'shown.bs.modal'=>  new JsExpression("
            function(e){
                var modal = $(this);

                $.ajax({
                    type: 'GET',
                    url: 'user-sharings/form',
                    data: { 
                        UserSharings: {
                            sharing_type_id: app.user.sharings.selectedTypeId ,
                        } 
                    },
                    success: function(response){
                        modal.find('.modal-body').html(response);
                    }
                });
        }", []),
        'hide.bs.modal'=>  new JsExpression(
                "function() {
                            $(this).find('.modal-body').html('');
                            $(this).find('.modal-footer .toBeclosed').show();
                            $(this).find('#confirm-sharing-close').hide();
                 }"
                , [])
    ]
]);
?>

<?php
 Modal::end();
?>