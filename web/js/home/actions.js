$('body').on('click', '#home-user-navigation-stop', function(){
    app.message_code = 'routes.stop.navigation';
     app.yconfirmation.action = function(){
         
         geoJSON_layer.routes.clearLayers(); // apaga as rotas do usuário do layers routes, removendo-a da tela consecutivamente.
         
         directions.setOrigin();
         directions.setDestination();
         me.latlng_history.destroy();
         
         app.directions.myOrigin = false;
         app.directions.free = true; // Entra no modo navegação normal
         app.directions.pause = false;
         
         $('#home-user-navigation-stop').fadeOut('now');
         $('#map_menu .nav-destination-reset').fadeOut('now'); //corrige bug se essa ação é acionada pela trigger do botão redefinir rota
     };
    $('#home_confirmation_modal').modal('show');
});
    
$('body').on('click', '#home_btn_my_location', function(){
    if(!showMyLocation()){
        app.directions.myOrigin = false;
        app.directions.free = false;
        app.directions.pause = false;
        //Atualizando o menu do mapa quando a navegação for desativada
        map_popup_menu.setContent(map_conf.popup_menu.getContent({nav:0}));
        map_popup_menu.update();
    }
    
});
$('body').on('click', '#user-navigation-pane-toggle, #home-user-menu-navigation', function(){
    if($('#user-navigation-pane-toggle').children('span').hasClass('glyphicon-chevron-right')){
        $("#user-navigation-container").animate( {'margin-left' : "0px"}, 400);
        $('#user-navigation-pane-toggle').children('span').removeClass('glyphicon-chevron-right');
        $('#user-navigation-pane-toggle').children('span').addClass('glyphicon-chevron-left');
    }else{
        $("#user-navigation-container").animate( {'margin-left' : "-1000px"}, 400);
        $('#user-navigation-pane-toggle').children('span').removeClass('glyphicon-chevron-left');
        $('#user-navigation-pane-toggle').children('span').addClass('glyphicon-chevron-right');
    }
      //Tradução das instruções do plugin directions
       for(i=0; i<$('#instructions').find('div.mapbox-directions-step-maneuver').length;++i){
           $('#instructions').find('div.mapbox-directions-step-maneuver')[i].innerHTML = app.directions.t($('#instructions').find('div.mapbox-directions-step-maneuver')[i].innerHTML);
       }
       $('#instructions ol.mapbox-directions-steps').before('<a class="translater-control hide"></a>');
       
});

// Botão do painel de navegação que dispara a query de roteamento para o plugin directions
//$('body').on('click', '#directions-query-btn', function(){
      //if(directions.queryable())
  //      directions.query();
//});

$('body').on('click', '#routes li.mapbox-directions-route', function(){
      //Tradução das instruções do plugin directions
       for(i=0; i<$('#instructions').find('div.mapbox-directions-step-maneuver').length;++i){
           $('#instructions').find('div.mapbox-directions-step-maneuver')[i].innerHTML = app.directions.t($('#instructions').find('div.mapbox-directions-step-maneuver')[i].innerHTML);
       }
       $('#instructions ol.mapbox-directions-steps').before('<a class="translater-control hide"></a>');
});


$('body').on('mousemove', '#user-navigation-container', function(){
      //Tradução das instruções do plugin directions
      if(!$('#instructions').children().first().hasClass('translater-control')){
          $('#instructions ol.mapbox-directions-steps').before('<a class="translater-control hide"></a>');
           if($('#instructions').find('div.mapbox-directions-step-maneuver').length>0){
               for(i=0; i<$('#instructions').find('div.mapbox-directions-step-maneuver').length;++i){
                   $('#instructions').find('div.mapbox-directions-step-maneuver')[i].innerHTML = app.directions.t($('#instructions').find('div.mapbox-directions-step-maneuver')[i].innerHTML);
               }
               $('#instructions').children().first().remove();
           }
      }
});
$('body').on('touchmove', '#user-navigation-container', function(){
      //Tradução das instruções do plugin directions
      if(!$('#instructions').children().first().hasClass('translater-control')){
          $('#instructions ol.mapbox-directions-steps').before('<a class="translater-control hide"></a>');
           if($('#instructions').find('div.mapbox-directions-step-maneuver').length>0){
               for(i=0; i<$('#instructions').find('div.mapbox-directions-step-maneuver').length;++i){
                   $('#instructions').find('div.mapbox-directions-step-maneuver')[i].innerHTML = app.directions.t($('#instructions').find('div.mapbox-directions-step-maneuver')[i].innerHTML);
               }
               $('#instructions').children().first().remove();
           }
      }
});

// Clique dos botões do Modal de Confirmação
$('body').on('click', '#no-confirm, #yes-confirm', function(){
    app.user_confirmation = parseInt($(this).val());
    
    // Executa ou não a requisição após a confirmação
    if(app.user_confirmation){
        if(app.request.ajax!=null){
            $.ajax(app.request.ajax);
        }
        if(app.yconfirmation.action!=null){
            if(typeof(app.yconfirmation.action)==='function'){
                app.yconfirmation.action();
            }
        }
    }else{
          if(app.nconfirmation.action!=null){
                if(typeof(app.nconfirmation.action)==='function'){
                    app.nconfirmation.action();
                }
            }
    }
});

// Clique dos botões do Modal de Compartilhamento
$('body').on('click', '#no-confirm-sharing, #yes-confirm-sharing', function(){
    app.user_confirmation = parseInt($(this).val());
    sharing_modal = $('#home_user_sharings_modal');
    
    if(app.user_confirmation){
        //Configurando a requisição de compartilhamento
        app.user.sharings.form = $('#user-sharings-form');
        app.user.sharings.form[0].elements[1].value = app.user.id;//id do usuério
        app.user.sharings.form[0].elements[2].value = app.user.sharings.selectedTypeId; // tipo do compartilhamento
        app.user.sharings.form[0].elements[3].value = null; // id do conteúdo compartilhado, rota inda não criada
        app.user.sharings.form[0].elements[4].value = app.user.sharings.form.find('.feeding-text').val(); // text do compartilhamento
        app.user.sharings.form[0].elements[5].value = JSON.stringify(app.directions.origin.geometry), //geoJSON da origem
        app.user.sharings.form[0].elements[6].value = JSON.stringify(app.directions.destination.geometry), //geoJSON do destino
        app.user.sharings.form[0].elements[7].value = JSON.stringify(me.layers.route.toGeoJSON().geometry), //geoJSON da LineString da rota
        app.user.sharings.form[0].elements[8].value = app.directions.elapsed_time, //tempo gasto em rota pelo usuário, calculado pelo js
        app.user.sharings.form[0].elements[9].value = app.directions.origin.properties.name, //endereço textual da origem (rua)
        app.user.sharings.form[0].elements[10].value = app.directions.destination.properties.name, //endereço textual do destino (rua)

        $.ajax({
            type: 'POST',
            url: 'user-sharings/create',
            //data: { id_sharing_type: id },
            data: app.user.sharings.form.serialize(),
            success: function(response){
                sharing_modal.find('.modal-body').html(response);
                $('#home_user_sharings_modal').find('.modal-footer .toBeclosed').hide();
                $('#home_user_sharings_modal').find('#confirm-sharing-close').show();
            }
        });
    }else{
        ;;
    }
    
});