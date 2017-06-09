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
$('body').on('click', '#user-feeding-list li.item', function(){
    app.user_feedings.id = $(this).prop('id').split('-')[4];
    app.user.sharings.content.id = $(this).prop('id').split('-')[3];
    app.user.sharings.selectedTypeId = $(this).prop('id').split('-')[2];
    $('#panel_user_feeding_item_modal').modal('show');
});

$('body').on('click', 'li#user-feeding-load-button', function(){
    
    var _button = $(this);
    app.user_feedings.id = $(this).prev().prop('id').split('-')[4];
    
    $('#user-feeding').fadeOut(800, function(){
        $.ajax({
             url: 'user-feedings/more',
                    type: 'GET',
                    //async: false,
                    data:{
                         id: app.user_feedings.id,
                    },
                    success: function(rtn){
                        _button.before(rtn);
                        $('#user-feeding').fadeIn(800);
                    },
        });
    })
});

//Modal de fotos dos bicicletários
$('body').on('click', '.btn.bike-keeper-photos', function(e){
    app.bike_keeper.id = $(this).parent().next().val();
});