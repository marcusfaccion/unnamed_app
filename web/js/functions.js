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
//Define configurações para cada um dos markers feature
function onEachAlertMarkerFeature(feature, layer){
     if (feature.properties) {
        layer.bindPopup(
            '', //renderiza o conteúdo do popup somente quando ele for aberto para melhorar a performance
                { 
                    maxWidth : popup.maxWidth,
                    minWidth : popup.minWidth,
                }
        );
        // É necessário atualizar o conteúdo do popup toda vez que o usuário o edita (comentário, like ou deslike ...)
//        layer.on('popupclose', function(e){
//            e.popup.setContent($('#alert-popup-content-'+feature.properties.id).parent().html());
//            e.popup.update();
//        });
        // É necessário atualizar (requisiçáo http xhr) o conteúdo do popup toda vez que este abrir
        layer.on('popupopen', function(e){
            e.popup.setContent(
                popup.getContentAjax(
                    'alerts/render-popup'+(app.controller.id=='home'?'':'-readonly'),
                    {
                        type: 'GET',
                        data: {id: feature.properties.id},
                        async: false,
                    })
                );
            e.popup.update();
        });
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
        layer.bindPopup('',
            { 
                maxWidth : popup.maxWidth,
                minWidth : popup.minWidth,
            }
        );
        
        //Atualiza conteúdo do popup toda vez que ele for aberto
        layer.on('popupopen', function(e){
            e.popup.setContent(
                popup.getContentAjax(
                    'bike-keepers/render-popup'+(app.controller.id=='home'?'':'-readonly'),
                    {
                        type: 'GET',
                        data: {id: feature.properties.id},
                        async: false,
                    })
                );
            e.popup.update();
        });
     }
}

/**
 * A Function that will be called once for each created Feature, after it has
 * been created and styled. Useful for attaching events and popups to features. 
 * The default is to do nothing with the newly created layers:
 * @param Object(Feature) feature LineString GeoJSON
 * @param {type} latlng
 * @returns {L.Marker}
*/
function onEachRouteLineStringFeature(feature, layer){
    ;;
}

/**
 * A Function defining the Path options for styling GeoJSON lines
 * and polygons, called internally when data is added.
 * The default value is to not override any defaults:
 * @param Object(Feature) feature LineString GeoJSON 
 * @returns Object(GeoJSON Feature style)
*/
function styleEachRouteLineStringFeature(feature){
    return map_conf.lineString.options;
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

function timeout(func, delay){
    window.setTimeout(func(), delay);
    timeout(func, delay);
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