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
        
        
        //verificando se browser suporta API geolocation
        if (!'geolocation' in navigator) {
            alert('Seu navergador não possui suporte a geolocalização ou está desativada');
        }
        //Inicializando opções de mapa e renderizando
        map = new L.map('map', map_conf.options);
        //http://leafletjs.com/examples/geojson/
         
        //camada Alerts
        geoJSON_layer.alerts = L.geoJson(null,{   
                            pointToLayer: generateAlertMarkerFeature,
                            onEachFeature: onEachAlertMarkerFeature
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
            'Alerts': geoJSON_layer.alerts},
        {
            position: 'bottomright'
        }).addTo(map);
        

        //Adicionando a camada geoJSON para renderização dinâmica de geojson Features
        
       //Plotando Alertas
        $.ajax({
            url: 'alerts/get-user-features',
            data: { user_id: app.user.id },
            type: 'GET',
            async: false,
            success: function(geojson){
                geoJSON_layer.alerts.addData(
                        JSON.parse(geojson)
                );
            }
        });
        
        /**
         * EventListeners do mapa
         */
        // Interaction Events
        //map.on('contextmenu', onContextMenuFired);
        //map.on('preclick', onPreclick);
        Loading.hide();
});
 