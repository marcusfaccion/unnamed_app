// Triggered Functions
function onLocationFound(e) {
    
    var radius = (e.accuracy / 2).toFixed(1);
   
    if(my_location.latlng!=null){
        // Atualizando a posição usuário
        my_location.latlng_history.add(my_location.latlng);
        my_location.latlng = [e.latlng.lat, e.latlng.lng];
        my_location.marker.setLatLng(my_location.latlng);
        my_location.circle.setLatLng(my_location.latlng);
        my_location.marker.update();
        my_location.marker.update();
    }else{
        my_location.latlng = [e.latlng.lat, e.latlng.lng];
        my_location.marker = L.marker(
            my_location.latlng,
            {
                icon: L.mapbox.marker.icon({
                    'marker-size': 'large',
                    'marker-symbol': 'bicycle',
                    'marker-color': '#008A8A'
                }),
                riseOnHover: true
            }).bindPopup("Você está " + radius + " metros deste ponto")
            .openPopup();
        my_location.circle = L.circle(e.latlng, radius);   
        
        my_location.marker.addTo(map);
        my_location.circle.addTo(map);
    }    
}
function onLocationError(e) {
    if(my_location.latlng_history.items.length>0){
        // Atualizando a posição usuário para a última válida
        my_location.latlng = my_location.latlng_history.getLast();
        my_location.marker.setLatLng(my_location.latlng);
        my_location.circle.setLatLng(my_location.latlng);
        my_location.marker.update();
        my_location.marker.update();
    }
    
    //alert(e.message);
}
function onContextMenuFired(e){
    map_popup_menu.setLatLng(e.latlng);
    selectedlatlng = [e.latlng.lat, e.latlng.lng];
    //selectedlatlng = [e.latlng[0],e.latlng[1]];
    
    map.openPopup(map_popup_menu);
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
    if(map.hasLayer(my_location.marker)){
        map.removeLayer(my_location.marker);
        map.removeLayer(my_location.circle);
        map.stopLocate();
        my_location.latlng = null;
        my_location.latlng_history.destroy();
        return false;
    }
    map.locate({setView: map_conf.locate.setView, enableHighAccuracy: map_conf.locate.enableHighAccuracy , watch: map_conf.locate.watch});
    return true;
}