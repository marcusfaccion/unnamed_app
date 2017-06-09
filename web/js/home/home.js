$(document).ready(function() {
        //Bootstrapping 
        Loading.show();
        
        //Inicializando variáveis global
        /**
         * ID do usuário logado no sistema
         * @type intesger
         */
        app.user.id = $('#app-user-id').val();
        app.controller.id = $('#app-controller-id').val();
        
        //verificando se browser suporta API geolocation
        if (!'geolocation' in navigator) {
             //mostra o modal com informações da API Geolocation
            app.message_code = 'user.agent.geolocation.unavailable';
            $('#home_geolocation_info_modal').modal('show');
        }
        //Inicializando opções de mapa e renderizando
        map = new L.map('map', map_conf.options);
        
        //Adicionando a camada geoJSON para renderização dinâmica de geojson Features
        //http://leafletjs.com/examples/geojson/
         
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
        
        
        // create the initial directions object, from which the layer
        // and inputs will pull data.
        directions = L.mapbox.directions({
            profile: 'mapbox.cycling',
            language: 'pt',
            units: 'metric',
        });
        
        directions.on('highlightRoute', function(e){
           // console.log('highlightRoute capturado');
           // console.log(e);
        });
        directions.on('highlightStep', function(e){
           // console.log('highlightStep capturado');
           // console.log(e);
        });
        
        directions.on('origin', function(e,t){
           // console.log('origin capturado');
           // console.log(e);
           if(directions.getDestination())
                app.directions.pause=false; //para que o mapa possa aplicar flyTo() caso a origem seja a localização do usuário
        });
        directions.on('destination', function(e){
           // console.log('destination capturado');
           // console.log(e);
           if(directions.getOrigin())
                app.directions.pause=false; //para que o mapa possa aplicar flyTo() caso a origem seja a localização do usuário
        });
        directions.on('load', function(e){
           // console.log('load capturado');
           // console.log(e);
           app.directions.loadbyUser = true;
           geoJSON_layer.routes.clearLayers(); // apaga as rotas do usuário do layers routes, removendo-a da tela consecutivamente.
           showDirectionsResetMenu();//para mostrar o menu de reset de destino e origem
        });
        directions.on('selectRoute', function(e){
           // console.log('selectRoute capturado');
            //console.log(e);
            if(!app.directions.loadbyUser){
                app.directions.routes = [e.route];
            }
            $('#home_user_navigation_modal').modal('show');
           
        });
        directions.on('unload', function(e){
            //console.log('unload capturado');
           // console.log(e);
        });
        
        
        
        directionsLayer = L.mapbox.directions.layer(directions, {
            readonly:true, //Não permite que origin e destination sejem definidos pelo evento click do L.map
            // Não permite o drag and drop dos marcadores
            routeStyle: {color: '#015196', weight: 7, opacity: .6},
        }).addTo(map);

        directionsInputControl = L.mapbox.directions.inputControl('inputs', directions)
            .addTo(map);

        directionsErrorsControl = L.mapbox.directions.errorsControl('errors', directions)
            .addTo(map);

        directionsRoutesControl = L.mapbox.directions.routesControl('routes', directions)
            .addTo(map);

        directionsInstructionsControl = L.mapbox.directions.instructionsControl('instructions', directions)
            .addTo(map);
        
//        directions.setOrigin(L.latLng(-22.957444931986, -43.382949829102));
//        directions.setDestination(L.latLng(-22.951754475745, -43.564739227295));
        //directions.addWaypoint(index, waypoint);
//        directions.query({},function(err, r){
//            console.log('Callback');
//            console.log(r);
//        });
        
        //Adicionando Controle de camadas
        L.control.layers(
        {
            //base layers
            'Mapa': L.tileLayer(leaflet_style.app_style).addTo(map),
            'Satélite': L.tileLayer(leaflet_style.satellite)
        },
        {
           //overLayers
            'Alertas': geoJSON_layer.alerts,
            'Bicicletários': geoJSON_layer.bike_keepers,
            'Rotas': directionsLayer,
            'Minhas rotas': geoJSON_layer.routes,
        },
        {
            position: 'bottomright'
        }).addTo(map);
        
       // Adicionando controle de geocoding (provider nominatim.openstreetmap.org)
       // plugin by Per Liedman
       // https://github.com/perliedman/leaflet-control-geocoder
       control_geocoder = L.Control.geocoder({
           position: 'bottomright',
           placeholder: 'Digite um local',
           //geocoder: new L.Control.Geocoder.Mapbox(map_conf.accessToken),
           geocoder: new L.Control.Geocoder.Nominatim(), // Nominatim geocoding service by OSM
           defaultMarkGeocode: true,
           showResultIcons: true,
       }).addTo(map);
       
       app.geocoder = control_geocoder.options.geocoder;
       
       //Plotando Alertas
        $.ajax({
            url: 'alerts/get-features',
            type: 'GET',
            async: false,
            success: function(geojson){
                geoJSON_layer.alerts.addData(
                        JSON.parse(geojson)
                );
            }
        });
        //Plotando Bicicletários
        $.ajax({
            url: 'bike-keepers/get-features',
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
        
        // Atualiza de 20 em 20s a posição do usuário
        //  se a navegação em rota estiver ativada.
        // Checa se o usuário chegou ao destino
        setInterval(function() {
            if((app.directions.myOrigin || app.directions.free) && !app.directions.pause){
                if(me.latLng!=null){
                    map.flyTo(me.latLng, map_conf.locate.maxZoom);
                }
            }
            //Checa se chegou ao destino
            if(directions.queryable() && app.directions.myOrigin && !$('#home_user_navigation_modal').hasClass('in')){
                //Se a localização do usuário estiver a uma distância linear de até 10 metros do local destino, considerar como destino alcançado
                //console.log(parseInt(map.distance(me.latlng, [directions.getDestination().geometry.coordinates[1],directions.getDestination().geometry.coordinates[0]])))
                if(parseInt(map.distance(me.latlng, [directions.getDestination().geometry.coordinates[1],directions.getDestination().geometry.coordinates[0]]))<=10){
                    app.directions.myOrigin = false; //Interrompe atualização da posição do usuário
                    app.directions.free = false;
                    app.directions.pause = false;
                    
                    //Tempo total real em rota gasto pelo usuário 
                    app.t1 = performance.now();
                    app.directions.elapsed_time = (app.t1 - app.t0)/1000;//msec to sec
                    
                    //Guarda o layers (LineString) da rota 
                    me.layers.route = L.polyline(me.latlng_history.toLineStringArray(),map_conf.lineString.options);
                    //Adiciona a Latlng do destino ao layer LineString da rota
                    me.layers.route.addLatLng([directions.getDestination().geometry.coordinates[1],directions.getDestination().geometry.coordinates[0]]);
                    //me.layers.route.addTo(map);
                    
                    geoJSON_layer.routes.addData(me.layers.route.toGeoJSON()); // Adiciona a rota do usuário a camada de rotas.
                   
                   app.message_code = 'routes.share.question';
                   app.request.ajax.url = 'app/get-confirm-message';
                   app.request.ajax.type = 'post';
                   app.request.ajax.data = {confirm_message: app.message_code};
                   app.user.sharings.selectedTypeId = app.user.sharings.types.routes; // tipo de compartilhamento
                   
                   
                   app.yconfirmation.action = function(){
                        // Confirmação para compartilhar
                        $('#home_user_sharings_modal').modal('show');   
                   }
                   
                    // Confirmação para compartilhar
                    $('#home_confirmation_modal').modal('show');
                }
            }
        },1000*20);
        
        /**
         * EventListeners do mapa
         */
        // Location Events
        map.on('locationfound', onLocationFound);
        map.on('locationerror', onLocationError);
        // Interaction Events
        map.on('click', onClickFired);
        map.on('contextmenu', onContextMenuFired);
        map.on('preclick', onPreclick);
        //map.on('popupopen', function(){});
        //map.on('popupclose', function(){});
        Loading.hide();
});
 
