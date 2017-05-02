//var x;

$('body').on('click', '.btn.like', function(e){
        var n_likes = $(this).children('.badge').html(); 
        var alert_id = $(this).parent().parent().find('.alert-id').val();
        var user_avaliation = 0; // 0 significa não avaliado
        
        if(!$(this).hasClass('disabled')){
            $(this).parent().next().children('small').toggleClass('text-muted');
            $(this).parent().next().children('small').toggleClass('text-danger');
            $(this).parent().next().children('.btn.dislike').toggleClass('disabled');
            
            if($(this).parent().next().children('.btn.dislike').hasClass('disabled')){
                ++n_likes;                
                user_avaliation = 1; // Usuário avaliou como positivo
            }else{
                --n_likes; 
                user_avaliation = 0; // Usuário desfez avaliação
            }
            // Para não aparecer a badge caso <= 0
            if(n_likes <= 0){
                n_likes = null;
            }
            
            var _this = $(this);
            $.ajax({
                url: 'alerts/ilike',
                type: 'POST',
                data: {
                    Alerts: {
                        id: alert_id,
                        likes: n_likes,
                        avaliated: user_avaliation,
                    }
               },
               success: function(){
                    _this.children('.badge').html(n_likes);
                    _this.parent().fadeOut('slow', function(){
                        $(this).fadeIn('slow');
                        
                        $(this).find('.btn-classes').val($(this).find('.btn.like').attr('class'));
                        $(this).find('.small-classes').val($(this).find('.btn.like').next().attr('class'));
                        
                        $(this).next().find('.btn-classes').val($(this).next().find('.btn.dislike').attr('class'));
                        $(this).next().find('.small-classes').val($(this).next().find('.btn.dislike').next().attr('class'));
                    });
               }
            });
        }
});

$('body').on('click', '.btn.dislike', function(e){
        var n_dislikes = $(this).children('.badge').html(); 
        var alert_id = $(this).parent().parent().find('.alert-id').val();
        var user_avaliation = 0;
        
        if(!$(this).hasClass('disabled')){
            $(this).parent().prev().children('.btn.like').toggleClass('disabled');
            $(this).parent().prev().children('small').toggleClass('text-muted');
            $(this).parent().prev().children('small').toggleClass('text-success');
            
            if($(this).parent().prev().children('.btn.like').hasClass('disabled')){
                ++n_dislikes;
                user_avaliation = 1; // Usuário avaliou como positivo
            }else{
                --n_dislikes;
                user_avaliation = 0; // Usuário desfez avaliação
            }
            
            // Para não aparecer a badge caso <= 0
            if(n_dislikes <= 0){
                n_dislikes = null;
            }
            
            var _this = $(this);
            $.ajax({
                url: 'alerts/idislike',
                type: 'POST',
                data: {
                    Alerts: {
                        id: alert_id,
                        dislikes: n_dislikes,
                        avaliated: user_avaliation,
                    }
               },
               success: function(){
                   _this.children('.badge').html(n_dislikes);
                    _this.parent().fadeOut('slow', function(){
                        $(this).fadeIn('slow');
                        
                        $(this).find('.btn-classes').val($(this).find('.btn.dislike').attr('class'));
                        $(this).find('.small-classes').val($(this).find('.btn.dislike').next().attr('class'));
                        
                        $(this).prev().find('.btn-classes').val($(this).prev().find('.btn.like').attr('class'));
                        $(this).prev().find('.small-classes').val($(this).prev().find('.btn.like').next().attr('class'));
                    });
               }
            });
            
             
        }
});

$('body').on('click', '.btn.alert-exists', function(e){
        
        var alert_id = $(this).parent().parent().find('.alert-id').val();
        
        // obtém os estados dos botões, outrora armazenados, para usá-los na ativação do alerta 
        var btn_like_classes = $(this).parent().parent().find('.btn-classes.like').val();
        var small_like_classes = $(this).parent().parent().find('.small-classes.like').val();
        var btn_dislike_classes = $(this).parent().parent().find('.btn-classes.dislike').val();
        var small_dislike_classes = $(this).parent().parent().find('.small-classes.dislike').val();
        
        if($(this).parent().parent().find('.btn.like').hasClass('disabled') &&
           $(this).parent().parent().find('.btn.dislike').hasClass('disabled')){ // O usuário já disse anteriormente que o alerta não existe
           
            $(this).parent().parent().find('.btn.like').attr('class', btn_like_classes);
            $(this).parent().parent().find('.btn.like').next().attr('class', small_like_classes);
            $(this).parent().parent().find('.btn.dislike').attr('class', btn_dislike_classes);
            $(this).parent().parent().find('.btn.dislike').next().attr('class', small_dislike_classes);
            
            var _this = $(this);
            $.ajax({
                url: 'alerts/exists',
                type: 'POST',
                data: {
                    Alerts: {
                        id: alert_id,
                    }
               },
                success: function(rtn){
                   if(rtn){
                       _this.fadeOut('slow', function(){
                           $(this).next().fadeIn('slow');
                           $(this).next().next().fadeOut('now');
                       });
                       return;
                   }
               }
            });
        }
});

$('body').on('click', '.btn.alert-notexists', function(e){
    
        var alert_id = $(this).parent().parent().find('.alert-id').val();
        
        // armazena os estados dos botões antes de desativálos
        var btn_like_classes = $(this).parent().parent().find('.btn-classes.like').val();
        var small_like_classes = $(this).parent().parent().find('.small-classes.like').val();
        var btn_dislike_classes = $(this).parent().parent().find('.btn-classes.dislike').val();
        var small_dislike_classes = $(this).parent().parent().find('.small-classes.dislike').val();
        
        if(!$(this).parent().parent().find('.btn.like').hasClass('disabled') ||
           !$(this).parent().parent().find('.btn.dislike').hasClass('disabled')){ // O usuário diz que o alerta não existe
           
            $(this).parent().parent().find('.btn.like').addClass('disabled');
            $(this).parent().parent().find('.btn.like').next().removeClass('text-success').addClass('text-muted');
            $(this).parent().parent().find('.btn.dislike').addClass('disabled');
            $(this).parent().parent().find('.btn.dislike').next().removeClass('text-danger').addClass('text-muted');
            
            var _this = $(this);
            $.ajax({
                url: 'alerts/not-exists',
                type: 'POST',
                data: {
                    Alerts: {
                        id: alert_id,
                    }
               },
               success: function(rtn){
                   if(rtn){
                       _this.fadeOut('slow', function(){
                           $(this).prev().fadeIn('slow');
                           $(this).next().fadeIn('slow');
                       });
                       return;
                   }
               }
            });
        }
});

// Deletando um alerta
$('body').on('click', '.btn.alert-disable', function(e){
    
        var alert_id = $(this).parent().parent().find('.alert-id').val();
        
        // Percorre todo o Layer alerts para encontrar o alerta sendo excluído
        geoJSON_layer.alerts.getLayers().forEach(
                function(alert_layer, index, array){
                    if(alert_layer.isPopupOpen()){
                        //Mensagem de confirmação
                        app.message_code = 'alerts.disable.confirmation'; 
                        app.layers.selected = alert_layer;
                        //Configurando a requisição de exclusão
                        app.request.ajax = {
                                    url: 'alerts/disable',
                                    type: 'POST',
                                    data:{
                                        Alerts: {id: alert_id}
                                    },
                                    success: function(rtn){
                                        var al = app.layers.selected;
                                        if(rtn){ //fecha o popup e remove o alerta do mapa
                                            al.closePopup();
                                            geoJSON_layer.alerts.removeLayer(al);
                                        }
                                    },
                                }
                        //Mostra o modal de confirmação
                        $('#home_confirmation_modal').modal('show');
                     } 
                });
});

$('body').on('click', '.btn.alert-comment', function(e){
    var $btn = $(this).button('loading');
    var alert_comment = $(this).parent().parent().find('textarea.alert-comment').val();
    var last_comment = $(this).parent().parent().parent().find('.popup-comment-container .wrapper').children().last();
   
    alert_comment = alert_comment.trim();
    
    $(this).parent().prev().removeClass('has-success');//Reset do formulário
    
    if(alert_comment.length==0){
        $(this).parent().prev().addClass('has-error');
    }else{
        $(this).parent().prev().removeClass('has-error');
        var _this = $(this);
        $.ajax({
            url:'alert-comments/create',
            type: 'POST',
            data: {
                AlertComments:{
                   user_id: app.user.id,
                   alert_id: _this.parent().parent().parent().children().first().val(),
                   text: alert_comment,
                }
            },
            success: function(rtn){
                if(rtn){ // comentário salvo
                 _this.parent().prev().addClass('has-success');
                 _this.parent().parent().find('textarea.alert-comment').val('');
                  last_comment.after(rtn).fadeIn('now');
                  // show mensagem de sucesso ex: small - comentário feito!
                } 
            }
        });
    }
    $btn.button('reset');
});