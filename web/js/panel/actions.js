$('body').on('click', '#messages-user-friends-sidebar .messages-friend', function(){
    user_id2 = $(this).children().last().val();
    app.messages.user2.id = user_id2;
    if(1){
        $(this).find('span.conversation-alert').remove();
        $(this).parent().children().toArray().forEach(function(li,i){
           $(li).removeClass('bg-light-primary');
        });
        $(this).addClass('bg-light-primary');
        
        $('#messages-conversation').fadeOut('slow');
        Loading.show();
        $.ajax({
             url: 'messages/conversation',
                    type: 'GET',
                    //async: false,
                    data:{
                        user_id: app.user.id,
                        user_id2: user_id2,
                    },
                    success: function(rtn){
                        if(rtn){ 
                            $('#messages-conversation').fadeIn('slow', function(){
                                $('#messages-conversation-placeholder').html(rtn);
                                $('#messages-user-friends-sidebar .btn.collapse-sidebar').effect( "shake" );
                            });
                        }
                        Loading.hide();
                    },
        });
    }
    
});

$('body').on('click', '#messages-user-friends-sidebar .btn.collapse-sidebar', function(){
   $('#messages-user-friends-sidebar').animate({width:'toggle'},800,function(){
                                    $('#messages-show-sidebar').fadeIn('now');
                                }); 
});

$('body').on('click', '#messages-show-sidebar', function(){
    //$('#messages-user-friends-sidebar .messages-friend').removeClass('bg-light-primary');
    $('#messages-user-friends-sidebar').animate({width:'toggle'},800,function(){
                                    $('#messages-conversation-placeholder').html('<h2 class="text-muted"><cite>Selecione uma conversa.</cite></h2>');
                                    $('#messages-show-sidebar').fadeOut('now');
                                });
});

//Envia a mensagem usando a tecla Enter
$('body').on('keyup', '#userconversations-text', function(e){
    if(e.keyCode==13 && !e.shiftKey){
        $('#messages-conversation .btn.send').click();
    }
});

$('body').on('click', '#messages-conversation .btn.send', function(){
     user_id2 = app.messages.user2.id;
    
     textarea = $(this).parent().prev();
     textarea.parent().removeClass('has-success');
     
     text = textarea.val().trim();
    if(user_id2!==null && text.length>0){ 
        $(this).parent().prev().parent().removeClass('has-error');
        Loading.show();
        $.ajax({
             url: 'messages/create',
                    type: 'POST',
                    //async: false,
                    data:{
                        UserConversations: { 
                            user_id: app.user.id,
                            user_id2: user_id2,
                            text: text,
                        },
                    },
                    success: function(rtn){
                        if(rtn){ 
                            $('#messages-conversation-placeholder').html(rtn);
                            textarea.parent().addClass('has-success');
                            textarea.val('');
                        }
                        Loading.hide();
                    },
        });
    }else{
        $(this).parent().prev().parent().addClass('has-error');
    }    
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