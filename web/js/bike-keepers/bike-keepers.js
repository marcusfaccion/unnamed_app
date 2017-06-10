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
        $('[data-toggle="popover"]').tooltip({container: 'body'});
        

        //Inicializando opções de mapa e renderizando
        map = new L.map('map', map_conf.options);
        //http://leafletjs.com/examples/geojson/
         
        //camada Alerts
        geoJSON_layer.bike_keepers = L.geoJson(null,{   
                            pointToLayer: generateBikeKeeperMarkerFeature,
                            onEachFeature: onEachBikeKeeperMarkerFeature
                            }).addTo(map);
                            
        //camada de usuário online
        /*geoJSON_layer.users = L.geoJson(null,{   
                            pointToLayer: generateUserMarkerFeature,
                            onEachFeature: onEachUserMarkerFeature
                            }).addTo(map);*/
        
        ////Inicializando o conteúdo do menu de contexto
        map_popup_menu.setContent(map_conf.popup_menu.getContent());
       
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
            'Bicicletários': geoJSON_layer.bike_keepers},
        {
            position: 'bottomright'
        }).addTo(map);
        

        //Adicionando a camada geoJSON para renderização dinâmica de geojson Features
        
       //Plotando Alertas
        $.ajax({
            url: 'bike-keepers/get-user-features',
            data: { user_id: app.user.id },
            type: 'GET',
            async: false,
            success: function(geojson){
                geoJSON_layer.bike_keepers.addData(
                        JSON.parse(geojson)
                );
            }
        });
        
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
        },1000*60);
        
        /**
         * EventListeners do mapa
         */
        // Interaction Events
        //map.on('contextmenu', onContextMenuFired);
        //map.on('preclick', onPreclick);
        Loading.hide();
});
 
