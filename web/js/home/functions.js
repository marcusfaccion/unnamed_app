// Triggered Functions
function onLocationFound(e) {
    
    var radius = (e.accuracy / 2).toFixed(1);
   
    if(me.latlng!=null){
        // Atualizando a posição usuário
        me.latlng_history.add(me.latlng);
        me.latlng = [e.latlng.lat, e.latlng.lng];
        me.marker.setLatLng(me.latlng);
        me.circle.setLatLng(me.latlng);
        me.marker.update();
        me.marker.update();
    }else{
        me.latlng = [e.latlng.lat, e.latlng.lng];
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
function onContextMenuFired(e){
    map_popup_menu.setLatLng(e.latlng);
    selectedlatlng = [e.latlng.lat, e.latlng.lng];
    //selectedlatlng = [e.latlng[0],e.latlng[1]];
    
    map.openPopup(map_popup_menu);
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
 * @returns {Boolean} true se a localização foi ativada false caso contrário 
 */
function showMyLocation(){
    if(map.hasLayer(me.marker)){
        map.removeLayer(me.marker);
        map.removeLayer(me.circle);
        map.stopLocate();
        me.latlng = null;
        me.latlng_history.destroy();
        return false;
    }
    map.locate({setView: map_conf.locate.setView, enableHighAccuracy: map_conf.locate.enableHighAccuracy , watch: map_conf.locate.watch});
    return true;
}
