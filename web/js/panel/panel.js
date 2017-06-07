/**
 * id do usuário com quem se conversa.
 * @type int 
 */
app.messages.user2.id = null;

$(document).ready(function() {
        //Bootstrapping 
        Loading.show();
        
        //Inicializando opções de mapa e renderizando
        map = new L.map('map', map_conf.options);
        
        //camada Alerts
        geoJSON_layer.alerts = L.geoJson(null,{   
                            pointToLayer: generateAlertMarkerFeature,
                            onEachFeature: onEachAlertMarkerFeature
                            }).addTo(map);
        //camada bike_keepers
        geoJSON_layer.bike_keepers = L.geoJson(null,{   
                            pointToLayer: generateBikeKeeperMarkerFeature,
                            onEachFeature: onEachBikeKeeperMarkerFeature
                            }).addTo(map);
        //camada rotas
        geoJSON_layer.routes = L.geoJson(null,{
                            onEachFeature: onEachRouteLineStringFeature,
                            style: styleEachRouteLineStringFeature,
                            }).addTo(map);
        
        // Adicionando controle de atribuição/créditos
        L.control.attribution( 
            {
                    prefix: "<a class='hidden-xs' href='http://openstreetmap.org'>OpenStreetMap contributors</a> <a class='visible-xs' href='http://openstreetmap.org'>OSM</a> <a class='hidden-xs' href='http://creativecommons.org/licenses/by-sa/2.0/'> CC-BY-SA</a><a href='http://mapbox.com'>© Mapbox</a>"
            }
         ).addTo(map);
        
        //Adicionando Controle de Zoom
        L.control.zoom(
        {
            position: 'bottomright',
            zoomInTitle: 'Aumentar Zoom',
            zoomOutTitle: 'Diminuir Zoom',
        }).addTo(map);
        
        //Adicionando Controle de camadas
        L.control.layers(
        {
            //base layers
            'Mapa': L.tileLayer(leaflet_style.app_style).addTo(map),
            'Satélite': L.tileLayer(leaflet_style.satellite)
        },
        {
        },
        {
            position: 'bottomright'
        }).addTo(map);
        
       
        
        //Inicializando variáveis global
        /**
         * ID do usuário logado no sistema
         * @type intesger
         */
        app.user.id = $('#app-user-id').val();
        app.controller.id = $('#app-controller-id').val();
        
        
        //Iniciando componentes
        $('[data-toggle="tooltip"]').tooltip({container: 'body'});
        $('[data-toggle="popover"]').popover({container: 'body'});
        
        //Atualiza as mensagens da conversa de 5 em 5s
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
                           //Atualiza a listagem de amigos e status online/offline 
                           $.ajax({
                                url: 'panel/online-friends',
                                       type: 'GET',
                                       //async: false,
                                data:{
                                        user_id: app.user.id,
                                        user_id2: app.messages.user2.id,
                                },
                                success: function(rtn){
                                    if(rtn){ 
                                           $('#messages-friends-placeholder').html(rtn);
                                    }
                                },
                            });
                        }
                    },
                });
        },1000*60);
        
        Loading.hide();
});
 
