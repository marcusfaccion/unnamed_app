var _return;

var Icons;

/* Usar com map.hasLayer?
 * var alert = {
    id: null,
    marker: null,
    title: null,
    description: null,
    latlng: null,
}

var alerts = {
    alert: []
}*/

var geoJSON_layer = {
    alerts:{},
    bike_keepers:{},
};

var me = {
    id: null,
    marker: null,
    circle: null,
    latlng: null,
    latlng_history: {
        items: [],
        getFirst: function(){
            return this.items[0];
        },
        getLast: function(){
            return this.items[this.items.length-1];
        },
        getItem: function(pos){
            return this.items[pos];
        },
        add: function(item){
            this.items.push(item);
        },
        remove: function(start, countDelete){
            this.items.splice(start, countDelete);
        },
        destroy: function(){
            this.items = [];
        }
    },
    setNull: function(){
        this.id = this.marker = this.circle = this.latlng = null;
        this.latlng_history.destroy();
    }
};

var users = {
    user: [],
    new: function(id){
        var me_copy = jQuery.extend({}, me);
        this.user.push(me_copy);
        this.user[this.user.length-1].setNull();
        this.user[this.user.length-1].id = id;
    }
};

var isAjax = false;

var selectedlatlng;

var mapbox_accessToken = {
    public_token: 'pk.eyJ1IjoibWFyY3VzZmFjY2lvbiIsImEiOiJjaXNxZ29jcHMwMjRyMnNwaHVxcmRlYjg4In0.eX6DVM4oZvmsJJn8o3B_oA',
    app_token: 'sk.eyJ1IjoibWFyY3VzZmFjY2lvbiIsImEiOiJjaXVxdzdxZ2wwMDNiMnlydnU4bTd2OWJjIn0.acmOjnBC4xZiFQhsJHSNjg'
};

var mapbox_style = {
    app_style: 'mapbox://styles/marcusfaccion/cita05dqg000d2iry8gtjoyi9',
    satellite: 'mapbox://styles/marcusfaccion/ciuqwismc00bt2jodqxhts3r0'
}

var leaflet_style = 
        {
            app_style: 'https://api.mapbox.com/styles/v1/marcusfaccion/cita05dqg000d2iry8gtjoyi9/tiles/256/{z}/{x}/{y}?access_token='+mapbox_accessToken.app_token,
            satellite: 'https://api.mapbox.com/styles/v1/marcusfaccion/ciuqwismc00bt2jodqxhts3r0/tiles/256/{z}/{x}/{y}?access_token='+mapbox_accessToken.app_token
        };

// Área do mapa restrita ao Rio de Janeiro
// http://www.latlong.net/
var map_bounding_box = [
    [-23.112931, -43.816910], // vértice ao Suldoeste
    [-22.724613, -43.054733]  // vértice ao Nordeste
];

//LatLng inicial do mapa (centro do BoundingBox)
var map_center = L.latLngBounds(map_bounding_box).getCenter();
map_center = [map_center.lat, map_center.lng];

var map;
var map_popup_menu = L.popup(); 

// Variavel global de configuração
var map_conf = {
             // leafletJS map <options>
             options:{
               center: map_center,
               zoom:    12.0, // zoom inicial
               maxZoom: 18.0, //limit do leafletJS
               minZoom:  12.0,
               layers: null,
               crs: L.CRS.EPSG3857, // Sistema de Referenciamento de Coordenadas geográficas utilizando Projeção EPSG3857 mais comum e indicada para mapas online
               bounceAtZoomLimits: false,
               zoomControl: false,
               attributionControl: false,
               // map.setMaxBounds altera dinâmicamente
               maxBounds: map_bounding_box,
               worldCopyJump: true,
             },
             url_style: leaflet_style.app_style,
             accessToken: mapbox_accessToken.public_token,  
             locate: {
                   setView: true,
                   watch:   true, // para rastrear posição
                   maxZoom: 16,
                   timeout: 20*1000, //20 secs de timeout para geolocalização
                   maximumAge:  0,
                   enableHighAccuracy: true
               },
             popup_menu: {
                   getContent: function(){
                        $.ajax({
                            type: 'GET',
                            url: 'home/build-popup-menu',
                            async: false,
                            success: function(response){
                                _return = response; 
                            }
                       });
                       return _return;
                   }
            }
};

/**
 *  Variavel de controle do painel de Novaegação (Rounting/Geocoding)
 * @type Boolean
 * 
 * 0 - manter fechado
 * 1 - manter aberto
 * 2 - indiferente
 */
keepOpenUserNavigationPane = 2;

//Configurando o token de acesso a API MapBox
L.mapbox.accessToken = map_conf.accessToken;
        

$(document).ready(function() {
        //Bootstrapping 
        Loading.show();
        
        //Iniciando componentes
        $('[data-toggle="tooltip"]').tooltip({container: 'body'});
        $('[data-toggle="popover"]').tooltip({container: 'body'});
        
        
        //verificando se browser suporta API geolocation
        if (!'geolocation' in navigator) {
            alert('Seu navergador não possui suporte a geolocalização ou está desativada');
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
           //overLayers
            'Alertas': geoJSON_layer.alerts,
            'Bicicletários': geoJSON_layer.bike_keepers
        },
        {
            position: 'bottomright'
        }).addTo(map);
        
        //Adicionando Controle de Geocoding
         /*L.Control.geocoder({
             collapsed: false,
             placeholder: 'Destino...',
             position: 'bottomright',
             errorMessage: 'Não encontrado.'
         }).addTo(map);
        L.Control.geocoder({
             collapsed: false,
             placeholder: 'Origem...',
             position: 'bottomright',
             errorMessage: 'Não encontrado.'
         }).addTo(map);*/
         
        
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
        
        /**
         * EventListeners do mapa
         */
        // Location Events
        map.on('locationfound', onLocationFound);
        map.on('locationerror', onLocationError);
        // Interaction Events
        map.on('contextmenu', onContextMenuFired);
        map.on('preclick', onPreclick);
        Loading.hide();
});
 
