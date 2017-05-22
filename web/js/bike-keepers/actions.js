$('body').on('click', '#active-bike-keepers .bike-keeper-select-all', function(){
    $('#bike-keepers-table').find('input[type=checkbox]').prop('checked',true);
});

$('body').on('click', '#active-bike-keepers .bike-keeper-noselect-all', function(){
    $('#bike-keepers-table').find('input[type=checkbox]').prop('checked',false);
});

$('body').on('click', '#bike-keepers-table tr.data input', function(){
    $('#bike-keepers-table tr.data').removeClass('success');
    $(this).parent().parent().toggleClass('success');
});

//Seleciona o id do bicicletário para a atualização da capacidade do bicicletário
$('body').on('click', '#bike-keepers-table .btn.bike-keeper-capacity', function(){
    app.bike_keeper.id = $(this).parent().parent().parent().find('th input').val();
    app.message_code = 'bike-keepers.update.used-capacity';
});

//Interface de cliques para o slider de atualização de quantidade de vagas usadas
$('body').on('click', '#bike_keepers_used_capacity_modal .bike-keeper-used-capacity-display .btn', function(){
    capacity = parseInt($('#bike_keepers_used_capacity_modal .bike-keeper-used-capacity-display').find('strong').html());
    if($(this).hasClass('btn-up') && $(this).parent().next().slider( "option", "max")>capacity){
        ++capacity;
        $(this).parent().next().slider( "option", "value", capacity);
        $(this).prev().html($(this).parent().next().slider( "option", "value"));
    }else{
        if($(this).hasClass('btn-down') && $(this).parent().next().slider( "option", "min")<capacity){
            --capacity;
            $(this).parent().next().slider( "option", "value", capacity);
            $(this).next().html($(this).parent().next().slider( "option", "value"));
        }
    }
    app.bike_keeper.used_capacity = capacity;
});

//Salva a atualização da capacidade do bicicletário
$('body').on('click', '#bike_keepers_used_capacity_modal .btn.bike-keeper-capacity.save', function(){
    Loading.show();
    $.ajax({
        type: 'POST',
        url: 'bike-keepers/used-capacity',
        data: { BikeKeepers: { 
                id: app.bike_keeper.id,
                used_capacity: app.bike_keeper.used_capacity,
                }},
        success: function(response){
            $('#bike_keepers_used_capacity_modal .modal-body').html(response); // atualiza a tabela de bicicletários
            Loading.hide();
        }
    });
});

//Seleciona o id do bicicletário para atualizá-lo
$('body').on('click', '#bike-keepers-table .btn.bike-keeper-update', function(){
    app.bike_keeper.id = $(this).parent().parent().parent().find('th input').val();
});

//Exibe opções oculta no formulário de atualização de biicletário
$('body').on('change', '#bike_keepers_update_modal .bike-keepers.input_show_trigger', function(e){
    if(($(this).attr('value'))==0){
        $('.bike-keepers-input-hidden'+$(this).attr('data-target-input')).fadeIn('now').removeClass('hide');
        $('.bike-keepers-input-hidden'+$(this).attr('data-target-input')+' input').attr('disabled', false);
    }else{
        var radio = $(this);
        $('.bike-keepers-input-hidden'+$(this).attr('data-target-input')).fadeOut('now', function(){
            $('.bike-keepers-input-hidden'+radio.attr('data-target-input')).addClass('hide');
            $('.bike-keepers-input-hidden'+radio.attr('data-target-input')+' input').attr('disabled', true);
        });
    }
});

// Desativa todos os bicicletários selecionados
$('body').on('click', '#active-bike-keepers .btn.bike-keeper-disable-all', function(){
    bike_keeper_ids = [];
    $('#bike-keepers-table').find('input[type=checkbox]:checked').each(
    function(index, elm){
        bike_keeper_ids[index] = elm.value; // guarda os ids marcados para desativação
    });
    if(bike_keeper_ids.length>0){ 
        //Mensagem de confirmação
        app.message_code = 'bike-keepers.disable-all.confirmation'; 
        //Função para executar após a requisição
        app.request.afterAction = function(rtn, str){
            Loading.show();    
            $.ajax({
                type: 'GET',
                url: 'bike-keepers/active-bike-keepers',
                data: { user_id: app.user.id },
                success: function(response){
                    $('#bike-keepers-container').html(response); // atualiza a tabela de bicicletários
                    Loading.hide();
                }
            });
        };
        //Configurando a requisição de desativação
        app.request.ajax = {
                    url: 'bike-keepers/disable',
                    type: 'POST',
                    async: false,
                    data:{
                        BikeKeepers: {id: bike_keeper_ids}
    //                                  BikeKeepers: {id: bike_keeper_layer.feature.properties.id}
                    },
                    success: function(rtn){
                        if(rtn){ //remove o bicicletário do mapa
                            app.layers.selected.forEach(function(layer, index, array){
                                geoJSON_layer.bike_keepers.removeLayer(layer);
                            });
                        }
                    },
                    complete: app.request.afterAction,
                }

        // Percorre todo o Layer bike_keepers para encontrar os bicicletários sendo desativados
        app.layers.selected = [];
        geoJSON_layer.bike_keepers.getLayers().forEach(
                function(bike_keeper_layer, index, array){
                    if(bike_keeper_ids.includes(bike_keeper_layer.feature.properties.id.toString())){
                       app.layers.selected[index] = bike_keeper_layer; // guarda bicicletário
                     }
                });
        //Mostra o modal de confirmação
        $('#bike_keepers_confirmation_modal').modal('show');
    }else{
        app.message_code = 'bike_keepers.disable-all.select-error'; 
        //Mostra o modal de inforrmação
        $('#bike_keepers_information_modal').modal('show');
    }
});

//desativa o bicicletário selecionado
$('body').on('click', '#bike-keepers-table .btn.bike-keeper-disable-one', function(){
    app.bike_keeper.id = $(this).parent().parent().find('th input').val();
        //Mensagem de confirmação
        app.message_code = 'bike-keepers.disable.confirmation'; 
        //Função para executar após a requisição
        app.request.afterAction = function(rtn, str){
            Loading.show();    
            $.ajax({
                type: 'GET',
                url: 'bike-keepers/active-bike-keepers',
                data: { user_id: app.user.id },
                success: function(response){
                    $('#bike-keepers-container').html(response); // atualiza a tabela de bicicletários
                    Loading.hide();
                }
            });
        };
        //Configurando a requisição de desativação
        app.request.ajax = {
                    url: 'bike-keepers/disable',
                    type: 'POST',
                    async: false,
                    data:{
                        BikeKeepers: {id: app.bike_keeper.id}
    //                                  BikeKeepers: {id: bike_keeper_layer.feature.properties.id}
                    },
                    success: function(rtn){
                        if(rtn){ //remove o bicicletário do mapa
                                geoJSON_layer.bike_keepers.removeLayer(app.layers.selected);
                        }
                    },
                    complete: app.request.afterAction,
                }

        // Percorre todo o Layer bike_keepers para encontrar os bicicletários sendo desativados
        app.layers.selected = [];
        geoJSON_layer.bike_keepers.getLayers().forEach(
                function(bike_keeper_layer, index, array){
                    if(app.bike_keeper.id==bike_keeper_layer.feature.properties.id){
                       app.layers.selected = bike_keeper_layer; // guarda bicicletário
                     }
                });
        //Mostra o modal de confirmação
        $('#bike_keepers_confirmation_modal').modal('show');
});

// Destaca o bicicletário no mapa aplicando um setView e zoom
$('body').on('click', '#bike-keepers-table .btn.bike-keeper-off, #bike-keepers-table .btn.bike-keeper-on', function(){
    app.bike_keeper.id = $(this).parent().parent().find('th input').val();
    
    if($(this).hasClass('bike-keeper-off') && !$(this).hasClass('bike-keeper-on')){
        action = 'off';
        app.message_code = 'bike-keepers.off.confirmation'; 
    }
    if($(this).hasClass('bike-keeper-on') && !$(this).hasClass('bike-keeper-off')){
        action = 'on';
        app.message_code = 'bike-keepers.on.confirmation'; 
    }
    
    geoJSON_layer.bike_keepers.getLayers().forEach(
                function(bike_keeper_layer, index, array){
                    if(app.bike_keeper.id==bike_keeper_layer.feature.properties.id){
                       app.layers.selected = bike_keeper_layer; // guarda bicicletário
                     }
                });
      
        //Configurando a requisição de fechamento do expediente do bicicletário
        app.request.ajax = {
                    url: 'bike-keepers/'+action,
                    type: 'POST',
                    async: false,
                    data:{
                        BikeKeepers: {id: app.bike_keeper.id}
    //                                  BikeKeepers: {id: bike_keeper_layer.feature.properties.id}
                    },
                    success: function(rtn){
                        if(rtn){ //remove o bicicletário do mapa
                               // geoJSON_layer.bike_keepers.removeLayer(app.layers.selected);
                               ;;
                        }
                    },
                    complete: app.request.afterAction,
                }
        //Função para executar após a requisição
        app.request.afterAction = function(rtn, str){
            Loading.show();    
            $.ajax({
                type: 'GET',
                url: 'bike-keepers/active-bike-keepers',
                data: { user_id: app.user.id },
                success: function(response){
                    $('#bike-keepers-container').html(response); // atualiza a tabela de bicicletários
                    Loading.hide();
                }
            });
        };
        
        //Mostra o modal de confirmação
        $('#bike_keepers_confirmation_modal').modal('show');
    
});

// Destaca o bicicletário no mapa aplicando um setView e zoom
$('body').on('click', '#bike-keepers-table .btn.bike-keeper-view-on-map', function(){
    app.bike_keeper.id = $(this).parent().parent().find('th input').val();
     geoJSON_layer.bike_keepers.getLayers().forEach(
                function(bike_keeper_layer, index, array){
                    if(app.bike_keeper.id==bike_keeper_layer.feature.properties.id){
                       app.layers.selected = bike_keeper_layer; // guarda bicicletário
                     }
                });
     window.scrollTo(0, 0); //faz um scroll da tela para o mapa
     corner = app.layers.selected.getLatLng(); // obtém o objeto L.latlng do layer bicicletário
     bounds = L.latLngBounds(corner, corner); // obtém o objeto L.latlngBounds para o bicicletário
     map.fitBounds(bounds); // aplica o setview com zoom max
    
});

// Aba Problemas //

// Destaca o bicicletário no mapa aplicando um setView e zoom
$('body').on('click', '#nonbike-keeper-accordion .btn.nonbike-keeper-view-on-map', function(){
     app.bike_keeper.id = $(this).parent().find('input:last-child').val();
     geoJSON_layer.bike_keepers.getLayers().forEach(
                function(bike_keeper_layer, index, array){
                    if(app.bike_keeper.id==bike_keeper_layer.feature.properties.id){
                       app.layers.selected = bike_keeper_layer; // guarda bicicletário
                     }
                });
     window.scrollTo(0, 0); //faz um scroll da tela para o mapa
     corner = app.layers.selected.getLatLng(); // obtém o objeto L.latlng do layer bicicletário
     bounds = L.latLngBounds(corner, corner); // obtém o objeto L.latlngBounds para o bicicletário
     map.fitBounds(bounds); // aplica o setview com zoom max 
});

// Desativa o bicicletário e o remove do mapa
$('body').on('click', '#nonbike-keeper-accordion .btn.nonbike-keeper-disable', function(){
        app.bike_keeper.id = $(this).parent().find('input:last-child').val();
        //Mensagem de confirmação
        app.message_code = 'bike-keepers.disable.confirmation'; 
        //Função para executar após a requisição
        app.request.afterAction = function(rtn, str){
            Loading.show();    
            $.ajax({
                type: 'POST',
                url: 'bike-keepers/delete-user-bike-keeper-nonexistence',
                data: { BikeKeepers: { id: app.bike_keeper.id }},
                success: function(_response){
                    if(_response){
                        $.ajax({
                            type: 'GET',
                            url: 'bike-keepers/active-bike-keepers',
                            data: { user_id: app.user.id, tab: 'problem'},
                            success: function(response){
                                $('#bike-keepers-container').html(response); // atualiza as tabelas de bicicletários
                                Loading.hide();
                            }
                        });

                    }
                ;;}
            });
        };
        //Configurando a requisição de desativação
        app.request.ajax = {
                    url: 'bike-keepers/disable',
                    type: 'POST',
                    async: false,
                    data:{
                        BikeKeepers: {id: app.bike_keeper.id}
    //                                  BikeKeepers: {id: bike_keeper_layer.feature.properties.id}
                    },
                    success: function(rtn){
                        if(rtn){ //remove o bicicletário do mapa
                                geoJSON_layer.bike_keepers.removeLayer(app.layers.selected);
                        }
                    },
                    complete: app.request.afterAction,
                }

        // Percorre todo o Layer bike_keepers para encontrar os bicicletários sendo desativados
        app.layers.selected = [];
        geoJSON_layer.bike_keepers.getLayers().forEach(
                function(bike_keeper_layer, index, array){
                    if(app.bike_keeper.id==bike_keeper_layer.feature.properties.id){
                       app.layers.selected = bike_keeper_layer; // guarda bicicletário
                     }
                });
        //Mostra o modal de confirmação
        $('#bike_keepers_confirmation_modal').modal('show');
});

//Seleciona o bicicletário para verificação de informantes
$('body').on('click', '#nonbike-keeper-accordion .btn.nonbike-keeper-view-users', function(){
    app.bike_keeper.id = $(this).parent().parent().find('input:last-child').val();
});

// Adiciona informante como amigo se for o caso
$('body').on('click', '#bike_keepers_view_users_modal .btn.nonbike-keeper-user-add', function(){
    user_friend_id = $(this).parent().parent().find('input:last-child').val();
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