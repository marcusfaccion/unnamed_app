//var x;

$('body').on('click', '.btn.bike-keeper-like', function(e){ 
        var n_likes = $(this).children('.badge').html(); 
        var bike_keeper_id = $(this).parent().parent().find('.bike-keeper-id').val();
        var user_avaliation = 0; // 0 significa não avaliado
        
        if(!$(this).hasClass('disabled')){
            $(this).parent().next().children('small').toggleClass('text-muted');
            $(this).parent().next().children('small').toggleClass('text-danger');
            $(this).parent().next().children('.btn.bike-keeper-dislike').toggleClass('disabled');
            
            if($(this).parent().next().children('.btn.bike-keeper-dislike').hasClass('disabled')){
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
                url: 'bike-keepers/ilike',
                type: 'POST',
                async: false, //Precisa ser síncrono com o script server side pois utiliza em sua lógica elementos html que são atualizados dinâmicamente 
                data: {
                    BikeKeepers: {
                        id: bike_keeper_id,
                        likes: n_likes,
                        avaliated: user_avaliation,
                    }
               },
               success: function(){
                    _this.children('.badge').html(n_likes);
                    _this.parent().fadeOut('slow', function(){
                        $(this).fadeIn('slow');
                        
                        $(this).find('.btn-classes').val($(this).find('.btn.bike-keeper-like').attr('class'));
                        $(this).find('.small-classes').val($(this).find('.btn.bike-keeper-like').next().attr('class'));
                        
                        $(this).next().find('.btn-classes').val($(this).next().find('.btn.bike-keeper-dislike').attr('class'));
                        $(this).next().find('.small-classes').val($(this).next().find('.btn.bike-keeper-dislike').next().attr('class'));
                    });
               }
            });
        }
});

$('body').on('click', '.btn.bike-keeper-dislike', function(e){ 
        var n_dislikes = $(this).children('.badge').html(); 
        var bike_keeper_id = $(this).parent().parent().find('.bike-keeper-id').val();
        var user_avaliation = 0;
        
        if(!$(this).hasClass('disabled')){
            $(this).parent().prev().children('.btn.bike-keeper-like').toggleClass('disabled');
            $(this).parent().prev().children('small').toggleClass('text-muted');
            $(this).parent().prev().children('small').toggleClass('text-success');
            
            if($(this).parent().prev().children('.btn.bike-keeper-like').hasClass('disabled')){
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
                url: 'bike-keepers/idislike',
                type: 'POST',
                async: false, //Precisa ser síncrono com o script server side pois utiliza em sua lógica elementos html que são atualizados dinâmicamente 
                data: {
                    BikeKeepers: {
                        id: bike_keeper_id,
                        dislikes: n_dislikes,
                        avaliated: user_avaliation,
                    }
               },
               success: function(){
                   _this.children('.badge').html(n_dislikes);
                    _this.parent().fadeOut('slow', function(){
                        $(this).fadeIn('slow');
                        
                        $(this).find('.btn-classes').val($(this).find('.btn.bike-keeper-dislike').attr('class'));
                        $(this).find('.small-classes').val($(this).find('.btn.bike-keeper-dislike').next().attr('class'));
                        
                        $(this).prev().find('.btn-classes').val($(this).prev().find('.btn.bike-keeper-like').attr('class'));
                        $(this).prev().find('.small-classes').val($(this).prev().find('.btn.bike-keeper-like').next().attr('class'));
                    });
               }
            });
            
             
        }
});

$('body').on('click', '.btn.bike-keeper-exists', function(e){
        
        var bike_keeper_id = $(this).parent().parent().find('.bike-keeper-id').val();
        
        // obtém os estados dos botões, outrora armazenados, para usá-los na ativação do bicicletário 
        var btn_like_classes = $(this).parent().parent().find('.btn-classes.bike-keeper-like').val();
        var small_like_classes = $(this).parent().parent().find('.small-classes.bike-keeper-like').val();
        var btn_dislike_classes = $(this).parent().parent().find('.btn-classes.bike-keeper-dislike').val();
        var small_dislike_classes = $(this).parent().parent().find('.small-classes.bike-keeper-dislike').val();
        
        if($(this).parent().parent().find('.btn.bike-keeper-like').hasClass('disabled') &&
           $(this).parent().parent().find('.btn.bike-keeper-dislike').hasClass('disabled')){ // O usuário já disse anteriormente que o bicicletário não existe
           
            $(this).parent().parent().find('.btn.bike-keeper-like').attr('class', btn_like_classes);
            $(this).parent().parent().find('.btn.bike-keeper-like').next().attr('class', small_like_classes);
            $(this).parent().parent().find('.btn.bike-keeper-dislike').attr('class', btn_dislike_classes);
            $(this).parent().parent().find('.btn.bike-keeper-dislike').next().attr('class', small_dislike_classes);
            
            var _this = $(this);
            $.ajax({
                url: 'bike-keepers/exists',
                type: 'POST',
                async: false,
                data: {
                    BikeKeepers: {
                        id: bike_keeper_id,
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

$('body').on('click', '.btn.bike-keeper-notexists', function(e){ 
    
        var bike_keeper_id = $(this).parent().parent().find('.bike-keeper-id').val();
        
        // armazena os estados dos botões antes de desativálos
        var btn_like_classes = $(this).parent().parent().find('.btn-classes.bike-keeper-like').val();
        var small_like_classes = $(this).parent().parent().find('.small-classes.bike-keeper-like').val();
        var btn_dislike_classes = $(this).parent().parent().find('.btn-classes.bike-keeper-dislike').val();
        var small_dislike_classes = $(this).parent().parent().find('.small-classes.bike-keeper-dislike').val();
        
        if(!$(this).parent().parent().find('.btn.bike-keeper-like').hasClass('disabled') ||
           !$(this).parent().parent().find('.btn.bike-keeper-dislike').hasClass('disabled')){ // O usuário diz que o bicicletário não existe
           
            $(this).parent().parent().find('.btn.bike-keeper-like').addClass('disabled');
            $(this).parent().parent().find('.btn.bike-keeper-like').next().removeClass('text-success').addClass('text-muted');
            $(this).parent().parent().find('.btn.bike-keeper-dislike').addClass('disabled');
            $(this).parent().parent().find('.btn.bike-keeper-dislike').next().removeClass('text-danger').addClass('text-muted');
            
            var _this = $(this);
            $.ajax({
                url: 'bike-keepers/not-exists',
                type: 'POST',
                async: false,
                data: {
                    BikeKeepers: {
                        id: bike_keeper_id,
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

// Desativando um bicicletário
$('body').on('click', '.btn.bike-keeper-disable', function(e){
    
        var bike_keeper_id = $(this).parent().parent().find('.bike-keeper-id').val();
        
        // Percorre todo o Layer bike_keepers para encontrar o bicicletário sendo excluído
        geoJSON_layer.bike_keepers.getLayers().forEach(
                function(bike_keeper_layer, index, array){
                    if(bike_keeper_layer.isPopupOpen()){
                        //Mensagem de confirmação
                        app.message_code = 'bike-keepers.disable.confirmation'; 
                        app.layers.selected = bike_keeper_layer;
                        //Configurando a requisição de desativação
                        app.request.ajax = {
                                    url: 'bike-keepers/disable',
                                    type: 'POST',
                                    data:{
                                        BikeKeepers: {id: bike_keeper_id}
                                    },
                                    success: function(rtn){
                                        var al = app.layers.selected;
                                        if(rtn){ //fecha o popup e remove o bicicletário do mapa
                                            al.closePopup();
                                            geoJSON_layer.bike_keepers.removeLayer(al);
                                        }
                                    },
                                }
                        //Mostra o modal de confirmação
                        $('#home_confirmation_modal').modal('show');
                     } 
                });
});



$('body').on('click', '.btn.bike-keeper-photos', function(e){
    app.bike_keeper.id = $(this).parent().next().val();
    console.log(app.bike_keeper.id);
});

$('body').on('click', '.btn.bike-keeper-comment', function(e){
    var $btn = $(this).button('loading');
    var bike_keeper_comment = $(this).parent().parent().find('textarea.bike-keeper-comment').val();
    var last_comment = $(this).parent().parent().parent().find('.popup-comment-container .wrapper').children().last();
    var n_comments = parseInt($(this).parent().parent().parent().find('.popup-comment-header .comment-counter .badge').html());
    
    // Parse Int n_comments retorna NaN se não existirem comentários
    n_comments = Number.isNaN(n_comments)?0:n_comments;
   
    bike_keeper_comment = bike_keeper_comment.trim();
    
    $(this).parent().prev().removeClass('has-success');//Reset do formulário
    $(this).parent().parent().find('small.text-success').fadeOut('now');
    
    if(bike_keeper_comment.length==0){
        $(this).parent().prev().addClass('has-error');
        $(this).parent().parent().find('small.text-danger').fadeIn('now');
    }else{
        $(this).parent().prev().removeClass('has-error');
        $(this).parent().parent().find('small.text-danger').fadeOut('now');
        var _this = $(this);
        $.ajax({
            url:'bike-keeper-comments/create',
            type: 'POST',
            data: {
                BikeKeeperComments:{
                   user_id: app.user.id,
                   bike_keeper_id: _this.parent().parent().parent().children().first().val(),
                   text: bike_keeper_comment,
                }
            },
            success: function(rtn){
                if(rtn){ // comentário salvo
                 _this.parent().prev().addClass('has-success');
                 _this.parent().parent().find('textarea.bike-keeper-comment').val('');
                 _this.parent().parent().find('small.text-success').fadeIn('now');
                  last_comment.after(rtn).fadeIn('now');
                 _this.parent().parent().parent().find('.popup-comment-header .comment-counter .badge').html(++n_comments);
                  // show mensagem de sucesso ex: small - comentário feito!
                } 
            }
        });
    }
    $btn.button('reset');
});