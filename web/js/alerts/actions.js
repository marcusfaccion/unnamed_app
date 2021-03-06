$('body').on('click', '#active-alerts .alert-select-all', function(){
    $('#alerts-table').find('input[type=checkbox]').prop('checked',true);
});

$('body').on('click', '#active-alerts .alert-noselect-all', function(){
    $('#alerts-table').find('input[type=checkbox]').prop('checked',false);
});

$('body').on('click', '#alerts-table tr.data input', function(){
    $('#alerts-table tr.data').removeClass('success');
    $(this).parent().parent().toggleClass('success');
});

$('body').on('click', '#alerts-table .btn.alert-update', function(){
    app.alert.id = $(this).parent().parent().find('th input').val();
    $('#alert_update_modal').modal('show');
});

// Desativa todos os alertas selecionados
$('body').on('click', '#active-alerts .btn.alert-disable-all', function(){
    alert_ids = [];
    $('#alerts-table').find('input[type=checkbox]:checked').each(
    function(index, elm){
        alert_ids[index] = elm.value; // guarda os ids marcados para desativação
    });
    if(alert_ids.length>0){ 
        //Mensagem de confirmação
        app.message_code = 'alerts.disable-all.confirmation'; 
        //Função para executar após a requisição
        app.request.afterAction = function(rtn, str){
            Loading.show();    
            $.ajax({
                type: 'GET',
                url: 'alerts/active-alerts',
                data: { user_id: app.user.id },
                success: function(response){
                    $('#alerts-container').html(response); // atualiza a tabela de alertas
                    Loading.hide();
                }
            });
        };
        //Configurando a requisição de desativação
        app.request.ajax = {
                    url: 'alerts/disable',
                    type: 'POST',
                    async: false,
                    data:{
                        Alerts: {id: alert_ids}
    //                                  Alerts: {id: alert_layer.feature.properties.id}
                    },
                    success: function(rtn){
                        if(rtn){ //remove o alerta do mapa
                            app.layers.selected.forEach(function(layer, index, array){
                                geoJSON_layer.alerts.removeLayer(layer);
                            });
                        }
                    },
                    complete: app.request.afterAction,
                }

        // Percorre todo o Layer alerts para encontrar os alertas sendo desativados
        app.layers.selected = [];
        geoJSON_layer.alerts.getLayers().forEach(
                function(alert_layer, index, array){
                    if(alert_ids.includes(alert_layer.feature.properties.id.toString())){
                       app.layers.selected[index] = alert_layer; // guarda alerta
                     }
                });
        //Mostra o modal de confirmação
        $('#alerts_confirmation_modal').modal('show');
    }else{
        app.message_code = 'alerts.disable-all.select-error'; 
        //Mostra o modal de inforrmação
        $('#alerts_information_modal').modal('show');
    }
});

//desativa o alerta selecionado
$('body').on('click', '#alerts-table .btn.alert-disable-one', function(){
    app.alert.id = $(this).parent().parent().find('th input').val();
        //Mensagem de confirmação
        app.message_code = 'alerts.disable.confirmation'; 
        //Função para executar após a requisição
        app.request.afterAction = function(rtn, str){
            Loading.show();    
            $.ajax({
                type: 'GET',
                url: 'alerts/active-alerts',
                data: { user_id: app.user.id },
                success: function(response){
                    $('#alerts-container').html(response); // atualiza a tabela de alertas
                    Loading.hide();
                }
            });
        };
        //Configurando a requisição de desativação
        app.request.ajax = {
                    url: 'alerts/disable',
                    type: 'POST',
                    async: false,
                    data:{
                        Alerts: {id: app.alert.id}
    //                                  Alerts: {id: alert_layer.feature.properties.id}
                    },
                    success: function(rtn){
                        if(rtn){ //remove o alerta do mapa
                                geoJSON_layer.alerts.removeLayer(app.layers.selected);
                        }
                    },
                    complete: app.request.afterAction,
                }

        // Percorre todo o Layer alerts para encontrar os alertas sendo desativados
        app.layers.selected = [];
        geoJSON_layer.alerts.getLayers().forEach(
                function(alert_layer, index, array){
                    if(app.alert.id==alert_layer.feature.properties.id){
                       app.layers.selected = alert_layer; // guarda alerta
                     }
                });
        //Mostra o modal de confirmação
        $('#alerts_confirmation_modal').modal('show');
});

// Destaca o alerta no mapa aplicando um setView e zoom
$('body').on('click', '#alerts-table .btn.alert-view-on-map', function(){
    app.alert.id = $(this).parent().parent().find('th input').val();
     geoJSON_layer.alerts.getLayers().forEach(
                function(alert_layer, index, array){
                    if(app.alert.id==alert_layer.feature.properties.id){
                       app.layers.selected = alert_layer; // guarda alerta
                     }
                });
     window.scrollTo(0, 0); //faz um scroll da tela para o mapa
     corner = app.layers.selected.getLatLng(); // obtém o objeto L.latlng do layer alerta
     bounds = L.latLngBounds(corner, corner); // obtém o objeto L.latlngBounds para o alerta
     map.fitBounds(bounds); // aplica o setview com zoom max
    
});

$('body').on('click', '#alert_update_modal .btn.alert-save', function(){
    $('#alert_update_modal .modal-title').fadeOut('now');
    isAjax = $('#alerts-widget-form').find('.isAjax').val();
    
    if(isAjax){
        preloader.hide('alert_update_modal .modal-body', 'cicle_ball', '64', function(){
             $.ajax({
                type: 'POST',
                url: 'alerts/update',
                async: false,
                data:  $('#alerts-widget-form').serialize(),
                success: function(response){
                     $('#alert_update_modal .modal-body').html('');
                     preloader.show('alert_update_modal .modal-body', 'cicle_ball', '64', function (){
                        $('#alert_update_modal .modal-body').html(response).hide().fadeIn('fast');  
                     });
                     $('#alert_update_modal .modal-title').fadeIn('now');
                }
             });
        });
    }
});

$('body').on('click', '#alert_update_modal .btn.alert-back', function(){
    $('#alert_update_modal').modal('hide');
});

// Aba Problemas //

// Destaca o alerta no mapa aplicando um setView e zoom
$('body').on('click', '#nonalert-accordion .btn.nonalert-view-on-map', function(){
     app.alert.id = $(this).parent().find('input:last-child').val();
     geoJSON_layer.alerts.getLayers().forEach(
                function(alert_layer, index, array){
                    if(app.alert.id==alert_layer.feature.properties.id){
                       app.layers.selected = alert_layer; // guarda alerta
                     }
                });
     window.scrollTo(0, 0); //faz um scroll da tela para o mapa
     corner = app.layers.selected.getLatLng(); // obtém o objeto L.latlng do layer alerta
     bounds = L.latLngBounds(corner, corner); // obtém o objeto L.latlngBounds para o alerta
     map.fitBounds(bounds); // aplica o setview com zoom max 
});

// Desativa o alerta e o remove do mapa
$('body').on('click', '#nonalert-accordion .btn.nonalert-disable', function(){
        app.alert.id = $(this).parent().find('input:last-child').val();
        //Mensagem de confirmação
        app.message_code = 'alerts.disable.confirmation'; 
        //tab de navegação ativa
        app.user.tab = 'problem'
        //Função para executar após a requisição
        app.request.afterAction = function(rtn, str){
            Loading.show();    
            $.ajax({
                type: 'POST',
                url: 'alerts/delete-user-alert-nonexistence',
                data: { Alerts: { id: app.alert.id }},
                success: function(_response){
                    if(_response){
                        $.ajax({
                            type: 'GET',
                            url: 'alerts/active-alerts',
                            data: { user_id: app.user.id, tab: app.user.tab },
                            success: function(response){
                                $('#alerts-container').html(response); // atualiza as tabelas de alertas
                                Loading.hide();
                            }
                        });

                    }
                ;;}
            });
        };
        //Configurando a requisição de desativação
        app.request.ajax = {
                    url: 'alerts/disable',
                    type: 'POST',
                    async: false,
                    data:{
                        Alerts: {id: app.alert.id}
    //                                  Alerts: {id: alert_layer.feature.properties.id}
                    },
                    success: function(rtn){
                        if(rtn){ //remove o alerta do mapa
                                geoJSON_layer.alerts.removeLayer(app.layers.selected);
                        }
                    },
                    complete: app.request.afterAction,
                }

        // Percorre todo o Layer alerts para encontrar os alertas sendo desativados
        app.layers.selected = [];
        geoJSON_layer.alerts.getLayers().forEach(
                function(alert_layer, index, array){
                    if(app.alert.id==alert_layer.feature.properties.id){
                       app.layers.selected = alert_layer; // guarda alerta
                     }
                });
        //Mostra o modal de confirmação
        $('#alerts_confirmation_modal').modal('show');
});

// Destaca o alerta no mapa aplicando um setView e zoom
$('body').on('click', '#nonalert-accordion .btn.nonalert-view-users', function(){
    app.alert.id = $(this).parent().parent().find('input:last-child').val();
});

// Destaca o alerta no mapa aplicando um setView e zoom
$('body').on('click', '#alert_view_users_modal .btn.nonalert-user-add', function(){
    user_friend_id = $(this).parent().find('input:last-child').val();
    _this = $(this);
    Loading.show();    
    $.ajax({
        url: "friends/add",
        type: "POST",
        data: {UserFriendshipRequest : {requested_user_id: user_friend_id}},
        success: function(_response){
            if(_response){
                _this.parent().find('small').html(_response).fadeIn('now');
                _this.addClass('disabled');
                Loading.hide();
            }
        ;;}
    });
    
});

// Clique dos botões do Modal de Confirmação
$('body').on('click', '#no-confirm, #yes-confirm', function(){
    app.user_confirmation = parseInt($(this).val());
    
    // Executa ou não a requisição após a confirmação
    if(app.user_confirmation){
        $.ajax(app.request.ajax);
    }
});