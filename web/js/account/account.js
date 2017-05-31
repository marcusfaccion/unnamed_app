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
        
        
        // Atualiza de 1 em 1min o status de online
        setInterval(function() {
                $.ajax({
                    url: 'users/set-online',
                           type: 'POST',
                           //async: false,
                    data:{
                        OnlineUsers: {
                            user_id: app.user.id,
                        }
                    },
                    success: function(rtn){
                        if(rtn){
                            ;;
                        }
                    },
                });
        },1000*15);
        
        Loading.hide();
});
 
