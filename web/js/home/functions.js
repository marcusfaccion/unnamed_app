// Triggered Functions
function onLocationFound(e) {
    console.log('found location event')
    var radius = (e.accuracy / 7).toFixed(1);
   
    if(me.latlng!=null){
        // Atualizando a posição usuário
        me.latlng_history.add(me.latlng);
        
        me.latlng = [e.latlng.lat, e.latlng.lng];
        me.latLng = e.latlng;
        
        me.marker.setLatLng(me.latlng);
        me.circle.setLatLng(me.latlng);
        me.marker.update();
        me.marker.update();
    }else{
        me.latlng = [e.latlng.lat, e.latlng.lng];
        me.latLng = e.latlng;
        me.marker = L.marker(
            me.latlng,
            {
                icon: L.mapbox.marker.icon({
                    'marker-size': 'large',
                    'marker-symbol': 'bicycle',
                    'marker-color': '#008A8A'
                }),
                riseOnHover: true
            }).bindPopup("Você está " + radius + " metros deste ponto")
            .openPopup();
        me.circle = L.circle(e.latlng, radius);   
        
        me.marker.addTo(map);
        me.circle.addTo(map);
        //Mostra a localização ao usuário caso não esteja com visualização automática após o locationfound event
        //if(!map_conf.locate.setView){
        if(app.directions.free){
            map.panTo(me.latLng, map_conf.locate.maxZoom);
        }else{
            map.panTo(me.latLng, map_conf.locate.mexZoom);
        }
    }    
}
function onLocationError(e) {
    if(me.latlng_history.items.length>0){
        // Atualizando a posição usuário para a última válida
        me.latlng = me.latlng_history.getLast();
        me.marker.setLatLng(me.latlng);
        me.circle.setLatLng(me.latlng);
        me.marker.update();
        me.marker.update();
    }
    
    //alert(e.message);
}
function onClickFired(e){
    ;;
}
function onContextMenuFired(e){
    
    //Correção fina da Latitude para o clique (positivando a latitude faz deslocar o local clicado 0.00008 graus ao norte)
    mylatlng = L.latLng([e.latlng.lat+0.00008, e.latlng.lng]);
    
    map_popup_menu.setLatLng(mylatlng);
    selectedlatlng = [mylatlng.lat, mylatlng.lng];
    app.latLng = mylatlng;
    //selectedlatlng = [e.latlng[0],e.latlng[1]];
    
    //Popup Events Listeners
    map.on('popupclose', function(e){
        showDirectionsResetMenu();
        e.popup.setContent($('#map_menu').parent().html());
        e.popup.update();
    });
    
    //Popup Events Listeners
    map.on('popupopen', function(e){
        showDirectionsResetMenu();
    });
    
    //map.openPopup(map_popup_menu);
    map_popup_menu.addTo(map);
}

function onPreclick(e){ 
     closeNavigationPane();
}

function openNavigationPane(){
    if((keepOpenUserNavigationPane==1||keepOpenUserNavigationPane==2) && $('#user-navigation-pane-toggle').children('span').hasClass('glyphicon-chevron-right')){
        $('#user-navigation-pane-toggle').fadeOut('now');
        $("#user-navigation-container").animate( {'margin-left' : "0px"}, 400);
        $('#user-navigation-pane-toggle').children('span').removeClass('glyphicon-chevron-right');
        $('#user-navigation-pane-toggle').children('span').addClass('glyphicon-chevron-left');
        $('#user-navigation-pane-toggle').show('now');
        return true;
     }
     return false;
}
function closeNavigationPane(){
    if((keepOpenUserNavigationPane==0||keepOpenUserNavigationPane==2) && $('#user-navigation-pane-toggle').children('span').hasClass('glyphicon-chevron-left')){
        $("#user-navigation-container").animate( {'margin-left' : "-1000px"}, 400);
        $('#user-navigation-pane-toggle').children('span').removeClass('glyphicon-chevron-left');
        $('#user-navigation-pane-toggle').children('span').addClass('glyphicon-chevron-right'); 
        return true;
     }
     return false;
}

function onCreatedMarkerClick(marker){
    
}

/**
 * Função para mostrar e esconder a localização do usuário no mapa.
 * Se a localização estiver ativa ao ser acionada essa função remove o L.Marker e o L.Circle do mapa e interrompe o rastreamento da posição do usuário
 * Se não estiver ativa ela adiciona ao mapa o L.Marker e o L.Circle e inicia o rastreamento da posição do usuário
 * @param bool enable se false desativa a localização
 * @returns {Boolean} true se a localização foi ativada false caso contrário 
 */
function showMyLocation(enable, after){
    geoJSON_layer.routes.clearLayers(); // apaga as rotas do usuário do layers routes, removendo-a da tela consecutivamente.
    if(typeof(enable)==='undefined'){
        enable = !map.hasLayer(me.marker);
    };
    if(enable){ 
        if(!app.user.location){
            map.locate({setView: map_conf.locate.setView, enableHighAccuracy: map_conf.locate.enableHighAccuracy , watch: map_conf.locate.watch});
        }
        app.user.location = true;
        return true;
    }else{
        map.removeLayer(me.marker);
        map.removeLayer(me.circle);
        map.stopLocate();
        me.latlng = null;
        me.latlng_history.destroy();
        app.user.location = false;
        return false;
    }
    
    return false;
}

function userNavigationStart(enable, itemmenu){
    if(enable){
        if(showMyLocation(true)){
            $(itemmenu).parent().fadeOut('now', function(){
                $(itemmenu).parent().next().fadeIn('now');
                $(itemmenu).parent().next().next().fadeIn('now');
            });
        }
    }else{
        if(!showMyLocation(false)){
            $(itemmenu).parent().fadeOut('now', function(){
                $(itemmenu).parent().next().fadeOut('now');
                $(itemmenu).parent().prev().fadeIn('now');
            });
        }
    }
}

function resetDirections(){
    
    if(directions.queryable() && $('#home-user-navigation-stop').css('display')=='block'){
        $('#home-user-navigation-stop').click();//trigger para o botão de para navegação de rota
    }else{
        directions.setOrigin();
        directions.setDestination();
    }
    showDirectionsResetMenu(); 
}

/**
 * Ativa e desativa o menu de redefinir rota
 * @returns {Boolean} true se o menu é exibido ou false caso não
 */
function showDirectionsResetMenu(){
    if(directions.queryable()){
        $('#map_menu .nav-destination-reset').show();
        return true;
    }else{
        if($('#map_menu .nav-destination-reset').css('display')=='block'){
            $('#map_menu .nav-destination-reset').hide();
            return false;
        }    
    }
}

/**
 * Define Mapbox directions.destination
 * @param L.latLng latLng
 * @returns {undefined}
 */
function setDestination(latLng){
    directions.setDestination(latLng);
    if(directions.getOrigin()){
        app.directions.routes = [];
        // proximity a L.LatLng object that is fed into the geocoder and biases matches around a point
        directions.query({proximity:null}, function(err, results){
            var results_clone = (JSON.parse(JSON.stringify(results)));
            app.directions.origin = results_clone.origin;
            app.directions.destination = results_clone.destination;
            results_clone.routes.forEach(function(route, i){
                app.directions.routes.push(route);
//                route.steps.forEach(function(step, j){
//                    //step.maneuver.instruction = app.t(step.maneuver.instruction, 'pt-BR')
//                    console.log(route);
//                })
            });
        });
    }
    showDirectionsResetMenu();
}
/**
 * Define Mapbox directions.origin
 * @param L.latLng latLng
 * @returns {undefined}
 */
function setOrigin(latLng){
    directions.setOrigin(latLng);
    if(directions.getDestination()){
        app.directions.routes = [];
        // proximity a L.LatLng object that is fed into the geocoder and biases matches around a point
        directions.query({proximity:null}, function(err, results){
            var results_clone = (JSON.parse(JSON.stringify(results))); 
            app.directions.origin = results_clone.origin;
            app.directions.destination = results_clone.destination;
            results_clone.routes.forEach(function(route, i){
                app.directions.routes.push(route);
//                route.steps.forEach(function(step, j){
//                    //step.maneuver.instruction = app.t(step.maneuver.instruction, 'pt-BR')
//                })
            });
        });
    }
    showDirectionsResetMenu();
}