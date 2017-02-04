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

/**
 * Gera um L.Marker a partir do feature geoJSON passado
 * @param {geoJSON Feature} feature
 * @param {Latlng|array} latlng do marcador
 * @returns L.Marker
 */
function generateAlertMarkerFeature(feature, latlng){
    if (feature.properties && feature.properties.type_desc) {
        return L.marker(latlng,
     {
        icon: Icons[feature.properties.type_desc_en],
        title: feature.properties.title,
        alt: feature.properties.type_desc,
        riseOnHover: true
       });
    }
}

function onEachAlertMarkerFeature(feature, layer){
     if (feature.properties) {
        //layer.bindPopup('<b>Alerta id: </b>'+feature.properties.id );
        layer.bindPopup(popup.getContentAjax(
            'alerts/render-popup',
            {
                type: 'GET',
                data: {id: feature.properties.id},
                async: false,
            }
        ));
     }
}
/**
 * Gera um L.Marker a partir do feature geoJSON passado
 * @param {geoJSON Feature} feature
 * @param {Latlng|array} latlng do marcador
 * @returns L.Marker
 */
function generateBikeKeeperMarkerFeature(feature, latlng){
    if (feature.properties && feature.properties.type_desc) {
        return L.marker(latlng,
     {
        icon: Icons[feature.properties.type_desc_en],
        title: feature.properties.title,
        alt: feature.properties.type_desc,
        keyboard: true,
        riseOnHover: true
       });
    }
}

function onEachBikeKeeperMarkerFeature(feature, layer){
     if (feature.properties) {
        //layer.bindPopup('<b>Alerta id: </b>'+feature.properties.id );
        layer.bindPopup(popup.getContentAjax(
            'bike-keepers/render-popup',
            {
                type: 'GET',
                data: {id: feature.properties.id},
                async: false,
            }
        ));
     }
}
/**
 * Gera um L.Marker a partir do feature geoJSON passado
 * @param {geoJSON Feature} feature
 * @param {Latlng|array} latlng do marcador
 * @returns L.Marker
 */
function generateUserMarkerFeature(feature, latlng){
    if (feature.properties && feature.properties.type_desc) {
        return L.marker(latlng,
     {
        icon: Icons[feature.properties.type_desc_en],
        title: feature.properties.title,
        alt: feature.properties.type_desc,
        keyboard: true,
        riseOnHover: true
       });
    }
}

function onEachUserMarkerFeature(feature, layer){
     if (feature.properties) {
        //layer.bindPopup('<b>Alerta id: </b>'+feature.properties.id );
        layer.bindPopup(popup.getContentAjax(
            'users/render-popup',
            {
                type: 'GET',
                data: {id: feature.properties.id},
                async: false,
            }
        ));
     }
}

// Editar código para criar um helper
/*
function uploadFile(){
    myApp.showPleaseWait(); //show dialog
    var file=document.getElementById('file_name').files[0];
    var formData = new FormData();
    formData.append("file_name", file);
    ajax = new XMLHttpRequest();
    ajax.upload.addEventListener("progress", progressHandler, false);
    ajax.addEventListener("load", completeHandler, false);
    ajax.open("POST", "/to/action");
    ajax.send(formData);
}

function progressHandler(event){
    var percent = (event.loaded / event.total) * 100;
    $('.bar').width(percent); //from bootstrap bar class
}

function completeHandler(){
    myApp.hidePleaseWait(); //hide dialog
    $('.bar').width(100);
}*/