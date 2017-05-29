/**
 * id do usuário com quem se conversa.
 * @type int 
 */
app.messages.user2.id = null;

$(document).ready(function() {
        //Bootstrapping 
        Loading.show();
        
        //Inicializando variáveis global
        /**
         * ID do usuário logado no sistema
         * @type intesger
         */
        app.user.id = $('#app-user-id').val();
        
        //Iniciando componentes
        $('[data-toggle="tooltip"]').tooltip({container: 'body'});
        $('[data-toggle="popover"]').popover({container: 'body'});
        
        setInterval(function() {
            if(app.messages.user2.id){
                $.ajax({
                    url: 'messages/conversation',
                           type: 'GET',
                           //async: false,
                    data:{
                        user_id: app.user.id,
                        user_id2: app.messages.user2.id,
                    },
                    success: function(rtn){
                        if(rtn){ 
                                $('#messages-conversation-placeholder').html(rtn);
                        }
                        Loading.hide();
                    },
                });
            }
        },1000*5);
        
        Loading.hide();
});
 
